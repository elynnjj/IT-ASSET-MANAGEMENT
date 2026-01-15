<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Disposal;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DisposalController
{
	public function index(Request $request): View
	{
		$tab = $request->query('tab', 'pending');
		$search = $request->query('q');
		$assetType = $request->query('assetType');

		if ($tab === 'pending') {
			// Show assets with status "Disposed" that have disposal records with dispStatus "Pending"
			$query = Asset::where('status', 'Disposed')
				->whereHas('disposals', function ($q) {
					$q->where('dispStatus', 'Pending');
				})
				->with('disposals', function ($q) {
					$q->where('dispStatus', 'Pending');
				})
				->when($search, function ($q) use ($search) {
					$q->where(function ($qq) use ($search) {
						$qq->where('assetID', 'like', "%{$search}%")
							->orWhere('serialNum', 'like', "%{$search}%")
							->orWhere('model', 'like', "%{$search}%");
					});
				})
				->when($assetType, function ($q) use ($assetType) {
					$q->where('assetType', $assetType);
				})
				->orderBy('assetID');

			$assets = $query->get();
		} else {
			// Show assets with disposal records where dispStatus is "Disposed"
			$query = Asset::whereHas('disposals', function ($q) {
				$q->where('dispStatus', 'Disposed');
			})
				->with(['disposals' => function ($q) {
					$q->where('dispStatus', 'Disposed')->with('invoice');
				}])
				->when($search, function ($q) use ($search) {
					$q->where(function ($qq) use ($search) {
						$qq->where('assetID', 'like', "%{$search}%")
							->orWhere('serialNum', 'like', "%{$search}%")
							->orWhere('model', 'like', "%{$search}%");
					});
				})
				->when($assetType, function ($q) use ($assetType) {
					$q->where('assetType', $assetType);
				})
				->orderBy('assetID');

			$assets = $query->get();
		}

		return view('ITDept.manageDisposal.assetDisposal', [
			'assets' => $assets,
			'tab' => $tab,
			'search' => $search,
			'assetType' => $assetType,
		]);
	}

	public function dispose(string $assetID): RedirectResponse
	{
		$asset = Asset::where('assetID', $assetID)->firstOrFail();

		// Check if asset is already disposed
		if ($asset->status === 'Disposed') {
			return back()->withErrors(['asset' => 'Asset is already disposed']);
		}

		// Check if asset is currently checked out
		if ($asset->status === 'Checked Out') {
			return back()->withErrors(['asset' => 'Cannot dispose asset. Asset is currently checked out. Please check-in the asset first.']);
		}

		// Create disposal record
		Disposal::create([
			'dispStatus' => 'Pending',
			'dispDate' => Carbon::now()->toDateString(),
			'assetID' => $asset->assetID,
		]);

		// Update asset status to Disposed
		$asset->status = 'Disposed';
		$asset->save();

		return redirect()->route('itdept.asset-disposal', ['tab' => 'pending'])
			->with('status', 'Asset disposed successfully');
	}

	public function bulkDispose(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'selectedAssets' => ['required', 'array', 'min:1'],
			'selectedAssets.*' => ['required', 'string', 'exists:assets,assetID'],
			'disposalInvoiceFile' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
		]);

		// Get disposal date first (same for all assets in this bulk disposal)
		$disposalDate = Carbon::now()->toDateString();
		
		// Store the disposal invoice file with renamed filename: disposal_disposalDate.extension
		$file = $request->file('disposalInvoiceFile');
		$originalExtension = $file->getClientOriginalExtension();
		$baseFileName = 'disposal_' . $disposalDate . '.' . $originalExtension;
		
		// Handle filename conflicts by adding a unique suffix if file already exists
		$storagePath = 'invoices/' . $baseFileName;
		$counter = 1;
		while (Storage::disk('public')->exists($storagePath)) {
			$newFileName = 'disposal_' . $disposalDate . '_' . $counter . '.' . $originalExtension;
			$storagePath = 'invoices/' . $newFileName;
			$counter++;
		}
		
		// Store file with the renamed filename
		$file->storeAs('invoices', basename($storagePath), 'public');
		
		// Use the final filename (which may have been modified if there was a conflict)
		$finalFileName = basename($storagePath);

		// Create invoice record
		$invoice = Invoice::create([
			'fileName' => $finalFileName,
		]);

		$assetIDs = $validated['selectedAssets'];
		$updated = 0;

		foreach ($assetIDs as $assetID) {
			$disposal = Disposal::where('assetID', $assetID)
				->where('dispStatus', 'Pending')
				->first();

			if ($disposal) {
				$disposal->dispStatus = 'Disposed';
				$disposal->dispDate = $disposalDate;
				$disposal->invoiceID = $invoice->invoiceID;
				$disposal->save();
				$updated++;
			}
		}

		if ($updated > 0) {
			return redirect()->route('itdept.asset-disposal', ['tab' => 'pending'])
				->with('status', "Successfully disposed {$updated} asset(s)");
		}

		return back()->withErrors(['assets' => 'No assets were disposed']);
	}

	public function downloadInvoice(string $disposeID)
	{
		$disposal = Disposal::with('invoice')->findOrFail($disposeID);
		
		if (!$disposal->invoice) {
			abort(404, 'Disposal invoice file not found');
		}
		
		// Use fileName to locate the stored file
		$filePath = 'invoices/' . $disposal->invoice->fileName;
		
		if (Storage::disk('public')->exists($filePath)) {
			// Generate download filename as disposal_disposalDate.extension
			$fileExtension = pathinfo($disposal->invoice->fileName, PATHINFO_EXTENSION);
			$downloadFileName = 'disposal_' . $disposal->dispDate . '.' . $fileExtension;
			
			return Storage::disk('public')->download($filePath, $downloadFileName);
		}
		
		abort(404, 'Disposal invoice file not found');
	}
}

