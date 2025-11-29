<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('IT Requests') }}
		</h2>
	</x-slot>

	<div class="py-12" x-data>
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Filters and Search --}}
					<div class="mb-6">
						<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
							<form id="filterForm" method="GET" action="{{ route('itdept.it-requests') }}" 
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

								{{-- Filter by Status --}}
								<select name="status" 
										class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
										onchange="this.form.submit()">
									<option value="">{{ __('All Status') }}</option>
									<option value="Pending IT" @selected(request('status') === 'Pending IT')>{{ __('Pending IT') }}</option>
									<option value="Completed" @selected(request('status') === 'Completed')>{{ __('Completed') }}</option>
								</select>

								{{-- Clear filters button --}}
								@if(request('q') || request('assetType') || request('status'))
									<a href="{{ route('itdept.it-requests') }}" 
										class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
										{{ __('Clear') }}
									</a>
								@endif
							</form>
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

					<!-- Requests Table -->
					<div class="overflow-x-auto">
						<table class="table-auto w-full border border-gray-300 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
							<thead class="bg-gray-100 dark:bg-gray-700">
								<tr>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Request Date') }}</th>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Asset ID') }}</th>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Requester Name') }}</th>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Department') }}</th>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Status') }}</th>
									<th class="px-4 py-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 w-auto">{{ __('Action') }}</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
								@forelse ($requests as $request)
									<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-800' }}">
										<td class="px-8 py-4">{{ \Carbon\Carbon::parse($request->requestDate)->format('d/m/y') }}</td>
										<td class="px-8 py-4">{{ $request->asset ? $request->asset->assetID : 'N/A' }}</td>
										<td class="px-8 py-4">{{ $request->requester ? $request->requester->fullName : 'N/A' }}</td>
										<td class="px-8 py-4">{{ $request->requester && $request->requester->department ? $request->requester->department : 'N/A' }}</td>
										<td class="px-8 py-4">
											@php
												$statusColors = [
													'Pending IT' => 'text-yellow-600',
													'Completed' => 'text-blue-600',
												];
												$statusColor = $statusColors[$request->status] ?? 'text-gray-600';
											@endphp
											<span class="text-sm font-medium {{ $statusColor }}">
												{{ $request->status }}
											</span>
										</td>
										<td class="px-4 py-4">
											<div class="flex items-center justify-center space-x-2">
												<a href="{{ route('itdept.it-requests.show', $request->requestID) }}" 
													class="inline-flex items-center justify-center px-4 py-2 rounded-md border transition"
													style="border-color: #4BA9C2; color: #4BA9C2; background-color: white;"
													onmouseover="this.style.backgroundColor='#f0f9ff'"
													onmouseout="this.style.backgroundColor='white'"
													title="{{ __('View Details') }}">
													{{ __('View Details') }}
												</a>
											</div>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="px-8 py-6 text-center text-gray-500">
											No requests found.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					{{-- Pagination --}}
					<div class="mt-4">
						{{ $requests->links() }}
					</div>

				</div>
			</div>
		</div>
	</div>
</x-app-layout>
