<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Repairs & Maintenance') }}
		</h2>
	</x-slot>

	<div class="py-12" x-data>
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Search, Filter, and New Maintenance Button in one row --}}
					<div class="mb-6">
						<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
							<form id="filterForm" method="GET" action="{{ route('itdept.repairs-maintenance') }}" 
								class="flex flex-wrap items-center gap-2 flex-1"
								x-data="{ timeout: null }">
								
								{{-- Search by Asset ID --}}
								<input type="text" name="q" value="{{ request('q') }}" 
									placeholder="{{ __('Search Asset ID') }}"
									class="flex-1 min-w-[200px] rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
									x-on:input="
										clearTimeout(timeout);
										timeout = setTimeout(() => $root.querySelector('#filterForm').submit(), 500);
									" />

								{{-- Filter by Asset Type --}}
								<select name="assetType" 
										class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
										onchange="this.form.submit()">
									<option value="">{{ __('All Asset Types') }}</option>
									@foreach($assetTypes as $type)
										<option value="{{ $type }}" @selected(request('assetType') === $type)>{{ $type }}</option>
									@endforeach
								</select>

								{{-- Clear filters button --}}
								@if(request('q') || request('assetType'))
									<a href="{{ route('itdept.repairs-maintenance') }}" 
										class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
										{{ __('Clear') }}
									</a>
								@endif
							</form>

							{{-- New Maintenance Button --}}
							<a href="{{ route('itdept.new-maintenance') }}" 
								class="inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
								style="background-color: #4BA9C2;"
								onmouseover="this.style.backgroundColor='#3a8ba5'"
								onmouseout="this.style.backgroundColor='#4BA9C2'">
								<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
								</svg>
								{{ __('New Maintenance') }}
							</a>
						</div>
					</div>

					{{-- Status message --}}
					@if (session('status'))
						<div class="mb-4 text-green-500 font-medium">
							{{ session('status') }}
						</div>
					@endif

					@if ($errors->any())
						<div class="mb-4 text-red-500 font-medium">
							<ul class="list-disc list-inside">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<!-- Maintenance Table -->
					<div class="overflow-x-auto">
						<table class="table-auto w-full border border-gray-300 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
							<thead class="bg-gray-100 dark:bg-gray-700">
								<tr>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Date') }}</th>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Asset ID') }}</th>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Maintenance Details') }}</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
								@forelse ($maintenances as $maintenance)
									<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-800' }}">
										<td class="px-8 py-4">{{ \Carbon\Carbon::parse($maintenance->mainDate)->format('d/m/y') }}</td>
										<td class="px-8 py-4">{{ $maintenance->asset ? $maintenance->asset->assetID : 'N/A' }}</td>
										<td class="px-8 py-4">{{ $maintenance->mainDesc }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="3" class="px-8 py-6 text-center text-gray-500">
											No maintenance records found.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					{{-- Pagination --}}
					<div class="mt-4">
						{{ $maintenances->links() }}
					</div>

				</div>
			</div>
		</div>
	</div>
</x-app-layout>
