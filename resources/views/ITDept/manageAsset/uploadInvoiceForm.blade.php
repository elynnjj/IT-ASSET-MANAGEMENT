<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Upload Invoice') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<div class="mb-6">
						<a href="{{ route('itdept.manage-assets.index') }}" class="text-sky-600 hover:text-sky-800 dark:text-sky-400 dark:hover:text-sky-300">
							← Back to Asset List
						</a>
					</div>

					<form action="{{ route('itdept.manage-assets.store-invoice') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
						@csrf

						{{-- Invoice Information --}}
						<div class="space-y-4">
							<h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-700 pb-2">
								{{ __('Invoice Information') }}
							</h3>

							<div>
								<x-input-label for="invoiceID" :value="__('Invoice ID')" />
								<x-text-input id="invoiceID" name="invoiceID" type="text" class="mt-1 block w-full" required />
								<x-input-error :messages="$errors->get('invoiceID')" class="mt-2" />
							</div>

							<div>
								<x-input-label for="invoiceFile" :value="__('Invoice File')" />
								<input type="file" id="invoiceFile" name="invoiceFile" accept=".pdf,.jpg,.jpeg,.png" 
									class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
									file:mr-4 file:py-2 file:px-4
									file:rounded-md file:border-0
									file:text-sm file:font-semibold
									file:bg-sky-50 file:text-sky-700
									hover:file:bg-sky-100
									dark:file:bg-sky-900 dark:file:text-sky-300 dark:hover:file:bg-sky-800"
									required />
								<p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Accepted formats: PDF, JPG, JPEG, PNG (Max: 10MB)</p>
								<x-input-error :messages="$errors->get('invoiceFile')" class="mt-2" />
							</div>
						</div>

						{{-- Asset Linking --}}
						<div class="space-y-4" x-data="{ assetCount: 1 }">
							<h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-700 pb-2">
								{{ __('Link Assets to Invoice') }}
							</h3>

							<div>
								<x-input-label for="assetCount" :value="__('Number of Assets')" />
								<x-text-input id="assetCount" name="assetCount" type="number" min="1" max="100" 
									class="mt-1 block w-full" 
									x-model.number="assetCount"
									required />
								<x-input-error :messages="$errors->get('assetCount')" class="mt-2" />
							</div>

							{{-- Dynamic Asset Dropdowns --}}
							<template x-for="i in Array.from({length: assetCount}, (_, i) => i + 1)" :key="i">
								<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-gray-200 dark:border-gray-700 rounded-md">
									<div>
										<label x-bind:for="'assetType_' + i" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
											<span x-text="'Asset Type ' + i"></span>
										</label>
										<select 
											x-bind:name="'assets[' + (i-1) + '][assetType]'"
											x-bind:id="'assetType_' + i"
											class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
											x-on:change="updateAssetDropdown(i, $event.target.value)"
											required>
											<option value="">Select Asset Type</option>
											<option value="Laptop">Laptop</option>
											<option value="Desktop">Desktop</option>
										</select>
									</div>

									<div>
										<label x-bind:for="'assetID_' + i" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
											<span x-text="'Asset ID ' + i"></span>
										</label>
										<select 
											x-bind:name="'assets[' + (i-1) + '][assetID]'"
											x-bind:id="'assetID_' + i"
											class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
											required>
											<option value="">Select Asset ID</option>
										</select>
									</div>
								</div>
							</template>
						</div>

						<div class="flex items-center justify-end space-x-4">
							<a href="{{ route('itdept.manage-assets.index') }}" 
							   class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600 transition">
								{{ __('Cancel') }}
							</a>
							<x-primary-button>{{ __('Upload Invoice') }}</x-primary-button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script>
		function updateAssetDropdown(index, assetType) {
			const assetIDSelect = document.getElementById('assetID_' + index);

			// Clear existing options
			assetIDSelect.innerHTML = '<option value="">Select Asset ID</option>';

			if (!assetType) {
				return;
			}

			// Fetch assets by type
			fetch(`{{ route('itdept.manage-assets.api.assets-by-type') }}?assetType=${assetType}`)
				.then(response => response.json())
				.then(assets => {
					assets.forEach(asset => {
						const option = document.createElement('option');
						option.value = asset.assetID;
						option.textContent = asset.assetID + (asset.model ? ' - ' + asset.model : '');
						assetIDSelect.appendChild(option);
					});
				})
				.catch(error => {
					console.error('Error fetching assets:', error);
				});
		}

		// Make function available globally
		window.updateAssetDropdown = updateAssetDropdown;
	</script>
</x-app-layout>

