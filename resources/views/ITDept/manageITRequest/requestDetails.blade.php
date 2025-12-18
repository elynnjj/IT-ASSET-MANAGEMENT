<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Request Details') }}
		</h2>
	</x-slot>

					{{-- Hardware Changes Confirmation Modal --}}
	<div id="hardwareChangesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: none;">
		<div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
			<div class="mt-3 text-center">
				<h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Hardware Changes Confirmation') }}</h3>
				<p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
					{{ __('This maintenance involved hardware changes. Do you want to update asset details now?') }}
				</p>
				<div class="flex justify-center gap-4">
					<button id="skipBtn" type="button" 
						class="interactive-button interactive-button-secondary"
						style="padding: 10px 16px; font-size: 11px;"
						onclick="submitMaintenance(false)">
						<span class="button-content">
							{{ __('No, Skip') }}
						</span>
					</button>
					<button id="updateBtn" type="button" 
						class="interactive-button interactive-button-primary"
						style="padding: 10px 16px; font-size: 11px;"
						onclick="submitMaintenance(true)">
						<span class="button-content">
							{{ __('Yes, Update Now') }}
						</span>
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			{{-- Breadcrumb --}}
			<div class="mb-6">
				<a href="{{ route('itdept.it-requests') }}" 
				   style="color: #4BA9C2;"
				   class="hover:opacity-80">
					← IT Requests
				</a>
				<span class="text-gray-600 dark:text-gray-400"> > </span>
				<span class="text-gray-600 dark:text-gray-400">{{ __('Request Details') }}</span>
			</div>

			{{-- Status message --}}
			@if (session('status'))
				<div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 rounded-md">
					{{ session('status') }}
				</div>
			@endif

			@if ($errors->any())
				<div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-300 rounded-md">
					<ul class="list-disc list-inside">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			{{-- Requester Details Section --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<h3 class="text-lg font-semibold mb-4">{{ __('Requester Details') }}</h3>
					<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
						<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-gray-50 dark:bg-gray-700">
							<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Name') }}</label>
							<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $itRequest->requester ? $itRequest->requester->fullName : 'N/A' }}</p>
						</div>
						<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-gray-50 dark:bg-gray-700">
							<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Department') }}</label>
							<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $itRequest->requester && $itRequest->requester->department ? $itRequest->requester->department : 'N/A' }}</p>
						</div>
						<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-gray-50 dark:bg-gray-700">
							<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('HOD') }}</label>
							<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $hod ? $hod->fullName : 'N/A' }}</p>
						</div>
					</div>
				</div>
			</div>

			{{-- Request Details Section --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<h3 class="text-lg font-semibold mb-4">{{ __('Request Details') }}</h3>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
						<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-gray-50 dark:bg-gray-700">
							<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Request Date') }}</label>
							<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ \Carbon\Carbon::parse($itRequest->requestDate)->format('d/m/Y') }}</p>
						</div>
						<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-gray-50 dark:bg-gray-700">
							<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Asset ID') }}</label>
							<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $itRequest->asset ? $itRequest->asset->assetID : 'N/A' }}</p>
						</div>
						<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-gray-50 dark:bg-gray-700">
							<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Status') }}</label>
							@php
								$statusColors = [
									'Pending IT' => 'text-yellow-600',
									'Completed' => 'text-blue-600',
								];
								$statusColor = $statusColors[$itRequest->status] ?? 'text-gray-600';
							@endphp
							<p class="text-sm font-medium {{ $statusColor }}">{{ $itRequest->status }}</p>
						</div>
						<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-gray-50 dark:bg-gray-700">
							<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Request Title') }}</label>
							<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $itRequest->title }}</p>
						</div>
					</div>
					<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-gray-50 dark:bg-gray-700">
						<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Request Description') }}</label>
						<p class="text-sm text-gray-900 dark:text-gray-100">{{ $itRequest->requestDesc }}</p>
					</div>
				</div>
			</div>

			{{-- Maintenance Details Section --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<h3 class="text-lg font-semibold mb-4">{{ __('Maintenance Details') }}</h3>

					{{-- Existing Maintenance Records --}}
					@if($itRequest->maintenances && $itRequest->maintenances->count() > 0)
						<div class="mb-6">
							<h4 class="text-md font-medium mb-3">{{ __('Previous Maintenance Records') }}</h4>
							<div class="space-y-3">
								@foreach($itRequest->maintenances as $maintenance)
									<div class="border border-gray-200 dark:border-gray-600 rounded-md p-4 bg-gray-50 dark:bg-gray-700">
										<div class="flex justify-between items-start mb-2">
											<div>
												<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Maintenance Date') }}</label>
												<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ \Carbon\Carbon::parse($maintenance->mainDate)->format('d/m/Y') }}</p>
											</div>
										</div>
										<div>
											<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Maintenance Description') }}</label>
											<p class="text-sm text-gray-900 dark:text-gray-100">{{ $maintenance->mainDesc }}</p>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					@endif

					{{-- Add Maintenance Form (only if status is Pending IT) --}}
					@if($itRequest->status === 'Pending IT')
						<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
							<h3 class="text-lg font-semibold mb-4">{{ __('Add Maintenance Details') }}</h3>
							<form id="maintenanceForm" action="{{ route('itdept.it-requests.maintenance', $itRequest->requestID) }}" method="POST">
								@csrf
								<input type="hidden" name="updateAsset" id="updateAsset" value="0">
								<div class="space-y-4">
									{{-- Maintenance Date --}}
									<div class="input-container">
										<x-input-label for="mainDate" :value="__('Maintenance Date')" class="text-[15px]" />
										<x-text-input id="mainDate" name="mainDate" type="date" 
											class="mt-1 block w-full interactive-input" 
											value="{{ old('mainDate', date('Y-m-d')) }}"
											required />
										<x-input-error :messages="$errors->get('mainDate')" class="mt-2" />
									</div>

									{{-- Maintenance Description --}}
									<div class="input-container">
										<x-input-label for="mainDesc" :value="__('Maintenance Description')" class="text-[15px]" />
										<textarea id="mainDesc" name="mainDesc" 
											rows="5"
											class="mt-1 block w-full interactive-textarea"
											placeholder="Describe the maintenance work performed..."
											required>{{ old('mainDesc') }}</textarea>
										<x-input-error :messages="$errors->get('mainDesc')" class="mt-2" />
									</div>

									{{-- Hardware Changes Checkbox --}}
									<div>
										<label class="inline-flex items-center interactive-option-label">
											<input type="checkbox" 
												name="hardwareChanges" 
												id="hardwareChangesCheckbox"
												class="me-2 interactive-checkbox">
											<span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Does this maintenance include hardware changes?') }}</span>
										</label>
									</div>
								</div>

								{{-- Submit Button --}}
								<div class="flex items-center justify-end mt-6">
									<button type="button" 
										id="submitMaintenanceBtn"
										class="interactive-button interactive-button-primary"
										style="padding: 10px 16px; font-size: 11px;">
										<span class="button-content">
											<span class="button-text">{{ __('Submit Maintenance') }}</span>
											<span class="button-spinner"></span>
										</span>
									</button>
								</div>
							</form>
						</div>
					@else
						<div class="border border-gray-200 dark:border-gray-600 rounded-md p-4 bg-gray-50 dark:bg-gray-700">
							<p class="text-sm text-gray-600 dark:text-gray-400 italic">This request has been completed. Maintenance details can no longer be added.</p>
						</div>
					@endif
				</div>
			</div>

		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const submitBtn = document.getElementById('submitMaintenanceBtn');
			const hardwareCheckbox = document.getElementById('hardwareChangesCheckbox');
			const modal = document.getElementById('hardwareChangesModal');
			const maintenanceForm = document.getElementById('maintenanceForm');

			if (submitBtn && hardwareCheckbox && modal && maintenanceForm) {
				submitBtn.addEventListener('click', function(e) {
					e.preventDefault();
					
					if (hardwareCheckbox.checked) {
						// Show modal if hardware changes checkbox is checked
						modal.style.display = 'block';
					} else {
						// Submit directly if checkbox is not checked
						document.getElementById('updateAsset').value = '0';
						submitBtn.classList.add('loading');
						submitBtn.disabled = true;
						maintenanceForm.submit();
					}
				});
			}

			// Close modal when clicking outside
			if (modal) {
				modal.addEventListener('click', function(e) {
					if (e.target === this) {
						this.style.display = 'none';
					}
				});
			}
		});

		function submitMaintenance(updateAsset) {
			const submitBtn = document.getElementById('submitMaintenanceBtn');
			document.getElementById('updateAsset').value = updateAsset ? '1' : '0';
			document.getElementById('hardwareChangesModal').style.display = 'none';
			if (submitBtn) {
				submitBtn.classList.add('loading');
				submitBtn.disabled = true;
			}
			document.getElementById('maintenanceForm').submit();
		}
	</script>

	<style>
		/* Input container with hover effects */
		.input-container {
			position: relative;
			transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
		}

		.input-container:hover {
			transform: translateY(-1px);
		}

		.input-container:has(.interactive-input:focus),
		.input-container:has(.interactive-textarea:focus) {
			transform: translateY(-2px);
		}

		/* Interactive input styling */
		.interactive-input,
		.interactive-textarea {
			width: 100%;
			padding: 8px 12px;
			border: 2px solid #9CA3AF;
			border-radius: 8px;
			font-size: 15px;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			background-color: #FFFFFF;
			position: relative;
		}

		.interactive-textarea {
			resize: vertical;
			min-height: 120px;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input,
			.interactive-textarea {
				background-color: #111827;
				border-color: #6B7280;
				color: #D1D5DB;
			}
		}

		.dark .interactive-input,
		.dark .interactive-textarea {
			background-color: #111827;
			border-color: #6B7280;
			color: #D1D5DB;
		}

		.interactive-input:hover,
		.interactive-textarea:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.15);
			transform: translateY(-1px);
			background-color: #FAFAFA;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input:hover,
			.interactive-textarea:hover {
				border-color: #4BA9C2;
				box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
				background-color: #1F2937;
			}
		}

		.dark .interactive-input:hover,
		.dark .interactive-textarea:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
			background-color: #1F2937;
		}

		.interactive-input:focus,
		.interactive-textarea:focus {
			outline: none;
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.15), 0 6px 16px rgba(75, 169, 194, 0.2);
			background-color: #FFFFFF;
			transform: translateY(-2px);
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input:focus,
			.interactive-textarea:focus {
				border-color: #4BA9C2;
				box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
				background-color: #111827;
				color: #D1D5DB;
			}
		}

		.dark .interactive-input:focus,
		.dark .interactive-textarea:focus {
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

		.interactive-button-secondary {
			background: linear-gradient(135deg, #797979 0%, #666666 100%);
			color: white;
			box-shadow: 0 4px 12px rgba(121, 121, 121, 0.3);
		}

		.interactive-button-secondary::before {
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

		.interactive-button-secondary:hover {
			background: linear-gradient(135deg, #666666 0%, #555555 100%);
			box-shadow: 0 8px 20px rgba(121, 121, 121, 0.5);
			transform: translateY(-2px) scale(1.02);
		}

		.interactive-button-secondary:active::before {
			width: 300px;
			height: 300px;
		}

		.interactive-button-secondary:active {
			background: linear-gradient(135deg, #555555 0%, #444444 100%);
			transform: translateY(0) scale(0.98);
			box-shadow: 0 2px 8px rgba(121, 121, 121, 0.3);
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
			width: 18px;
			height: 18px;
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

		@keyframes spin {
			to { transform: rotate(360deg); }
		}

		/* Interactive checkbox styling */
		.interactive-checkbox {
			width: 18px;
			height: 18px;
			border: 2px solid #9CA3AF;
			border-radius: 4px;
			cursor: pointer;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			position: relative;
			appearance: none;
			-webkit-appearance: none;
			-moz-appearance: none;
			background-color: #FFFFFF;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-checkbox {
				background-color: #111827;
				border-color: #6B7280;
			}
		}

		.dark .interactive-checkbox {
			background-color: #111827;
			border-color: #6B7280;
		}

		.interactive-checkbox:hover {
			border-color: #4BA9C2;
			box-shadow: 0 2px 8px rgba(75, 169, 194, 0.15);
			transform: scale(1.05);
		}

		@media (prefers-color-scheme: dark) {
			.interactive-checkbox:hover {
				background-color: #1F2937;
			}
		}

		.dark .interactive-checkbox:hover {
			background-color: #1F2937;
		}

		.interactive-checkbox:checked {
			background-color: #4BA9C2;
			border-color: #4BA9C2;
		}

		.interactive-checkbox:checked::after {
			content: '✓';
			position: absolute;
			left: 50%;
			top: 50%;
			transform: translate(-50%, -50%);
			color: white;
			font-size: 12px;
			font-weight: bold;
		}

		.interactive-checkbox:focus {
			outline: none;
			box-shadow: 0 0 0 3px rgba(75, 169, 194, 0.2);
		}

		/* Interactive option label hover effect */
		.interactive-option-label {
			cursor: pointer;
			transition: color 0.2s ease;
			padding: 4px 8px;
			border-radius: 4px;
		}

		.interactive-option-label:hover {
			color: #4BA9C2;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-option-label:hover {
				color: #4BA9C2;
			}
		}

		.dark .interactive-option-label:hover {
			color: #4BA9C2;
		}

		/* Dark mode support for buttons */
		.dark .interactive-button-primary {
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.4);
		}

		.dark .interactive-button-primary:hover {
			box-shadow: 0 8px 20px rgba(75, 169, 194, 0.6);
		}

		.dark .interactive-button-secondary {
			box-shadow: 0 4px 12px rgba(121, 121, 121, 0.4);
		}

		.dark .interactive-button-secondary:hover {
			box-shadow: 0 8px 20px rgba(121, 121, 121, 0.6);
		}
	</style>
</x-app-layout>
