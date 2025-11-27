<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Asset Disposal') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			{{-- Main Content Card --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					{{-- Tabs: Pending Disposal / Disposed Asset --}}
					<div class="flex border-b mb-6" style="border-color: #4BA9C2;">
						<a href="{{ route('itdept.asset-disposal', ['tab' => 'pending']) }}"
						   class="flex-1 text-center py-2 font-medium 
						   {{ (request('tab', 'pending') === 'pending') ? 'border-b-2' : '' }}"
						   style="{{ (request('tab', 'pending') === 'pending') ? 'color: #4BA9C2; border-bottom-color: #4BA9C2;' : 'color: #6B7280;' }}">
							{{ __('Pending Disposal') }}
						</a>
						<a href="{{ route('itdept.asset-disposal', ['tab' => 'disposed']) }}"
						   class="flex-1 text-center py-2 font-medium 
						   {{ (request('tab') === 'disposed') ? 'border-b-2' : '' }}"
						   style="{{ (request('tab') === 'disposed') ? 'color: #4BA9C2; border-bottom-color: #4BA9C2;' : 'color: #6B7280;' }}">
							{{ __('Disposed Asset') }}
						</a>
					</div>

					{{-- Search, Filter, and Dispose Section --}}
					<div class="mb-6" x-data="{ 
						selectedAssets: [],
						toggleAll(event) {
							const checkboxes = document.querySelectorAll('input[type=checkbox][name=selectedAsset]');
							checkboxes.forEach(cb => {
								cb.checked = event.target.checked;
								if(event.target.checked) {
									if(!this.selectedAssets.includes(cb.value)) {
										this.selectedAssets.push(cb.value);
									}
								} else {
									this.selectedAssets = this.selectedAssets.filter(id => id !== cb.value);
								}
							});
						}
					}" 
					x-on:toggle-asset.window="
						const index = selectedAssets.indexOf($event.detail);
						if(index > -1) {
							selectedAssets.splice(index, 1);
						} else {
							selectedAssets.push($event.detail);
						}
					">
						<form method="GET" action="{{ route('itdept.asset-disposal') }}" 
							class="flex flex-wrap items-center gap-4"
							id="filterForm"
							x-data="{ timeout: null }">
							<input type="hidden" name="tab" value="{{ request('tab', 'pending') }}" />

							{{-- Search Bar --}}
							<div class="flex-1 min-w-[250px] relative">
								<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
									<svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
									</svg>
								</div>
								<input type="text" 
									name="q" 
									value="{{ request('q') }}" 
									placeholder="{{ __('Search Serial Number, Asset ID or Model') }}"
									class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
									x-on:input="
										clearTimeout(timeout);
										timeout = setTimeout(() => $root.querySelector('#filterForm').submit(), 500);
									">
							</div>

							{{-- Filter Asset Type Dropdown --}}
							<select name="assetType" 
								x-on:change="$root.querySelector('#filterForm').submit()"
								class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
								<option value="">{{ __('Filter Asset Type') }}</option>
								<option value="Laptop" {{ request('assetType') === 'Laptop' ? 'selected' : '' }}>{{ __('Laptop') }}</option>
								<option value="Desktop" {{ request('assetType') === 'Desktop' ? 'selected' : '' }}>{{ __('Desktop') }}</option>
							</select>

							{{-- Dispose Button --}}
							@if(request('tab', 'pending') === 'pending')
							<button type="button" 
								@click="if(selectedAssets.length > 0) { if(confirm('Are you sure you want to dispose of ' + selectedAssets.length + ' asset(s)?')) { document.getElementById('disposeForm').submit(); } } else { alert('Please select at least one asset to dispose.'); }"
								class="inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
								style="background-color: #4BA9C2;"
								onmouseover="this.style.backgroundColor='#3a8ba5'"
								onmouseout="this.style.backgroundColor='#4BA9C2'">
								<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
								</svg>
								{{ __('Dispose') }}
							</button>
							@endif
						</form>

						{{-- Hidden form for disposal --}}
						<form id="disposeForm" method="POST" action="#" style="display: none;">
							@csrf
							@method('POST')
							<input type="hidden" name="selectedAssets" :value="JSON.stringify(selectedAssets)">
						</form>
					</div>

					{{-- Assets Table --}}
					<div class="overflow-x-auto">
						<table class="table-auto w-full border border-gray-300 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
							<thead class="bg-gray-100 dark:bg-gray-700">
								<tr>
									@if(request('tab', 'pending') === 'pending')
									<th class="px-4 py-4 text-center">
										<input type="checkbox" 
											x-on:change="toggleAll($event)"
											class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
									</th>
									@endif
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Asset ID') }}</th>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Serial Number') }}</th>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Model') }}</th>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Processor') }}</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
								@php
									// Sample data - replace with actual data from controller
									$assets = collect([
										['assetID' => 'LAPTOP001', 'serialNum' => 'NMCVBN2001', 'model' => 'DELL LATITUDE 3450', 'processor' => '13th Gen Intel Core i5-1335U'],
										['assetID' => 'LAPTOP002', 'serialNum' => 'NVCVNN2093', 'model' => 'DELL LATITUDE 3450', 'processor' => '13th Gen Intel Core i5-1335U'],
										['assetID' => 'LAPTOP003', 'serialNum' => 'NMCVBN2001', 'model' => 'DELL LATITUDE 3450', 'processor' => '13th Gen Intel Core i5-1335U'],
									]);
								@endphp
								@forelse ($assets as $asset)
									<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-800' }}">
										@if(request('tab', 'pending') === 'pending')
										<td class="px-4 py-4 text-center">
											<input type="checkbox" 
												name="selectedAsset"
												value="{{ $asset['assetID'] }}"
												x-on:change="$dispatch('toggle-asset', { detail: '{{ $asset['assetID'] }}' })"
												class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
										</td>
										@endif
										<td class="px-8 py-4">{{ $asset['assetID'] }}</td>
										<td class="px-8 py-4">{{ $asset['serialNum'] ?? '-' }}</td>
										<td class="px-8 py-4">{{ $asset['model'] ?? '-' }}</td>
										<td class="px-8 py-4">{{ $asset['processor'] ?? '-' }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="{{ request('tab', 'pending') === 'pending' ? '5' : '4' }}" class="px-8 py-6 text-center text-gray-500">
											{{ __('No assets found.') }}
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>


				</div>
			</div>
		</div>
	</div>
</x-app-layout>

