<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Upload Invoice') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			{{-- Breadcrumb --}}
			<div class="mb-6">
				<a href="{{ route('itdept.manage-assets.index') }}" 
				   style="color: #4BA9C2;"
				   class="hover:opacity-80">
					← Assets
				</a>
				<span class="text-gray-600 dark:text-gray-400"> > </span>
				<span class="text-gray-600 dark:text-gray-400">{{ __('Upload Invoice') }}</span>
			</div>

			{{-- Main Content Card --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Title --}}
					<div class="mb-6">
						<h1 class="text-xl font-semibold">{{ __('Upload Invoice') }}</h1>
					</div>

					<form action="{{ route('itdept.manage-assets.store-invoice') }}" method="POST" enctype="multipart/form-data">
						@csrf

						{{-- Invoice Information Section --}}
						<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
							<h3 class="text-lg font-semibold mb-4">{{ __('Invoice Information') }}</h3>
							<div class="space-y-5">
								<div>
									<x-input-label for="invoiceFile" :value="__('Invoice File')" />
									<input type="file" id="invoiceFile" name="invoiceFile" accept=".pdf,.jpg,.jpeg,.png" 
										class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
										border border-gray-300 dark:border-gray-700 rounded-md
										file:mr-4 file:py-2 file:px-4
										file:rounded-md file:border-0
										file:text-sm file:font-semibold
										file:bg-blue-50 dark:file:bg-blue-900
										file:text-blue-700 dark:file:text-blue-300
										hover:file:bg-blue-100 dark:hover:file:bg-blue-800
										cursor-pointer"
										required />
									<p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Accepted formats: PDF, JPG, JPEG, PNG (Max: 10MB)</p>
									<x-input-error :messages="$errors->get('invoiceFile')" class="mt-2" />
								</div>
							</div>
						</div>

						{{-- Asset Linking Section --}}
						<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md" x-data="{ assetCount: 1 }">
							<h3 class="text-lg font-semibold mb-4">{{ __('Link Assets to Invoice') }}</h3>
							<div class="space-y-5">
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
									<div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-md mb-4">
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
												x-bind:data-index="i"
												class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm asset-select"
												required>
												<option value="">Select Asset ID</option>
											</select>
										</div>
									</div>
								</template>
							</div>
						</div>

						<div class="flex items-center justify-end space-x-6 mt-6">
							<a href="{{ route('itdept.manage-assets.index') }}" 
							   class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
							   style="background-color: #797979;"
							   onmouseover="this.style.backgroundColor='#666666'"
							   onmouseout="this.style.backgroundColor='#797979'">
								{{ __('Cancel') }}
							</a>
							<button type="submit" 
								class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
								style="background-color: #4BA9C2;"
								onmouseover="this.style.backgroundColor='#3a8ba5'"
								onmouseout="this.style.backgroundColor='#4BA9C2'">
								{{ __('Upload Invoice') }}
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	{{-- Confirmation Modal --}}
	<div id="confirmOverwriteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
		<div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
			<div class="mt-3 text-center">
				<div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900">
					<svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
					</svg>
				</div>
				<h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mt-5">
					Overwrite Existing Invoice?
				</h3>
				<div class="mt-2 px-7 py-3">
					<p class="text-sm text-gray-500 dark:text-gray-400">
						This asset already has an invoice linked to it. Selecting it will overwrite the existing invoice. Do you want to proceed?
					</p>
					<p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-2" id="modalAssetInfo"></p>
				</div>
				<div class="items-center px-4 py-3">
					<button id="confirmOverwriteBtn" 
						class="px-4 py-2 text-white text-base font-medium rounded-md w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 mb-2 transition ease-in-out duration-150"
						style="background-color: #4BA9C2;"
						onmouseover="this.style.backgroundColor='#3a8ba5'"
						onmouseout="this.style.backgroundColor='#4BA9C2'">
						Yes, Overwrite
					</button>
					<button id="cancelOverwriteBtn" 
						class="px-4 py-2 text-white text-base font-medium rounded-md w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150"
						style="background-color: #797979;"
						onmouseover="this.style.backgroundColor='#666666'"
						onmouseout="this.style.backgroundColor='#797979'">
						Cancel
					</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		// Store asset data with invoice status per dropdown
		const assetDataMap = new Map();

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
						
						// Add indicator for assets with existing invoices
						if (asset.hasInvoice) {
							option.textContent += ' ⚠️ (Has Invoice)';
							option.dataset.hasInvoice = 'true';
						}
						
						assetIDSelect.appendChild(option);
						
						// Store asset data for confirmation modal (key: assetID)
						assetDataMap.set(asset.assetID, asset);
					});
					
					// Remove existing event listener and add new one
					const newSelect = assetIDSelect.cloneNode(true);
					assetIDSelect.parentNode.replaceChild(newSelect, assetIDSelect);
					
					// Add event listener for asset selection
					newSelect.addEventListener('change', function() {
						handleAssetSelection(index, this.value, this.options[this.selectedIndex]);
					});
				})
				.catch(error => {
					console.error('Error fetching assets:', error);
				});
		}

		function handleAssetSelection(index, assetID, selectedOption) {
			if (!assetID) {
				return;
			}

			const asset = assetDataMap.get(assetID);
			
			// Check if asset has an existing invoice
			if (asset && asset.hasInvoice) {
				// Show confirmation modal
				const modal = document.getElementById('confirmOverwriteModal');
				const modalAssetInfo = document.getElementById('modalAssetInfo');
				const confirmBtn = document.getElementById('confirmOverwriteBtn');
				const cancelBtn = document.getElementById('cancelOverwriteBtn');
				const assetSelect = document.getElementById('assetID_' + index);
				
				// Set asset info in modal
				modalAssetInfo.textContent = `Asset: ${asset.assetID}${asset.model ? ' - ' + asset.model : ''}`;
				
				// Show modal
				modal.classList.remove('hidden');
				
				// Remove previous event listeners by cloning buttons
				const newConfirmBtn = confirmBtn.cloneNode(true);
				const newCancelBtn = cancelBtn.cloneNode(true);
				confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
				cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
				
				// Handle confirmation
				newConfirmBtn.onclick = function() {
					modal.classList.add('hidden');
					// Asset is already selected, proceed
				};
				
				// Handle cancellation
				newCancelBtn.onclick = function() {
					modal.classList.add('hidden');
					// Reset to empty selection
					assetSelect.value = '';
				};
				
				// Close modal when clicking outside
				const modalClickHandler = function(e) {
					if (e.target === modal) {
						modal.classList.add('hidden');
						assetSelect.value = '';
						modal.removeEventListener('click', modalClickHandler);
					}
				};
				modal.addEventListener('click', modalClickHandler);
			}
		}

		// Make functions available globally
		window.updateAssetDropdown = updateAssetDropdown;
		window.handleAssetSelection = handleAssetSelection;
	</script>
</x-app-layout>

