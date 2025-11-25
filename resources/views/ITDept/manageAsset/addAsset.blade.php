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
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Title --}}
					<div class="mb-6">
						<h1 class="text-xl font-semibold">{{ __('Add New Asset') }}</h1>
					</div>

					{{-- Add Asset Manually Section --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-4">{{ __('Add Asset Manually') }}</h3>
						<form action="{{ route('itdept.manage-assets.store') }}" method="POST">
							@csrf
							<div class="space-y-5">
								{{-- Row 1: Asset Type --}}
								<div>
									<x-input-label :value="__('Asset Type')" />
									<div class="mt-2 space-x-6">
										<label class="inline-flex items-center">
											<input type="radio" name="assetType" value="Laptop" 
												class="me-2"
												style="accent-color: #4BA9C2;"
												required>
											<span class="text-gray-700 dark:text-gray-300">Laptop</span>
										</label>
										<label class="inline-flex items-center">
											<input type="radio" name="assetType" value="Desktop" 
												class="me-2"
												style="accent-color: #4BA9C2;"
												required>
											<span class="text-gray-700 dark:text-gray-300">Desktop</span>
										</label>
									</div>
									<x-input-error :messages="$errors->get('assetType')" class="mt-2" />
								</div>

								{{-- Row 2: Asset ID and Serial Number --}}
								<div class="grid grid-cols-2 gap-4">
									<div>
										<x-input-label for="assetID" :value="__('Asset ID')" />
										<x-text-input id="assetID" name="assetID" type="text" 
											class="mt-1 block w-full" 
											placeholder="Enter the asset ID"
											required />
										<x-input-error :messages="$errors->get('assetID')" class="mt-2" />
									</div>
									<div>
										<x-input-label for="serialNum" :value="__('Serial Number')" />
										<x-text-input id="serialNum" name="serialNum" type="text" 
											class="mt-1 block w-full" 
											placeholder="Enter the serial number" />
										<x-input-error :messages="$errors->get('serialNum')" class="mt-2" />
									</div>
								</div>

								{{-- Row 3: Model --}}
								<div>
									<x-input-label for="model" :value="__('Model')" />
									<x-text-input id="model" name="model" type="text" 
										class="mt-1 block w-full" 
										placeholder="Enter the model" />
									<x-input-error :messages="$errors->get('model')" class="mt-2" />
								</div>

								{{-- Row 4: RAM and Storage --}}
								<div class="grid grid-cols-2 gap-4">
									<div>
										<x-input-label for="ram" :value="__('RAM')" />
										<x-text-input id="ram" name="ram" type="text" 
											class="mt-1 block w-full" 
											placeholder="Enter RAM size, type & brand (eg; 16GB DDR5 KINGSTON)" />
										<x-input-error :messages="$errors->get('ram')" class="mt-2" />
									</div>
									<div>
										<x-input-label for="storage" :value="__('Storage')" />
										<x-text-input id="storage" name="storage" type="text" 
											class="mt-1 block w-full" 
											placeholder="Enter the storage size & type (eg; 1TB SSD)" />
										<x-input-error :messages="$errors->get('storage')" class="mt-2" />
									</div>
								</div>

								{{-- Row 5: Purchase Date and OS Version --}}
								<div class="grid grid-cols-2 gap-4">
									<div>
										<x-input-label for="purchaseDate" :value="__('Purchase Date')" />
										<x-text-input id="purchaseDate" name="purchaseDate" type="date" 
											class="mt-1 block w-full" />
										<x-input-error :messages="$errors->get('purchaseDate')" class="mt-2" />
									</div>
									<div>
										<x-input-label :value="__('OS Version')" />
										<div class="mt-2 space-x-6">
											<label class="inline-flex items-center">
												<input type="radio" name="osVer" value="Windows 10" 
													class="me-2"
													style="accent-color: #4BA9C2;">
												<span class="text-gray-700 dark:text-gray-300">Windows 10</span>
											</label>
											<label class="inline-flex items-center">
												<input type="radio" name="osVer" value="Windows 11" 
													class="me-2"
													style="accent-color: #4BA9C2;">
												<span class="text-gray-700 dark:text-gray-300">Windows 11</span>
											</label>
										</div>
										<x-input-error :messages="$errors->get('osVer')" class="mt-2" />
									</div>
								</div>

								{{-- Row 6: Processor --}}
								<div>
									<x-input-label for="processor" :value="__('Processor')" />
									<x-text-input id="processor" name="processor" type="text" 
										class="mt-1 block w-full" 
										placeholder="Enter the processor details" />
									<x-input-error :messages="$errors->get('processor')" class="mt-2" />
								</div>
							</div>

							{{-- Buttons --}}
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
									{{ __('Add Asset') }}
								</button>
							</div>
						</form>
					</div>

					{{-- Bulk Upload Container --}}
					<div x-data="{ showBulkUpload: false }">
						{{-- Button to Show Bulk Upload Section --}}
						<div class="flex items-center justify-center mt-6">
							<button type="button" 
								@click="showBulkUpload = !showBulkUpload"
								class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
								style="background-color: #4BA9C2;"
								onmouseover="this.style.backgroundColor='#3a8ba5'"
								onmouseout="this.style.backgroundColor='#4BA9C2'">
								{{ __('Add Asset in Bulk') }}
							</button>
						</div>

						{{-- Divider Line --}}
						<div x-show="showBulkUpload" x-transition class="my-6 border-t border-gray-300 dark:border-gray-600" style="display: none;"></div>

						{{-- Add Asset in Bulk Section --}}
						<div x-show="showBulkUpload" x-transition class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md" style="display: none;">
							<h3 class="text-lg font-semibold mb-4">{{ __('Add Asset in Bulk') }}</h3>
							<form action="{{ route('itdept.manage-assets.import') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
								@csrf
								<div>
									<x-input-label for="bulkFile" :value="__('File')" />
									<input type="file" id="bulkFile" name="file" accept=".csv" 
										class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300
											border border-gray-300 dark:border-gray-700 rounded-md
											file:mr-4 file:py-2 file:px-4
											file:rounded-md file:border-0
											file:text-sm file:font-semibold
											file:bg-blue-50 dark:file:bg-blue-900
											file:text-blue-700 dark:file:text-blue-300
											hover:file:bg-blue-100 dark:hover:file:bg-blue-800
											cursor-pointer"
										required />
									<x-input-error :messages="$errors->get('file')" class="mt-2" />
								</div>

								<div class="flex items-center justify-end space-x-6 mt-6">
									<a href="{{ route('itdept.manage-assets.template') }}" 
									   class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
									   style="background-color: #4BA9C2;"
									   onmouseover="this.style.backgroundColor='#3a8ba5'"
									   onmouseout="this.style.backgroundColor='#4BA9C2'">
										<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
										</svg>
										{{ __('Download Template') }}
									</a>
									<button type="submit" 
										class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
										style="background-color: #4BA9C2;"
										onmouseover="this.style.backgroundColor='#3a8ba5'"
										onmouseout="this.style.backgroundColor='#4BA9C2'">
										{{ __('Add Asset') }}
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
