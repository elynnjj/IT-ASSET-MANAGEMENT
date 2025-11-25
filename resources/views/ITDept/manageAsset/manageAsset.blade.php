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
                    <div class="flex border-b mb-6" style="border-color: #4BA9C2;">
                        <a href="{{ route('itdept.manage-assets.index', ['assetType' => 'Laptop']) }}"
                           class="flex-1 text-center py-2 font-medium 
                           {{ $assetType === 'Laptop' ? 'border-b-2' : '' }}"
                           style="{{ $assetType === 'Laptop' ? 'color: #4BA9C2; border-bottom-color: #4BA9C2;' : 'color: #6B7280;' }}">
                            {{ __('Laptops') }}
                        </a>
                        <a href="{{ route('itdept.manage-assets.index', ['assetType' => 'Desktop']) }}"
                           class="flex-1 text-center py-2 font-medium 
                           {{ $assetType === 'Desktop' ? 'border-b-2' : '' }}"
                           style="{{ $assetType === 'Desktop' ? 'color: #4BA9C2; border-bottom-color: #4BA9C2;' : 'color: #6B7280;' }}">
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
								class="inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
								style="background-color: #4BA9C2;"
								onmouseover="this.style.backgroundColor='#3a8ba5'"
								onmouseout="this.style.backgroundColor='#4BA9C2'">
									<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
									</svg>
									{{ __('Upload Invoice') }}
								</a>

								{{-- Add Asset button inline with form --}}
								<a href="{{ route('itdept.manage-assets.create') }}" 
								class="inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90 ml-auto"
								style="background-color: #4BA9C2;"
								onmouseover="this.style.backgroundColor='#3a8ba5'"
								onmouseout="this.style.backgroundColor='#4BA9C2'">
									<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
									</svg>
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
								])
								<tr>
									@foreach ($columns as $c)
										<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
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
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Current User') }}</th>
									@php($statusActive = ($sort ?? null) === 'status')
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
										<a href="{{ request()->fullUrlWithQuery([
											'sort' => 'status', 
											'dir' => ($statusActive && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc'
										]) }}" 
										class="inline-flex items-center gap-1">
											<span>{{ __('Status') }}</span>
											<span class="text-xs">
												@if ($statusActive)
													{{ ($dir ?? 'asc') === 'asc' ? '▲' : '▼' }}
												@else
													▲▼
												@endif
											</span>
										</a>
									</th>
									<th class="px-4 py-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 w-auto">{{ __('Action') }}</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
								@forelse ($assets as $asset)
									@php($currentAssignment = $asset->currentAssignment())
									<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-800' }}">
										<td class="px-8 py-4">{{ $asset->assetID }}</td>
										<td class="px-8 py-4">{{ $asset->serialNum ?? '-' }}</td>
										<td class="px-8 py-4">{{ $asset->model ?? '-' }}</td>
										<td class="px-8 py-4">
											@if($currentAssignment)
												{{ $currentAssignment->user->fullName }}
											@else
												With IT
											@endif
										</td>
										<td class="px-8 py-4">
											<span class="text-sm font-medium {{ $asset->status === 'Available' ? 'text-green-600' : 'text-red-600' }}">
												{{ $asset->status ?? 'Available' }}
											</span>
										</td>
										<td class="px-4 py-4">
											<div class="flex items-center justify-center space-x-2">
												<a href="{{ route('itdept.manage-assets.show', $asset->assetID) }}" 
												   class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold uppercase tracking-widest rounded-md border transition"
												   style="border-color: #4BA9C2; color: #4BA9C2; background-color: white;"
												   onmouseover="this.style.backgroundColor='#f0f9ff'"
												   onmouseout="this.style.backgroundColor='white'">
													{{ __('View Details') }}
												</a>
												<a href="{{ route('itdept.manage-assets.edit', $asset->assetID) }}" 
												   class="inline-flex items-center justify-center px-4 py-2 rounded-md border transition"
												   style="border-color: #4BA9C2; color: #4BA9C2; background-color: white;"
												   onmouseover="this.style.backgroundColor='#f0f9ff'"
												   onmouseout="this.style.backgroundColor='white'"
												   title="{{ __('Edit') }}">
													<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
													</svg>
												</a>
											</div>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="px-8 py-6 text-center text-gray-500">
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
