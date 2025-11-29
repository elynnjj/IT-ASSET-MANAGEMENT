<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('My Requests') }}
		</h2>
	</x-slot>

	<div class="py-12" x-data>
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Add Request Button --}}
					<div class="mb-6 flex justify-end">
						<a href="{{ route('employee.submit-it-request') }}" 
						class="inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
						style="background-color: #4BA9C2;"
						onmouseover="this.style.backgroundColor='#3a8ba5'"
						onmouseout="this.style.backgroundColor='#4BA9C2'">
							<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
							</svg>
							{{ __('New Request') }}
						</a>
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
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 w-64">{{ __('Request Title') }}</th>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Request Description') }}</th>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Status') }}</th>
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 w-64">{{ __('HOD Name') }}</th>
									<th class="px-4 py-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 w-auto">{{ __('Action') }}</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
								@forelse ($requests as $request)
									<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-800' }}">
										<td class="px-8 py-4">{{ \Carbon\Carbon::parse($request->requestDate)->format('d/m/y') }}</td>
										<td class="px-8 py-4">{{ $request->title }}</td>
										<td class="px-8 py-4">{{ $request->requestDesc }}</td>
										<td class="px-8 py-4">
											@php
												$statusColors = [
													'Pending' => 'text-yellow-600',
													'Approved' => 'text-green-600',
													'Rejected' => 'text-red-600',
													'Pending IT' => 'text-yellow-600',
													'Completed' => 'text-blue-600',
												];
												$statusColor = $statusColors[$request->status] ?? 'text-gray-600';
											@endphp
											<span class="text-sm font-medium {{ $statusColor }}">
												{{ $request->status }}
											</span>
										</td>
										<td class="px-8 py-4">{{ $request->approver ? $request->approver->fullName : 'N/A' }}</td>
										<td class="px-4 py-4">
											<div class="flex items-center justify-center space-x-2">
												@if($request->status === 'Pending')
													<form action="{{ route('employee.it-requests.destroy', $request->requestID) }}" method="POST" class="inline" onsubmit="return confirm('Delete this request?');">
														@csrf
														@method('DELETE')
														<button type="submit" 
															class="inline-flex items-center justify-center px-4 py-2 rounded-md border transition"
															style="border-color: #4BA9C2; color: #4BA9C2; background-color: white;"
															onmouseover="this.style.backgroundColor='#f0f9ff'"
															onmouseout="this.style.backgroundColor='white'"
															title="{{ __('Delete') }}">
															<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
																<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
															</svg>
														</button>
													</form>
												@endif
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
