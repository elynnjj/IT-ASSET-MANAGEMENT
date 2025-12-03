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
									class="w-3/4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
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
										class="w-3/4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
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
										class="w-3/4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
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
										class="w-3/4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
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
										class="w-3/4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
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
										class="w-3/4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
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
								class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
								style="background-color: #4BA9C2;"
								onmouseover="this.style.backgroundColor='#3a8ba5'"
								onmouseout="this.style.backgroundColor='#4BA9C2'">
								<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
								</svg>
								{{ __('Generate Report') }}
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

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
