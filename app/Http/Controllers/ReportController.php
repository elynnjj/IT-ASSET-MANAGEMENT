<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Disposal;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController
{
	public function index(): View
	{
		return view('ITDept.generateReport.reports');
	}

	public function generateReport(Request $request)
	{
		$validated = $request->validate([
			'reportType' => ['required', 'in:asset-inventory,user-report'],
			'assetType' => ['required_if:reportType,asset-inventory', 'nullable', 'in:all,Laptop,Desktop'],
			'assetStatus' => ['required_if:reportType,asset-inventory', 'nullable', 'in:all,available,disposed'],
			'department' => ['required_if:reportType,user-report', 'nullable'],
			'userRole' => ['required_if:reportType,user-report', 'nullable', 'in:all,HOD,Employee'],
			'userStatus' => ['required_if:reportType,user-report', 'nullable', 'in:all,active,inactive'],
		]);

		if ($validated['reportType'] === 'asset-inventory') {
			return $this->generateAssetInventoryReport($validated);
		} else {
			return $this->generateUserReport($validated);
		}
	}

	private function generateAssetInventoryReport(array $filters)
	{
		$assetType = $filters['assetType'] ?? 'all';
		$assetStatus = $filters['assetStatus'] ?? 'all';

		// Start building query with eager loading
		$query = Asset::with([
			'assignments' => function ($q) {
				$q->whereNull('checkinDate')->with('user');
			},
			'disposals',
			'invoice'
		]);

		// Filter by asset type
		if ($assetType !== 'all') {
			$query->where('assetType', $assetType);
		}

		// Filter by asset status
		if ($assetStatus === 'available') {
			// Available = Available or Checked Out (not disposed)
			// Exclude assets that have disposal records
			$query->whereIn('status', ['Available', 'Checked Out'])
				->whereDoesntHave('disposals');
		} elseif ($assetStatus === 'disposed') {
			// Disposed = assets with disposal records (Pending or Disposed status) or status is Disposed
			$query->where(function ($q) {
				$q->whereHas('disposals', function ($subQ) {
					$subQ->whereIn('dispStatus', ['Pending', 'Disposed']);
				})->orWhere('status', 'Disposed');
			});
		}

		// Get results
		$assets = $query->orderBy('assetID')->get();

		// Prepare data for PDF
		$data = [
			'assets' => $assets,
			'filters' => [
				'assetType' => $assetType === 'all' ? 'All Asset' : $assetType,
				'assetStatus' => $assetStatus === 'all' ? 'All Asset' : ($assetStatus === 'available' ? 'Available' : 'Disposed'),
			],
			'generatedAt' => now()->format('Y-m-d H:i:s'),
		];

		$pdf = Pdf::loadView('ITDept.generateReport.pdf.asset-inventory', $data);
		$pdf->setPaper('a4', 'landscape');

		$filename = 'Asset_Inventory_Report_' . now()->format('Ymd_His') . '.pdf';

		return $pdf->download($filename);
	}

	private function generateUserReport(array $filters)
	{
		$department = $filters['department'] ?? 'all';
		$userRole = $filters['userRole'] ?? 'all';
		$userStatus = $filters['userStatus'] ?? 'all';

		// Build query
		$query = User::query();

		// Filter by department
		if ($department !== 'all') {
			$query->where('department', $department);
		}

		// Filter by user role
		if ($userRole !== 'all') {
			$query->where('role', $userRole);
		}

		// Filter by status
		if ($userStatus === 'active') {
			$query->where('accStat', 'active');
		} elseif ($userStatus === 'inactive') {
			$query->where('accStat', 'inactive');
		}
		// If 'all', don't filter by status

		// Exclude ITDept role from reports
		$users = $query->whereIn('role', ['HOD', 'Employee'])
			->orderBy('department')
			->orderBy('role')
			->orderBy('fullName')
			->get();

		// Prepare data for PDF
		$data = [
			'users' => $users,
			'filters' => [
				'department' => $department === 'all' ? 'All Department' : $department,
				'userRole' => $userRole === 'all' ? 'All Roles' : $userRole,
			],
			'generatedAt' => now()->format('Y-m-d H:i:s'),
		];

		$pdf = Pdf::loadView('ITDept.generateReport.pdf.user-report', $data);
		$pdf->setPaper('a4', 'landscape');

		$filename = 'User_Report_' . now()->format('Ymd_His') . '.pdf';

		return $pdf->download($filename);
	}
}

