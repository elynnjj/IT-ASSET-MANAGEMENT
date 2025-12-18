<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Repairs & Maintenance') }}
		</h2>
	</x-slot>

	<div class="py-12" x-data>
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Search, Filter, and New Maintenance Button in one row --}}
					<div class="mb-6">
						<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
							<form id="filterForm" method="GET" action="{{ route('itdept.repairs-maintenance') }}" 
								class="flex flex-wrap items-center gap-2 flex-1">
								
								{{-- Search by Asset ID --}}
								<div class="input-container flex-1 min-w-[200px]">
									<input type="text" id="searchInput" name="q" value="{{ request('q') }}" 
										placeholder="{{ __('Search Asset ID') }}"
										class="interactive-input w-full"
										style="padding: 8px 12px; font-size: 13px;"
										autocomplete="off" />
								</div>

								{{-- Filter by Asset Type --}}
								<div class="input-container">
									<select name="assetType" id="assetTypeSelect"
											class="interactive-select"
											style="padding: 8px 32px 8px 12px; font-size: 13px; min-width: 180px;">
										<option value="">{{ __('All Asset Types') }}</option>
										@foreach($assetTypes as $type)
											<option value="{{ $type }}" @selected(request('assetType') === $type)>{{ $type }}</option>
										@endforeach
									</select>
								</div>

								{{-- Clear filters button --}}
								@if(request('q') || request('assetType'))
									<a href="{{ route('itdept.repairs-maintenance') }}" 
										class="interactive-button interactive-button-secondary"
										style="padding: 8px 16px; font-size: 11px;">
										<span class="button-content">
											{{ __('Clear') }}
										</span>
									</a>
								@endif
							</form>

							{{-- New Maintenance Button --}}
							<a href="{{ route('itdept.new-maintenance') }}" 
								class="interactive-button interactive-button-primary"
								style="padding: 10px 16px; font-size: 11px;">
								<span class="button-content">
									<svg class="w-3 h-3 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
									</svg>
									{{ __('New Maintenance') }}
								</span>
							</a>
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

					<!-- Maintenance Table -->
					<div class="overflow-x-auto" id="maintenancesTableContainer">
						<div id="loadingIndicator" class="hidden text-center py-4 text-gray-500 dark:text-gray-400">
							<p>Searching...</p>
						</div>
						<table class="table-auto w-full border border-gray-300 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700" id="maintenancesTable">
							<thead class="bg-gray-100 dark:bg-gray-700">
								<tr>
									<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Date') }}</th>
									<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Asset ID') }}</th>
									<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Maintenance Details') }}</th>
								</tr>
							</thead>
							<tbody id="maintenancesTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
								@forelse ($maintenances as $maintenance)
									<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-800' }}">
										<td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($maintenance->mainDate)->format('d/m/y') }}</td>
										<td class="px-4 py-2 text-sm">{{ $maintenance->asset ? $maintenance->asset->assetID : 'N/A' }}</td>
										<td class="px-4 py-2 text-sm">{{ $maintenance->mainDesc }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500">
											No maintenance records found.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					{{-- Pagination --}}
					<div class="mt-4 pagination-container">
						{{ $maintenances->links() }}
					</div>

				</div>
			</div>
		</div>
	</div>

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
		.input-container:has(.interactive-select:focus) {
			transform: translateY(-2px);
		}

		/* Interactive input styling */
		.interactive-input {
			width: 100%;
			border: 2px solid #9CA3AF;
			border-radius: 8px;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			background-color: #FFFFFF;
			position: relative;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input {
				background-color: #111827;
				border-color: #6B7280;
				color: #D1D5DB;
			}
		}

		.dark .interactive-input {
			background-color: #111827;
			border-color: #6B7280;
			color: #D1D5DB;
		}

		.interactive-input:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.15);
			transform: translateY(-1px);
			background-color: #FAFAFA;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input:hover {
				border-color: #4BA9C2;
				box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
				background-color: #1F2937;
			}
		}

		.dark .interactive-input:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
			background-color: #1F2937;
		}

		.interactive-input:focus {
			outline: none;
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.15), 0 6px 16px rgba(75, 169, 194, 0.2);
			background-color: #FFFFFF;
			transform: translateY(-2px);
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input:focus {
				border-color: #4BA9C2;
				box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
				background-color: #111827;
				color: #D1D5DB;
			}
		}

		.dark .interactive-input:focus {
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
			background-color: #111827;
			color: #D1D5DB;
		}

		/* Interactive select styling */
		.interactive-select {
			border: 2px solid #9CA3AF;
			border-radius: 8px;
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
		}
	</style>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Real-time search functionality with AJAX
			const searchInput = document.getElementById('searchInput');
			const assetTypeSelect = document.getElementById('assetTypeSelect');
			const filterForm = document.getElementById('filterForm');
			const maintenancesTableBody = document.getElementById('maintenancesTableBody');
			const maintenancesTableContainer = document.getElementById('maintenancesTableContainer');
			const loadingIndicator = document.getElementById('loadingIndicator');
			const maintenancesTable = document.getElementById('maintenancesTable');
			let searchTimeout = null;
			let currentRequest = null;

			function performSearch() {
				const formData = new FormData(filterForm);
				const searchParams = new URLSearchParams();
				
				// Add all form data to URL params
				for (const [key, value] of formData.entries()) {
					if (value) {
						searchParams.append(key, value);
					}
				}
				
				// Preserve existing query parameters (page)
				const urlParams = new URLSearchParams(window.location.search);
				['page'].forEach(param => {
					if (urlParams.has(param) && !searchParams.has(param)) {
						searchParams.append(param, urlParams.get(param));
					}
				});

				// Show loading indicator
				if (maintenancesTable) maintenancesTable.style.opacity = '0.5';
				if (loadingIndicator) loadingIndicator.classList.remove('hidden');

				// Create abort controller for request cancellation
				const abortController = new AbortController();
				currentRequest = abortController;

				// Fetch results via AJAX
				fetch('{{ route("itdept.repairs-maintenance") }}?' + searchParams.toString(), {
					method: 'GET',
					headers: {
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'text/html',
					},
					signal: abortController.signal
				})
				.then(response => response.text())
				.then(html => {
					// Parse the response HTML
					const parser = new DOMParser();
					const doc = parser.parseFromString(html, 'text/html');
					const newTableBody = doc.querySelector('#maintenancesTableBody');
					const newPagination = doc.querySelector('.pagination-container') || doc.querySelector('.mt-4');
					
					if (newTableBody) {
						// Update table body
						maintenancesTableBody.innerHTML = newTableBody.innerHTML;
						
						// Update pagination if exists
						const paginationContainer = document.querySelector('.pagination-container') || document.querySelector('.mt-4');
						if (paginationContainer && newPagination) {
							paginationContainer.innerHTML = newPagination.innerHTML;
						}
						
						// Update URL without reload
						const newUrl = '{{ route("itdept.repairs-maintenance") }}?' + searchParams.toString();
						window.history.pushState({}, '', newUrl);
					}
				})
				.catch(error => {
					if (error.name !== 'AbortError') {
						console.error('Search error:', error);
					}
				})
				.finally(() => {
					// Hide loading indicator
					if (maintenancesTable) maintenancesTable.style.opacity = '1';
					if (loadingIndicator) loadingIndicator.classList.add('hidden');
					currentRequest = null;
				});
			}

			// Search input event listener
			if (searchInput && filterForm && maintenancesTableBody) {
				searchInput.addEventListener('input', function() {
					// Clear previous timeout
					clearTimeout(searchTimeout);
					
					// Cancel previous request if still pending
					if (currentRequest) {
						currentRequest.abort();
					}
					
					// Set new timeout to search after 300ms of no typing
					searchTimeout = setTimeout(function() {
						performSearch();
					}, 300);
				});

				// Also search on Enter key press
				searchInput.addEventListener('keydown', function(e) {
					if (e.key === 'Enter') {
						e.preventDefault();
						clearTimeout(searchTimeout);
						if (currentRequest) {
							currentRequest.abort();
						}
						performSearch();
					}
				});
			}

			// Asset type dropdown event listener
			if (assetTypeSelect) {
				assetTypeSelect.addEventListener('change', function() {
					clearTimeout(searchTimeout);
					if (currentRequest) {
						currentRequest.abort();
					}
					performSearch();
				});
			}
		});
	</script>
</x-app-layout>
