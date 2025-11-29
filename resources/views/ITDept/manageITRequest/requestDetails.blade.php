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
				<div class="flex justify-center space-x-4">
					<button id="skipBtn" type="button" 
						class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition"
						onclick="submitMaintenance(false)">
						{{ __('No, Skip') }}
					</button>
					<button id="updateBtn" type="button" 
						class="px-4 py-2 text-white rounded-md transition"
						style="background-color: #4BA9C2;"
						onmouseover="this.style.backgroundColor='#3a8ba5'"
						onmouseout="this.style.backgroundColor='#4BA9C2'"
						onclick="submitMaintenance(true)">
						{{ __('Yes, Update Now') }}
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
						<div class="border border-gray-200 dark:border-gray-600 rounded-md p-4 bg-gray-50 dark:bg-gray-700">
							<h4 class="text-md font-medium mb-4">{{ __('Add Maintenance Details') }}</h4>
							<form id="maintenanceForm" action="{{ route('itdept.it-requests.maintenance', $itRequest->requestID) }}" method="POST">
								@csrf
								<input type="hidden" name="updateAsset" id="updateAsset" value="0">
								<div class="space-y-5">
									{{-- Maintenance Date --}}
									<div>
										<x-input-label for="mainDate" :value="__('Maintenance Date')" />
										<x-text-input id="mainDate" name="mainDate" type="date" 
											class="mt-1 block w-full" 
											value="{{ old('mainDate', date('Y-m-d')) }}"
											required />
										<x-input-error :messages="$errors->get('mainDate')" class="mt-2" />
									</div>

									{{-- Maintenance Description --}}
									<div>
										<x-input-label for="mainDesc" :value="__('Maintenance Description')" />
										<textarea id="mainDesc" name="mainDesc" 
											rows="5"
											class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
											placeholder="Describe the maintenance work performed..."
											required>{{ old('mainDesc') }}</textarea>
										<x-input-error :messages="$errors->get('mainDesc')" class="mt-2" />
									</div>

									{{-- Hardware Changes Checkbox --}}
									<div>
										<label class="inline-flex items-center">
											<input type="checkbox" 
												name="hardwareChanges" 
												id="hardwareChangesCheckbox"
												class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
												style="accent-color: #4BA9C2;">
											<span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Does this maintenance include hardware changes?') }}</span>
										</label>
									</div>
								</div>

								{{-- Submit Button --}}
								<div class="flex items-center justify-end mt-6">
									<button type="button" 
										id="submitMaintenanceBtn"
										class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
										style="background-color: #4BA9C2;"
										onmouseover="this.style.backgroundColor='#3a8ba5'"
										onmouseout="this.style.backgroundColor='#4BA9C2'">
										{{ __('Submit Maintenance') }}
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
			document.getElementById('updateAsset').value = updateAsset ? '1' : '0';
			document.getElementById('hardwareChangesModal').style.display = 'none';
			document.getElementById('maintenanceForm').submit();
		}
	</script>
</x-app-layout>
