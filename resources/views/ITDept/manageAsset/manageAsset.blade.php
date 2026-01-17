<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Assets') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- View Laptops / Desktops --}}
                    <div class="flex border-b mb-6" style="border-color: #4BA9C2;">
                        <a href="{{ route('itdept.manage-assets.index', ['assetType' => 'Laptop']) }}"
                           class="flex-1 text-center py-2 font-medium 
                           {{ $assetType === 'Laptop' ? 'border-b-2' : '' }}"
                           style="{{ $assetType === 'Laptop' ? 'color: #4BA9C2; border-bottom-color: #4BA9C2;' : 'color: #6B7280;' }}">
                            {{ __('Laptops') }}
                        </a>
                        <a href="{{ route('itdept.manage-assets.index', ['assetType' => 'Desktop']) }}"
                           class="flex-1 text-center py-2 font-medium 
                           {{ $assetType === 'Desktop' ? 'border-b-2' : '' }}"
                           style="{{ $assetType === 'Desktop' ? 'color: #4BA9C2; border-bottom-color: #4BA9C2;' : 'color: #6B7280;' }}">
                            {{ __('Desktops') }}
                        </a>
                    </div>

                    {{-- Search / Filter / Add --}}
					<div class="mb-6">
						<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
							<form id="filterForm" method="GET" action="{{ route('itdept.manage-assets.index') }}" 
								class="flex flex-wrap items-center gap-2 flex-1">
								<input type="hidden" name="assetType" value="{{ $assetType }}" />

								{{-- Search input with auto-submit --}}
								<div class="input-container flex-1 min-w-[200px]">
									<input type="text" id="searchInput" name="q" value="{{ $q }}" 
									placeholder="{{ __('Search asset ID, serial number, model or current user') }}"
										class="interactive-input w-full"
										style="padding: 8px 12px; font-size: 13px;"
										autocomplete="off" />
								</div>

								{{-- Status Filter Dropdown --}}
								<div class="input-container">
									<select name="status" id="statusFilter" 
										class="interactive-input"
										style="padding: 8px 12px; font-size: 13px; cursor: pointer;"
										onchange="document.getElementById('filterForm').submit();">
										<option value="">{{ __('All Status') }}</option>
										<option value="Available" {{ $filterStatus === 'Available' ? 'selected' : '' }}>{{ __('Available') }}</option>
										<option value="Checked Out" {{ $filterStatus === 'Checked Out' ? 'selected' : '' }}>{{ __('Checked Out') }}</option>
									</select>
								</div>

								{{-- Upload Invoice button --}}
								<a href="{{ route('itdept.manage-assets.upload-invoice') }}" 
								class="interactive-button interactive-button-primary"
								style="padding: 10px 16px; font-size: 11px;">
									<span class="button-content">
										<svg class="w-3 h-3 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
									</svg>
									{{ __('Upload Invoice') }}
									</span>
								</a>

								{{-- Add Asset button inline with form --}}
								<a href="{{ route('itdept.manage-assets.create') }}" 
								class="interactive-button interactive-button-primary ml-auto"
								style="padding: 10px 16px; font-size: 11px;">
									<span class="button-content">
										<svg class="w-3 h-3 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
									</svg>
									{{ __('Add New Asset') }}
									</span>
								</a>
							</form>
						</div>
					</div>

                    {{-- Progress bar (shown during import) --}}
                    <div id="importProgressBar" class="mb-4 hidden">
                        <div class="p-4 rounded-lg" style="background-color: rgba(75, 169, 194, 0.1); border: 1px solid rgba(75, 169, 194, 0.3);">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <svg class="animate-spin h-5 w-5 mr-2" style="color: #4BA9C2;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <p class="font-medium" style="color: #4BA9C2;">
                                        <span id="progressText">Processing import...</span>
                                    </p>
                                </div>
                                <span id="progressCount" class="font-semibold" style="color: #4BA9C2;">0 / 0</span>
                            </div>
                            <div class="w-full rounded-full h-3" style="background-color: rgba(75, 169, 194, 0.2);">
                                <div id="progressBarFill" class="h-3 rounded-full transition-all duration-300" style="width: 0%; background: linear-gradient(135deg, #4BA9C2 0%, #3a8ba5 100%);"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Status message (shown after import completes) --}}
                    <div id="importStatusMessage" class="mb-4 hidden">
                        <div class="p-4 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-green-700 dark:text-green-300 font-medium" id="statusMessageText"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Legacy status message (for non-import operations) --}}
                    @if (session('status') && !request()->has('progressId'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-green-700 dark:text-green-300 font-medium">
                                    {{ session('status') }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Assets Table -->
					<div class="overflow-x-auto" id="assetsTableContainer">
						<div id="loadingIndicator" class="hidden text-center py-4 text-gray-500 dark:text-gray-400">
							<p>Searching...</p>
						</div>
						<table class="table-auto w-full border border-gray-300 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700" id="assetsTable">
							<thead class="bg-gray-100 dark:bg-gray-700">
								@php($columns = [
									['key' => 'assetID', 'label' => 'Asset ID'],
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
								<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Serial Number') }}</th>
								<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Model') }}</th>
									<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Current User') }}</th>
								<th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('Status') }}</th>
									<th class="px-3 py-2 text-center text-sm font-semibold text-gray-700 dark:text-gray-200" style="width: 20%;">{{ __('Action') }}</th>
								</tr>
							</thead>
							<tbody id="assetsTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
								@forelse ($assets as $asset)
									@php($currentAssignment = $asset->currentAssignment())
									<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-800' }}">
										<td class="px-4 py-2 text-sm">{{ $asset->assetID }}</td>
										<td class="px-4 py-2 text-sm">{{ $asset->serialNum ?? '-' }}</td>
										<td class="px-4 py-2 text-sm">{{ $asset->model ?? '-' }}</td>
										<td class="px-4 py-2 text-sm">
											@if($currentAssignment)
												{{ $currentAssignment->userFullName ?? ($currentAssignment->user->fullName ?? 'User Deleted') }}
											@else
												With IT
											@endif
										</td>
										<td class="px-4 py-2">
											<span class="text-sm font-medium {{ $asset->status === 'Available' ? 'text-green-600' : 'text-red-600' }}">
												{{ $asset->status ?? 'Available' }}
											</span>
										</td>
										<td class="px-3 py-2">
											<div class="flex items-center justify-center gap-2">
												<a href="{{ route('itdept.manage-assets.show', $asset->assetID) }}" 
												   class="interactive-button interactive-button-secondary"
												   style="padding: 6px 12px; font-size: 11px;"
												   title="{{ __('View') }}">
													<span class="button-content">
														{{ __('View') }}
													</span>
												</a>
												{{-- Check-Out Icon Button --}}
												@if($asset->status !== 'Checked Out')
												<a href="{{ route('itdept.manage-assets.checkout', $asset->assetID) }}" 
												   class="interactive-button-icon-checkout"
												   title="{{ __('Check-Out') }}">
													<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
														<path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
													</svg>
												</a>
												@else
												<button type="button" disabled
													class="interactive-button-icon-disabled"
													title="{{ __('Check-Out') }}">
													<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
														<path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
													</svg>
												</button>
												@endif
												{{-- Check-In Icon Button --}}
												@if($asset->status === 'Checked Out' && $currentAssignment)
												<form action="{{ route('itdept.manage-assets.checkin', $asset->assetID) }}" method="POST" class="inline checkin-asset-form">
													@csrf
													@method('PATCH')
													<button type="submit" 
														class="interactive-button-icon-checkin"
														title="{{ __('Check-In') }}">
														<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
															<path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
														</svg>
													</button>
												</form>
												@else
												<button type="button" disabled
													class="interactive-button-icon-disabled"
													title="{{ __('Check-In') }}">
													<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
														<path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
													</svg>
												</button>
												@endif
											</div>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
											No assets found.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

                    {{-- Pagination --}}
                    <div class="mt-4 pagination-container">
                        {{ $assets->links() }}
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

        .input-container:has(.interactive-input:focus) {
            transform: translateY(-2px);
        }

        /* Interactive input styling */
        .interactive-input {
            width: 100%;
            padding: 14px;
            border: 2px solid #9CA3AF;
            border-radius: 8px;
            font-size: 15px;
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

        .button-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            z-index: 1;
        }

        /* Dark mode support for buttons */
        .dark .interactive-button-primary {
            box-shadow: 0 4px 12px rgba(75, 169, 194, 0.4);
        }

        .dark .interactive-button-primary:hover {
            box-shadow: 0 8px 20px rgba(75, 169, 194, 0.6);
        }

        .dark .interactive-button-secondary {
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
        }

        .dark .interactive-button-secondary:hover {
            box-shadow: 0 8px 20px rgba(107, 114, 128, 0.6);
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

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .dark .interactive-button-delete {
            box-shadow: 0 4px 12px rgba(180, 8, 20, 0.4);
        }

        .dark .interactive-button-delete:hover {
            box-shadow: 0 8px 20px rgba(180, 8, 20, 0.6);
        }

        /* Icon button styling for check-in/check-out */
        .interactive-button-icon-checkout,
        .interactive-button-icon-checkin {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            padding: 0;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(75, 169, 194, 0.3);
        }

        .interactive-button-icon-checkout {
            background: linear-gradient(135deg, #4BA9C2 0%, #3a8ba5 100%);
            color: white;
        }

        .interactive-button-icon-checkin {
            background: linear-gradient(135deg, #1D9F26 0%, #1A8F22 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(29, 159, 38, 0.3);
        }

        .interactive-button-icon-checkout::before,
        .interactive-button-icon-checkin::before {
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

        .interactive-button-icon-checkout:hover {
            background: linear-gradient(135deg, #3a8ba5 0%, #2d6b82 100%);
            box-shadow: 0 4px 12px rgba(75, 169, 194, 0.5);
            transform: translateY(-2px) scale(1.1);
        }

        .interactive-button-icon-checkin:hover {
            background: linear-gradient(135deg, #1A8F22 0%, #167F1A 100%);
            box-shadow: 0 4px 12px rgba(29, 159, 38, 0.5);
            transform: translateY(-2px) scale(1.1);
        }

        .interactive-button-icon-checkout:active::before,
        .interactive-button-icon-checkin:active::before {
            width: 200px;
            height: 200px;
        }

        .interactive-button-icon-checkout:active,
        .interactive-button-icon-checkin:active {
            transform: translateY(0) scale(0.95);
        }

        .interactive-button-icon-checkout svg,
        .interactive-button-icon-checkin svg {
            width: 16px;
            height: 16px;
            position: relative;
            z-index: 1;
        }

        .interactive-button-icon-disabled {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            padding: 0;
            border: none;
            border-radius: 6px;
            cursor: not-allowed;
            background-color: #B2B2B2;
            color: white;
            opacity: 0.5;
        }

        .interactive-button-icon-disabled svg {
            width: 16px;
            height: 16px;
        }

        /* Dark mode support */
        .dark .interactive-button-icon-checkout {
            box-shadow: 0 2px 8px rgba(75, 169, 194, 0.4);
        }

        .dark .interactive-button-icon-checkout:hover {
            box-shadow: 0 4px 12px rgba(75, 169, 194, 0.6);
        }

        .dark .interactive-button-icon-checkin {
            box-shadow: 0 2px 8px rgba(29, 159, 38, 0.4);
        }

        .dark .interactive-button-icon-checkin:hover {
            box-shadow: 0 4px 12px rgba(29, 159, 38, 0.6);
        }
    </style>

    <script>
        // Function to initialize check-in button handlers
        function initializeCheckinHandlers() {
            const checkinForms = document.querySelectorAll('.checkin-asset-form');
            checkinForms.forEach(form => {
                // Remove existing listeners to avoid duplicates
                const newForm = form.cloneNode(true);
                form.parentNode.replaceChild(newForm, form);
                
                const submitButton = newForm.querySelector('button[type="submit"]');
                if (submitButton) {
                    newForm.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        
                        // Show confirmation dialog
                        const confirmed = await window.showConfirmation(
                            'Are you sure you want to check-in this asset?',
                            'Check-In Asset'
                        );
                        
                        if (confirmed) {
                            // User confirmed - submit the form
                            newForm.submit();
                        }
                    });
                }
            });
        }

        // Function to initialize delete button handlers
        function initializeDeleteHandlers() {
            const deleteForms = document.querySelectorAll('.delete-asset-form');
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
                            'Are you sure you want to delete this asset? This action cannot be undone.',
                            'Delete Asset'
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
            // Initialize check-in handlers on page load
            initializeCheckinHandlers();
            
            // Initialize delete handlers on page load
            initializeDeleteHandlers();

            // Real-time search functionality with AJAX
            const searchInput = document.getElementById('searchInput');
            const filterForm = document.getElementById('filterForm');
            const assetsTableBody = document.getElementById('assetsTableBody');
            const assetsTableContainer = document.getElementById('assetsTableContainer');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const assetsTable = document.getElementById('assetsTable');
            let searchTimeout = null;
            let currentRequest = null;

            if (searchInput && filterForm && assetsTableBody) {
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
                    if (assetsTable) assetsTable.style.opacity = '0.5';
                    if (loadingIndicator) loadingIndicator.classList.remove('hidden');

                    // Create abort controller for request cancellation
                    const abortController = new AbortController();
                    currentRequest = abortController;

                    // Fetch results via AJAX
                    fetch('{{ route("itdept.manage-assets.index") }}?' + searchParams.toString(), {
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
                        const newTableBody = doc.querySelector('#assetsTableBody');
                        const newPagination = doc.querySelector('.pagination-container') || doc.querySelector('.mt-4');
                        
                        if (newTableBody) {
                            // Update table body
                            assetsTableBody.innerHTML = newTableBody.innerHTML;
                            
                            // Update pagination if exists
                            const paginationContainer = document.querySelector('.pagination-container') || document.querySelector('.mt-4');
                            if (paginationContainer && newPagination) {
                                paginationContainer.innerHTML = newPagination.innerHTML;
                            }
                            
                            // Update URL without reload
                            const newUrl = '{{ route("itdept.manage-assets.index") }}?' + searchParams.toString();
                            window.history.pushState({}, '', newUrl);
                            
                            // Re-initialize check-in and delete button handlers for new rows
                            initializeCheckinHandlers();
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
                        if (assetsTable) assetsTable.style.opacity = '1';
                        if (loadingIndicator) loadingIndicator.classList.add('hidden');
                        currentRequest = null;
                    });
                }
            }

            // Import progress polling
            const progressId = new URLSearchParams(window.location.search).get('progressId');
            if (progressId) {
                const progressBar = document.getElementById('importProgressBar');
                const progressBarFill = document.getElementById('progressBarFill');
                const progressText = document.getElementById('progressText');
                const progressCount = document.getElementById('progressCount');
                const statusMessage = document.getElementById('importStatusMessage');
                const statusMessageText = document.getElementById('statusMessageText');

                if (progressBar && progressBarFill && progressText && progressCount && statusMessage && statusMessageText) {
                    progressBar.classList.remove('hidden');
                    window.importStartTime = Date.now();

                    let pollInterval = setInterval(function() {
                        fetch('{{ route("itdept.manage-assets.import.progress") }}?progressId=' + progressId)
                            .then(response => response.json())
                            .then(data => {
                                const total = data.total || 0;
                                const processed = data.processed || 0;
                                const created = data.created || 0;
                                const pendingJobs = data.pendingJobs || 0;
                                const percentage = total > 0 ? Math.round((processed / total) * 100) : 0;

                                // Smooth progress bar update
                                progressBarFill.style.width = percentage + '%';
                                
                                // Update counter with current values (increments one by one)
                                progressCount.textContent = processed + ' / ' + total;
                                
                                // Show more detailed progress info
                                const remaining = total - processed;
                                let statusText = 'Processing import... (' + created + ' asset(s) added)';
                                if (remaining > 0) {
                                    statusText += ' - ' + remaining + ' remaining';
                                }
                                if (pendingJobs > 0) {
                                    statusText += ' - ' + pendingJobs + ' job(s) pending in queue';
                                }
                                progressText.textContent = statusText;

                                // If no progress after 5 seconds and there are pending jobs, show warning
                                if (processed === 0 && total > 0 && pendingJobs > 0) {
                                    const elapsed = Date.now() - (window.importStartTime || Date.now());
                                    if (elapsed > 5000) {
                                        progressText.textContent = 'Waiting for queue worker to process jobs... (' + pendingJobs + ' pending)';
                                    }
                                }

                                if (data.isComplete) {
                                    clearInterval(pollInterval);
                                    progressBar.classList.add('hidden');

                                    if (data.message) {
                                        statusMessageText.textContent = data.message;
                                        statusMessage.classList.remove('hidden');

                                        // Redirect after 3 seconds if redirectAssetType is provided
                                        if (data.redirectAssetType) {
                                            setTimeout(function() {
                                                const currentUrl = new URL(window.location.href);
                                                currentUrl.searchParams.delete('progressId');
                                                currentUrl.searchParams.set('assetType', data.redirectAssetType);
                                                window.location.href = currentUrl.toString();
                                            }, 3000);
                                        } else {
                                            // Remove progressId from URL after 3 seconds
                                            setTimeout(function() {
                                                const currentUrl = new URL(window.location.href);
                                                currentUrl.searchParams.delete('progressId');
                                                window.history.replaceState({}, '', currentUrl.toString());
                                            }, 3000);
                                        }
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Progress check error:', error);
                            });
                    }, 500); // Poll every 500ms for smoother updates
                }
            }
        });
    </script>
</x-app-layout>