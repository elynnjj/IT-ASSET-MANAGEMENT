<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssignAsset;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ManageAssetController
{
	public function index(Request $request): View
	{
		$assetType = $request->query('assetType');
		$search = $request->query('q');
		$status = $request->query('status');

		$allowedSorts = ['assetID', 'assetType', 'serialNum', 'model', 'status', 'purchaseDate'];
		$sort = $request->query('sort');
		$dir = strtolower((string) $request->query('dir')) === 'desc' ? 'desc' : 'asc';
		if (!in_array($sort, $allowedSorts, true)) {
			$sort = 'assetID';
		}

		$query = Asset::query()
			->with(['assignments' => function ($q) {
				$q->whereNull('checkinDate')->with('user');
			}])
			->whereIn('status', ['Available', 'Checked Out']) // Only show active assets
			->when($assetType, function ($q) use ($assetType) {
				$q->where('assetType', $assetType);
			})
			->when($search, function ($q) use ($search) {
				$q->where(function ($qq) use ($search) {
					$qq->where('assetID', 'like', "%{$search}%")
						->orWhere('serialNum', 'like', "%{$search}%")
						->orWhere('model', 'like', "%{$search}%");
				});
			})
			->when($status, function ($q) use ($status) {
				$q->where('status', $status);
			})
			->orderBy($sort, $dir);

		$assets = $query->paginate(10)->withQueryString();

		return view('ITDept.manageAsset.manageAsset', [
			'assets' => $assets,
			'assetType' => $assetType,
			'sort' => $sort,
			'dir' => $dir,
			'q' => $search,
			'filterStatus' => $status,
		]);
	}

	public function create(): View
	{
		return view('ITDept.manageAsset.addAsset');
	}

	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'assetID' => ['required', 'string', 'max:255', 'unique:assets,assetID'],
			'assetType' => ['required', 'in:Laptop,Desktop'],
			'serialNum' => ['nullable', 'string', 'max:255'],
			'model' => ['nullable', 'string', 'max:255'],
			'ram' => ['nullable', 'string', 'max:255'],
			'storage' => ['nullable', 'string', 'max:255'],
			'purchaseDate' => ['nullable', 'date'],
			'osVer' => ['nullable', 'in:Windows 10,Windows 11'],
			'processor' => ['nullable', 'string', 'max:255'],
		]);

		$payload = $validated;
		$payload['status'] = 'Available';

		Asset::create($payload);

		return redirect()->route('itdept.manage-assets.index', ['assetType' => $validated['assetType']])
			->with('status', 'Asset created');
	}

	public function show(string $assetID): View
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();
		$currentAssignment = $asset->currentAssignment();
		
		// Get all previous assignments (where checkinDate is not null)
		$previousAssignments = AssignAsset::where('assetID', $assetID)
			->whereNotNull('checkinDate')
			->with('user')
			->orderBy('checkoutDate', 'desc')
			->get();
		
		return view('ITDept.manageAsset.assetDetails', [
			'asset' => $asset,
			'currentAssignment' => $currentAssignment,
			'previousAssignments' => $previousAssignments,
		]);
	}

	public function checkoutForm(string $assetID): View
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();
		$users = User::where('role', '!=', 'ITDept')
			->where('accStat', 'active')
			->orderBy('fullName')
			->get();

		// Get active assignments for each user (where checkinDate is null)
		$userAssignments = AssignAsset::whereNull('checkinDate')
			->with(['user', 'asset'])
			->get()
			->keyBy('userID');

		return view('ITDept.manageAsset.checkOutAsset', [
			'asset' => $asset,
			'users' => $users,
			'userAssignments' => $userAssignments,
		]);
	}

	public function checkout(Request $request, string $assetID): RedirectResponse
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();

		$validated = $request->validate([
			'userID' => ['required', 'string', 'exists:users,userID'],
			'checkoutDate' => ['required', 'date'],
		]);

		// Check if asset is available
		if ($asset->status !== 'Available') {
			return back()->withErrors(['asset' => 'Asset is not available for checkout']);
		}

		// Check if user already has an active assignment
		$activeAssignment = AssignAsset::where('userID', $validated['userID'])
			->whereNull('checkinDate')
			->with(['user', 'asset'])
			->first();

		if ($activeAssignment) {
			$user = $activeAssignment->user;
			$assignedAsset = $activeAssignment->asset;
			return back()->withErrors([
				'userID' => "{$user->fullName} has been assigned to {$assignedAsset->assetID}. Please check-in the current asset before assigning a new one."
			]);
		}

		// Create assignment record
		AssignAsset::create([
			'assetID' => $asset->assetID,
			'userID' => $validated['userID'],
			'checkoutDate' => $validated['checkoutDate'],
		]);

		// Update asset status
		$asset->status = 'Checked Out';
		$asset->save();

		return redirect()->route('itdept.manage-assets.show', $asset->assetID)
			->with('status', 'Asset checked out successfully');
	}

	public function checkin(string $assetID): RedirectResponse
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();
		$currentAssignment = $asset->currentAssignment();

		if (!$currentAssignment) {
			return back()->withErrors(['asset' => 'No active assignment found for this asset']);
		}

		// Update assignment with check-in date
		$currentAssignment->checkinDate = Carbon::now();
		$currentAssignment->save();

		// Update asset status back to Available
		$asset->status = 'Available';
		$asset->save();

		return redirect()->route('itdept.manage-assets.show', $asset->assetID)
			->with('status', 'Asset checked in successfully');
	}

	public function edit(string $assetID): View
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();
		$invoices = Invoice::all();
		return view('ITDept.manageAsset.editAsset', ['asset' => $asset, 'invoices' => $invoices]);
	}

	public function update(Request $request, string $assetID): RedirectResponse
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();

		$validated = $request->validate([
			'assetType' => ['nullable', 'in:Laptop,Desktop'],
			'serialNum' => ['nullable', 'string', 'max:255'],
			'model' => ['nullable', 'string', 'max:255'],
			'ram' => ['nullable', 'string', 'max:255'],
			'storage' => ['nullable', 'string', 'max:255'],
			'purchaseDate' => ['nullable', 'date'],
			'osVer' => ['nullable', 'in:Windows 10,Windows 11'],
			'processor' => ['nullable', 'string', 'max:255'],
		]);

		// Use existing assetType if not provided
		if (!isset($validated['assetType'])) {
			$validated['assetType'] = $asset->assetType;
		}

		$asset->update($validated);

		return redirect()->route('itdept.manage-assets.index', ['assetType' => $asset->assetType])
			->with('status', 'Asset updated');
	}

	public function destroy(string $assetID): RedirectResponse
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();
		$asset->delete();
		return back()->with('status', 'Asset deleted');
	}

	public function downloadTemplate(): StreamedResponse
	{
		$headers = ['assetID', 'assetType', 'serialNum', 'model', 'ram', 'storage', 'purchaseDate', 'osVer', 'processor'];
		$filename = 'asset_import_template.csv';
		$currentDate = Carbon::now()->format('Y-m-d');

		return response()->streamDownload(function () use ($headers, $currentDate) {
			$output = fopen('php://output', 'w');
			fputcsv($output, $headers);
			// Example row with current date
			fputcsv($output, ['LAP001', 'Laptop', 'SN123456', 'Dell Latitude 5420', '16GB', '512GB SSD', $currentDate, 'Windows 11', 'Intel i7-11850H']);
			fclose($output);
		}, $filename, [
			'Content-Type' => 'text/csv',
			'Content-Disposition' => 'attachment; filename="asset_import_template.csv"',
		]);
	}

	public function importCsv(Request $request): RedirectResponse
	{
		$request->validate([
			'file' => ['required', 'file', 'mimes:csv,txt'],
		]);

		$path = $request->file('file')->getRealPath();
		$handle = fopen($path, 'r');
		if ($handle === false) {
			return back()->withErrors(['file' => 'Cannot open uploaded file']);
		}

		$header = fgetcsv($handle);
		if (!$header) {
			fclose($handle);
			return back()->withErrors(['file' => 'Empty CSV file']);
		}

		// Normalize headers (trim whitespace and convert to lowercase for matching)
		$header = array_map('trim', $header);
		$headerMap = array_combine(array_map('strtolower', $header), $header);

		$created = 0; $skipped = 0; $errors = 0;

		while (($row = fgetcsv($handle)) !== false) {
			if (count($row) !== count($header)) {
				$skipped++;
				continue;
			}
			
			$data = [];
			foreach ($header as $index => $headerName) {
				$data[$headerName] = isset($row[$index]) ? trim($row[$index]) : '';
			}
			
			if (empty($data)) { $skipped++; continue; }

			// Basic per-row validation
			if (!isset($data['assetID'], $data['assetType'])) {
				$skipped++; continue;
			}
			if (!in_array($data['assetType'], ['Laptop', 'Desktop'], true)) {
				$skipped++; continue;
			}
			
			// Validate osVer if provided
			if (isset($data['osVer']) && !empty($data['osVer']) && !in_array($data['osVer'], ['Windows 10', 'Windows 11'], true)) {
				$skipped++; continue;
			}

			try {
				// Skip existing assets entirely (do not update)
				if (Asset::where('assetID', trim($data['assetID']))->exists()) {
					$skipped++;
					continue;
				}

				$asset = new Asset();
				$asset->assetID = trim($data['assetID']);
				$asset->assetType = trim($data['assetType']);
				$asset->serialNum = !empty($data['serialNum']) ? trim($data['serialNum']) : null;
				$asset->model = !empty($data['model']) ? strtoupper(trim($data['model'])) : null;
				$asset->ram = !empty($data['ram']) ? trim($data['ram']) : null;
				$asset->storage = !empty($data['storage']) ? trim($data['storage']) : null;
				
				// Handle purchaseDate - validate format (model casts to date)
				// If empty, automatically set to current date
				if (!empty($data['purchaseDate'])) {
					$date = trim($data['purchaseDate']);
					try {
						// Validate and parse date format
						$parsedDate = Carbon::createFromFormat('Y-m-d', $date);
						$asset->purchaseDate = $parsedDate->toDateString();
					} catch (\Exception $e) {
						// If date parsing fails, set to current date
						$asset->purchaseDate = Carbon::now()->toDateString();
					}
				} else {
					// If purchaseDate is empty, automatically set to current date
					$asset->purchaseDate = Carbon::now()->toDateString();
				}
				
				$asset->osVer = !empty($data['osVer']) ? trim($data['osVer']) : null;
				$asset->processor = !empty($data['processor']) ? trim($data['processor']) : null;
				$asset->status = 'Available';
				$asset->save();
				$created++;
			} catch (\Throwable $e) {
				Log::error('Asset import error: ' . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString()]);
				$errors++;
			}
		}

		fclose($handle);

		return back()->with('status', "Imported: $created created, $skipped skipped (existing), $errors errors");
	}

	public function uploadInvoiceForm(): View
	{
		return view('ITDept.manageAsset.uploadInvoiceForm');
	}

	public function storeInvoice(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'invoiceFile' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
			'assetCount' => ['required', 'integer', 'min:1', 'max:100'],
			'assets' => ['required', 'array', 'min:1'],
			'assets.*.assetType' => ['required', 'in:Laptop,Desktop'],
			'assets.*.assetID' => ['required', 'string', 'exists:assets,assetID'],
		]);

		// Store the invoice file
		$file = $request->file('invoiceFile');
		$fileName = $file->getClientOriginalName();
		$filePath = $file->store('invoices', 'public');

		// Create invoice record (invoiceID will be auto-generated)
		$invoice = Invoice::create([
			'fileName' => $fileName,
			'filePath' => $filePath,
		]);

		// Update assets with invoice ID
		foreach ($validated['assets'] as $assetData) {
			$asset = Asset::where('assetID', $assetData['assetID'])->first();
			if ($asset) {
				$asset->invoiceID = $invoice->invoiceID;
				$asset->save();
			}
		}

		return redirect()->route('itdept.manage-assets.index')
			->with('status', 'Invoice uploaded and linked to assets successfully');
	}

	public function getAssetsByType(Request $request)
	{
		$assetType = $request->query('assetType');
		
		if (!$assetType || !in_array($assetType, ['Laptop', 'Desktop'])) {
			return response()->json([]);
		}

		$assets = Asset::where('assetType', $assetType)
			->whereNull('invoiceID') // Only show assets that don't have an invoice yet
			->orderBy('assetID')
			->get(['assetID', 'assetType', 'model']);

		return response()->json($assets);
	}

	public function downloadInvoice(string $invoiceID)
	{
		$invoice = Invoice::findOrFail($invoiceID);
		
		// If filePath exists, use it
		if ($invoice->filePath && Storage::disk('public')->exists($invoice->filePath)) {
			return Storage::disk('public')->download($invoice->filePath, $invoice->fileName);
		}
		
		// Fallback: try to find file by searching all invoice files
		// This handles old invoices that don't have filePath stored
		$allFiles = Storage::disk('public')->allFiles('invoices');
		
		// Try to match by checking if any file's last modified time matches invoice creation
		// or use a simple approach: get all files and try the most recent one
		// Note: This is a fallback - ideally all invoices should have filePath stored
		if (count($allFiles) > 0) {
			// Get the most recently modified file as a fallback
			$filesWithTime = collect($allFiles)->map(function($file) {
				return [
					'path' => $file,
					'time' => Storage::disk('public')->lastModified($file)
				];
			})->sortByDesc('time');
			
			$matchingFile = $filesWithTime->first()['path'] ?? null;
			if ($matchingFile && Storage::disk('public')->exists($matchingFile)) {
				return Storage::disk('public')->download($matchingFile, $invoice->fileName);
			}
		}
		
		abort(404, 'Invoice file not found');
	}

	public function installedSoftwareForm(string $assetID): View
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();
		
		// Parse existing software
		$existingSoftware = [];
		$officeVersion = null;
		$othersSoftware = null;
		
		if ($asset->installedSoftware) {
			// Handle both comma-separated and newline-separated (for backward compatibility)
			$softwareArray = preg_split('/[,\n]+/', $asset->installedSoftware);
			$softwareArray = array_map('trim', $softwareArray);
			$softwareArray = array_filter($softwareArray);
			
			$predefinedSoftware = [
				'Adobe Acrobat Reader',
				'Adobe Acrobat Pro DC',
				'Foxit Reader',
				'7zip',
				'Anydesk',
				'Antivirus',
				'VPN',
				'Autodesk AutoCad',
				'DraftSight',
				'ProgeCad'
			];
			
			foreach ($softwareArray as $software) {
				// Check if it's Microsoft Office with version
				if (preg_match('/^Microsoft Office\s+(\d{4})$/', $software, $matches)) {
					$existingSoftware[] = 'Microsoft Office';
					$officeVersion = $matches[1];
				}
				// Check if it's a predefined software
				elseif (in_array($software, $predefinedSoftware)) {
					$existingSoftware[] = $software;
				}
				// Otherwise it's "Others"
				else {
					$existingSoftware[] = 'Others';
					$othersSoftware = $software;
				}
			}
		}
		
		return view('ITDept.manageAsset.installedSoftwareForm', [
			'asset' => $asset,
			'existingSoftware' => $existingSoftware,
			'officeVersion' => $officeVersion,
			'othersSoftware' => $othersSoftware,
		]);
	}

	public function storeInstalledSoftware(Request $request, string $assetID): RedirectResponse
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();

		$validated = $request->validate([
			'software' => ['nullable', 'array'],
			'software.*' => ['string'],
			'officeVersion' => ['nullable', 'string', 'in:2010,2013,2019,2020,2024'],
			'othersSoftware' => ['nullable', 'string', 'max:255'],
		]);

		$softwareList = [];

		if (!empty($validated['software'])) {
			foreach ($validated['software'] as $software) {
				if ($software === 'Microsoft Office' && !empty($validated['officeVersion'])) {
					$softwareList[] = 'Microsoft Office ' . $validated['officeVersion'];
				} elseif ($software === 'Others' && !empty($validated['othersSoftware'])) {
					$softwareList[] = trim($validated['othersSoftware']);
				} elseif ($software !== 'Microsoft Office' && $software !== 'Others') {
					$softwareList[] = $software;
				}
			}
		}

		// Update asset with software list as comma-separated string
		$asset->installedSoftware = !empty($softwareList) ? implode(', ', $softwareList) : null;
		$asset->save();

		return redirect()->route('itdept.manage-assets.show', $asset->assetID)
			->with('status', 'Installed software updated successfully');
	}
}

