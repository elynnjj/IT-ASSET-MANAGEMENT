<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssignAsset;
use App\Models\Disposal;
use App\Models\ITRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController
{
    public function index()
    {
        // Get month and year from request, default to current month/year
        $selectedMonth = request()->input('month', now()->month);
        $selectedYear = request()->input('year', now()->year);
        
        // Validate month and year
        $selectedMonth = max(1, min(12, (int)$selectedMonth));
        $selectedYear = max(2000, min(2100, (int)$selectedYear));

        // Total Active Assets (not disposed)
        $totalActiveAssets = Asset::where(function ($query) {
            $query->where('status', '!=', 'Disposed')
                  ->orWhereNull('status');
        })->count();

        // Asset Checked-Out (assets with status 'Checked Out' or with active assignments)
        $assetCheckedOut = Asset::where(function ($query) {
            $query->where('status', 'Checked Out')
                  ->orWhereHas('assignments', function ($q) {
                      $q->whereNull('checkinDate');
                  });
        })->distinct()->count();

        // Asset Disposed
        $assetDisposed = Asset::where('status', 'Disposed')->count();

        // Total Active Users
        $totalActiveUsers = User::where('accStat', 'active')->count();

        // Get pending IT requests (up to 3)
        $pendingITRequests = ITRequest::where('status', 'Pending IT')
            ->with(['requester', 'asset'])
            ->orderBy('requestDate', 'desc')
            ->orderBy('requestID', 'desc')
            ->limit(3)
            ->get();

        // Asset Status for Pie Chart
        // Get all assets with their disposal status
        $allAssets = Asset::with('disposals')->get();
        
        // Initialize status counts
        $statusData = [
            'Available' => 0,
            'Checked Out' => 0,
            'Pending Dispose' => 0,
            'Disposed' => 0,
        ];
        
        foreach ($allAssets as $asset) {
            // Check if asset has disposal records
            $pendingDisposal = $asset->disposals()->where('dispStatus', 'pending')->exists();
            $disposed = $asset->disposals()->where('dispStatus', 'disposed')->exists();
            
            if ($disposed) {
                $statusData['Disposed']++;
            } elseif ($pendingDisposal) {
                $statusData['Pending Dispose']++;
            } elseif ($asset->status === 'Checked Out' || $asset->assignments()->whereNull('checkinDate')->exists()) {
                $statusData['Checked Out']++;
            } else {
                $statusData['Available']++;
            }
        }

        // Get calendar events for the selected month with detailed information
        $currentMonth = $selectedMonth;
        $currentYear = $selectedYear;
        
        // Get checkout events with asset and user details
        $checkoutEvents = AssignAsset::with(['asset', 'user'])
            ->whereYear('checkoutDate', $currentYear)
            ->whereMonth('checkoutDate', $currentMonth)
            ->get();
        
        // Get checkin events with asset and user details
        $checkinEvents = AssignAsset::with(['asset', 'user'])
            ->whereNotNull('checkinDate')
            ->whereYear('checkinDate', $currentYear)
            ->whereMonth('checkinDate', $currentMonth)
            ->get();
        
        // Get disposal events with asset details
        $disposalEvents = Disposal::with('asset')
            ->whereYear('dispDate', $currentYear)
            ->whereMonth('dispDate', $currentMonth)
            ->get();
        
        // Combine all events by date with detailed information
        $calendarEvents = [];
        
        foreach ($checkoutEvents as $event) {
            $date = $event->checkoutDate->format('Y-m-d');
            if (!isset($calendarEvents[$date])) {
                $calendarEvents[$date] = [];
            }
            $calendarEvents[$date][] = [
                'type' => 'checkout',
                'assetID' => $event->asset->assetID ?? 'N/A',
                'userName' => $event->user->fullName ?? 'N/A',
            ];
        }
        
        foreach ($checkinEvents as $event) {
            $date = $event->checkinDate->format('Y-m-d');
            if (!isset($calendarEvents[$date])) {
                $calendarEvents[$date] = [];
            }
            $calendarEvents[$date][] = [
                'type' => 'checkin',
                'assetID' => $event->asset->assetID ?? 'N/A',
                'userName' => $event->user->fullName ?? 'N/A',
            ];
        }
        
        foreach ($disposalEvents as $event) {
            // Use dispDate if available, otherwise fall back to created_at
            $date = $event->dispDate 
                ? $event->dispDate->format('Y-m-d') 
                : ($event->created_at ? $event->created_at->format('Y-m-d') : now()->format('Y-m-d'));
            
            if (!isset($calendarEvents[$date])) {
                $calendarEvents[$date] = [];
            }
            $calendarEvents[$date][] = [
                'type' => 'disposal',
                'assetID' => $event->asset->assetID ?? 'N/A',
            ];
        }

        // If AJAX request, return only calendar events
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'calendarEvents' => $calendarEvents,
            ]);
        }

        return view('ITDept.dashboard', [
            'totalActiveAssets' => $totalActiveAssets,
            'assetCheckedOut' => $assetCheckedOut,
            'assetDisposed' => $assetDisposed,
            'totalActiveUsers' => $totalActiveUsers,
            'statusData' => $statusData,
            'calendarEvents' => $calendarEvents,
            'pendingITRequests' => $pendingITRequests,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
        ]);
    }

    public function hodDashboard()
    {
        $user = request()->user();
        
        // Get month and year from request, default to current month/year
        $selectedMonth = request()->input('month', now()->month);
        $selectedYear = request()->input('year', now()->year);
        
        // Validate month and year
        $selectedMonth = max(1, min(12, (int)$selectedMonth));
        $selectedYear = max(2000, min(2100, (int)$selectedYear));

        // Get current user's assigned asset using the User model method
        $currentUserAsset = $user->currentAssignedAsset();
        $userAsset = null;
        
        if ($currentUserAsset) {
            // Load the asset relationship
            $currentUserAsset->load('asset');
            if ($currentUserAsset->asset) {
                $userAsset = $currentUserAsset->asset;
            }
        }

        // Get latest IT requests that haven't been approved/rejected by this HOD yet
        // Only show requests with status 'Pending' that are assigned to this HOD
        // Use the same query structure as approvalRequestForHOD for consistency
        $pendingApprovalRequests = ITRequest::where('approverID', $user->userID)
            ->where('status', 'Pending') // Only pending requests (not yet approved/rejected)
            ->with(['requester', 'asset'])
            ->orderBy('requestDate', 'desc') // Latest first
            ->orderBy('requestID', 'desc') // Most recent request ID first
            ->limit(3) // Show only the 3 latest
            ->get();
        
        // Ensure it's always a collection (get() already returns a collection, but this is a safeguard)
        if (!$pendingApprovalRequests instanceof \Illuminate\Support\Collection) {
            $pendingApprovalRequests = collect();
        }

        // Get IT request activities for calendar
        $currentMonth = $selectedMonth;
        $currentYear = $selectedYear;
        
        // Get all IT requests from department users
        $allITRequests = ITRequest::whereHas('requester', function ($query) use ($user) {
                $query->where('department', $user->department);
            })
            ->with(['requester', 'asset'])
            ->get();
        
        // Combine IT request events by date
        $calendarEvents = [];
        
        foreach ($allITRequests as $request) {
            // Add submitted event (on requestDate)
            $submittedDate = $request->requestDate->format('Y-m-d');
            if ($request->requestDate->year == $currentYear && $request->requestDate->month == $currentMonth) {
                if (!isset($calendarEvents[$submittedDate])) {
                    $calendarEvents[$submittedDate] = [];
                }
                $calendarEvents[$submittedDate][] = [
                    'type' => 'submitted',
                    'requestID' => $request->requestID,
                    'title' => $request->title,
                    'requesterName' => $request->requester->fullName ?? 'N/A',
                    'requesterRole' => $request->requester->role ?? 'Employee',
                    'assetID' => $request->asset->assetID ?? 'N/A',
                    'status' => $request->status,
                ];
            }
            
            // Add approved event (when status is 'Pending IT' and updated_at is in this month)
            if ($request->status === 'Pending IT' && $request->updated_at) {
                $updatedDate = $request->updated_at->format('Y-m-d');
                if ($request->updated_at->year == $currentYear && $request->updated_at->month == $currentMonth) {
                    // Only add if the updated date is different from request date (to avoid duplicates)
                    if ($updatedDate !== $submittedDate) {
                        if (!isset($calendarEvents[$updatedDate])) {
                            $calendarEvents[$updatedDate] = [];
                        }
                        $calendarEvents[$updatedDate][] = [
                            'type' => 'approved',
                            'requestID' => $request->requestID,
                            'title' => $request->title,
                            'requesterName' => $request->requester->fullName ?? 'N/A',
                            'assetID' => $request->asset->assetID ?? 'N/A',
                            'status' => $request->status,
                        ];
                    }
                }
            }
            
            // Add rejected event (when status is 'Rejected' and updated_at is in this month)
            if ($request->status === 'Rejected' && $request->updated_at) {
                $updatedDate = $request->updated_at->format('Y-m-d');
                if ($request->updated_at->year == $currentYear && $request->updated_at->month == $currentMonth) {
                    // Only add if the updated date is different from request date (to avoid duplicates)
                    if ($updatedDate !== $submittedDate) {
                        if (!isset($calendarEvents[$updatedDate])) {
                            $calendarEvents[$updatedDate] = [];
                        }
                        $calendarEvents[$updatedDate][] = [
                            'type' => 'rejected',
                            'requestID' => $request->requestID,
                            'title' => $request->title,
                            'requesterName' => $request->requester->fullName ?? 'N/A',
                            'assetID' => $request->asset->assetID ?? 'N/A',
                            'status' => $request->status,
                        ];
                    }
                }
            }
        }

        // If AJAX request, return only calendar events
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'calendarEvents' => $calendarEvents,
                'selectedMonth' => $selectedMonth,
                'selectedYear' => $selectedYear,
            ]);
        }

        return view('HOD.dashboard', [
            'user' => $user,
            'userAsset' => $userAsset,
            'currentUserAsset' => $currentUserAsset,
            'pendingApprovalRequests' => $pendingApprovalRequests,
            'calendarEvents' => $calendarEvents,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
        ]);
    }

    public function employeeDashboard()
    {
        $user = request()->user();
        
        // Get month and year from request, default to current month/year
        $selectedMonth = request()->input('month', now()->month);
        $selectedYear = request()->input('year', now()->year);
        
        // Validate month and year
        $selectedMonth = max(1, min(12, (int)$selectedMonth));
        $selectedYear = max(2000, min(2100, (int)$selectedYear));

        // Get current user's assigned asset using the User model method
        $currentUserAsset = $user->currentAssignedAsset();
        $userAsset = null;
        
        if ($currentUserAsset) {
            // Load the asset relationship
            $currentUserAsset->load('asset');
            if ($currentUserAsset->asset) {
                $userAsset = $currentUserAsset->asset;
            }
        }

        // Get latest IT requests made by this employee (latest 3)
        $myITRequests = ITRequest::where('requesterID', $user->userID)
            ->with(['requester', 'asset', 'approver'])
            ->orderBy('requestDate', 'desc')
            ->orderBy('requestID', 'desc')
            ->limit(3)
            ->get();
        
        // Ensure it's always a collection
        if (!$myITRequests instanceof \Illuminate\Support\Collection) {
            $myITRequests = collect();
        }

        // Get HOD for the employee's department
        $hod = User::where('role', 'HOD')
            ->where('department', $user->department)
            ->first();

        // Get IT request activities for calendar (employee's own requests)
        $currentMonth = $selectedMonth;
        $currentYear = $selectedYear;
        
        // Get all IT requests made by this employee
        $allITRequests = ITRequest::where('requesterID', $user->userID)
            ->with(['requester', 'asset'])
            ->get();
        
        // Combine IT request events by date
        $calendarEvents = [];
        
        foreach ($allITRequests as $request) {
            // Add submitted event (on requestDate)
            $submittedDate = $request->requestDate->format('Y-m-d');
            if ($request->requestDate->year == $currentYear && $request->requestDate->month == $currentMonth) {
                if (!isset($calendarEvents[$submittedDate])) {
                    $calendarEvents[$submittedDate] = [];
                }
                $calendarEvents[$submittedDate][] = [
                    'type' => 'submitted',
                    'requestID' => $request->requestID,
                    'title' => $request->title,
                    'requesterName' => $request->requester->fullName ?? 'N/A',
                    'requesterRole' => $request->requester->role ?? 'Employee',
                    'assetID' => $request->asset->assetID ?? 'N/A',
                    'status' => $request->status,
                ];
            }
            
            // Add approved event (when status is 'Pending IT' and updated_at is in this month)
            if ($request->status === 'Pending IT' && $request->updated_at) {
                $updatedDate = $request->updated_at->format('Y-m-d');
                if ($request->updated_at->year == $currentYear && $request->updated_at->month == $currentMonth) {
                    // Only add if the updated date is different from request date (to avoid duplicates)
                    if ($updatedDate !== $submittedDate) {
                        if (!isset($calendarEvents[$updatedDate])) {
                            $calendarEvents[$updatedDate] = [];
                        }
                        $calendarEvents[$updatedDate][] = [
                            'type' => 'approved',
                            'requestID' => $request->requestID,
                            'title' => $request->title,
                            'requesterName' => $request->requester->fullName ?? 'N/A',
                            'assetID' => $request->asset->assetID ?? 'N/A',
                            'status' => $request->status,
                        ];
                    }
                }
            }
            
            // Add rejected event (when status is 'Rejected' and updated_at is in this month)
            if ($request->status === 'Rejected' && $request->updated_at) {
                $updatedDate = $request->updated_at->format('Y-m-d');
                if ($request->updated_at->year == $currentYear && $request->updated_at->month == $currentMonth) {
                    // Only add if the updated date is different from request date (to avoid duplicates)
                    if ($updatedDate !== $submittedDate) {
                        if (!isset($calendarEvents[$updatedDate])) {
                            $calendarEvents[$updatedDate] = [];
                        }
                        $calendarEvents[$updatedDate][] = [
                            'type' => 'rejected',
                            'requestID' => $request->requestID,
                            'title' => $request->title,
                            'requesterName' => $request->requester->fullName ?? 'N/A',
                            'assetID' => $request->asset->assetID ?? 'N/A',
                            'status' => $request->status,
                        ];
                    }
                }
            }
        }

        // If AJAX request, return only calendar events
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'calendarEvents' => $calendarEvents,
                'selectedMonth' => $selectedMonth,
                'selectedYear' => $selectedYear,
            ]);
        }

        return view('Employee.dashboard', [
            'user' => $user,
            'userAsset' => $userAsset,
            'currentUserAsset' => $currentUserAsset,
            'hod' => $hod,
            'myITRequests' => $myITRequests,
            'calendarEvents' => $calendarEvents,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
        ]);
    }
}

