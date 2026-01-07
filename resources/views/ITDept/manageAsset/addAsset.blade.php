<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Add New Asset') }}
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
				<span class="text-gray-600 dark:text-gray-400">{{ __('Add New Asset') }}</span>
			</div>

			{{-- Main Content Card --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100" x-data="{ showBulkUpload: {{ $errors->has('file') ? 'true' : 'false' }} }">

					{{-- Title and Add Asset in Bulk Button --}}
					<div class="mb-6 flex items-center justify-between">
						<h1 class="text-xl font-semibold">{{ __('Add New Asset') }}</h1>
						<button type="button" 
							@click="showBulkUpload = !showBulkUpload"
							class="interactive-button interactive-button-primary"
							style="padding: 10px 16px; font-size: 11px;">
							<span class="button-content">
								<svg class="w-3 h-3 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
								</svg>
								{{ __('Add Asset in Bulk') }}
							</span>
						</button>
					</div>

					{{-- Add Asset in Bulk Section --}}
					<div x-show="showBulkUpload" x-transition class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-md" style="display: none;">
						<h3 class="text-lg font-semibold mb-4">{{ __('Add Asset in Bulk') }}</h3>
						
						{{-- Error messages --}}
						@if ($errors->has('file'))
							<div class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-lg">
								<div class="flex items-center">
									<svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
									</svg>
									<p class="text-red-700 dark:text-red-300 font-medium">
										{{ $errors->first('file') }}
									</p>
								</div>
							</div>
						@endif
						
						<form action="{{ route('itdept.manage-assets.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
							@csrf
							<div class="input-container">
								<x-input-label for="bulkFile" :value="__('CSV File')" class="text-[15px]" />
								<div class="custom-file-input-wrapper mt-1">
									<input type="file" id="bulkFile" name="file" accept=".csv" 
										class="hidden-file-input"
										required />
									<button type="button" class="file-select-button" onclick="document.getElementById('bulkFile').click()">
										{{ __('Choose File') }}
									</button>
									<div class="file-display-area" id="bulkFileDisplay">
										<span class="file-placeholder">{{ __('No file chosen') }}</span>
									</div>
								</div>
								<p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Accepted format: CSV (Max: 10MB)</p>
							</div>

							<div class="flex items-center justify-end space-x-6 mt-6">
								<a href="{{ route('itdept.manage-assets.template') }}" 
								   class="interactive-button interactive-button-primary"
								   style="padding: 10px 16px; font-size: 11px;">
									<span class="button-content">
										<svg class="w-3 h-3 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
										</svg>
										{{ __('Download Template') }}
									</span>
								</a>
								<button type="submit" 
									class="interactive-button interactive-button-primary"
									style="padding: 10px 16px; font-size: 11px;">
									<span class="button-content">
										<span class="button-text">{{ __('Add Asset') }}</span>
										<span class="button-spinner"></span>
									</span>
								</button>
							</div>
						</form>
					</div>

					{{-- Add Asset Manually Section --}}
					<div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-4">{{ __('Add Asset Manually') }}</h3>
						<form action="{{ route('itdept.manage-assets.store') }}" method="POST">
							@csrf
							<div class="space-y-4">
								{{-- Row 1: Asset Type --}}
								<div>
									<x-input-label :value="__('Asset Type')" class="text-[15px]" />
									<div class="mt-2 space-x-6">
										<label class="inline-flex items-center interactive-option-label">
											<input type="radio" name="assetType" value="Laptop" 
												id="assetTypeLaptop"
												class="me-2 interactive-radio"
												required
												onchange="generateAssetID('Laptop')">
											<span class="text-gray-700 dark:text-gray-300 text-[15px]">Laptop</span>
										</label>
										<label class="inline-flex items-center interactive-option-label">
											<input type="radio" name="assetType" value="Desktop" 
												id="assetTypeDesktop"
												class="me-2 interactive-radio"
												required
												onchange="generateAssetID('Desktop')">
											<span class="text-gray-700 dark:text-gray-300 text-[15px]">Desktop</span>
										</label>
									</div>
									<x-input-error :messages="$errors->get('assetType')" class="mt-2" />
								</div>

								{{-- Row 2: Asset ID and Serial Number --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="input-container">
										<x-input-label for="assetID" :value="__('Asset ID')" class="text-[15px]" />
										<x-text-input id="assetID" name="assetID" type="text" 
											class="mt-1 block w-full interactive-input readonly-input" 
											placeholder="Select asset type to auto-generate"
											readonly />
										<x-input-error :messages="$errors->get('assetID')" class="mt-2" />
									</div>
									<div class="input-container">
										<x-input-label for="serialNum" :value="__('Serial Number')" class="text-[15px]" />
										<x-text-input id="serialNum" name="serialNum" type="text" 
											class="mt-1 block w-full interactive-input" 
											placeholder="Enter the serial number"
											onblur="validateSerialNumber(this.value)" />
										<x-input-error :messages="$errors->get('serialNum')" class="mt-2" />
									</div>
								</div>

								{{-- Row 3: Model --}}
								<div class="input-container">
									<x-input-label for="model" :value="__('Model')" class="text-[15px]" />
									<x-text-input id="model" name="model" type="text" 
										class="mt-1 block w-full interactive-input" 
										placeholder="Enter the model" />
									<x-input-error :messages="$errors->get('model')" class="mt-2" />
								</div>

								{{-- Row 4: RAM and Storage --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="input-container">
										<x-input-label for="ram" :value="__('RAM')" class="text-[15px]" />
										<x-text-input id="ram" name="ram" type="text" 
											class="mt-1 block w-full interactive-input" 
											placeholder="Enter RAM size, type & brand (eg; 16GB DDR5 KINGSTON)" />
										<x-input-error :messages="$errors->get('ram')" class="mt-2" />
									</div>
									<div class="input-container">
										<x-input-label for="storage" :value="__('Storage')" class="text-[15px]" />
										<x-text-input id="storage" name="storage" type="text" 
											class="mt-1 block w-full interactive-input" 
											placeholder="Enter the storage size & type (eg; 1TB SSD)" />
										<x-input-error :messages="$errors->get('storage')" class="mt-2" />
									</div>
								</div>

								{{-- Row 5: Purchase Date and OS Version --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="input-container">
										<x-input-label for="purchaseDate" :value="__('Purchase Date')" class="text-[15px]" />
										<x-text-input id="purchaseDate" name="purchaseDate" type="date" 
											class="mt-1 block w-full interactive-input" />
										<x-input-error :messages="$errors->get('purchaseDate')" class="mt-2" />
									</div>
									<div>
										<x-input-label :value="__('OS Version')" class="text-[15px]" />
										<div class="mt-2 space-x-6">
											<label class="inline-flex items-center interactive-option-label">
												<input type="radio" name="osVer" value="Windows 10" 
													class="me-2 interactive-radio">
												<span class="text-gray-700 dark:text-gray-300 text-[15px]">Windows 10</span>
											</label>
											<label class="inline-flex items-center interactive-option-label">
												<input type="radio" name="osVer" value="Windows 11" 
													class="me-2 interactive-radio">
												<span class="text-gray-700 dark:text-gray-300 text-[15px]">Windows 11</span>
											</label>
										</div>
										<x-input-error :messages="$errors->get('osVer')" class="mt-2" />
									</div>
								</div>

								{{-- Row 6: Processor --}}
								<div class="input-container">
									<x-input-label for="processor" :value="__('Processor')" class="text-[15px]" />
									<x-text-input id="processor" name="processor" type="text" 
										class="mt-1 block w-full interactive-input" 
										placeholder="Enter the processor details" />
									<x-input-error :messages="$errors->get('processor')" class="mt-2" />
								</div>
							</div>

							{{-- Buttons --}}
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
										<span class="button-text">{{ __('Add Asset') }}</span>
										<span class="button-spinner"></span>
									</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

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
		.input-container:has(.hidden-file-input:focus) {
			transform: translateY(-2px);
		}

		/* Interactive input styling */
		.interactive-input {
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
			.interactive-input {
				background-color: #111827;
				border-color: #6B7280;
				color: #D1D5DB;
			}
		}

		.dark .interactive-input {
			background-color: #111827;
			border-color: #6B7280;
			color: #D1D5DB;
		}

		.interactive-input:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.15);
			transform: translateY(-1px);
			background-color: #FAFAFA;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input:hover {
				border-color: #4BA9C2;
				box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
				background-color: #1F2937;
			}
		}

		.dark .interactive-input:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
			background-color: #1F2937;
		}

		.interactive-input:focus {
			outline: none;
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.15), 0 6px 16px rgba(75, 169, 194, 0.2);
			background-color: #FFFFFF;
			transform: translateY(-2px);
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input:focus {
				border-color: #4BA9C2;
				box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
				background-color: #111827;
				color: #D1D5DB;
			}
		}

		.dark .interactive-input:focus {
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
			background-color: #111827;
			color: #D1D5DB;
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

		/* Interactive radio button styling */
		.interactive-radio {
			width: 18px;
			height: 18px;
			border: 2px solid #9CA3AF;
			border-radius: 50%;
			cursor: pointer;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			position: relative;
			appearance: none;
			-webkit-appearance: none;
			-moz-appearance: none;
			background-color: #FFFFFF;
			accent-color: #4BA9C2;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-radio {
				background-color: #111827;
				border-color: #6B7280;
			}
		}

		.dark .interactive-radio {
			background-color: #111827;
			border-color: #6B7280;
		}

		.interactive-radio:hover {
			border-color: #4BA9C2;
			box-shadow: 0 2px 8px rgba(75, 169, 194, 0.15);
			transform: scale(1.05);
		}

		@media (prefers-color-scheme: dark) {
			.interactive-radio:hover {
				background-color: #1F2937;
			}
		}

		.dark .interactive-radio:hover {
			background-color: #1F2937;
		}

		.interactive-radio:checked {
			background-color: #4BA9C2;
			border-color: #4BA9C2;
		}

		.interactive-radio:checked::after {
			content: '';
			position: absolute;
			left: 50%;
			top: 50%;
			transform: translate(-50%, -50%);
			width: 8px;
			height: 8px;
			border-radius: 50%;
			background-color: white;
		}

		.interactive-radio:focus {
			outline: none;
			box-shadow: 0 0 0 3px rgba(75, 169, 194, 0.2);
		}

		/* Interactive radio label hover effect */
		.interactive-option-label {
			cursor: pointer;
			transition: color 0.2s ease;
			padding: 4px 8px;
			border-radius: 4px;
		}

		.interactive-option-label:hover {
			color: #4BA9C2;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-option-label:hover {
				color: #4BA9C2;
			}
		}

		.dark .interactive-option-label:hover {
			color: #4BA9C2;
		}

		/* Read-only input styling */
		.readonly-input {
			background-color: #F3F4F6 !important;
			cursor: not-allowed !important;
			color: #6B7280 !important;
		}

		.dark .readonly-input {
			background-color: #374151 !important;
			color: #9CA3AF !important;
		}

		.readonly-input:hover,
		.readonly-input:focus {
			border-color: #9CA3AF !important;
			box-shadow: none !important;
			transform: none !important;
		}
	</style>

	<script>
		// Pre-loaded asset IDs from server
		const assetIDs = {
			'Laptop': @json($nextLaptopID ?? 'LAPTOP0001'),
			'Desktop': @json($nextDesktopID ?? 'DESKTOP0001')
		};

		// Function to set asset ID when asset type is selected
		function generateAssetID(assetType) {
			const assetIDInput = document.getElementById('assetID');
			if (!assetIDInput) return;

			// Validate asset type
			if (!assetType || !['Laptop', 'Desktop'].includes(assetType)) {
				console.error('Invalid asset type:', assetType);
				return;
			}

			// Set the pre-generated asset ID
			if (assetIDs[assetType]) {
				assetIDInput.value = assetIDs[assetType];
			} else {
				console.error('Asset ID not found for type:', assetType);
				assetIDInput.value = '';
			}
		}

		// Function to validate serial number uniqueness
		async function validateSerialNumber(serialNum) {
			if (!serialNum || serialNum.trim() === '') {
				return; // Skip validation if empty
			}

			try {
				const response = await fetch(`{{ route('itdept.manage-assets.index') }}?q=${encodeURIComponent(serialNum)}`, {
					method: 'GET',
					headers: {
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'application/json',
					}
				});

				// Check if serial number exists by searching assets
				// We'll use a simpler approach - check via form submission validation
				// The backend will handle the validation and return error
			} catch (error) {
				console.error('Error validating serial number:', error);
			}
		}

		document.addEventListener('DOMContentLoaded', function() {
			// Add loading state to submit buttons on form submission
			const forms = document.querySelectorAll('form');
			
			forms.forEach(form => {
				const submitButton = form?.querySelector('button[type="submit"]');
				
				if (form && submitButton) {
					form.addEventListener('submit', function(e) {
						// Validate serial number before submission
						const serialNumInput = form.querySelector('#serialNum');
						if (serialNumInput && serialNumInput.value.trim() !== '') {
							// Serial number validation will be handled by backend
							// If there's an error, the form won't submit and error will be shown
						}

						submitButton.classList.add('loading');
						submitButton.disabled = true;
					});
				}
			});

			// Handle bulk file input change to display filename
			const bulkFileInput = document.getElementById('bulkFile');
			const bulkFileDisplay = document.getElementById('bulkFileDisplay');
			const noFileText = @json(__('No file chosen'));
			
			if (bulkFileInput && bulkFileDisplay) {
				bulkFileInput.addEventListener('change', function(e) {
					const file = e.target.files[0];
					if (file) {
						bulkFileDisplay.innerHTML = '<span class="file-name">' + file.name + '</span>';
					} else {
						bulkFileDisplay.innerHTML = '<span class="file-placeholder">' + noFileText + '</span>';
					}
				});
			}
		});
	</script>
</x-app-layout>
