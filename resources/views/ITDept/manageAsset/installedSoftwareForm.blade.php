<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ !empty($existingSoftware) ? __('Edit Installed Software') : __('Add Installed Software') }}
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
				<span class="text-gray-600 dark:text-gray-400">{{ __('Installed Software') }}</span>
			</div>

			{{-- Main Content Card --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<style>
						/* Interactive checkbox styling */
						.interactive-checkbox {
							width: 18px;
							height: 18px;
							border: 2px solid #9CA3AF;
							border-radius: 4px;
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
							.interactive-checkbox {
								background-color: #111827;
								border-color: #6B7280;
							}
						}

						.dark .interactive-checkbox {
							background-color: #111827;
							border-color: #6B7280;
						}

						.interactive-checkbox:hover {
							border-color: #4BA9C2;
							box-shadow: 0 2px 8px rgba(75, 169, 194, 0.15);
							transform: scale(1.05);
						}

						@media (prefers-color-scheme: dark) {
							.interactive-checkbox:hover {
								background-color: #1F2937;
							}
						}

						.dark .interactive-checkbox:hover {
							background-color: #1F2937;
						}

						.interactive-checkbox:checked {
							background-color: #4BA9C2;
							border-color: #4BA9C2;
						}

						.interactive-checkbox:checked::after {
							content: '';
							position: absolute;
							left: 5px;
							top: 2px;
							width: 5px;
							height: 10px;
							border: solid white;
							border-width: 0 2px 2px 0;
							transform: rotate(45deg);
						}

						.interactive-checkbox:focus {
							outline: none;
							box-shadow: 0 0 0 3px rgba(75, 169, 194, 0.2);
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

						/* Interactive checkbox/radio label hover effect */
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

						/* Interactive input for Others field */
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

						/* Interactive button styling */
						.interactive-button {
							display: inline-flex;
							align-items: center;
							justify-content: center;
							padding: 10px 16px;
							font-weight: 600;
							font-size: 11px;
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

					{{-- Title --}}
					<div class="mb-6">
						<h1 class="text-xl font-semibold">{{ __('Installed Software') }}</h1>
					</div>

					{{-- Asset Information --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-3">{{ __('Asset Information') }}</h3>
						<div class="grid grid-cols-2 gap-4 text-sm">
							<div>
								<label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Asset ID') }}:</label>
								<p class="text-gray-900 dark:text-gray-100 font-bold">{{ $asset->assetID }}</p>
							</div>
							<div>
								<label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Model') }}:</label>
								<p class="text-gray-900 dark:text-gray-100 font-bold">{{ $asset->model ?? '-' }}</p>
							</div>
						</div>
					</div>

					<form action="{{ route('itdept.manage-assets.installed-software.store', $asset->assetID) }}" method="POST"
						x-data="{ 
							showOfficeVersion: {{ in_array('Microsoft Office', $existingSoftware) ? 'true' : 'false' }},
							showOthersInput: {{ in_array('Others', $existingSoftware) ? 'true' : 'false' }},
							officeVersion: '{{ $officeVersion ?? '' }}',
							othersSoftware: '{{ $othersSoftware ?? '' }}'
						}">
						@csrf

						{{-- Select Installed Software Section --}}
						<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
							<h3 class="text-lg font-semibold mb-4">{{ __('Select Installed Software') }}</h3>
							<div class="space-y-1">
								{{-- Microsoft Office --}}
								<div class="flex items-start">
									<label class="flex items-center block interactive-option-label">
										<input type="checkbox" name="software[]" value="Microsoft Office" 
											@if(in_array('Microsoft Office', $existingSoftware)) checked @endif
											x-on:change="showOfficeVersion = $event.target.checked; if (!$event.target.checked) officeVersion = '';"
											class="interactive-checkbox">
										<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Microsoft Office</span>
									</label>
								</div>
								<div x-show="showOfficeVersion" x-transition class="ms-6 mb-3">
									<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Version') }}</label>
									<div class="space-y-2">
										<label class="inline-flex items-center me-4 interactive-option-label">
											<input type="radio" name="officeVersion" value="2010" x-model="officeVersion" 
												@if(($officeVersion ?? '') === '2010') checked @endif
												class="me-2 interactive-radio">
											<span class="text-sm text-gray-700 dark:text-gray-300">2010</span>
										</label>
										<label class="inline-flex items-center me-4 interactive-option-label">
											<input type="radio" name="officeVersion" value="2013" x-model="officeVersion" 
												@if(($officeVersion ?? '') === '2013') checked @endif
												class="me-2 interactive-radio">
											<span class="text-sm text-gray-700 dark:text-gray-300">2013</span>
										</label>
										<label class="inline-flex items-center me-4 interactive-option-label">
											<input type="radio" name="officeVersion" value="2019" x-model="officeVersion" 
												@if(($officeVersion ?? '') === '2019') checked @endif
												class="me-2 interactive-radio">
											<span class="text-sm text-gray-700 dark:text-gray-300">2019</span>
										</label>
										<label class="inline-flex items-center me-4 interactive-option-label">
											<input type="radio" name="officeVersion" value="2020" x-model="officeVersion" 
												@if(($officeVersion ?? '') === '2020') checked @endif
												class="me-2 interactive-radio">
											<span class="text-sm text-gray-700 dark:text-gray-300">2020</span>
										</label>
										<label class="inline-flex items-center interactive-option-label">
											<input type="radio" name="officeVersion" value="2024" x-model="officeVersion" 
												@if(($officeVersion ?? '') === '2024') checked @endif
												class="me-2 interactive-radio">
											<span class="text-sm text-gray-700 dark:text-gray-300">2024</span>
										</label>
									</div>
								</div>

								{{-- Other Software Options --}}
								<div class="block">
									<label class="flex items-center interactive-option-label">
									<input type="checkbox" name="software[]" value="Adobe Acrobat Reader" 
										@if(in_array('Adobe Acrobat Reader', $existingSoftware)) checked @endif
											class="interactive-checkbox">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Adobe Acrobat Reader</span>
								</label>
								</div>

								<div class="block">
									<label class="flex items-center interactive-option-label">
									<input type="checkbox" name="software[]" value="Adobe Acrobat Pro DC" 
										@if(in_array('Adobe Acrobat Pro DC', $existingSoftware)) checked @endif
											class="interactive-checkbox">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Adobe Acrobat Pro DC</span>
								</label>
								</div>

								<div class="block">
									<label class="flex items-center interactive-option-label">
									<input type="checkbox" name="software[]" value="Foxit Reader" 
										@if(in_array('Foxit Reader', $existingSoftware)) checked @endif
											class="interactive-checkbox">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Foxit Reader</span>
								</label>
								</div>

								<div class="block">
									<label class="flex items-center interactive-option-label">
									<input type="checkbox" name="software[]" value="7zip" 
										@if(in_array('7zip', $existingSoftware)) checked @endif
											class="interactive-checkbox">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">7zip</span>
								</label>
								</div>

								<div class="block">
									<label class="flex items-center interactive-option-label">
									<input type="checkbox" name="software[]" value="Anydesk" 
										@if(in_array('Anydesk', $existingSoftware)) checked @endif
											class="interactive-checkbox">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Anydesk</span>
								</label>
								</div>

								<div class="block">
									<label class="flex items-center interactive-option-label">
									<input type="checkbox" name="software[]" value="Antivirus" 
										@if(in_array('Antivirus', $existingSoftware)) checked @endif
											class="interactive-checkbox">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Antivirus</span>
								</label>
								</div>

								<div class="block">
									<label class="flex items-center interactive-option-label">
									<input type="checkbox" name="software[]" value="VPN" 
										@if(in_array('VPN', $existingSoftware)) checked @endif
											class="interactive-checkbox">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">VPN</span>
								</label>
								</div>

								<div class="block">
									<label class="flex items-center interactive-option-label">
									<input type="checkbox" name="software[]" value="Autodesk AutoCad" 
										@if(in_array('Autodesk AutoCad', $existingSoftware)) checked @endif
											class="interactive-checkbox">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Autodesk AutoCad</span>
								</label>
								</div>

								<div class="block">
									<label class="flex items-center interactive-option-label">
									<input type="checkbox" name="software[]" value="DraftSight" 
										@if(in_array('DraftSight', $existingSoftware)) checked @endif
											class="interactive-checkbox">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">DraftSight</span>
								</label>
								</div>

								<div class="block">
									<label class="flex items-center interactive-option-label">
									<input type="checkbox" name="software[]" value="ProgeCad" 
										@if(in_array('ProgeCad', $existingSoftware)) checked @endif
											class="interactive-checkbox">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">ProgeCad</span>
								</label>
								</div>

								{{-- Others --}}
								<div class="flex items-center gap-3">
									<label class="inline-flex items-center interactive-option-label">
										<input type="checkbox" name="software[]" value="Others" 
											@if(in_array('Others', $existingSoftware)) checked @endif
											x-on:change="showOthersInput = $event.target.checked; if (!$event.target.checked) othersSoftware = '';"
											class="interactive-checkbox">
										<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Others</span>
									</label>
									<div x-show="showOthersInput" x-transition class="flex-1">
										<div class="input-container">
										<input id="othersSoftware" name="othersSoftware" type="text" 
												class="interactive-input" 
										x-model="othersSoftware"
										value="{{ $othersSoftware ?? '' }}"
										placeholder="Enter additional software (eg; Foxit Editor Pro, Nitro Pro)" />
										</div>
									<x-input-error :messages="$errors->get('othersSoftware')" class="mt-2" />
									</div>
								</div>
							</div>
						</div>

						<div class="flex items-center justify-end space-x-6 mt-6">
							<a href="{{ route('itdept.manage-assets.show', $asset->assetID) }}" 
							   class="interactive-button interactive-button-secondary">
								<span class="button-content">
									<span class="button-text">{{ __('Cancel') }}</span>
								</span>
							</a>
							<button type="submit"
								class="interactive-button interactive-button-primary">
								<span class="button-content">
									<span class="button-text">{{ __('Add Software') }}</span>
									<span class="button-spinner"></span>
								</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

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
		});
	</script>
</x-app-layout>
