<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssignAsset;
use App\Models\Disposal;
use App\Models\User;
use Illuminate\View\View;

class DashboardController
{
    public function index(): View
    {
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

        // Get calendar events for the current month with detailed information
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
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

        return view('ITDept.dashboard', [
            'totalActiveAssets' => $totalActiveAssets,
            'assetCheckedOut' => $assetCheckedOut,
            'assetDisposed' => $assetDisposed,
            'totalActiveUsers' => $totalActiveUsers,
            'statusData' => $statusData,
            'calendarEvents' => $calendarEvents,
        ]);
    }
}

