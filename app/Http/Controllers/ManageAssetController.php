<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssignAsset;
use App\Models\Invoice;
use App\Models\User;
use App\Notifications\AssetAssignmentNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ManageAssetController
{
	/**
	 * Generate the next asset ID based on asset type
	 * Format: LAPTOP0001, LAPTOP0002, DESKTOP0001, etc.
	 */
	private function generateNextAssetID(string $assetType): string
	{
		$prefix = strtoupper($assetType);
		
		// Find all assets of this type that match the prefix pattern
		$assets = Asset::where('assetType', $assetType)
			->where('assetID', 'like', $prefix . '%')
			->get();
		
		$maxNumber = 0;
		$prefixLength = strlen($prefix);
		
		foreach ($assets as $asset) {
			// Extract the number part after the prefix
			$numberPart = substr($asset->assetID, $prefixLength);
			// Check if it's a valid number
			if (is_numeric($numberPart)) {
				$num = (int)$numberPart;
				if ($num > $maxNumber) {
					$maxNumber = $num;
				}
			}
		}
		
		$nextNumber = $maxNumber + 1;
		
		// Format with leading zeros (4 digits)
		return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
	}

	public function index(Request $request): View
	{
		$assetType = $request->query('assetType');
		$search = $request->query('q');
		$status = $request->query('status');

		$allowedSorts = ['assetID', 'assetType', 'purchaseDate'];
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
						->orWhere('model', 'like', "%{$search}%")
						->orWhereHas('assignments', function ($assignmentQuery) use ($search) {
							$assignmentQuery->whereNull('checkinDate')
								->where(function ($userQuery) use ($search) {
									$userQuery->where('userFullName', 'like', "%{$search}%")
										->orWhereHas('user', function ($u) use ($search) {
											$u->where('fullName', 'like', "%{$search}%");
										});
								});
						});
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
		// Pre-generate asset IDs for both types to avoid AJAX calls
		$nextLaptopID = $this->generateNextAssetID('Laptop');
		$nextDesktopID = $this->generateNextAssetID('Desktop');
		
		return view('ITDept.manageAsset.addAsset', [
			'nextLaptopID' => $nextLaptopID,
			'nextDesktopID' => $nextDesktopID,
		]);
	}

	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'assetType' => ['required', 'in:Laptop,Desktop'],
			'serialNum' => ['nullable', 'string', 'max:255'],
			'model' => ['nullable', 'string', 'max:255'],
			'ram' => ['nullable', 'string', 'max:255'],
			'storage' => ['nullable', 'string', 'max:255'],
			'purchaseDate' => ['nullable', 'date'],
			'osVer' => ['nullable', 'in:Windows 10,Windows 11'],
			'processor' => ['nullable', 'string', 'max:255'],
		]);

		// Check for duplicate serial number
		if (!empty($validated['serialNum'])) {
			$existingAsset = Asset::where('serialNum', $validated['serialNum'])->first();
			if ($existingAsset) {
				return back()->withErrors(['serialNum' => 'Serial number already exists. Each asset must have a unique serial number.'])->withInput();
			}
		}

		// Auto-generate asset ID
		$validated['assetID'] = $this->generateNextAssetID($validated['assetType']);
		$validated['status'] = 'Available';

		Asset::create($validated);

		return redirect()->route('itdept.manage-assets.index', ['assetType' => $validated['assetType']])
			->with('status', '1 asset successfully added');
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

		// Get IT requests for this asset (only Pending IT or Completed status)
		$itRequests = \App\Models\ITRequest::where('assetID', $assetID)
			->whereIn('status', ['Pending IT', 'Completed'])
			->with(['requester', 'approver'])
			->orderBy('requestDate', 'desc')
			->orderBy('requestID', 'desc')
			->get();

		// Get all maintenance records for this asset
		$maintenances = \App\Models\Maintenance::where('assetID', $assetID)
			->orderBy('mainDate', 'desc')
			->orderBy('mainID', 'desc')
			->get();
		
		return view('ITDept.manageAsset.assetDetails', [
			'asset' => $asset,
			'currentAssignment' => $currentAssignment,
			'previousAssignments' => $previousAssignments,
			'itRequests' => $itRequests,
			'maintenances' => $maintenances,
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

		// Get user information to store in assignment record (denormalization)
		$user = User::find($validated['userID']);
		
		// Create assignment record with user information (denormalization)
		$assignment = AssignAsset::create([
			'assetID' => $asset->assetID,
			'userID' => $validated['userID'],
			'checkoutDate' => $validated['checkoutDate'],
			'userFullName' => $user ? $user->fullName : null,
			'userDepartment' => $user ? $user->department : null,
		]);

		// Update asset status
		$asset->status = 'Checked Out';
		$asset->save();

		// Send notification to the assigned user
		$user = User::find($validated['userID']);
		if ($user) {
			$user->notify(new AssetAssignmentNotification($asset, $assignment));
		}

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

		return redirect()->route('itdept.manage-assets.show', $asset->assetID)
			->with('status', 'Asset updated successfully');
	}

	public function destroy(string $assetID): RedirectResponse
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();
		$asset->delete();
		return back()->with('status', 'Asset deleted');
	}

	public function downloadTemplate(): StreamedResponse
	{
		// Removed assetID from headers - it will be auto-generated
		$headers = ['assetType', 'serialNum', 'model', 'ram', 'storage', 'purchaseDate', 'osVer', 'processor'];
		$filename = 'asset_import_template.csv';
		$currentDate = Carbon::now()->format('Y-m-d');

		return response()->streamDownload(function () use ($headers, $currentDate) {
			$output = fopen('php://output', 'w');
			fputcsv($output, $headers);
			// Example row with current date (assetID will be auto-generated)
			fputcsv($output, ['Laptop', 'SN123456', 'Dell Latitude 5420', '16GB', '512GB SSD', $currentDate, 'Windows 11', 'Intel i7-11850H']);
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

		$created = 0; 
		$duplicateSerial = 0; // Track serial numbers that already exist
		$errors = 0;
		$importedAssetTypes = []; // Track which asset types were imported
		$createdByType = ['Laptop' => 0, 'Desktop' => 0]; // Track count per asset type

		while (($row = fgetcsv($handle)) !== false) {
			if (count($row) !== count($header)) {
				$errors++;
				continue;
			}
			
			$data = [];
			foreach ($header as $index => $headerName) {
				$data[$headerName] = isset($row[$index]) ? trim($row[$index]) : '';
			}
			
			if (empty($data)) { 
				$errors++;
				continue; 
			}

			// Basic per-row validation - assetID is no longer required
			if (!isset($data['assetType'])) {
				$errors++;
				continue;
			}
			if (!in_array($data['assetType'], ['Laptop', 'Desktop'], true)) {
				$errors++;
				continue;
			}
			
			// Validate osVer if provided
			if (isset($data['osVer']) && !empty($data['osVer']) && !in_array($data['osVer'], ['Windows 10', 'Windows 11'], true)) {
				$errors++;
				continue;
			}

			try {
				// Check for duplicate serial number
				if (!empty($data['serialNum'])) {
					$serialNum = trim($data['serialNum']);
					if (Asset::where('serialNum', $serialNum)->exists()) {
						$duplicateSerial++;
						continue; // Skip this row
					}
				}

				// Auto-generate asset ID based on asset type
				$assetID = $this->generateNextAssetID(trim($data['assetType']));

				$asset = new Asset();
				$asset->assetID = $assetID;
				$asset->assetType = trim($data['assetType']);
				$asset->serialNum = !empty($data['serialNum']) ? trim($data['serialNum']) : null;
				$asset->model = !empty($data['model']) ? trim($data['model']) : null;
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
				
				// Track asset type for redirect and count
				$assetType = trim($data['assetType']);
				if (!in_array($assetType, $importedAssetTypes)) {
					$importedAssetTypes[] = $assetType;
				}
				if (isset($createdByType[$assetType])) {
					$createdByType[$assetType]++;
				}
			} catch (\Throwable $e) {
				Log::error('Asset import error: ' . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString()]);
				$errors++;
			}
		}

		fclose($handle);

		// Build success message
		if ($created > 0) {
			// Build message with breakdown by type
			$typeMessages = [];
			if ($createdByType['Laptop'] > 0) {
				$typeMessages[] = $createdByType['Laptop'] . " Laptop(s)";
			}
			if ($createdByType['Desktop'] > 0) {
				$typeMessages[] = $createdByType['Desktop'] . " Desktop(s)";
			}
			
			$message = "Successfully added: " . implode(", ", $typeMessages);
			
			// Add unsuccessful messages
			$unsuccessfulMessages = [];
			if ($duplicateSerial > 0) {
				$unsuccessfulMessages[] = "$duplicateSerial asset(s) skipped (duplicate serial number)";
			}
			if ($errors > 0) {
				$unsuccessfulMessages[] = "$errors asset(s) unsuccessful";
			}
			
			if (!empty($unsuccessfulMessages)) {
				$message .= ". Unsuccessful: " . implode(", ", $unsuccessfulMessages);
			}
			
			// Determine which asset type to show (prefer first imported type, or default to Laptop)
			$redirectAssetType = !empty($importedAssetTypes) ? $importedAssetTypes[0] : 'Laptop';
			
			return redirect()->route('itdept.manage-assets.index', ['assetType' => $redirectAssetType])
				->with('status', $message);
		} else {
			// No assets were created, show error message
			$unsuccessfulMessages = [];
			if ($duplicateSerial > 0) {
				$unsuccessfulMessages[] = "$duplicateSerial asset(s) skipped (duplicate serial number)";
			}
			if ($errors > 0) {
				$unsuccessfulMessages[] = "$errors asset(s) unsuccessful";
			}
			
			if ($duplicateSerial > 0 && $errors == 0) {
				// All assets were duplicates
				$message = "No new assets were added. All $duplicateSerial asset(s) already exist in the system (duplicate serial numbers).";
			} elseif ($duplicateSerial == 0 && $errors > 0) {
				// All assets had errors
				$message = "No assets were added. $errors asset(s) had errors.";
			} elseif ($duplicateSerial > 0 && $errors > 0) {
				// Mix of duplicates and errors
				$message = "No assets were added. Unsuccessful: " . implode(", ", $unsuccessfulMessages);
			} else {
				// No data processed
				$message = "No assets were added. Please check your CSV file format.";
			}
			
			return back()->withErrors(['file' => $message])->withInput();
		}
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
		
		// Handle filename conflicts by adding a unique suffix if file already exists
		$storagePath = 'invoices/' . $fileName;
		$counter = 1;
		while (Storage::disk('public')->exists($storagePath)) {
			$pathInfo = pathinfo($fileName);
			$newFileName = $pathInfo['filename'] . '_' . $counter . '.' . ($pathInfo['extension'] ?? '');
			$storagePath = 'invoices/' . $newFileName;
			$counter++;
		}
		
		// Store file with the final filename
		$file->storeAs('invoices', basename($storagePath), 'public');
		
		// Use the final filename (which may have been modified if there was a conflict)
		$finalFileName = basename($storagePath);

		// Create invoice record (invoiceID will be auto-generated)
		$invoice = Invoice::create([
			'fileName' => $finalFileName,
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
			->orderBy('assetID')
			->get(['assetID', 'assetType', 'model', 'invoiceID']);

		// Transform to include invoice status
		$assets = $assets->map(function ($asset) {
			return [
				'assetID' => $asset->assetID,
				'assetType' => $asset->assetType,
				'model' => $asset->model,
				'hasInvoice' => !is_null($asset->invoiceID),
			];
		});

		return response()->json($assets);
	}

	public function getNextAssetID(Request $request)
	{
		try {
			$assetType = $request->query('assetType');
			
			if (!$assetType || !in_array($assetType, ['Laptop', 'Desktop'])) {
				return response()->json(['error' => 'Invalid asset type'], 400);
			}

			$nextAssetID = $this->generateNextAssetID($assetType);
			
			return response()->json(['assetID' => $nextAssetID]);
		} catch (\Exception $e) {
			Log::error('Error generating next asset ID: ' . $e->getMessage(), [
				'assetType' => $request->query('assetType'),
				'trace' => $e->getTraceAsString()
			]);
			return response()->json(['error' => 'Failed to generate asset ID'], 500);
		}
	}

	public function viewInvoice(string $invoiceID)
	{
		$invoice = Invoice::findOrFail($invoiceID);
		
		// Use fileName to locate the file
		$filePath = 'invoices/' . $invoice->fileName;
		
		if (Storage::disk('public')->exists($filePath)) {
			$file = Storage::disk('public')->get($filePath);
			$mimeType = Storage::disk('public')->mimeType($filePath);
			
			// Get the first asset that has this invoice (invoice can have multiple assets)
			$asset = $invoice->assets()->first();
			$extension = pathinfo($invoice->fileName, PATHINFO_EXTENSION);
			$filename = $asset ? "Invoice_{$asset->assetID}.{$extension}" : $invoice->fileName;
			
			return response($file, 200)
				->header('Content-Type', $mimeType)
				->header('Content-Disposition', 'inline; filename="' . $filename . '"');
		}
		
		abort(404, 'Invoice file not found');
	}

	public function downloadInvoice(string $invoiceID)
	{
		$invoice = Invoice::findOrFail($invoiceID);
		
		// Use fileName to locate the file
		$filePath = 'invoices/' . $invoice->fileName;
		
		if (Storage::disk('public')->exists($filePath)) {
			// Get the first asset that has this invoice (invoice can have multiple assets)
			$asset = $invoice->assets()->first();
			$extension = pathinfo($invoice->fileName, PATHINFO_EXTENSION);
			$filename = $asset ? "Invoice_{$asset->assetID}.{$extension}" : $invoice->fileName;
			
			return Storage::disk('public')->download($filePath, $filename);
		}
		
		abort(404, 'Invoice file not found');
	}

	public function installedSoftwareForm(string $assetID): View
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();
		
		// Parse existing software
		$existingSoftware = [];
		$officeVersion = null;
		$othersSoftware = [];
		
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
				if (preg_match('/^Microsoft Office\s+(.+)$/', $software, $matches)) {
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
					$othersSoftware[] = $software;
				}
			}
		}
		
		// Convert othersSoftware array to comma-separated string for display
		$othersSoftware = !empty($othersSoftware) ? implode(', ', $othersSoftware) : '';
		
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
			'officeVersion' => ['nullable', 'string', 'max:255'],
			'othersSoftware' => ['nullable', 'string', 'max:255'],
		]);

		$softwareList = [];

		if (!empty($validated['software'])) {
			foreach ($validated['software'] as $software) {
				if ($software === 'Microsoft Office' && !empty($validated['officeVersion'])) {
					$softwareList[] = 'Microsoft Office ' . trim($validated['officeVersion']);
				} elseif ($software === 'Others' && !empty($validated['othersSoftware'])) {
					// Handle comma-separated values in othersSoftware
					$othersArray = array_map('trim', explode(',', $validated['othersSoftware']));
					$othersArray = array_filter($othersArray); // Remove empty values
					foreach ($othersArray as $other) {
						if (!empty($other)) {
							$softwareList[] = $other;
						}
					}
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

	public function viewAgreement(string $assetID)
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();
		$currentAssignment = $asset->currentAssignment();

		if (!$currentAssignment) {
			return back()->withErrors(['asset' => 'No active assignment found for this asset']);
		}

		$user = $currentAssignment->user;
		$checkoutDate = $currentAssignment->checkoutDate;

		// Parse installed software
		$installedSoftware = $asset->installedSoftware ?? '';
		$softwareList = [];
		if ($installedSoftware) {
			$softwareArray = preg_split('/[,\n]+/', $installedSoftware);
			$softwareArray = array_map('trim', $softwareArray);
			$softwareArray = array_filter($softwareArray);
			$softwareList = $softwareArray;
		}

		$data = [
			'asset' => $asset,
			'user' => $user,
			'checkoutDate' => $checkoutDate,
			'softwareList' => $softwareList,
		];

		$pdf = Pdf::loadView('ITDept.manageAsset.agreement', $data);
		$pdf->setPaper('a4', 'portrait');

		$filename = 'Asset_Agreement_' . $asset->assetID . '.pdf';

		return $pdf->stream($filename);
	}

	public function downloadAgreement(string $assetID)
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();
		$currentAssignment = $asset->currentAssignment();

		if (!$currentAssignment) {
			return back()->withErrors(['asset' => 'No active assignment found for this asset']);
		}

		$user = $currentAssignment->user;
		$checkoutDate = $currentAssignment->checkoutDate;

		// Parse installed software
		$installedSoftware = $asset->installedSoftware ?? '';
		$softwareList = [];
		if ($installedSoftware) {
			$softwareArray = preg_split('/[,\n]+/', $installedSoftware);
			$softwareArray = array_map('trim', $softwareArray);
			$softwareArray = array_filter($softwareArray);
			$softwareList = $softwareArray;
		}

		$data = [
			'asset' => $asset,
			'user' => $user,
			'checkoutDate' => $checkoutDate,
			'softwareList' => $softwareList,
		];

		$pdf = Pdf::loadView('ITDept.manageAsset.agreement', $data);
		$pdf->setPaper('a4', 'portrait');

		$filename = 'Asset_Agreement_' . $asset->assetID . '.pdf';

		return $pdf->download($filename);
	}
}

