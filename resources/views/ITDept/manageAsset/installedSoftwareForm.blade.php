<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ !empty($existingSoftware) ? __('Edit Installed Software') : __('Add Installed Software') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<div class="mb-6">
						<a href="{{ route('itdept.manage-assets.show', $asset->assetID) }}" class="text-sky-600 hover:text-sky-800 dark:text-sky-400 dark:hover:text-sky-300">
							← Back to Asset Details
						</a>
					</div>

					{{-- Asset Information --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-3">{{ __('Asset Information') }}</h3>
						<div class="grid grid-cols-2 gap-4 text-sm">
							<div>
								<label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Asset ID') }}:</label>
								<p class="text-gray-900 dark:text-gray-100">{{ $asset->assetID }}</p>
							</div>
							<div>
								<label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Model') }}:</label>
								<p class="text-gray-900 dark:text-gray-100">{{ $asset->model ?? '-' }}</p>
							</div>
						</div>
					</div>

					<form action="{{ route('itdept.manage-assets.installed-software.store', $asset->assetID) }}" method="POST" class="space-y-6"
						x-data="{ 
							showOfficeVersion: {{ in_array('Microsoft Office', $existingSoftware) ? 'true' : 'false' }},
							showOthersInput: {{ in_array('Others', $existingSoftware) ? 'true' : 'false' }},
							officeVersion: '{{ $officeVersion ?? '' }}',
							othersSoftware: '{{ $othersSoftware ?? '' }}'
						}">
						@csrf

						<div class="space-y-4">
							<h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-700 pb-2">
								{{ __('Select Installed Software') }}
							</h3>

							<div class="space-y-3">
								{{-- Microsoft Office --}}
								<div class="flex items-start">
									<label class="inline-flex items-center">
										<input type="checkbox" name="software[]" value="Microsoft Office" 
											@if(in_array('Microsoft Office', $existingSoftware)) checked @endif
											x-on:change="showOfficeVersion = $event.target.checked; if (!$event.target.checked) officeVersion = '';"
											class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
										<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Microsoft Office</span>
									</label>
								</div>
								<div x-show="showOfficeVersion" x-transition class="ms-6 mb-3">
									<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Version') }}</label>
									<div class="space-y-2">
										<label class="inline-flex items-center me-4">
											<input type="radio" name="officeVersion" value="2010" x-model="officeVersion" 
												@if(($officeVersion ?? '') === '2010') checked @endif
												class="me-2">
											<span class="text-sm text-gray-700 dark:text-gray-300">2010</span>
										</label>
										<label class="inline-flex items-center me-4">
											<input type="radio" name="officeVersion" value="2013" x-model="officeVersion" 
												@if(($officeVersion ?? '') === '2013') checked @endif
												class="me-2">
											<span class="text-sm text-gray-700 dark:text-gray-300">2013</span>
										</label>
										<label class="inline-flex items-center me-4">
											<input type="radio" name="officeVersion" value="2019" x-model="officeVersion" 
												@if(($officeVersion ?? '') === '2019') checked @endif
												class="me-2">
											<span class="text-sm text-gray-700 dark:text-gray-300">2019</span>
										</label>
										<label class="inline-flex items-center me-4">
											<input type="radio" name="officeVersion" value="2020" x-model="officeVersion" 
												@if(($officeVersion ?? '') === '2020') checked @endif
												class="me-2">
											<span class="text-sm text-gray-700 dark:text-gray-300">2020</span>
										</label>
										<label class="inline-flex items-center">
											<input type="radio" name="officeVersion" value="2024" x-model="officeVersion" 
												@if(($officeVersion ?? '') === '2024') checked @endif
												class="me-2">
											<span class="text-sm text-gray-700 dark:text-gray-300">2024</span>
										</label>
									</div>
								</div>

								{{-- Other Software Options --}}
								<label class="inline-flex items-center">
									<input type="checkbox" name="software[]" value="Adobe Acrobat Reader" 
										@if(in_array('Adobe Acrobat Reader', $existingSoftware)) checked @endif
										class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Adobe Acrobat Reader</span>
								</label>

								<label class="inline-flex items-center">
									<input type="checkbox" name="software[]" value="Adobe Acrobat Pro DC" 
										@if(in_array('Adobe Acrobat Pro DC', $existingSoftware)) checked @endif
										class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Adobe Acrobat Pro DC</span>
								</label>

								<label class="inline-flex items-center">
									<input type="checkbox" name="software[]" value="Foxit Reader" 
										@if(in_array('Foxit Reader', $existingSoftware)) checked @endif
										class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Foxit Reader</span>
								</label>

								<label class="inline-flex items-center">
									<input type="checkbox" name="software[]" value="7zip" 
										@if(in_array('7zip', $existingSoftware)) checked @endif
										class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">7zip</span>
								</label>

								<label class="inline-flex items-center">
									<input type="checkbox" name="software[]" value="Anydesk" 
										@if(in_array('Anydesk', $existingSoftware)) checked @endif
										class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Anydesk</span>
								</label>

								<label class="inline-flex items-center">
									<input type="checkbox" name="software[]" value="Antivirus" 
										@if(in_array('Antivirus', $existingSoftware)) checked @endif
										class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Antivirus</span>
								</label>

								<label class="inline-flex items-center">
									<input type="checkbox" name="software[]" value="VPN" 
										@if(in_array('VPN', $existingSoftware)) checked @endif
										class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">VPN</span>
								</label>

								<label class="inline-flex items-center">
									<input type="checkbox" name="software[]" value="Autodesk AutoCad" 
										@if(in_array('Autodesk AutoCad', $existingSoftware)) checked @endif
										class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Autodesk AutoCad</span>
								</label>

								<label class="inline-flex items-center">
									<input type="checkbox" name="software[]" value="DraftSight" 
										@if(in_array('DraftSight', $existingSoftware)) checked @endif
										class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">DraftSight</span>
								</label>

								<label class="inline-flex items-center">
									<input type="checkbox" name="software[]" value="ProgeCad" 
										@if(in_array('ProgeCad', $existingSoftware)) checked @endif
										class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
									<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">ProgeCad</span>
								</label>

								{{-- Others --}}
								<div class="flex items-start">
									<label class="inline-flex items-center">
										<input type="checkbox" name="software[]" value="Others" 
											@if(in_array('Others', $existingSoftware)) checked @endif
											x-on:change="showOthersInput = $event.target.checked; if (!$event.target.checked) othersSoftware = '';"
											class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
										<span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Others</span>
									</label>
								</div>
								<div x-show="showOthersInput" x-transition class="ms-6 mb-3">
									<x-input-label for="othersSoftware" :value="__('Enter Software Name')" />
									<x-text-input id="othersSoftware" name="othersSoftware" type="text" 
										class="mt-1 block w-full" 
										x-model="othersSoftware"
										value="{{ $othersSoftware ?? '' }}"
										placeholder="{{ __('Enter software name') }}" />
									<x-input-error :messages="$errors->get('othersSoftware')" class="mt-2" />
								</div>
							</div>
						</div>

						<div class="flex items-center justify-end space-x-4">
							<a href="{{ route('itdept.manage-assets.show', $asset->assetID) }}" 
							   class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600 transition">
								{{ __('Cancel') }}
							</a>
							<x-primary-button>{{ __('Save Software') }}</x-primary-button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
