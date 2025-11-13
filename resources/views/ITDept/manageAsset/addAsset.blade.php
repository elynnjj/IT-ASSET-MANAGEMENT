<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Add New Asset') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<form action="{{ route('itdept.manage-assets.store') }}" method="POST" class="space-y-4">
						@csrf
						<div>
							<x-input-label for="assetID" :value="__('Asset ID')" />
							<x-text-input id="assetID" name="assetID" type="text" class="mt-1 block w-full" required />
							<x-input-error :messages="$errors->get('assetID')" class="mt-2" />
						</div>

						<div>
							<x-input-label :value="__('Asset Type')" />
							<div class="mt-2 space-y-2">
								<label class="inline-flex items-center">
									<input type="radio" name="assetType" value="Laptop" class="me-2" required>
									<span>Laptop</span>
								</label>
								<label class="inline-flex items-center ms-6">
									<input type="radio" name="assetType" value="Desktop" class="me-2" required>
									<span>Desktop</span>
								</label>
							</div>
							<x-input-error :messages="$errors->get('assetType')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="serialNum" :value="__('Serial Number')" />
							<x-text-input id="serialNum" name="serialNum" type="text" class="mt-1 block w-full" />
							<x-input-error :messages="$errors->get('serialNum')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="model" :value="__('Model')" />
							<x-text-input id="model" name="model" type="text" class="mt-1 block w-full" />
							<x-input-error :messages="$errors->get('model')" class="mt-2" />
						</div>

						<div class="grid grid-cols-2 gap-4">
							<div>
								<x-input-label for="ram" :value="__('RAM')" />
								<x-text-input id="ram" name="ram" type="text" class="mt-1 block w-full" />
								<x-input-error :messages="$errors->get('ram')" class="mt-2" />
							</div>

							<div>
								<x-input-label for="storage" :value="__('Storage')" />
								<x-text-input id="storage" name="storage" type="text" class="mt-1 block w-full" />
								<x-input-error :messages="$errors->get('storage')" class="mt-2" />
							</div>
						</div>

						<div>
							<x-input-label for="purchaseDate" :value="__('Purchase Date')" />
							<x-text-input id="purchaseDate" name="purchaseDate" type="date" class="mt-1 block w-full" />
							<x-input-error :messages="$errors->get('purchaseDate')" class="mt-2" />
						</div>

						<div>
							<x-input-label :value="__('OS Version')" />
							<div class="mt-2 space-y-2">
								<label class="inline-flex items-center">
									<input type="radio" name="osVer" value="Windows 10" class="me-2">
									<span>Windows 10</span>
								</label>
								<label class="inline-flex items-center ms-6">
									<input type="radio" name="osVer" value="Windows 11" class="me-2">
									<span>Windows 11</span>
								</label>
							</div>
							<x-input-error :messages="$errors->get('osVer')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="processor" :value="__('Processor')" />
							<x-text-input id="processor" name="processor" type="text" class="mt-1 block w-full" />
							<x-input-error :messages="$errors->get('processor')" class="mt-2" />
						</div>

						<div class="flex items-center justify-end">
							<x-primary-button>{{ __('Create') }}</x-primary-button>
						</div>
					</form>

					<div class="mt-10" x-data="{ showBulkUpload: false }">
						<button type="button" @click="showBulkUpload = !showBulkUpload" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
							{{ __('Add Asset In Bulk') }}
						</button>

						<div x-show="showBulkUpload" x-transition class="mt-6">
							<h3 class="text-lg font-semibold mb-4">{{ __('Add assets in bulk') }}</h3>
							<div class="flex items-center justify-between mb-4">
								<a href="{{ route('itdept.manage-assets.template') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">{{ __('Download template') }}</a>
							</div>
							<form action="{{ route('itdept.manage-assets.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
								@csrf
								<input type="file" name="file" accept=".csv" required />
								<div>
									<x-primary-button>{{ __('Add assets') }}</x-primary-button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

