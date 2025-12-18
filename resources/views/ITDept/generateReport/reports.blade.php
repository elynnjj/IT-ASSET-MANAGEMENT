<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Reports') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			{{-- Main Content Card --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					{{-- Title --}}
					<div class="mb-6">
						<h1 class="text-xl font-semibold">{{ __('Generate Reports') }}</h1>
					</div>

					{{-- Generate Reports Form --}}
					<form method="POST" action="{{ route('itdept.reports.generate') }}" class="space-y-6">
						@csrf
						
						{{-- Section Header --}}
						<div class="mb-4">
							<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Select Report Details:') }}</h3>
						</div>

						{{-- Form Fields --}}
						<div class="space-y-4">
							{{-- Report Type --}}
							<div class="flex items-center">
								<label for="reportType" class="w-1/4 text-sm font-medium text-gray-700 dark:text-gray-300">
									{{ __('Report Type') }}
								</label>
								<select id="reportType" name="reportType" required
									class="w-3/4 interactive-select">
									<option value="">{{ __('Select report type') }}</option>
									<option value="asset-inventory">{{ __('Asset Inventory Report') }}</option>
									<option value="user-report">{{ __('User Report') }}</option>
								</select>
							</div>

							{{-- Asset Inventory Report Fields (hidden by default) --}}
							<div id="assetInventoryFields" style="display: none;">
								{{-- Asset Type --}}
								<div class="flex items-center">
									<label for="assetType" class="w-1/4 text-sm font-medium text-gray-700 dark:text-gray-300">
										{{ __('Asset Type') }} 
									</label>
									<select id="assetType" name="assetType" 
										class="w-3/4 interactive-select">
										<option value="">{{ __('Select asset type') }}</option>
										<option value="all">{{ __('All Asset') }}</option>
										<option value="Laptop">{{ __('Laptops') }}</option>
										<option value="Desktop">{{ __('Desktops') }}</option>
									</select>
								</div>

								{{-- Asset Status --}}
								<div class="flex items-center">
									<label for="assetStatus" class="w-1/4 text-sm font-medium text-gray-700 dark:text-gray-300">
										{{ __('Asset Status') }} 
									</label>
									<select id="assetStatus" name="assetStatus" 
										class="w-3/4 interactive-select">
										<option value="">{{ __('Select asset status') }}</option>
										<option value="all">{{ __('All Asset') }}</option>
										<option value="available">{{ __('Available') }}</option>
										<option value="disposed">{{ __('Disposed') }}</option>
									</select>
								</div>
							</div>

							{{-- User Report Fields (hidden by default) --}}
							<div id="userReportFields" style="display: none;">
								{{-- Department --}}
								<div class="flex items-center">
									<label for="department" class="w-1/4 text-sm font-medium text-gray-700 dark:text-gray-300">
										{{ __('Department') }} 
									</label>
									<select id="department" name="department" 
										class="w-3/4 interactive-select">
										<option value="">{{ __('Select department') }}</option>
										<option value="all">{{ __('All Department') }}</option>
										@php($departments = ['HR & Admin','Account','Service','Project','Supply Chain','Sales','Proposal'])
										@foreach ($departments as $dept)
											<option value="{{ $dept }}">{{ $dept }}</option>
										@endforeach
									</select>
								</div>

								{{-- User Role --}}
								<div class="flex items-center">
									<label for="userRole" class="w-1/4 text-sm font-medium text-gray-700 dark:text-gray-300">
										{{ __('User Role') }} 
									</label>
									<select id="userRole" name="userRole" 
										class="w-3/4 interactive-select">
										<option value="">{{ __('Select user role') }}</option>
										<option value="all">{{ __('All Roles') }}</option>
										<option value="HOD">{{ __('HOD') }}</option>
										<option value="Employee">{{ __('Employee') }}</option>
									</select>
								</div>

								{{-- Status --}}
								<div class="flex items-center">
									<label for="userStatus" class="w-1/4 text-sm font-medium text-gray-700 dark:text-gray-300">
										{{ __('Status') }} 
									</label>
									<select id="userStatus" name="userStatus" 
										class="w-3/4 interactive-select">
										<option value="">{{ __('Select status') }}</option>
										<option value="all">{{ __('All Status') }}</option>
										<option value="active">{{ __('Active') }}</option>
										<option value="inactive">{{ __('Inactive') }}</option>
									</select>
								</div>
							</div>
						</div>

						{{-- Generate Report Button --}}
						<div class="flex justify-end mt-6">
							<button type="submit" 
								class="interactive-button interactive-button-primary"
								style="padding: 10px 16px; font-size: 11px;">
								<span class="button-content">
									<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
									</svg>
									<span class="button-text">{{ __('Generate Report') }}</span>
									<span class="button-spinner"></span>
								</span>
							</button>
						</div>
					</form>
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

		.interactive-select:disabled {
			background-color: #F3F4F6;
			border-color: #D1D5DB;
			color: #6B7280;
			cursor: not-allowed;
			opacity: 0.7;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-select:disabled {
				background-color: #1F2937;
				border-color: #374151;
				color: #9CA3AF;
			}
		}

		.dark .interactive-select:disabled {
			background-color: #1F2937;
			border-color: #374151;
			color: #9CA3AF;
		}

		.interactive-select:disabled:hover {
			transform: none;
			box-shadow: none;
			border-color: #D1D5DB;
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

		/* Dark mode support for buttons */
		.dark .interactive-button-primary {
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.4);
		}
	</style>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const reportTypeSelect = document.getElementById('reportType');
			const assetInventoryFields = document.getElementById('assetInventoryFields');
			const userReportFields = document.getElementById('userReportFields');

			reportTypeSelect.addEventListener('change', function() {
				const selectedType = this.value;

				// Hide all conditional fields first
				assetInventoryFields.style.display = 'none';
				userReportFields.style.display = 'none';

				// Clear required attributes
				document.getElementById('assetType').removeAttribute('required');
				document.getElementById('assetStatus').removeAttribute('required');
				document.getElementById('department').removeAttribute('required');
				document.getElementById('userRole').removeAttribute('required');
				document.getElementById('userStatus').removeAttribute('required');

				// Clear values
				document.getElementById('assetType').value = '';
				document.getElementById('assetStatus').value = '';
				document.getElementById('department').value = '';
				document.getElementById('userRole').value = '';
				document.getElementById('userStatus').value = '';

				// Show relevant fields based on selection
				if (selectedType === 'asset-inventory') {
					assetInventoryFields.style.display = 'block';
					document.getElementById('assetType').setAttribute('required', 'required');
					document.getElementById('assetStatus').setAttribute('required', 'required');
				} else if (selectedType === 'user-report') {
					userReportFields.style.display = 'block';
					document.getElementById('department').setAttribute('required', 'required');
					document.getElementById('userRole').setAttribute('required', 'required');
					document.getElementById('userStatus').setAttribute('required', 'required');
				}
			});
		});
	</script>
</x-app-layout>
