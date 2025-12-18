<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Edit Asset') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			{{-- Breadcrumb --}}
			<div class="mb-6">
				<a href="{{ route('itdept.manage-assets.index', ['assetType' => $asset->assetType]) }}" 
				   style="color: #4BA9C2;"
				   class="hover:opacity-80">
					← Assets
				</a>
				<span class="text-gray-600 dark:text-gray-400"> > </span>
				<a href="{{ route('itdept.manage-assets.show', $asset->assetID) }}" 
				   style="color: #4BA9C2;"
				   class="hover:opacity-80">
					{{ $asset->assetID }}
				</a>
				<span class="text-gray-600 dark:text-gray-400"> > </span>
				<span class="text-gray-600 dark:text-gray-400">{{ __('Edit Asset') }}</span>
			</div>

			{{-- Main Content Card --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Title --}}
					<div class="mb-6">
						<h1 class="text-xl font-semibold">{{ __('Edit Asset') }}</h1>
					</div>

					{{-- Edit Asset Section --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-4">{{ __('Asset Information') }}</h3>
						<form action="{{ route('itdept.manage-assets.update', $asset->assetID) }}" method="POST">
							@csrf
							@method('PUT')
							{{-- Hidden field for assetType (required by controller but not displayed) --}}
							<input type="hidden" name="assetType" value="{{ $asset->assetType }}">

							<div class="space-y-4">
								{{-- Row 1: Asset ID and Serial Number --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="input-container">
										<x-input-label for="assetID" :value="__('Asset ID')" class="text-[15px]" />
										<x-text-input id="assetID" name="assetID" type="text" 
											class="mt-1 block w-full interactive-input" 
											value="{{ $asset->assetID }}" 
											placeholder="Enter the asset ID"
											disabled />
										<x-input-error :messages="$errors->get('assetID')" class="mt-2" />
									</div>
									<div class="input-container">
										<x-input-label for="serialNum" :value="__('Serial Number')" class="text-[15px]" />
										<x-text-input id="serialNum" name="serialNum" type="text" 
											class="mt-1 block w-full interactive-input" 
											value="{{ old('serialNum', $asset->serialNum) }}"
											placeholder="Enter the serial number" />
										<x-input-error :messages="$errors->get('serialNum')" class="mt-2" />
									</div>
								</div>

								{{-- Row 2: Model --}}
								<div class="input-container">
									<x-input-label for="model" :value="__('Model')" class="text-[15px]" />
									<x-text-input id="model" name="model" type="text" 
										class="mt-1 block w-full interactive-input" 
										value="{{ old('model', $asset->model) }}"
										placeholder="Enter the model" />
									<x-input-error :messages="$errors->get('model')" class="mt-2" />
								</div>

								{{-- Row 3: RAM and Storage --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="input-container">
										<x-input-label for="ram" :value="__('RAM')" class="text-[15px]" />
										<x-text-input id="ram" name="ram" type="text" 
											class="mt-1 block w-full interactive-input" 
											value="{{ old('ram', $asset->ram) }}"
											placeholder="Enter RAM size, type & brand (eg; 16GB DDR5 KINGSTON)" />
										<x-input-error :messages="$errors->get('ram')" class="mt-2" />
									</div>
									<div class="input-container">
										<x-input-label for="storage" :value="__('Storage')" class="text-[15px]" />
										<x-text-input id="storage" name="storage" type="text" 
											class="mt-1 block w-full interactive-input" 
											value="{{ old('storage', $asset->storage) }}"
											placeholder="Enter the storage size & type (eg; 1TB SSD)" />
										<x-input-error :messages="$errors->get('storage')" class="mt-2" />
									</div>
								</div>

								{{-- Row 4: Purchase Date and OS Version --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="input-container">
										<x-input-label for="purchaseDate" :value="__('Purchase Date')" class="text-[15px]" />
										<x-text-input id="purchaseDate" name="purchaseDate" type="date" 
											class="mt-1 block w-full interactive-input" 
											value="{{ old('purchaseDate', $asset->purchaseDate ? $asset->purchaseDate->format('Y-m-d') : '') }}" />
										<x-input-error :messages="$errors->get('purchaseDate')" class="mt-2" />
									</div>
									<div>
										<x-input-label :value="__('OS Version')" class="text-[15px]" />
										<div class="mt-2 space-x-6">
											<label class="inline-flex items-center interactive-option-label">
												<input type="radio" name="osVer" value="Windows 10" 
													class="me-2 interactive-radio"
													@checked(old('osVer', $asset->osVer) === 'Windows 10')>
												<span class="text-gray-700 dark:text-gray-300 text-[15px]">Windows 10</span>
											</label>
											<label class="inline-flex items-center interactive-option-label">
												<input type="radio" name="osVer" value="Windows 11" 
													class="me-2 interactive-radio"
													@checked(old('osVer', $asset->osVer) === 'Windows 11')>
												<span class="text-gray-700 dark:text-gray-300 text-[15px]">Windows 11</span>
											</label>
										</div>
										<x-input-error :messages="$errors->get('osVer')" class="mt-2" />
									</div>
								</div>

								{{-- Row 5: Processor --}}
								<div class="input-container">
									<x-input-label for="processor" :value="__('Processor')" class="text-[15px]" />
									<x-text-input id="processor" name="processor" type="text" 
										class="mt-1 block w-full interactive-input" 
										value="{{ old('processor', $asset->processor) }}"
										placeholder="Enter the processor details" />
									<x-input-error :messages="$errors->get('processor')" class="mt-2" />
								</div>
							</div>

							{{-- Buttons --}}
							<div class="flex items-center justify-end space-x-6 mt-6">
								<a href="{{ route('itdept.manage-assets.show', $asset->assetID) }}" 
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
										<span class="button-text">{{ __('Save Changes') }}</span>
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

		.input-container:has(.interactive-input:focus) {
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

		/* Disabled input styling */
		.interactive-input:disabled {
			background-color: #F3F4F6;
			cursor: not-allowed;
			opacity: 0.6;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input:disabled {
				background-color: #1F2937;
			}
		}

		.dark .interactive-input:disabled {
			background-color: #1F2937;
		}

		.interactive-input:disabled:hover {
			transform: none;
			border-color: #9CA3AF;
			box-shadow: none;
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
	</style>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Add loading state to submit button on form submission
			const form = document.querySelector('form');
			const submitButton = form?.querySelector('button[type="submit"]');
			
			if (form && submitButton) {
				form.addEventListener('submit', function() {
					submitButton.classList.add('loading');
					submitButton.disabled = true;
				});
			}
		});
	</script>
</x-app-layout>
