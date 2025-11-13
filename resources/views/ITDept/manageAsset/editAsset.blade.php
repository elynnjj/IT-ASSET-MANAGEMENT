<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Edit Asset') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<form action="{{ route('itdept.manage-assets.update', $asset->assetID) }}" method="POST" class="space-y-4">
						@csrf
						@method('PUT')

						<div>
							<x-input-label for="assetID" :value="__('Asset ID')" />
							<x-text-input id="assetID" name="assetID" type="text" class="mt-1 block w-full" value="{{ $asset->assetID }}" disabled />
						</div>

						<div>
							<x-input-label :value="__('Asset Type')" />
							<div class="mt-2 space-y-2">
								<label class="inline-flex items-center">
									<input type="radio" name="assetType" value="Laptop" class="me-2" @checked(old('assetType', $asset->assetType) === 'Laptop') required>
									<span>Laptop</span>
								</label>
								<label class="inline-flex items-center ms-6">
									<input type="radio" name="assetType" value="Desktop" class="me-2" @checked(old('assetType', $asset->assetType) === 'Desktop') required>
									<span>Desktop</span>
								</label>
							</div>
							<x-input-error :messages="$errors->get('assetType')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="serialNum" :value="__('Serial Number')" />
							<x-text-input id="serialNum" name="serialNum" type="text" class="mt-1 block w-full" value="{{ old('serialNum', $asset->serialNum) }}" />
							<x-input-error :messages="$errors->get('serialNum')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="model" :value="__('Model')" />
							<x-text-input id="model" name="model" type="text" class="mt-1 block w-full" value="{{ old('model', $asset->model) }}" />
							<x-input-error :messages="$errors->get('model')" class="mt-2" />
						</div>

						<div class="grid grid-cols-2 gap-4">
							<div>
								<x-input-label for="ram" :value="__('RAM')" />
								<x-text-input id="ram" name="ram" type="text" class="mt-1 block w-full" value="{{ old('ram', $asset->ram) }}" />
								<x-input-error :messages="$errors->get('ram')" class="mt-2" />
							</div>

							<div>
								<x-input-label for="storage" :value="__('Storage')" />
								<x-text-input id="storage" name="storage" type="text" class="mt-1 block w-full" value="{{ old('storage', $asset->storage) }}" />
								<x-input-error :messages="$errors->get('storage')" class="mt-2" />
							</div>
						</div>

						<div>
							<x-input-label for="purchaseDate" :value="__('Purchase Date')" />
							<x-text-input id="purchaseDate" name="purchaseDate" type="date" class="mt-1 block w-full" value="{{ old('purchaseDate', $asset->purchaseDate ? $asset->purchaseDate->format('Y-m-d') : '') }}" />
							<x-input-error :messages="$errors->get('purchaseDate')" class="mt-2" />
						</div>

						<div>
							<x-input-label :value="__('OS Version')" />
							<div class="mt-2 space-y-2">
								<label class="inline-flex items-center">
									<input type="radio" name="osVer" value="Windows 10" class="me-2" @checked(old('osVer', $asset->osVer) === 'Windows 10')>
									<span>Windows 10</span>
								</label>
								<label class="inline-flex items-center ms-6">
									<input type="radio" name="osVer" value="Windows 11" class="me-2" @checked(old('osVer', $asset->osVer) === 'Windows 11')>
									<span>Windows 11</span>
								</label>
							</div>
							<x-input-error :messages="$errors->get('osVer')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="processor" :value="__('Processor')" />
							<x-text-input id="processor" name="processor" type="text" class="mt-1 block w-full" value="{{ old('processor', $asset->processor) }}" />
							<x-input-error :messages="$errors->get('processor')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="status" :value="__('Status')" />
							<select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
								<option value="">Select Status</option>
								<option value="Available" @selected(old('status', $asset->status) === 'Available')>Available</option>
								<option value="inactive" @selected(old('status', $asset->status) === 'inactive')>Inactive</option>
								<option value="maintenance" @selected(old('status', $asset->status) === 'maintenance')>Maintenance</option>
								<option value="disposed" @selected(old('status', $asset->status) === 'disposed')>Disposed</option>
							</select>
							<x-input-error :messages="$errors->get('status')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="installedSoftware" :value="__('Installed Software')" />
							<textarea id="installedSoftware" name="installedSoftware" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('installedSoftware', $asset->installedSoftware) }}</textarea>
							<x-input-error :messages="$errors->get('installedSoftware')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="invoiceID" :value="__('Invoice')" />
							<select id="invoiceID" name="invoiceID" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
								<option value="">Select Invoice (Optional)</option>
								@foreach ($invoices as $invoice)
									<option value="{{ $invoice->invoiceID }}" @selected(old('invoiceID', $asset->invoiceID) === $invoice->invoiceID)>{{ $invoice->invoiceID }} - {{ $invoice->fileName }}</option>
								@endforeach
							</select>
							<x-input-error :messages="$errors->get('invoiceID')" class="mt-2" />
						</div>

						<div class="flex items-center justify-end">
							<x-primary-button>{{ __('Save Changes') }}</x-primary-button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

