<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Approval Request') }}
		</h2>
	</x-slot>

	<div class="py-12" x-data>
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Filter / Status Selection --}}
					<div class="mb-6">
						<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
							<form id="filterForm" method="GET" action="{{ route('hod.approval-request') }}" 
								class="flex flex-wrap items-center gap-2 flex-1">
								
								{{-- Status dropdown --}}
								<select name="status" 
										class="interactive-select"
										style="padding: 8px 32px 8px 12px; font-size: 13px;"
										onchange="this.form.submit()">
									<option value="">{{ __('All Status') }}</option>
									<option value="Pending" @selected(request('status') === 'Pending')>{{ __('Pending') }}</option>
									<option value="Rejected" @selected(request('status') === 'Rejected')>{{ __('Rejected') }}</option>
									<option value="Pending IT" @selected(request('status') === 'Pending IT')>{{ __('Pending IT') }}</option>
									<option value="Completed" @selected(request('status') === 'Completed')>{{ __('Completed') }}</option>
								</select>
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
									<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200" style="width: 10%;">{{ __('Request Date') }}</th>
									<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200" style="width: 12%;">{{ __('Requester Name') }}</th>
									<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200" style="width: 10%;">{{ __('Asset ID') }}</th>
									<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200" style="width: 15%;">{{ __('Request Title') }}</th>
									<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200" style="width: 30%;">{{ __('Request Description') }}</th>
									<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200" style="width: 10%;">{{ __('Status') }}</th>
									<th class="px-3 py-2 text-center text-sm font-semibold text-gray-700 dark:text-gray-200" style="width: 13%;">{{ __('Action') }}</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
								@forelse ($requests as $request)
									<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-800' }}">
										<td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($request->requestDate)->format('d/m/y') }}</td>
										<td class="px-4 py-2 text-sm">{{ $request->requester->fullName ?? 'N/A' }}</td>
										<td class="px-4 py-2 text-sm">{{ $request->asset ? $request->asset->assetID : 'N/A' }}</td>
										<td class="px-4 py-2 text-sm">{{ $request->title }}</td>
										<td class="px-4 py-2 text-sm">{{ $request->requestDesc }}</td>
										<td class="px-4 py-2">
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
										<td class="px-3 py-2">
											<div class="flex items-center justify-center gap-2">
												@if($request->status === 'Pending')
													<form action="{{ route('hod.it-requests.approve', $request->requestID) }}" method="POST" class="inline approval-form">
														@csrf
														<button type="submit" 
															class="interactive-button interactive-button-approve"
															style="padding: 6px 12px; font-size: 11px;"
															title="{{ __('Approve') }}">
															<span class="button-content">
																<span class="button-text">{{ __('Approve') }}</span>
																<span class="button-spinner"></span>
															</span>
														</button>
													</form>
													<form action="{{ route('hod.it-requests.reject', $request->requestID) }}" method="POST" class="inline rejection-form">
														@csrf
														<button type="submit" 
															class="interactive-button interactive-button-reject"
															style="padding: 6px 12px; font-size: 11px;"
															title="{{ __('Reject') }}">
															<span class="button-content">
																<span class="button-text">{{ __('Reject') }}</span>
																<span class="button-spinner"></span>
															</span>
														</button>
													</form>
												@endif
											</div>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
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

	<style>
		/* Interactive select styling */
		.interactive-select {
			width: 100%;
			padding: 8px 32px 8px 12px;
			border: 2px solid #9CA3AF;
			border-radius: 8px;
			font-size: 15px;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			background-color: #FFFFFF;
			position: relative;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-select {
				background-color: #111827;
				border-color: #6B7280;
				color: #D1D5DB;
			}
		}

		.dark .interactive-select {
			background-color: #111827;
			border-color: #6B7280;
			color: #D1D5DB;
		}

		.interactive-select:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.15);
			transform: translateY(-1px);
			background-color: #FAFAFA;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-select:hover {
				border-color: #4BA9C2;
				box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
				background-color: #1F2937;
			}
		}

		.dark .interactive-select:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
			background-color: #1F2937;
		}

		.interactive-select:focus {
			outline: none;
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.15), 0 6px 16px rgba(75, 169, 194, 0.2);
			background-color: #FFFFFF;
			transform: translateY(-2px);
		}

		@media (prefers-color-scheme: dark) {
			.interactive-select:focus {
				border-color: #4BA9C2;
				box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
				background-color: #111827;
				color: #D1D5DB;
			}
		}

		.dark .interactive-select:focus {
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
			background-color: #111827;
			color: #D1D5DB;
		}

		/* Interactive button styling */
		.interactive-button {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			border: none;
			border-radius: 8px;
			cursor: pointer;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			position: relative;
			overflow: hidden;
			text-decoration: none;
		}

		.interactive-button-approve {
			background: linear-gradient(135deg, #1D9F26 0%, #1A8F22 100%);
			color: white;
			box-shadow: 0 4px 12px rgba(29, 159, 38, 0.3);
		}

		.interactive-button-approve::before {
			content: '';
			position: absolute;
			top: 50%;
			left: 50%;
			width: 0;
			height: 0;
			border-radius: 50%;
			background: rgba(255, 255, 255, 0.3);
			transform: translate(-50%, -50%);
			transition: width 0.6s, height 0.6s;
		}

		.interactive-button-approve:hover {
			background: linear-gradient(135deg, #1A8F22 0%, #17891F 100%);
			box-shadow: 0 8px 20px rgba(29, 159, 38, 0.5);
			transform: translateY(-2px) scale(1.02);
		}

		.interactive-button-approve:active::before {
			width: 300px;
			height: 300px;
		}

		.interactive-button-approve:active {
			background: linear-gradient(135deg, #17891F 0%, #15721A 100%);
			transform: translateY(0) scale(0.98);
			box-shadow: 0 2px 8px rgba(29, 159, 38, 0.3);
		}

		.interactive-button-reject {
			background: linear-gradient(135deg, #B40814 0%, #A10712 100%);
			color: white;
			box-shadow: 0 4px 12px rgba(180, 8, 20, 0.3);
		}

		.interactive-button-reject::before {
			content: '';
			position: absolute;
			top: 50%;
			left: 50%;
			width: 0;
			height: 0;
			border-radius: 50%;
			background: rgba(255, 255, 255, 0.3);
			transform: translate(-50%, -50%);
			transition: width 0.6s, height 0.6s;
		}

		.interactive-button-reject:hover {
			background: linear-gradient(135deg, #A10712 0%, #990610 100%);
			box-shadow: 0 8px 20px rgba(180, 8, 20, 0.5);
			transform: translateY(-2px) scale(1.02);
		}

		.interactive-button-reject:active::before {
			width: 300px;
			height: 300px;
		}

		.interactive-button-reject:active {
			background: linear-gradient(135deg, #990610 0%, #86050E 100%);
			transform: translateY(0) scale(0.98);
			box-shadow: 0 2px 8px rgba(180, 8, 20, 0.3);
		}

		.button-content {
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
			position: relative;
			z-index: 1;
		}

		.button-spinner {
			display: none;
			width: 16px;
			height: 16px;
			border: 2px solid rgba(255, 255, 255, 0.3);
			border-top-color: white;
			border-radius: 50%;
			animation: spin 0.8s linear infinite;
		}

		.interactive-button.loading .button-spinner {
			display: block;
		}

		.interactive-button.loading .button-text {
			opacity: 0.7;
		}

		.interactive-button:disabled {
			opacity: 0.7;
			cursor: not-allowed;
			transform: none;
		}

		.interactive-button:disabled:hover {
			transform: none;
		}

		@keyframes spin {
			to { transform: rotate(360deg); }
		}

		/* Dark mode support for buttons */
		.dark .interactive-button-approve {
			box-shadow: 0 4px 12px rgba(29, 159, 38, 0.4);
		}

		.dark .interactive-button-approve:hover {
			box-shadow: 0 8px 20px rgba(29, 159, 38, 0.6);
		}

		.dark .interactive-button-reject {
			box-shadow: 0 4px 12px rgba(180, 8, 20, 0.4);
		}

		.dark .interactive-button-reject:hover {
			box-shadow: 0 8px 20px rgba(180, 8, 20, 0.6);
		}
	</style>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Add loading state to approve buttons on form submission
			const approveForms = document.querySelectorAll('.approval-form');
			approveForms.forEach(form => {
				const submitButton = form.querySelector('button[type="submit"]');
				if (submitButton) {
					form.addEventListener('submit', function() {
						submitButton.classList.add('loading');
						submitButton.disabled = true;
					});
				}
			});

			// Add confirmation and loading state to reject buttons on form submission
			const rejectForms = document.querySelectorAll('.rejection-form');
			rejectForms.forEach(form => {
				const submitButton = form.querySelector('button[type="submit"]');
				if (submitButton) {
					form.addEventListener('submit', async function(e) {
						e.preventDefault();
						
						const confirmed = await window.showConfirmation(
							'Are you sure you want to reject this request?',
							'Reject Request'
						);
						
						if (confirmed) {
							submitButton.classList.add('loading');
							submitButton.disabled = true;
							form.submit();
						}
					});
				}
			});
		});
	</script>
</x-app-layout>
