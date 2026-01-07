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
						<div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
							<h3 class="text-lg font-semibold mb-4">{{ __('Invoice Information') }}</h3>
							<div class="space-y-4">
								<div class="input-container">
									<x-input-label for="invoiceFile" :value="__('Invoice File')" />
									<div class="custom-file-input-wrapper mt-1">
									<input type="file" id="invoiceFile" name="invoiceFile" accept=".pdf,.jpg,.jpeg,.png" 
											class="hidden-file-input"
										required />
										<button type="button" class="file-select-button" onclick="document.getElementById('invoiceFile').click()">
											{{ __('Choose File') }}
										</button>
										<div class="file-display-area" id="fileDisplay">
											<span class="file-placeholder">{{ __('No file chosen') }}</span>
										</div>
									</div>
									<p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Accepted formats: PDF, JPG, JPEG, PNG (Max: 10MB)</p>
									<x-input-error :messages="$errors->get('invoiceFile')" class="mt-2" />
								</div>
							</div>
						</div>

						{{-- Asset Linking Section --}}
						<div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-md" x-data="{ assetCount: 1 }">
							<h3 class="text-lg font-semibold mb-4">{{ __('Link Assets to Invoice') }}</h3>
							<div class="space-y-4">
								<div class="input-container">
									<x-input-label for="assetCount" :value="__('Number of Assets')" />
									<x-text-input id="assetCount" name="assetCount" type="number" min="1" max="100" 
										class="mt-1 block w-full interactive-input" 
										x-model.number="assetCount"
										required />
									<x-input-error :messages="$errors->get('assetCount')" class="mt-2" />
								</div>

								{{-- Dynamic Asset Dropdowns --}}
								<template x-for="i in Array.from({length: assetCount}, (_, i) => i + 1)" :key="i">
									<div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-md mb-4">
										<div class="input-container">
											<label x-bind:for="'assetType_' + i" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
												<span x-text="'Asset Type ' + i"></span>
											</label>
											<select 
												x-bind:name="'assets[' + (i-1) + '][assetType]'"
												x-bind:id="'assetType_' + i"
												class="mt-1 block w-full interactive-select"
												x-on:change="updateAssetDropdown(i, $event.target.value)"
												required>
												<option value="">Select Asset Type</option>
												<option value="Laptop">Laptop</option>
												<option value="Desktop">Desktop</option>
											</select>
										</div>

										<div class="input-container">
											<label x-bind:for="'assetID_' + i" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
												<span x-text="'Asset ID ' + i"></span>
											</label>
											<select 
												x-bind:name="'assets[' + (i-1) + '][assetID]'"
												x-bind:id="'assetID_' + i"
												x-bind:data-index="i"
												class="mt-1 block w-full interactive-select asset-select"
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
							   class="interactive-button interactive-button-secondary"
							   style="padding: 10px 16px; font-size: 11px;">
								<span class="button-content">
								{{ __('Cancel') }}
								</span>
							</a>
							<button type="submit" 
								class="interactive-button interactive-button-primary"
								style="padding: 10px 16px; font-size: 11px;">
								<span class="button-content">
									<span class="button-text">{{ __('Upload Invoice') }}</span>
									<span class="button-spinner"></span>
								</span>
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
						class="interactive-button interactive-button-primary w-full mb-2"
						style="padding: 10px 16px; font-size: 11px;">
						<span class="button-content">
							{{ __('Yes, Overwrite') }}
						</span>
					</button>
					<button id="cancelOverwriteBtn" 
						class="interactive-button interactive-button-secondary w-full"
						style="padding: 10px 16px; font-size: 11px;">
						<span class="button-content">
							{{ __('Cancel') }}
						</span>
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

	<style>
		/* Input container with hover effects */
		.input-container {
			position: relative;
			transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
		}

		.input-container:hover {
			transform: translateY(-1px);
		}

		.input-container:has(.interactive-input:focus),
		.input-container:has(.interactive-textarea:focus),
		.input-container:has(.interactive-select:focus),
		.input-container:has(.hidden-file-input:focus) {
			transform: translateY(-2px);
		}

		/* Interactive input styling */
		.interactive-input,
		.interactive-textarea,
		.interactive-select {
			width: 100%;
			padding: 8px 12px;
			border: 2px solid #9CA3AF;
			border-radius: 8px;
			font-size: 15px;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			background-color: #FFFFFF;
			position: relative;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input,
			.interactive-textarea,
			.interactive-select {
				background-color: #111827;
				border-color: #6B7280;
				color: #D1D5DB;
			}
		}

		.dark .interactive-input,
		.dark .interactive-textarea,
		.dark .interactive-select {
			background-color: #111827;
			border-color: #6B7280;
			color: #D1D5DB;
		}

		.interactive-input:hover,
		.interactive-textarea:hover,
		.interactive-select:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.15);
			transform: translateY(-1px);
			background-color: #FAFAFA;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input:hover,
			.interactive-textarea:hover,
			.interactive-select:hover {
				border-color: #4BA9C2;
				box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
				background-color: #1F2937;
			}
		}

		.dark .interactive-input:hover,
		.dark .interactive-textarea:hover,
		.dark .interactive-select:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
			background-color: #1F2937;
		}

		.interactive-input:focus,
		.interactive-textarea:focus,
		.interactive-select:focus {
			outline: none;
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.15), 0 6px 16px rgba(75, 169, 194, 0.2);
			background-color: #FFFFFF;
			transform: translateY(-2px);
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input:focus,
			.interactive-textarea:focus,
			.interactive-select:focus {
				border-color: #4BA9C2;
				box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
				background-color: #111827;
				color: #D1D5DB;
			}
		}

		.dark .interactive-input:focus,
		.dark .interactive-textarea:focus,
		.dark .interactive-select:focus {
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
			background-color: #111827;
			color: #D1D5DB;
		}

		.interactive-textarea {
			resize: vertical;
			min-height: 120px;
		}

		/* Custom file input wrapper - side by side layout */
		.custom-file-input-wrapper {
			display: flex;
			width: 100%;
			gap: 0;
			border: 2px solid #9CA3AF;
			border-radius: 8px;
			overflow: hidden;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			background-color: #FFFFFF;
		}

		@media (prefers-color-scheme: dark) {
			.custom-file-input-wrapper {
				background-color: #111827;
				border-color: #6B7280;
			}
		}

		.dark .custom-file-input-wrapper {
			background-color: #111827;
			border-color: #6B7280;
		}

		.custom-file-input-wrapper:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.15);
		}

		@media (prefers-color-scheme: dark) {
			.custom-file-input-wrapper:hover {
				border-color: #4BA9C2;
				box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
			}
		}

		.dark .custom-file-input-wrapper:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
		}

		.custom-file-input-wrapper:has(.hidden-file-input:focus) {
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.15), 0 6px 16px rgba(75, 169, 194, 0.2);
		}

		@media (prefers-color-scheme: dark) {
			.custom-file-input-wrapper:has(.hidden-file-input:focus) {
				border-color: #4BA9C2;
				box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
			}
		}

		.dark .custom-file-input-wrapper:has(.hidden-file-input:focus) {
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
		}

		.hidden-file-input {
			position: absolute;
			opacity: 0;
			width: 0;
			height: 0;
			pointer-events: none;
		}

		/* File select button (left side) */
		.file-select-button {
			padding: 8px 20px;
			border: none;
			border-right: 2px solid #9CA3AF;
			border-radius: 0;
			background: linear-gradient(135deg, #4BA9C2 0%, #3a8ba5 100%);
			color: white;
			font-size: 15px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			white-space: nowrap;
			flex-shrink: 0;
		}

		@media (prefers-color-scheme: dark) {
			.file-select-button {
				border-right-color: #6B7280;
			}
		}

		.dark .file-select-button {
			border-right-color: #6B7280;
		}

		.file-select-button:hover {
			background: linear-gradient(135deg, #3a8ba5 0%, #2d6b82 100%);
			box-shadow: 0 4px 8px rgba(75, 169, 194, 0.3);
		}

		.file-select-button:active {
			background: linear-gradient(135deg, #2d6b82 0%, #1f5a6f 100%);
			transform: scale(0.98);
		}

		/* File display area (right side) */
		.file-display-area {
			flex: 1;
			padding: 8px 12px;
			display: flex;
			align-items: center;
			background-color: #FFFFFF;
			color: #374151;
			font-size: 15px;
		}

		@media (prefers-color-scheme: dark) {
			.file-display-area {
				background-color: #111827;
				color: #D1D5DB;
			}

			.file-placeholder {
				color: #9CA3AF;
			}

			.file-name {
				color: #D1D5DB;
			}
		}

		.dark .file-display-area {
			background-color: #111827;
			color: #D1D5DB;
		}

		.file-placeholder {
			color: #9CA3AF;
		}

		.dark .file-placeholder {
			color: #9CA3AF;
		}

		.file-name {
			color: #374151;
			font-weight: 500;
		}

		.dark .file-name {
			color: #D1D5DB;
		}

		/* Interactive button styling */
		.interactive-button {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			padding: 14px 28px;
			font-weight: 600;
			font-size: 13px;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			border: none;
			border-radius: 8px;
			cursor: pointer;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			position: relative;
			overflow: hidden;
			text-decoration: none;
		}

		.interactive-button-primary {
			background: linear-gradient(135deg, #4BA9C2 0%, #3a8ba5 100%);
			color: white;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.3);
		}

		.interactive-button-primary::before {
			content: '';
			position: absolute;
			top: 50%;
			left: 50%;
			width: 0;
			height: 0;
			border-radius: 50%;
			background: rgba(255, 255, 255, 0.3);
			transform: translate(-50%, -50%);
			transition: width 0.6s, height 0.6s;
		}

		.interactive-button-primary:hover {
			background: linear-gradient(135deg, #3a8ba5 0%, #2d6b82 100%);
			box-shadow: 0 8px 20px rgba(75, 169, 194, 0.5);
			transform: translateY(-2px) scale(1.02);
		}

		.interactive-button-primary:active::before {
			width: 300px;
			height: 300px;
		}

		.interactive-button-primary:active {
			background: linear-gradient(135deg, #2d6b82 0%, #1f5a6f 100%);
			transform: translateY(0) scale(0.98);
			box-shadow: 0 2px 8px rgba(75, 169, 194, 0.3);
		}

		.interactive-button-secondary {
			background: linear-gradient(135deg, #797979 0%, #666666 100%);
			color: white;
			box-shadow: 0 4px 12px rgba(121, 121, 121, 0.3);
		}

		.interactive-button-secondary::before {
			content: '';
			position: absolute;
			top: 50%;
			left: 50%;
			width: 0;
			height: 0;
			border-radius: 50%;
			background: rgba(255, 255, 255, 0.3);
			transform: translate(-50%, -50%);
			transition: width 0.6s, height 0.6s;
		}

		.interactive-button-secondary:hover {
			background: linear-gradient(135deg, #666666 0%, #555555 100%);
			box-shadow: 0 8px 20px rgba(121, 121, 121, 0.5);
			transform: translateY(-2px) scale(1.02);
		}

		.interactive-button-secondary:active::before {
			width: 300px;
			height: 300px;
		}

		.interactive-button-secondary:active {
			background: linear-gradient(135deg, #555555 0%, #444444 100%);
			transform: translateY(0) scale(0.98);
			box-shadow: 0 2px 8px rgba(121, 121, 121, 0.3);
		}

		.button-content {
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
			position: relative;
			z-index: 1;
		}

		.button-spinner {
			display: none;
			width: 18px;
			height: 18px;
			border: 2px solid rgba(255, 255, 255, 0.3);
			border-top-color: white;
			border-radius: 50%;
			animation: spin 0.8s linear infinite;
		}

		.interactive-button.loading .button-spinner {
			display: block;
		}

		.interactive-button.loading .button-text {
			opacity: 0.7;
		}

		@keyframes spin {
			to { transform: rotate(360deg); }
		}

		/* Dark mode support for buttons */
		.dark .interactive-button-primary {
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.4);
		}

		.dark .interactive-button-primary:hover {
			box-shadow: 0 8px 20px rgba(75, 169, 194, 0.6);
		}

		.dark .interactive-button-secondary {
			box-shadow: 0 4px 12px rgba(121, 121, 121, 0.4);
		}

		.dark .interactive-button-secondary:hover {
			box-shadow: 0 8px 20px rgba(121, 121, 121, 0.6);
		}
	</style>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Add loading state to submit buttons on form submission
			const forms = document.querySelectorAll('form');
			
			forms.forEach(form => {
				const submitButton = form?.querySelector('button[type="submit"]');
				
				if (form && submitButton) {
					form.addEventListener('submit', function() {
						submitButton.classList.add('loading');
						submitButton.disabled = true;
					});
				}
			});

			// Handle file input change to display filename
			const fileInput = document.getElementById('invoiceFile');
			const fileDisplay = document.getElementById('fileDisplay');
			const noFileText = @json(__('No file chosen'));
			
			if (fileInput && fileDisplay) {
				fileInput.addEventListener('change', function(e) {
					const file = e.target.files[0];
					if (file) {
						fileDisplay.innerHTML = '<span class="file-name">' + file.name + '</span>';
					} else {
						fileDisplay.innerHTML = '<span class="file-placeholder">' + noFileText + '</span>';
					}
				});
			}
		});
	</script>
</x-app-layout>

