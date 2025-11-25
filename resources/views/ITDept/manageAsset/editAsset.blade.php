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

							<div class="space-y-5">
								{{-- Row 1: Asset ID and Serial Number --}}
								<div class="grid grid-cols-2 gap-4">
									<div>
										<x-input-label for="assetID" :value="__('Asset ID')" />
										<x-text-input id="assetID" name="assetID" type="text" 
											class="mt-1 block w-full bg-gray-100 dark:bg-gray-800 cursor-not-allowed" 
											value="{{ $asset->assetID }}" 
											placeholder="Enter the asset ID"
											disabled />
										<x-input-error :messages="$errors->get('assetID')" class="mt-2" />
									</div>
									<div>
										<x-input-label for="serialNum" :value="__('Serial Number')" />
										<x-text-input id="serialNum" name="serialNum" type="text" 
											class="mt-1 block w-full" 
											value="{{ old('serialNum', $asset->serialNum) }}"
											placeholder="Enter the serial number" />
										<x-input-error :messages="$errors->get('serialNum')" class="mt-2" />
									</div>
								</div>

								{{-- Row 2: Model --}}
								<div>
									<x-input-label for="model" :value="__('Model')" />
									<x-text-input id="model" name="model" type="text" 
										class="mt-1 block w-full" 
										value="{{ old('model', $asset->model) }}"
										placeholder="Enter the model" />
									<x-input-error :messages="$errors->get('model')" class="mt-2" />
								</div>

								{{-- Row 3: RAM and Storage --}}
								<div class="grid grid-cols-2 gap-4">
									<div>
										<x-input-label for="ram" :value="__('RAM')" />
										<x-text-input id="ram" name="ram" type="text" 
											class="mt-1 block w-full" 
											value="{{ old('ram', $asset->ram) }}"
											placeholder="Enter RAM size, type & brand (eg; 16GB DDR5 KINGSTON)" />
										<x-input-error :messages="$errors->get('ram')" class="mt-2" />
									</div>
									<div>
										<x-input-label for="storage" :value="__('Storage')" />
										<x-text-input id="storage" name="storage" type="text" 
											class="mt-1 block w-full" 
											value="{{ old('storage', $asset->storage) }}"
											placeholder="Enter the storage size & type (eg; 1TB SSD)" />
										<x-input-error :messages="$errors->get('storage')" class="mt-2" />
									</div>
								</div>

								{{-- Row 4: Purchase Date and OS Version --}}
								<div class="grid grid-cols-2 gap-4">
									<div>
										<x-input-label for="purchaseDate" :value="__('Purchase Date')" />
										<x-text-input id="purchaseDate" name="purchaseDate" type="date" 
											class="mt-1 block w-full" 
											value="{{ old('purchaseDate', $asset->purchaseDate ? $asset->purchaseDate->format('Y-m-d') : '') }}" />
										<x-input-error :messages="$errors->get('purchaseDate')" class="mt-2" />
									</div>
									<div>
										<x-input-label :value="__('OS Version')" />
										<div class="mt-2 space-x-6">
											<label class="inline-flex items-center">
												<input type="radio" name="osVer" value="Windows 10" 
													class="me-2"
													style="accent-color: #4BA9C2;"
													@checked(old('osVer', $asset->osVer) === 'Windows 10')>
												<span class="text-gray-700 dark:text-gray-300">Windows 10</span>
											</label>
											<label class="inline-flex items-center">
												<input type="radio" name="osVer" value="Windows 11" 
													class="me-2"
													style="accent-color: #4BA9C2;"
													@checked(old('osVer', $asset->osVer) === 'Windows 11')>
												<span class="text-gray-700 dark:text-gray-300">Windows 11</span>
											</label>
										</div>
										<x-input-error :messages="$errors->get('osVer')" class="mt-2" />
									</div>
								</div>

								{{-- Row 5: Processor --}}
								<div>
									<x-input-label for="processor" :value="__('Processor')" />
									<x-text-input id="processor" name="processor" type="text" 
										class="mt-1 block w-full" 
										value="{{ old('processor', $asset->processor) }}"
										placeholder="Enter the processor details" />
									<x-input-error :messages="$errors->get('processor')" class="mt-2" />
								</div>
							</div>

							{{-- Buttons --}}
							<div class="flex items-center justify-end space-x-6 mt-6">
								<a href="{{ route('itdept.manage-assets.show', $asset->assetID) }}" 
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
									{{ __('Save Changes') }}
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
