<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssignAsset;
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
        $assetStatusCounts = Asset::selectRaw('COALESCE(status, "Available") as status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Ensure we have all statuses
        $statusLabels = ['Available', 'Checked Out', 'Disposed'];
        $statusData = [];
        foreach ($statusLabels as $status) {
            $statusData[$status] = $assetStatusCounts[$status] ?? 0;
        }

        // Get calendar events for the current month
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Get checkout dates
        $checkoutDates = AssignAsset::whereYear('checkoutDate', $currentYear)
            ->whereMonth('checkoutDate', $currentMonth)
            ->pluck('checkoutDate')
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->toArray();
        
        // Get checkin dates
        $checkinDates = AssignAsset::whereNotNull('checkinDate')
            ->whereYear('checkinDate', $currentYear)
            ->whereMonth('checkinDate', $currentMonth)
            ->pluck('checkinDate')
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->toArray();
        
        // Get disposed assets (using updated_at when status is Disposed)
        // Note: This assumes disposal happens when status is set to Disposed
        $disposalDates = Asset::where('status', 'Disposed')
            ->whereYear('updated_at', $currentYear)
            ->whereMonth('updated_at', $currentMonth)
            ->get()
            ->map(function ($asset) {
                return $asset->updated_at->format('Y-m-d');
            })
            ->toArray();
        
        // Combine all events by date
        $calendarEvents = [];
        foreach ($checkoutDates as $date) {
            if (!isset($calendarEvents[$date])) {
                $calendarEvents[$date] = [];
            }
            $calendarEvents[$date][] = 'checkout';
        }
        
        foreach ($checkinDates as $date) {
            if (!isset($calendarEvents[$date])) {
                $calendarEvents[$date] = [];
            }
            $calendarEvents[$date][] = 'checkin';
        }
        
        foreach ($disposalDates as $date) {
            if (!isset($calendarEvents[$date])) {
                $calendarEvents[$date] = [];
            }
            $calendarEvents[$date][] = 'disposal';
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

