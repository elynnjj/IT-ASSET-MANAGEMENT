<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Assets') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- View Laptops / Desktops --}}
                    <div class="flex border-b border-sky-400 mb-6">
                        <a href="{{ route('itdept.manage-assets.index', ['assetType' => 'Laptop']) }}"
                           class="flex-1 text-center py-2 font-medium 
                           {{ $assetType === 'Laptop' ? 'text-sky-600 border-b-2 border-sky-400' : 'text-gray-600' }}">
                            {{ __('Laptops') }}
                        </a>
                        <a href="{{ route('itdept.manage-assets.index', ['assetType' => 'Desktop']) }}"
                           class="flex-1 text-center py-2 font-medium 
                           {{ $assetType === 'Desktop' ? 'text-sky-600 border-b-2 border-sky-400' : 'text-gray-600' }}">
                            {{ __('Desktops') }}
                        </a>
                    </div>

                    {{-- Search / Filter / Add --}}
					<div class="mb-6">
						<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
							<form id="filterForm" method="GET" action="{{ route('itdept.manage-assets.index') }}" 
								class="flex flex-wrap items-center gap-2 flex-1"
								x-data="{ timeout: null }">
								<input type="hidden" name="assetType" value="{{ $assetType }}" />

								{{-- Search input with auto-submit --}}
								<input type="text" name="q" value="{{ $q }}" 
									placeholder="{{ __('Search asset ID, serial number or model') }}"
									class="flex-1 min-w-[200px] rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
									x-on:input="
											clearTimeout(timeout);
											timeout = setTimeout(() => $root.querySelector('#filterForm').submit(), 500);
									" />

								{{-- Upload Invoice button --}}
								<a href="{{ route('itdept.manage-assets.upload-invoice') }}" 
								class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
									{{ __('Upload Invoice') }}
								</a>

								{{-- Add Asset button inline with form --}}
								<a href="{{ route('itdept.manage-assets.create') }}" 
								class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-auto">
									{{ __('Add New Asset') }}
								</a>
							</form>
						</div>
					</div>

                    {{-- Status message --}}
                    @if (session('status'))
                        <div class="mb-4 text-green-500 font-medium">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Assets Table -->
					<div class="overflow-x-auto">
						<table class="table-auto w-full border border-gray-300 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
							<thead class="bg-gray-100 dark:bg-gray-700">
								@php($columns = [
									['key' => 'assetID', 'label' => 'Asset ID'],
									['key' => 'serialNum', 'label' => 'Serial Number'],
									['key' => 'model', 'label' => 'Model'],
									['key' => 'status', 'label' => 'Status'],
									['key' => 'purchaseDate', 'label' => 'Purchase Date'],
								])
								<tr>
									@foreach ($columns as $c)
										<th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
											@php($isActive = ($sort ?? null) === $c['key'])
											<a href="{{ request()->fullUrlWithQuery([
												'sort' => $c['key'], 
												'dir' => ($isActive && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc'
											]) }}" 
											class="inline-flex items-center gap-1">
												<span>{{ __($c['label']) }}</span>
												<span class="text-xs">
													@if ($isActive)
														{{ ($dir ?? 'asc') === 'asc' ? '▲' : '▼' }}
													@else
														▲▼
													@endif
												</span>
											</a>
										</th>
									@endforeach
									<th class="px-6 py-3"></th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
								@forelse ($assets as $asset)
									<tr>
										<td class="px-6 py-3">{{ $asset->assetID }}</td>
										<td class="px-6 py-3">{{ $asset->serialNum ?? '-' }}</td>
										<td class="px-6 py-3">{{ $asset->model ?? '-' }}</td>
										<td class="px-6 py-3">{{ $asset->status ?? '-' }}</td>
										<td class="px-6 py-3">{{ $asset->purchaseDate ? $asset->purchaseDate->format('Y-m-d') : '-' }}</td>
										<td class="px-6 py-3 text-right space-x-2">
											<a href="{{ route('itdept.manage-assets.show', $asset->assetID) }}" class="underline">{{ __('View Details') }}</a>
											<a href="{{ route('itdept.manage-assets.edit', $asset->assetID) }}" class="underline">{{ __('Edit') }}</a>
											<form action="{{ route('itdept.manage-assets.destroy', $asset->assetID) }}" method="POST" class="inline" onsubmit="return confirm('Delete this asset?');">
												@csrf
												@method('DELETE')
												<button type="submit" class="underline text-red-600">{{ __('Delete') }}</button>
											</form>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="px-6 py-4 text-center text-gray-500">
											No assets found.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $assets->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
