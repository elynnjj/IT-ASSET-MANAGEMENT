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
					<form method="POST" action="#" class="space-y-6">
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
								<select id="reportType" name="reportType" 
									class="w-3/4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
									<option value="">{{ __('Select report type') }}</option>
									<option value="asset-usage">{{ __('Asset Usage Report') }}</option>
									<option value="maintenance">{{ __('Maintenance Schedule Report') }}</option>
									<option value="user-activity">{{ __('User Activity Report') }}</option>
									<option value="department-performance">{{ __('Department Performance Report') }}</option>
									<option value="asset-inventory">{{ __('Asset Inventory Report') }}</option>
									<option value="checkout-history">{{ __('Checkout History Report') }}</option>
								</select>
							</div>

							{{-- Month --}}
							<div class="flex items-center">
								<label for="month" class="w-1/4 text-sm font-medium text-gray-700 dark:text-gray-300">
									{{ __('Month') }}
								</label>
								<select id="month" name="month" 
									class="w-3/4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
									<option value="">{{ __('Select month') }}</option>
									@php
										$months = [
											'01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
											'05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
											'09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
										];
									@endphp
									@foreach($months as $num => $name)
										<option value="{{ $num }}">{{ $name }}</option>
									@endforeach
								</select>
							</div>

							{{-- Department --}}
							<div class="flex items-center">
								<label for="department" class="w-1/4 text-sm font-medium text-gray-700 dark:text-gray-300">
									{{ __('Department') }}
								</label>
								<select id="department" name="department" 
									class="w-3/4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
									<option value="">{{ __('Select user department') }}</option>
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
									<option value="ITDept">{{ __('IT Department') }}</option>
									<option value="Employee">{{ __('Employee') }}</option>
									<option value="HOD">{{ __('Head of Department') }}</option>
								</select>
							</div>

							{{-- Asset Type --}}
							<div class="flex items-center">
								<label for="assetType" class="w-1/4 text-sm font-medium text-gray-700 dark:text-gray-300">
									{{ __('Asset Type') }}
								</label>
								<select id="assetType" name="assetType" 
									class="w-3/4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
									<option value="">{{ __('Select asset type') }}</option>
									<option value="Laptop">{{ __('Laptop') }}</option>
									<option value="Desktop">{{ __('Desktop') }}</option>
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
									<option value="Available">{{ __('Available') }}</option>
									<option value="Checked Out">{{ __('Checked Out') }}</option>
									<option value="Disposed">{{ __('Disposed') }}</option>
								</select>
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
</x-app-layout>
