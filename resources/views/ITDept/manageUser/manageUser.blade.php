<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Users') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- View HOD / Employee --}}
                    <div class="flex border-b mb-6" style="border-color: #4BA9C2;">
                        <a href="{{ route('itdept.manage-users.index', ['role' => 'HOD']) }}"
                           class="flex-1 text-center py-2 font-medium 
                           {{ $role === 'HOD' ? 'border-b-2' : '' }}"
                           style="{{ $role === 'HOD' ? 'color: #4BA9C2; border-bottom-color: #4BA9C2;' : 'color: #6B7280;' }}">
                            {{ __('Head Of Department (HOD)') }}
                        </a>
                        <a href="{{ route('itdept.manage-users.index', ['role' => 'Employee']) }}"
                           class="flex-1 text-center py-2 font-medium 
                           {{ $role === 'Employee' ? 'border-b-2' : '' }}"
                           style="{{ $role === 'Employee' ? 'color: #4BA9C2; border-bottom-color: #4BA9C2;' : 'color: #6B7280;' }}">
                            {{ __('Employee') }}
                        </a>
                    </div>

                    {{-- Search / Filter / Add --}}
					<div class="mb-6">
						<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
							<form id="filterForm" method="GET" action="{{ route('itdept.manage-users.index') }}" 
								class="flex flex-wrap items-center gap-2 flex-1">
								<input type="hidden" name="role" value="{{ $role }}" />

								{{-- Search input with real-time search --}}
								<div class="input-container flex-1 min-w-[200px]">
									<input type="text" id="searchInput" name="q" value="{{ $q }}" 
										placeholder="{{ __('Search name or username') }}"
										class="interactive-input w-full"
										style="padding: 8px 12px; font-size: 13px;"
										autocomplete="off" />
								</div>

								{{-- Department dropdown --}}
								<div class="input-container">
									<select name="department" id="departmentSelect"
											class="interactive-select"
											style="padding: 8px 32px 8px 12px; font-size: 13px; min-width: 180px;">
										<option value="">{{ __('All departments') }}</option>
										@php($departments = ['HR & Admin','Account','Service','Project','Supply Chain','Sales','Proposal'])
										@foreach ($departments as $dept)
											<option value="{{ $dept }}" @selected($filterDepartment === $dept)>{{ $dept }}</option>
										@endforeach
									</select>
								</div>

								{{-- Status dropdown --}}
								<div class="input-container">
									<select name="status" id="statusSelect"
											class="interactive-select"
											style="padding: 8px 32px 8px 12px; font-size: 13px; min-width: 150px;">
										<option value="">{{ __('All status') }}</option>
										<option value="active" @selected($filterStatus === 'active')>{{ __('Active') }}</option>
										<option value="inactive" @selected($filterStatus === 'inactive')>{{ __('Inactive') }}</option>
									</select>
								</div>

								{{-- Add User button inline with form --}}
								<a href="{{ route('itdept.manage-users.create') }}" 
								class="interactive-button interactive-button-primary ml-auto"
								style="padding: 10px 16px; font-size: 11px;">
									<span class="button-content">
										<svg class="w-3 h-3 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
										</svg>
										{{ __('Add User') }}
									</span>
								</a>
							</form>
						</div>
					</div>

                    {{-- Status message --}}
                    @if (session('status'))
                        <div class="mb-4 text-green-500 font-medium">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Users Table -->
					<div class="overflow-x-auto" id="usersTableContainer">
						<div id="loadingIndicator" class="hidden text-center py-4 text-gray-500 dark:text-gray-400">
							<p>Searching...</p>
						</div>
						<table class="table-auto w-full border border-gray-300 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700" id="usersTable">
							<thead class="bg-gray-100 dark:bg-gray-700">
								@php($columns = [
									['key' => 'userID', 'label' => 'Username'],
									['key' => 'fullName', 'label' => 'Full Name'],
									['key' => 'email', 'label' => 'Email'],
									['key' => 'department', 'label' => 'Department'],
								])
								<tr>
									@foreach ($columns as $c)
										<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
											@php($isActive = ($sort ?? null) === $c['key'])
											<a href="{{ request()->fullUrlWithQuery([
												'sort' => $c['key'], 
												'dir' => ($isActive && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc'
											]) }}" 
											class="inline-flex items-center gap-1">
												<span>{{ __($c['label']) }}</span>
												<span class="text-xs">
													@if ($isActive)
														{{ ($dir ?? 'asc') === 'asc' ? '▲' : '▼' }}
													@else
														▲▼
													@endif
												</span>
											</a>
										</th>
									@endforeach
									@php($statusActive = ($sort ?? null) === 'accStat')
									<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
										<a href="{{ request()->fullUrlWithQuery([
											'sort' => 'accStat', 
											'dir' => ($statusActive && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc'
										]) }}" 
										class="inline-flex items-center gap-1">
											<span>{{ __('Status') }}</span>
											<span class="text-xs">
												@if ($statusActive)
													{{ ($dir ?? 'asc') === 'asc' ? '▲' : '▼' }}
												@else
													▲▼
												@endif
											</span>
										</a>
									</th>
									<th class="px-3 py-2 text-center text-sm font-semibold text-gray-700 dark:text-gray-200" style="width: 20%;">{{ __('Action') }}</th>
								</tr>
							</thead>
							<tbody id="usersTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
								@forelse ($users as $user)
									<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-800' }}">
										<td class="px-4 py-2 text-sm">{{ $user->userID }}</td>
										<td class="px-4 py-2 text-sm">{{ $user->fullName }}</td>
										<td class="px-4 py-2 text-sm">{{ $user->email ?? '-' }}</td>
										<td class="px-4 py-2 text-sm">{{ $user->department ?? '-' }}</td>
										<td class="px-4 py-2">
											<span class="text-sm font-medium {{ $user->accStat === 'active' ? 'text-green-600' : 'text-red-600' }}">
												{{ ucfirst($user->accStat ?? 'active') }}
											</span>
										</td>
										<td class="px-3 py-2">
											<div class="flex items-center justify-center gap-2">
												@if($user->accStat === 'active')
												<form action="{{ route('itdept.manage-users.deactivate', $user->userID) }}" method="POST" class="inline">
													@csrf
													@method('PATCH')
													<button type="submit" 
														class="interactive-button interactive-button-deactivate"
														style="padding: 6px 10px; font-size: 11px; min-width: 100px;"
														title="{{ __('Deactivate') }}">
														<span class="button-content">
															{{ __('Deactivate') }}
														</span>
													</button>
												</form>
												@else
												<form action="{{ route('itdept.manage-users.activate', $user->userID) }}" method="POST" class="inline">
													@csrf
													@method('PATCH')
													<button type="submit" 
														class="interactive-button interactive-button-activate"
														style="padding: 6px 10px; font-size: 11px; min-width: 100px;"
														title="{{ __('Activate') }}">
														<span class="button-content">
															{{ __('Activate') }}
														</span>
													</button>
												</form>
												@endif
												<a href="{{ route('itdept.manage-users.edit', $user->userID) }}" 
												   class="interactive-button interactive-button-secondary"
												   style="padding: 6px 10px; font-size: 11px;"
												   title="{{ __('Edit') }}">
													<span class="button-content">
														<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
														</svg>
													</span>
												</a>
												<form action="{{ route('itdept.manage-users.destroy', $user->userID) }}" method="POST" class="inline delete-user-form">
													@csrf
													@method('DELETE')
													<button type="submit" 
														class="interactive-button interactive-button-delete"
														style="padding: 6px 10px; font-size: 11px;"
														title="{{ __('Delete') }}">
														<span class="button-content">
															<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
																<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
															</svg>
															<span class="button-spinner"></span>
														</span>
													</button>
												</form>
											</div>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
											No users found.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

                    {{-- Pagination --}}
                    <div class="mt-4 pagination-container">
                        {{ $users->links() }}
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
            background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
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
            background: linear-gradient(135deg, #4B5563 0%, #374151 100%);
            box-shadow: 0 8px 20px rgba(107, 114, 128, 0.5);
            transform: translateY(-2px) scale(1.02);
        }

        .interactive-button-secondary:active::before {
            width: 300px;
            height: 300px;
        }

        .interactive-button-secondary:active {
            background: linear-gradient(135deg, #374151 0%, #1F2937 100%);
            transform: translateY(0) scale(0.98);
            box-shadow: 0 2px 8px rgba(107, 114, 128, 0.3);
        }

        .interactive-button-delete {
            background: linear-gradient(135deg, #B40814 0%, #A10712 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(180, 8, 20, 0.3);
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

        .interactive-button-activate {
            background: linear-gradient(135deg, #1D9F26 0%, #1A8F22 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(29, 159, 38, 0.3);
        }

        .interactive-button-activate::before {
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

        .interactive-button-activate:hover {
            background: linear-gradient(135deg, #1A8F22 0%, #177F1E 100%);
            box-shadow: 0 8px 20px rgba(29, 159, 38, 0.5);
            transform: translateY(-2px) scale(1.02);
        }

        .interactive-button-activate:active::before {
            width: 300px;
            height: 300px;
        }

        .interactive-button-activate:active {
            background: linear-gradient(135deg, #177F1E 0%, #146F1A 100%);
            transform: translateY(0) scale(0.98);
            box-shadow: 0 2px 8px rgba(29, 159, 38, 0.3);
        }

        .interactive-button-deactivate {
            background: linear-gradient(135deg, #B40814 0%, #A10712 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(180, 8, 20, 0.3);
        }

        .interactive-button-deactivate::before {
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

        .interactive-button-deactivate:hover {
            background: linear-gradient(135deg, #A10712 0%, #990610 100%);
            box-shadow: 0 8px 20px rgba(180, 8, 20, 0.5);
            transform: translateY(-2px) scale(1.02);
        }

        .interactive-button-deactivate:active::before {
            width: 300px;
            height: 300px;
        }

        .interactive-button-deactivate:active {
            background: linear-gradient(135deg, #990610 0%, #86050E 100%);
            transform: translateY(0) scale(0.98);
            box-shadow: 0 2px 8px rgba(180, 8, 20, 0.3);
        }

        .button-content {
            display: flex;
            align-items: center;
            justify-content: center;
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

        .interactive-button.loading .button-content {
            opacity: 0.7;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <script>
        // Function to initialize delete button handlers
        function initializeDeleteHandlers() {
            const deleteForms = document.querySelectorAll('.delete-user-form');
            deleteForms.forEach(form => {
                // Remove existing listeners to avoid duplicates
                const newForm = form.cloneNode(true);
                form.parentNode.replaceChild(newForm, form);
                
                const submitButton = newForm.querySelector('button[type="submit"]');
                if (submitButton) {
                    newForm.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        
                        // Show confirmation dialog
                        const confirmed = await window.showConfirmation(
                            'Are you sure you want to delete this user? This action cannot be undone.',
                            'Delete User'
                        );
                        
                        if (confirmed) {
                            // User confirmed - add loading state and submit
                            submitButton.classList.add('loading');
                            submitButton.disabled = true;
                            newForm.submit();
                        }
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize delete handlers on page load
            initializeDeleteHandlers();

            // Real-time search functionality with AJAX
            const searchInput = document.getElementById('searchInput');
            const departmentSelect = document.getElementById('departmentSelect');
            const statusSelect = document.getElementById('statusSelect');
            const filterForm = document.getElementById('filterForm');
            const usersTableBody = document.getElementById('usersTableBody');
            const usersTableContainer = document.getElementById('usersTableContainer');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const usersTable = document.getElementById('usersTable');
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
                
                // Preserve existing query parameters (sort, dir, page)
                const urlParams = new URLSearchParams(window.location.search);
                ['sort', 'dir', 'page'].forEach(param => {
                    if (urlParams.has(param) && !searchParams.has(param)) {
                        searchParams.append(param, urlParams.get(param));
                    }
                });

                // Show loading indicator
                if (usersTable) usersTable.style.opacity = '0.5';
                if (loadingIndicator) loadingIndicator.classList.remove('hidden');

                // Create abort controller for request cancellation
                const abortController = new AbortController();
                currentRequest = abortController;

                // Fetch results via AJAX
                fetch('{{ route("itdept.manage-users.index") }}?' + searchParams.toString(), {
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
                    const newTableBody = doc.querySelector('#usersTableBody');
                    const newPagination = doc.querySelector('.pagination-container') || doc.querySelector('.mt-4');
                    
                    if (newTableBody) {
                        // Update table body
                        usersTableBody.innerHTML = newTableBody.innerHTML;
                        
                        // Update pagination if exists
                        const paginationContainer = document.querySelector('.pagination-container') || document.querySelector('.mt-4');
                        if (paginationContainer && newPagination) {
                            paginationContainer.innerHTML = newPagination.innerHTML;
                        }
                        
                        // Update URL without reload
                        const newUrl = '{{ route("itdept.manage-users.index") }}?' + searchParams.toString();
                        window.history.pushState({}, '', newUrl);
                        
                        // Re-initialize delete button handlers for new rows
                        initializeDeleteHandlers();
                    }
                })
                .catch(error => {
                    if (error.name !== 'AbortError') {
                        console.error('Search error:', error);
                    }
                })
                .finally(() => {
                    // Hide loading indicator
                    if (usersTable) usersTable.style.opacity = '1';
                    if (loadingIndicator) loadingIndicator.classList.add('hidden');
                    currentRequest = null;
                });
            }

            // Search input event listener
            if (searchInput && filterForm && usersTableBody) {
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

            // Department dropdown event listener
            if (departmentSelect) {
                departmentSelect.addEventListener('change', function() {
                    clearTimeout(searchTimeout);
                    if (currentRequest) {
                        currentRequest.abort();
                    }
                    performSearch();
                });
            }

            // Status dropdown event listener
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
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
