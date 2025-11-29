<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('New IT Request') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			{{-- Breadcrumb --}}
			<div class="mb-6">
				<a href="{{ route('employee.my-requests') }}" 
				   style="color: #4BA9C2;"
				   class="hover:opacity-80">
					← My Requests
				</a>
				<span class="text-gray-600 dark:text-gray-400"> > </span>
				<span class="text-gray-600 dark:text-gray-400">{{ __('New IT Request') }}</span>
			</div>

			{{-- Main Content Card --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Title --}}
					<div class="mb-6">
						<h1 class="text-xl font-semibold">{{ __('New IT Request') }}</h1>
					</div>

					{{-- Session Status --}}
					@if (session('status'))
						<div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 rounded-md">
							{{ session('status') }}
						</div>
					@endif

					{{-- Asset Details Section --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-4">{{ __('Your Assigned Asset') }}</h3>
						@if($assignedAsset)
							<div class="grid grid-cols-2 gap-4">
								<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-white dark:bg-gray-800">
									<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Asset ID') }}</label>
									<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $assignedAsset->asset->assetID }}</p>
								</div>
								<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-white dark:bg-gray-800">
									<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Model') }}</label>
									<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $assignedAsset->asset->model ?? 'N/A' }}</p>
								</div>
							</div>
						@else
							<p class="text-sm text-gray-600 dark:text-gray-400 italic">No asset currently assigned to you.</p>
						@endif
					</div>

					{{-- HOD Information Section --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-4">{{ __('Approver Information') }}</h3>
						@if($hod)
							<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-white dark:bg-gray-800">
								<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Head of Department (HOD)') }}</label>
								<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $hod->fullName }}</p>
								<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $hod->department }} Department</p>
							</div>
						@else
							<p class="text-sm text-gray-600 dark:text-gray-400 italic">No HOD assigned for your department. Please contact IT Department.</p>
						@endif
					</div>

					{{-- New IT Request Form Section --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-4">{{ __('Request Details') }}</h3>
						<form action="{{ route('employee.it-requests.store') }}" method="POST">
							@csrf

							<div class="space-y-5">
								{{-- Request Date --}}
								<div>
									<x-input-label for="requestDate" :value="__('Request Date')" />
									<x-text-input id="requestDate" name="requestDate" type="date" 
										class="mt-1 block w-full" 
										value="{{ old('requestDate', date('Y-m-d')) }}"
										required />
									<x-input-error :messages="$errors->get('requestDate')" class="mt-2" />
								</div>

								{{-- Request Title --}}
								<div>
									<x-input-label for="title" :value="__('Request Title')" />
									<x-text-input id="title" name="title" type="text" 
										class="mt-1 block w-full" 
										placeholder="Enter a brief title for your request"
										value="{{ old('title') }}"
										required />
									<x-input-error :messages="$errors->get('title')" class="mt-2" />
								</div>

								{{-- Request Description --}}
								<div>
									<x-input-label for="requestDesc" :value="__('Request Description')" />
									<textarea id="requestDesc" name="requestDesc" 
										rows="5"
										class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
										placeholder="Describe your IT request in detail..."
										required>{{ old('requestDesc') }}</textarea>
									<x-input-error :messages="$errors->get('requestDesc')" class="mt-2" />
								</div>
							</div>

							{{-- Buttons --}}
							<div class="flex items-center justify-end space-x-6 mt-6">
								<a href="{{ route('employee.my-requests') }}" 
								   class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
								   style="background-color: #797979;"
								   onmouseover="this.style.backgroundColor='#666666'"
								   onmouseout="this.style.backgroundColor='#797979'">
									{{ __('Cancel') }}
								</a>
								<button type="submit" 
									class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
									style="background-color: #4BA9C2;"
									onmouseover="this.style.backgroundColor='#3a8ba5'"
									onmouseout="this.style.backgroundColor='#4BA9C2'">
									{{ __('Submit Request') }}
								</button>
							</div>
						</form>
					</div>

					{{-- Information Notice --}}
					<div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-md">
						<p class="text-sm text-blue-800 dark:text-blue-200">
							<strong>Note:</strong> Once you submit this request, your designated HOD will receive an email notification and will need to approve the request within 3 days.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
