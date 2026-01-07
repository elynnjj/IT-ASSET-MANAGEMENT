<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\ITRequest;
use App\Models\Maintenance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ITRequestsController
{
	public function index(Request $request): View
	{
		$user = $request->user();
		$tab = $request->query('tab', 'all');
		$search = $request->query('q');
		$status = $request->query('status');

		// Build query based on user role
		if ($user->role === 'Employee') {
			// Employees can only see their own requests
			$query = ITRequest::where('requesterID', $user->userID);
		} elseif ($user->role === 'HOD') {
			// HODs can see requests assigned to them for approval
			$query = ITRequest::where('approverID', $user->userID);
		} else {
			// ITDept can see all requests
			$query = ITRequest::query();
		}

		// Filter by tab
		if ($tab === 'pending') {
			$query->where('status', 'Pending');
		} elseif ($tab === 'approved') {
			$query->where('status', 'Approved');
		} elseif ($tab === 'rejected') {
			$query->where('status', 'Rejected');
		} elseif ($tab === 'completed') {
			$query->where('status', 'Completed');
		}

		// Filter by status if provided
		if ($status) {
			$query->where('status', $status);
		}

		// Search functionality
		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->where('requestID', 'like', "%{$search}%")
					->orWhere('title', 'like', "%{$search}%")
					->orWhere('requestDesc', 'like', "%{$search}%");
			});
		}

		// Load relationships
		$query->with(['requester', 'approver', 'asset']);

		// Order by request date descending
		$query->orderBy('requestDate', 'desc')->orderBy('requestID', 'desc');

		$requests = $query->paginate(10)->withQueryString();

		return view('ITDept.manageITRequest.manageITRequest', [
			'requests' => $requests,
			'tab' => $tab,
			'search' => $search,
			'status' => $status,
			'userRole' => $user->role,
		]);
	}

	public function create(): View
	{
		$assets = Asset::whereIn('status', ['Available', 'Checked Out'])
			->orderBy('assetID')
			->get(['assetID', 'assetType', 'model', 'status']);

		return view('ITDept.manageITRequest.createITRequest', [
			'assets' => $assets,
		]);
	}

	public function createForEmployee(Request $request): View
	{
		$user = $request->user();
		
		// Get current assigned asset for the employee
		$assignedAsset = $user->currentAssignedAsset();
		if ($assignedAsset) {
			$assignedAsset->load('asset');
		}

		// Get HOD for the employee's department
		$hod = User::where('role', 'HOD')
			->where('department', $user->department)
			->first();

		return view('Employee.manageITRequest.submitITRequests', [
			'assignedAsset' => $assignedAsset,
			'hod' => $hod,
		]);
	}

	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'requestDate' => ['required', 'date'],
			'title' => ['required', 'string', 'max:255'],
			'requestDesc' => ['required', 'string'],
			'assetID' => ['nullable', 'string', 'exists:assets,assetID'],
		]);

		$user = $request->user();

		// Determine approver based on department (HOD of the requester's department)
		$approver = User::where('role', 'HOD')
			->where('department', $user->department)
			->first();

		ITRequest::create([
			'requestDate' => $validated['requestDate'],
			'title' => $validated['title'],
			'requestDesc' => $validated['requestDesc'],
			'status' => 'Pending',
			'assetID' => $validated['assetID'] ?? null,
			'requesterID' => $user->userID,
			'approverID' => $approver?->userID,
		]);

		return redirect()->route('itdept.it-requests.index')
			->with('status', 'IT Request created successfully');
	}

	public function storeForEmployee(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'requestDate' => ['required', 'date'],
			'title' => ['required', 'string', 'max:255'],
			'requestDesc' => ['required', 'string'],
		]);

		$user = $request->user();

		// Get current assigned asset for the employee
		$assignedAsset = $user->currentAssignedAsset();

		// Check if user has an assigned asset
		if (!$assignedAsset) {
			return back()->withErrors(['asset' => 'No assigned asset. You must have an assigned asset to submit an IT request.'])->withInput();
		}

		// Determine approver based on department (HOD of the requester's department)
		$approver = User::where('role', 'HOD')
			->where('department', $user->department)
			->first();

		if (!$approver) {
			return back()->withErrors(['hod' => 'No HOD found for your department. Please contact IT Department.'])->withInput();
		}

		// Create the IT request
		$itRequest = ITRequest::create([
			'requestDate' => $validated['requestDate'],
			'title' => $validated['title'],
			'requestDesc' => $validated['requestDesc'],
			'status' => 'Pending',
			'assetID' => $assignedAsset?->assetID,
			'requesterID' => $user->userID,
			'approverID' => $approver->userID,
		]);

		// Send email notification to HOD
		$itRequest->load(['requester', 'asset']);
		$approver->notify(new \App\Notifications\ITRequestNotification($itRequest));

		return redirect()->route('employee.my-requests')
			->with('status', 'IT Request submitted successfully. Your HOD will be notified via email.');
	}

	public function show(string $requestID): View
	{
		$user = request()->user();
		$itRequest = ITRequest::with(['requester', 'approver', 'asset'])
			->findOrFail($requestID);

		// Check permissions based on role
		if ($user->role === 'Employee' && $itRequest->requesterID !== $user->userID) {
			abort(403, 'Unauthorized access');
		}

		if ($user->role === 'HOD' && $itRequest->approverID !== $user->userID) {
			abort(403, 'Unauthorized access');
		}

		return view('ITDept.manageITRequest.itRequestDetails', [
			'itRequest' => $itRequest,
			'userRole' => $user->role,
		]);
	}

	public function update(Request $request, string $requestID): RedirectResponse
	{
		$itRequest = ITRequest::findOrFail($requestID);
		$user = $request->user();

		// Only allow updates to title and description if status is Pending and user is the requester
		if ($itRequest->status === 'Pending' && $itRequest->requesterID === $user->userID) {
			$validated = $request->validate([
				'title' => ['required', 'string', 'max:255'],
				'requestDesc' => ['required', 'string'],
			]);

			$itRequest->update([
				'title' => $validated['title'],
				'requestDesc' => $validated['requestDesc'],
			]);

			return redirect()->route('itdept.it-requests.show', $requestID)
				->with('status', 'IT Request updated successfully');
		}

		return back()->withErrors(['request' => 'Cannot update request. It may not be pending or you may not have permission.']);
	}

	public function approve(Request $request, string $requestID): RedirectResponse
	{
		$itRequest = ITRequest::findOrFail($requestID);
		$user = $request->user();

		// Only HOD who is the approver can approve
		if ($user->role !== 'HOD' || $itRequest->approverID !== $user->userID) {
			return back()->withErrors(['request' => 'Unauthorized. Only the assigned HOD can approve this request.']);
		}

		if ($itRequest->status !== 'Pending') {
			return back()->withErrors(['request' => 'Only pending requests can be approved.']);
		}

		// Update status to Pending IT (ITDept will handle it)
		$itRequest->status = 'Pending IT';
		$itRequest->save();

		// Load relationships
		$itRequest->load(['requester', 'asset', 'approver']);

		// Send email notification to the employee (requester)
		if ($itRequest->requester) {
			$itRequest->requester->notify(new \App\Notifications\ITRequestStatusNotification(
				$itRequest,
				'approved',
				$user->fullName
			));
		}

		// Send email notification to ITDept users
		$itDeptUsers = User::where('role', 'ITDept')->get();
		foreach ($itDeptUsers as $itDeptUser) {
			$itDeptUser->notify(new \App\Notifications\ITRequestITDeptNotification($itRequest, 'approved_request'));
		}

		return redirect()->route('hod.approval-request')
			->with('status', 'IT Request approved successfully. Employee and IT Department have been notified.');
	}

	public function reject(Request $request, string $requestID): RedirectResponse
	{
		$itRequest = ITRequest::findOrFail($requestID);
		$user = $request->user();

		// Only HOD who is the approver can reject
		if ($user->role !== 'HOD' || $itRequest->approverID !== $user->userID) {
			return back()->withErrors(['request' => 'Unauthorized. Only the assigned HOD can reject this request.']);
		}

		if ($itRequest->status !== 'Pending') {
			return back()->withErrors(['request' => 'Only pending requests can be rejected.']);
		}

		$itRequest->status = 'Rejected';
		$itRequest->save();

		// Load relationships
		$itRequest->load(['requester', 'asset']);

		// Send email notification to the employee (requester)
		if ($itRequest->requester) {
			$itRequest->requester->notify(new \App\Notifications\ITRequestStatusNotification(
				$itRequest,
				'rejected',
				$user->fullName
			));
		}

		return redirect()->route('hod.approval-request')
			->with('status', 'IT Request rejected successfully. Employee has been notified.');
	}

	public function complete(Request $request, string $requestID): RedirectResponse
	{
		$itRequest = ITRequest::findOrFail($requestID);
		$user = $request->user();

		// Only ITDept can mark requests as completed
		if ($user->role !== 'ITDept') {
			return back()->withErrors(['request' => 'Unauthorized. Only IT Department can complete requests.']);
		}

		if ($itRequest->status !== 'Pending IT') {
			return back()->withErrors(['request' => 'Only requests with Pending IT status can be completed.']);
		}

		$itRequest->status = 'Completed';
		$itRequest->save();

		// Load relationships for notification
		$itRequest->load(['requester', 'asset']);

		// Send email notification to the requester (Employee or HOD)
		if ($itRequest->requester) {
			$itRequest->requester->notify(new \App\Notifications\ITRequestStatusNotification(
				$itRequest,
				'completed'
			));
		}

		return redirect()->route('itdept.it-requests.show', $requestID)
			->with('status', 'IT Request marked as completed successfully. Requester has been notified via email.');
	}

	public function destroy(string $requestID): RedirectResponse
	{
		$itRequest = ITRequest::findOrFail($requestID);
		$user = request()->user();

		// Only allow deletion if status is Pending or Pending IT and user is the requester or ITDept
		if (!in_array($itRequest->status, ['Pending', 'Pending IT'])) {
			return back()->withErrors(['request' => 'Only pending requests can be deleted.']);
		}

		if ($user->role !== 'ITDept' && $itRequest->requesterID !== $user->userID) {
			return back()->withErrors(['request' => 'Unauthorized. You can only delete your own requests.']);
		}

		$itRequest->delete();

		// Redirect based on user role
		$redirectRoute = match($user->role) {
			'Employee' => 'employee.my-requests',
			'HOD' => 'hod.my-requests',
			default => 'itdept.it-requests.index',
		};

		return redirect()->route($redirectRoute)
			->with('status', 'IT Request deleted successfully');
	}

	public function myRequestsForEmployee(Request $request): View
	{
		$user = $request->user();

		// Get all requests made by this employee
		$requests = ITRequest::where('requesterID', $user->userID)
			->with(['approver', 'asset'])
			->orderBy('requestDate', 'desc')
			->orderBy('requestID', 'desc')
			->paginate(10);

		return view('Employee.manageITRequest.myRequest', [
			'requests' => $requests,
		]);
	}

	public function createForHOD(Request $request): View
	{
		$user = $request->user();
		
		// Get current assigned asset for the HOD
		$assignedAsset = $user->currentAssignedAsset();
		if ($assignedAsset) {
			$assignedAsset->load('asset');
		}

		return view('HOD.manageITRequest.submitITRequests', [
			'assignedAsset' => $assignedAsset,
		]);
	}

	public function storeForHOD(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'requestDate' => ['required', 'date'],
			'title' => ['required', 'string', 'max:255'],
			'requestDesc' => ['required', 'string'],
		]);

		$user = $request->user();

		// Get current assigned asset for the HOD
		$assignedAsset = $user->currentAssignedAsset();

		// Check if user has an assigned asset
		if (!$assignedAsset) {
			return back()->withErrors(['asset' => 'No assigned asset. You must have an assigned asset to submit an IT request.'])->withInput();
		}

		// Create the IT request - HOD requests go directly to ITDept (Pending IT status)
		// approverID is set to null to differentiate HOD requests from Employee requests
		// Employee requests have approverID set to HOD's userID and start with "Pending" status
		$itRequest = ITRequest::create([
			'requestDate' => $validated['requestDate'],
			'title' => $validated['title'],
			'requestDesc' => $validated['requestDesc'],
			'status' => 'Pending IT', // HOD requests skip "Pending" status and go straight to ITDept
			'assetID' => $assignedAsset?->assetID,
			'requesterID' => $user->userID,
			'approverID' => null, // null = HOD request (no approval needed), HOD's userID = Employee request
		]);

		// Send email notification to ITDept users
		$itRequest->load(['requester', 'asset']);
		$itDeptUsers = User::where('role', 'ITDept')->get();
		foreach ($itDeptUsers as $itDeptUser) {
			$itDeptUser->notify(new \App\Notifications\ITRequestITDeptNotification($itRequest, 'new_request'));
		}

		return redirect()->route('hod.my-requests')
			->with('status', 'IT Request submitted successfully. IT Department has been notified.');
	}

	public function myRequestsForHOD(Request $request): View
	{
		$user = $request->user();

		// Get all requests made by this HOD
		$requests = ITRequest::where('requesterID', $user->userID)
			->with(['approver', 'asset'])
			->orderBy('requestDate', 'desc')
			->orderBy('requestID', 'desc')
			->paginate(10);

		return view('HOD.manageITRequest.myRequest', [
			'requests' => $requests,
		]);
	}

	public function approvalRequestForHOD(Request $request): View
	{
		$user = $request->user();
		$status = $request->query('status');

		// Get all requests from employees in the HOD's department that need approval
		$query = ITRequest::where('approverID', $user->userID)
			->with(['requester', 'asset']);

		// Filter by status if provided
		if ($status) {
			$query->where('status', $status);
		}

		// Order by request date descending
		$query->orderBy('requestDate', 'desc')->orderBy('requestID', 'desc');

		$requests = $query->paginate(10)->withQueryString();

		return view('HOD.manageITRequest.approvalRequest', [
			'requests' => $requests,
		]);
	}

	public function indexForITDept(Request $request): View
	{
		$search = $request->query('q');
		$status = $request->query('status');
		$assetType = $request->query('assetType');

		// Get all IT requests with status "Pending IT" or "Completed"
		$query = ITRequest::whereIn('status', ['Pending IT', 'Completed'])
			->with(['requester', 'asset']);

		// Filter by status
		if ($status) {
			$query->where('status', $status);
		}

		// Filter by asset type
		if ($assetType) {
			$query->whereHas('asset', function ($q) use ($assetType) {
				$q->where('assetType', $assetType);
			});
		}

		// Search by asset ID
		if ($search) {
			$query->whereHas('asset', function ($q) use ($search) {
				$q->where('assetID', 'like', "%{$search}%");
			});
		}

		// Order by request date descending
		$query->orderBy('requestDate', 'desc')->orderBy('requestID', 'desc');

		$requests = $query->paginate(10)->withQueryString();

		// Get unique asset types for filter
		$assetTypes = Asset::distinct()->pluck('assetType')->filter()->sort()->values();

		return view('ITDept.manageITRequest.ITRequests', [
			'requests' => $requests,
			'assetTypes' => $assetTypes,
		]);
	}

	public function showForITDept(string $requestID): View
	{
		$itRequest = ITRequest::with([
			'requester',
			'asset',
			'approver',
			'maintenances'
		])->findOrFail($requestID);

		// Get HOD of requester's department
		$hod = null;
		if ($itRequest->requester && $itRequest->requester->department) {
			$hod = User::where('role', 'HOD')
				->where('department', $itRequest->requester->department)
				->first();
		}

		return view('ITDept.manageITRequest.requestDetails', [
			'itRequest' => $itRequest,
			'hod' => $hod,
		]);
	}

	public function storeMaintenance(Request $request, string $requestID): RedirectResponse
	{
		$validated = $request->validate([
			'mainDate' => ['required', 'date'],
			'mainDesc' => ['required', 'string'],
			'updateAsset' => ['nullable', 'in:0,1'],
		]);

		$itRequest = ITRequest::with('asset')->findOrFail($requestID);

		// Create maintenance record
		Maintenance::create([
			'mainDate' => $validated['mainDate'],
			'mainDesc' => $validated['mainDesc'],
			'assetID' => $itRequest->assetID,
			'requestID' => $requestID,
		]);

		// Update request status to Completed
		$itRequest->status = 'Completed';
		$itRequest->save();

		// Load relationships for notification
		$itRequest->load(['requester', 'asset']);

		// Send email notification to the requester (Employee or HOD)
		if ($itRequest->requester) {
			$itRequest->requester->notify(new \App\Notifications\ITRequestStatusNotification(
				$itRequest,
				'completed'
			));
		}

		// Check if user wants to update asset details
		$updateAsset = $request->input('updateAsset', '0') === '1';

		if ($updateAsset && $itRequest->asset) {
			return redirect()->route('itdept.manage-assets.edit', $itRequest->asset->assetID)
				->with('status', 'Maintenance details added successfully. Please update the asset details.');
		}

		return redirect()->route('itdept.it-requests.show', $requestID)
			->with('status', 'Maintenance details added successfully. Request marked as completed. Requester has been notified via email.');
	}

	public function repairsAndMaintenance(Request $request): View
	{
		$assetType = $request->query('assetType');
		$search = $request->query('q');

		// Get all maintenance records without IT Request (requestID is null)
		$query = Maintenance::whereNull('requestID')
			->with('asset');

		// Filter by asset type
		if ($assetType) {
			$query->whereHas('asset', function ($q) use ($assetType) {
				$q->where('assetType', $assetType);
			});
		}

		// Search by asset ID
		if ($search) {
			$query->whereHas('asset', function ($q) use ($search) {
				$q->where('assetID', 'like', "%{$search}%");
			});
		}

		// Order by maintenance date descending
		$query->orderBy('mainDate', 'desc')->orderBy('mainID', 'desc');

		$maintenances = $query->paginate(10)->withQueryString();

		// Get unique asset types for filter
		$assetTypes = Asset::distinct()->pluck('assetType')->filter()->sort()->values();

		return view('ITDept.manageITRequest.repairsAndMaintenance', [
			'maintenances' => $maintenances,
			'assetTypes' => $assetTypes,
		]);
	}

	public function createMaintenance(Request $request): View
	{
		// Get unique asset types for dropdown
		$assetTypes = Asset::distinct()->pluck('assetType')->filter()->sort()->values();

		return view('ITDept.manageITRequest.newMaintenance', [
			'assetTypes' => $assetTypes,
		]);
	}

	public function storeMaintenanceWithoutRequest(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'assetID' => ['required', 'string'],
			'mainDate' => ['required', 'date'],
			'mainDesc' => ['required', 'string'],
			'updateAsset' => ['nullable', 'in:0,1'],
		], [
			'assetID.required' => 'The asset ID field is required.',
			'mainDate.required' => 'The maintenance date field is required.',
			'mainDesc.required' => 'The maintenance description field is required.',
		], [
			'assetID' => 'asset ID',
			'mainDate' => 'maintenance date',
			'mainDesc' => 'maintenance description',
		]);

		// Verify asset exists
		$asset = Asset::findOrFail($validated['assetID']);

		// Create maintenance record without requestID (null)
		Maintenance::create([
			'mainDate' => $validated['mainDate'],
			'mainDesc' => $validated['mainDesc'],
			'assetID' => $validated['assetID'],
			'requestID' => null,
		]);

		// Check if user wants to update asset details
		$updateAsset = $request->input('updateAsset', '0') === '1';

		if ($updateAsset) {
			return redirect()->route('itdept.manage-assets.edit', $validated['assetID'])
				->with('status', 'Maintenance details added successfully. Please update the asset details.');
		}

		return redirect()->route('itdept.repairs-maintenance')
			->with('status', 'Maintenance details added successfully.');
	}

	public function getAssetsByTypeForMaintenance(Request $request)
	{
		$assetType = $request->query('assetType');
		
		if (!$assetType) {
			return response()->json([]);
		}

		// Get all assets of the specified type (for maintenance, we show all assets)
		$assets = Asset::where('assetType', $assetType)
			->orderBy('assetID')
			->get(['assetID', 'assetType', 'model']);

		return response()->json($assets);
	}
}

