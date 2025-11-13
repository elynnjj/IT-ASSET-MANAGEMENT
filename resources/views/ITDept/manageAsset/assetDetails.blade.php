<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Asset Details') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<div class="mb-6">
						<a href="{{ route('itdept.manage-assets.index', ['assetType' => $asset->assetType]) }}" class="text-sky-600 hover:text-sky-800 dark:text-sky-400 dark:hover:text-sky-300">
							← Back to Asset List
						</a>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						{{-- Basic Information --}}
						<div class="space-y-4">
							<h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-700 pb-2">
								{{ __('Basic Information') }}
							</h3>
							
							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Asset ID') }}</label>
								<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $asset->assetID }}</p>
							</div>

							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Asset Type') }}</label>
								<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $asset->assetType }}</p>
							</div>

							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Serial Number') }}</label>
								<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $asset->serialNum ?? '-' }}</p>
							</div>

							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Model') }}</label>
								<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $asset->model ?? '-' }}</p>
							</div>

							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
								<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $asset->status ?? '-' }}</p>
							</div>

							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Purchase Date') }}</label>
								<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $asset->purchaseDate ? $asset->purchaseDate->format('Y-m-d') : '-' }}</p>
							</div>
						</div>

						{{-- Technical Specifications --}}
						<div class="space-y-4">
							<h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-700 pb-2">
								{{ __('Technical Specifications') }}
							</h3>

							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('RAM') }}</label>
								<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $asset->ram ?? '-' }}</p>
							</div>

							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Storage') }}</label>
								<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $asset->storage ?? '-' }}</p>
							</div>

							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('OS Version') }}</label>
								<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $asset->osVer ?? '-' }}</p>
							</div>

							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Processor') }}</label>
								<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $asset->processor ?? '-' }}</p>
							</div>

							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Installed Software') }}</label>
								<p class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $asset->installedSoftware ?? '-' }}</p>
							</div>

							@if($asset->invoice)
							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Invoice') }}</label>
								<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $asset->invoice->invoiceID }} - {{ $asset->invoice->fileName }}</p>
							</div>
							@endif
						</div>
					</div>

					{{-- Action Buttons --}}
					<div class="mt-8 flex items-center justify-end space-x-4">
						<a href="{{ route('itdept.manage-assets.edit', $asset->assetID) }}" 
						   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
							{{ __('Edit Asset') }}
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

