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
						class="interactive-button interactive-button-primary">
							<span class="button-content">
								<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
								</svg>
								{{ __('New Request') }}
							</span>
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
													<form action="{{ route('employee.it-requests.destroy', $request->requestID) }}" method="POST" class="inline delete-form">
														@csrf
														@method('DELETE')
														<button type="submit" 
															class="interactive-button interactive-button-delete"
															title="{{ __('Delete') }}">
															<span class="button-content">
																<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
																	<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
																</svg>
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

	<style>
		/* Interactive button styling */
		.interactive-button {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			padding: 14px 28px;
			font-weight: 600;
			font-size: 13px;
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

		.interactive-button-primary {
			background: linear-gradient(135deg, #4BA9C2 0%, #3a8ba5 100%);
			color: white;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.3);
		}

		.interactive-button-primary::before {
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

		.interactive-button-primary:hover {
			background: linear-gradient(135deg, #3a8ba5 0%, #2d6b82 100%);
			box-shadow: 0 8px 20px rgba(75, 169, 194, 0.5);
			transform: translateY(-2px) scale(1.02);
		}

		.interactive-button-primary:active::before {
			width: 300px;
			height: 300px;
		}

		.interactive-button-primary:active {
			background: linear-gradient(135deg, #2d6b82 0%, #1f5a6f 100%);
			transform: translateY(0) scale(0.98);
			box-shadow: 0 2px 8px rgba(75, 169, 194, 0.3);
		}

		.interactive-button-delete {
			background: linear-gradient(135deg, #B40814 0%, #A10712 100%);
			color: white;
			box-shadow: 0 4px 12px rgba(180, 8, 20, 0.3);
			padding: 10px 16px;
		}

		.interactive-button-delete::before {
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

		.interactive-button-delete:hover {
			background: linear-gradient(135deg, #A10712 0%, #990610 100%);
			box-shadow: 0 8px 20px rgba(180, 8, 20, 0.5);
			transform: translateY(-2px) scale(1.02);
		}

		.interactive-button-delete:active::before {
			width: 300px;
			height: 300px;
		}

		.interactive-button-delete:active {
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
		.dark .interactive-button-primary {
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.4);
		}

		.dark .interactive-button-primary:hover {
			box-shadow: 0 8px 20px rgba(75, 169, 194, 0.6);
		}

		.dark .interactive-button-delete {
			box-shadow: 0 4px 12px rgba(180, 8, 20, 0.4);
		}

		.dark .interactive-button-delete:hover {
			box-shadow: 0 8px 20px rgba(180, 8, 20, 0.6);
		}
	</style>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Handle delete button confirmation and loading state
			const deleteForms = document.querySelectorAll('.delete-form');
			deleteForms.forEach(form => {
				const submitButton = form.querySelector('button[type="submit"]');
				if (submitButton) {
					form.addEventListener('submit', function(e) {
						// Show confirmation dialog
						if (!confirm('Delete this request?')) {
							// User cancelled - prevent form submission
							e.preventDefault();
							return false;
						}
						// User confirmed - add loading state
						submitButton.classList.add('loading');
						submitButton.disabled = true;
					});
				}
			});
		});
	</script>
</x-app-layout>
