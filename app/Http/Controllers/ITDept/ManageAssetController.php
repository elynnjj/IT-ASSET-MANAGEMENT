<?php

namespace App\Http\Controllers\ITDept;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ManageAssetController extends Controller
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
		return view('ITDept.manageAsset.assetDetails', ['asset' => $asset]);
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
			'assetType' => ['required', 'in:Laptop,Desktop'],
			'serialNum' => ['nullable', 'string', 'max:255'],
			'model' => ['nullable', 'string', 'max:255'],
			'ram' => ['nullable', 'string', 'max:255'],
			'storage' => ['nullable', 'string', 'max:255'],
			'purchaseDate' => ['nullable', 'date'],
			'osVer' => ['nullable', 'in:Windows 10,Windows 11'],
			'processor' => ['nullable', 'string', 'max:255'],
			'status' => ['nullable', 'string', 'max:255'],
			'installedSoftware' => ['nullable', 'string'],
			'invoiceID' => ['nullable', 'string', 'exists:invoices,invoiceID'],
		]);

		$asset->update($validated);

		return redirect()->route('itdept.manage-assets.index', ['assetType' => $validated['assetType']])
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

		return response()->streamDownload(function () use ($headers) {
			$output = fopen('php://output', 'w');
			fputcsv($output, $headers);
			// Example row
			fputcsv($output, ['LAP001', 'Laptop', 'SN123456', 'Dell Latitude 5420', '16GB', '512GB SSD', '2024-01-15', 'Windows 11', 'Intel i7-11850H']);
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

		$created = 0; $skipped = 0; $errors = 0;

		while (($row = fgetcsv($handle)) !== false) {
			$data = array_combine($header, $row);
			if (!$data) { $skipped++; continue; }

			// Basic per-row validation
			if (!isset($data['assetID'], $data['assetType'])) {
				$skipped++; continue;
			}
			if (!in_array($data['assetType'], ['Laptop', 'Desktop'], true)) {
				$skipped++; continue;
			}

			try {
				// Skip existing assets entirely (do not update)
				if (Asset::where('assetID', $data['assetID'])->exists()) {
					$skipped++;
					continue;
				}

				$asset = new Asset();
				$asset->assetID = $data['assetID'];
				$asset->assetType = $data['assetType'];
				$asset->serialNum = $data['serialNum'] ?? null;
				$asset->model = $data['model'] ?? null;
				$asset->ram = $data['ram'] ?? null;
				$asset->storage = $data['storage'] ?? null;
				$asset->purchaseDate = !empty($data['purchaseDate']) ? $data['purchaseDate'] : null;
				$asset->osVer = $data['osVer'] ?? null;
				$asset->processor = $data['processor'] ?? null;
				$asset->status = 'Available';
				$asset->save();
				$created++;
			} catch (\Throwable $e) {
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
			'invoiceID' => ['required', 'string', 'max:255', 'unique:invoices,invoiceID'],
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

		// Create invoice record
		$invoice = Invoice::create([
			'invoiceID' => $validated['invoiceID'],
			'fileName' => $fileName,
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
}
