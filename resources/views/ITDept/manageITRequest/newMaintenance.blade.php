<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('New Maintenance') }}
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
				<a href="{{ route('itdept.repairs-maintenance') }}" 
				   style="color: #4BA9C2;"
				   class="hover:opacity-80">
					← Repairs & Maintenance
				</a>
				<span class="text-gray-600 dark:text-gray-400"> > </span>
				<span class="text-gray-600 dark:text-gray-400">{{ __('New Maintenance') }}</span>
			</div>

			{{-- Main Content Card --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Title --}}
					<div class="mb-6">
						<h1 class="text-xl font-semibold">{{ __('Add New Maintenance') }}</h1>
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

					{{-- Add New Maintenance Form Section --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-4">{{ __('Maintenance Details') }}</h3>
						<form id="maintenanceForm" action="{{ route('itdept.store-maintenance') }}" method="POST">
							@csrf
							<input type="hidden" name="updateAsset" id="updateAsset" value="0">
							<div class="space-y-5">
								{{-- Asset Type --}}
								<div>
									<x-input-label for="assetType" :value="__('Asset Type')" />
									<div class="mt-2 space-x-6">
										@foreach($assetTypes as $type)
											<label class="inline-flex items-center">
												<input type="radio" name="assetType" value="{{ $type }}" 
													class="me-2 asset-type-radio"
													style="accent-color: #4BA9C2;"
													required
													onchange="loadAssetsByType('{{ $type }}')">
												<span class="text-gray-700 dark:text-gray-300">{{ $type }}</span>
											</label>
										@endforeach
									</div>
									<x-input-error :messages="$errors->get('assetType')" class="mt-2" />
								</div>

								{{-- Asset ID --}}
								<div>
									<x-input-label for="assetID" :value="__('Asset ID')" />
									<select id="assetID" name="assetID" 
										class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
										required
										disabled>
										<option value="">{{ __('Select Asset Type first') }}</option>
									</select>
									<x-input-error :messages="$errors->get('assetID')" class="mt-2" />
								</div>

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

							{{-- Buttons --}}
							<div class="flex items-center justify-end space-x-6 mt-6">
								<a href="{{ route('itdept.repairs-maintenance') }}" 
									class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
									style="background-color: #797979;"
									onmouseover="this.style.backgroundColor='#666666'"
									onmouseout="this.style.backgroundColor='#797979'">
									{{ __('Cancel') }}
								</a>
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
				</div>
			</div>
		</div>
	</div>

	<script>
		function loadAssetsByType(assetType) {
			const assetSelect = document.getElementById('assetID');
			
			// Disable and clear options
			assetSelect.disabled = true;
			assetSelect.innerHTML = '<option value="">Loading...</option>';

			// Fetch assets by type
			fetch(`{{ route('itdept.maintenance.assets-by-type') }}?assetType=${assetType}`, {
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'Accept': 'application/json',
				}
			})
			.then(response => response.json())
			.then(assets => {
				assetSelect.innerHTML = '<option value="">{{ __("Select Asset") }}</option>';
				
				if (assets.length === 0) {
					assetSelect.innerHTML = '<option value="">{{ __("No assets found for this type") }}</option>';
					return;
				}

				assets.forEach(asset => {
					const option = document.createElement('option');
					option.value = asset.assetID;
					option.textContent = asset.assetID + (asset.model ? ` - ${asset.model}` : '');
					assetSelect.appendChild(option);
				});

				assetSelect.disabled = false;
			})
			.catch(error => {
				console.error('Error loading assets:', error);
				assetSelect.innerHTML = '<option value="">{{ __("Error loading assets") }}</option>';
			});
		}

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

			// Load assets if asset type is already selected (from old input)
			const selectedAssetType = document.querySelector('input[name="assetType"]:checked');
			if (selectedAssetType) {
				loadAssetsByType(selectedAssetType.value);
			}
		});

		function submitMaintenance(updateAsset) {
			document.getElementById('updateAsset').value = updateAsset ? '1' : '0';
			document.getElementById('hardwareChangesModal').style.display = 'none';
			document.getElementById('maintenanceForm').submit();
		}
	</script>
</x-app-layout>
