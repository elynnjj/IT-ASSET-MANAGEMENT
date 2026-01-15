<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Asset Disposal') }}
        </h2>
    </x-slot>

    <div class="py-12" 
        x-data="{
            selectedAssets: [],

            toggleAll(event) {
                const isChecked = event.target.checked;
                const checkboxes = [...document.querySelectorAll('.asset-checkbox')];
                this.selectedAssets = isChecked ? checkboxes.map(cb => cb.value) : [];
                checkboxes.forEach(cb => cb.checked = isChecked);
                this.updateSelectAllCheckbox();
            },

            toggleAsset(assetID, checked) {
                if (checked) {
                    if (!this.selectedAssets.includes(assetID)) {
                        this.selectedAssets.push(assetID);
                    }
                } else {
                    this.selectedAssets = this.selectedAssets.filter(id => id !== assetID);
                }
                this.updateSelectAllCheckbox();
            },

            updateSelectAllCheckbox() {
                const checkboxes = [...document.querySelectorAll('.asset-checkbox')];
                const selectAll = document.querySelector('#selectAll');

                if (!selectAll || checkboxes.length === 0) return;

                const checkedCount = checkboxes.filter(cb => cb.checked).length;
                const allChecked = checkedCount === checkboxes.length;
                const someChecked = checkedCount > 0 && checkedCount < checkboxes.length;

                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked;
            }
        }">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Status Messages --}}
                    @if (session('status'))
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

                    @if ($errors->any())
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                             class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-red-700 dark:text-red-300 font-medium">
                                        {{ $errors->first() }}
                                    </p>
                                </div>
                                <button @click="show = false" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Tabs --}}
                    <div class="flex border-b mb-6" style="border-color: #4BA9C2;">
                        <a href="{{ route('itdept.asset-disposal', ['tab' => 'pending'] + request()->query()) }}"
                           class="flex-1 text-center py-2 font-medium {{ $tab==='pending' ? 'border-b-2' : '' }}"
                           style="{{ $tab==='pending' ? 'color:#4BA9C2;border-bottom-color:#4BA9C2;' : 'color:#6B7280;' }}">
                            Pending Disposal
                        </a>

                        <a href="{{ route('itdept.asset-disposal', ['tab' => 'disposed'] + request()->query()) }}"
                           class="flex-1 text-center py-2 font-medium {{ $tab==='disposed' ? 'border-b-2' : '' }}"
                           style="{{ $tab==='disposed' ? 'color:#4BA9C2;border-bottom-color:#4BA9C2;' : 'color:#6B7280;' }}">
                            Disposed Asset
                        </a>
                    </div>

                    {{-- Search + Filter + Dispose --}}
                    <div class="mb-6 flex flex-wrap items-center gap-2">

                        <form method="GET" id="filterForm" class="flex flex-wrap items-center gap-2 flex-1">
                            <input type="hidden" name="tab" value="{{ $tab }}">

                            <div class="input-container flex-1 min-w-[200px]">
                                <input type="text" id="searchInput" name="q"
                                       value="{{ $search }}"
                                       placeholder="Search Serial, Asset ID, Model..."
                                       class="interactive-input w-full"
                                       style="padding: 8px 12px; font-size: 13px;"
                                       autocomplete="off" />
                            </div>
                            
                            <div class="input-container">
                                <select name="assetType" id="assetTypeSelect"
                                        class="interactive-select"
                                        style="padding: 8px 32px 8px 12px; font-size: 13px; min-width: 150px;">
                                    <option value="">Filter Asset Type</option>
                                    <option value="Laptop" {{ $assetType == 'Laptop' ? 'selected':'' }}>Laptop</option>
                                    <option value="Desktop" {{ $assetType == 'Desktop' ? 'selected':'' }}>Desktop</option>
                                </select>
                            </div>
                        </form>

                        {{-- Dispose Button --}}
                        @if ($tab==='pending')
                        <button type="button"
                                x-bind:disabled="selectedAssets.length === 0"
                                x-on:click="
                                    (async () => {
                                        if (selectedAssets.length === 0) return;
                                        const fileInput = document.getElementById('disposalInvoiceFile');
                                        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                                            window.showAlert('Please select a disposal invoice file before disposing assets.', 'warning');
                                            return;
                                        }
                                        
                                        const confirmed = await window.showConfirmation(
                                            'Dispose ' + selectedAssets.length + ' asset(s)?',
                                            'Dispose Assets'
                                        );
                                        
                                        if (!confirmed) return;

                                        const form = document.getElementById('disposeForm');
                                        // Remove only the selectedAssets inputs, preserve CSRF token
                                        const existingInputs = form.querySelectorAll('input[name=\'selectedAssets[]\']');
                                        existingInputs.forEach(input => input.remove());
                                        
                                        // Add selected assets
                                        selectedAssets.forEach(id => {
                                            let input = document.createElement('input');
                                            input.type='hidden';
                                            input.name='selectedAssets[]';
                                            input.value=id;
                                            form.appendChild(input);
                                        });
                                        form.submit();
                                    })();
                                "
                                x-bind:class="selectedAssets.length === 0 
                                    ? 'interactive-button interactive-button-secondary opacity-50 cursor-not-allowed' 
                                    : 'interactive-button interactive-button-delete'"
                                style="padding: 10px 16px; font-size: 11px;">
                            <span class="button-content">
                                Dispose
                            </span>
                        </button>
                        @endif
                    </div>

                    {{-- Hidden File Upload Section --}}
                    @if ($tab==='pending')
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md"
                         x-show="selectedAssets.length > 0"
                         x-transition
                         style="display: none;">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Disposal Invoice') }}</h3>
                        <div class="input-container">
                            <x-input-label for="disposalInvoiceFile" :value="__('Disposal Invoice File')" class="text-[15px]" />
                            <div class="custom-file-input-wrapper mt-1">
                                <input type="file" 
                                       id="disposalInvoiceFile" 
                                       name="disposalInvoiceFile" 
                                       form="disposeForm"
                                       accept=".pdf,.jpg,.jpeg,.png" 
                                       class="hidden-file-input"
                                       required />
                                <button type="button" class="file-select-button" onclick="document.getElementById('disposalInvoiceFile').click()">
                                    {{ __('Choose File') }}
                                </button>
                                <div class="file-display-area" id="disposalFileDisplay">
                                    <span class="file-placeholder">{{ __('No file chosen') }}</span>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Accepted formats: PDF, JPG, JPEG, PNG (Max: 10MB)</p>
                            <x-input-error :messages="$errors->get('disposalInvoiceFile')" class="mt-2" />
                        </div>
                    </div>
                    @endif

                    {{-- Hidden Form --}}
                    <form id="disposeForm" method="POST" action="{{ route('itdept.asset-disposal.bulk-dispose') }}" enctype="multipart/form-data">
                        @csrf
                    </form>

                    {{-- Table --}}
                    <div class="overflow-x-auto" id="disposalTableContainer">
                        <div id="loadingIndicator" class="hidden text-center py-4 text-gray-500 dark:text-gray-400">
                            <p>Searching...</p>
                        </div>
                        <table class="table-auto w-full border border-gray-300 dark:border-gray-700 divide-y" id="disposalTable">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    @if($tab==='pending')
                                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        <input id="selectAll" type="checkbox" 
                                               x-on:change="toggleAll($event)"
                                               class="rounded border-gray-300">
                                    </th>
                                    @endif

                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Asset ID</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Serial Number</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Model</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Processor</th>

                                    @if($tab==='disposed')
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Disposal Date</th>
                                    <th class="px-3 py-2 text-center text-sm font-semibold text-gray-700 dark:text-gray-200" style="width: 20%;">Action</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody id="disposalTableBody" class="divide-y dark:divide-gray-700">
                                @forelse($assets as $asset)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-800' }}">

                                        @if($tab==='pending')
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox"
                                                   value="{{ $asset->assetID }}"
                                                   class="asset-checkbox rounded border-gray-300"
                                                   x-on:change="toggleAsset('{{ $asset->assetID }}', $event.target.checked)">
                                        </td>
                                        @endif

                                        <td class="px-4 py-2 text-sm">{{ $asset->assetID }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $asset->serialNum ?? '-' }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $asset->model ?? '-' }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $asset->processor ?? '-' }}</td>

                                        @if($tab==='disposed')
                                        <td class="px-4 py-2 text-sm">
                                            {{ optional($asset->disposals->first())->dispDate?->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td class="px-3 py-2">
                                            @php($disposal = $asset->disposals->first())
                                            @if($disposal && $disposal->invoice)
                                                <a href="{{ route('itdept.asset-disposal.download-invoice', $disposal->disposeID) }}" 
                                                   class="interactive-button interactive-button-primary"
                                                   style="padding: 6px 12px; font-size: 11px;"
                                                   title="{{ __('Download Invoice') }}">
                                                    <span class="button-content">
                                                        Download Invoice
                                                    </span>
                                                </a>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 text-sm">No invoice</span>
                                            @endif
                                        </td>
                                        @endif
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
		.input-container:has(.interactive-select:focus),
		.input-container:has(.hidden-file-input:focus) {
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

		/* Custom file input wrapper - side by side layout */
		.custom-file-input-wrapper {
			display: flex;
			width: 100%;
			gap: 0;
			border: 2px solid #9CA3AF;
			border-radius: 8px;
			overflow: hidden;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			background-color: #FFFFFF;
		}

		@media (prefers-color-scheme: dark) {
			.custom-file-input-wrapper {
				background-color: #111827;
				border-color: #6B7280;
			}
		}

		.dark .custom-file-input-wrapper {
			background-color: #111827;
			border-color: #6B7280;
		}

		.custom-file-input-wrapper:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.15);
		}

		@media (prefers-color-scheme: dark) {
			.custom-file-input-wrapper:hover {
				border-color: #4BA9C2;
				box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
			}
		}

		.dark .custom-file-input-wrapper:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
		}

		.custom-file-input-wrapper:has(.hidden-file-input:focus) {
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.15), 0 6px 16px rgba(75, 169, 194, 0.2);
		}

		.hidden-file-input {
			position: absolute;
			opacity: 0;
			width: 0;
			height: 0;
			pointer-events: none;
		}

		.file-select-button {
			padding: 8px 20px;
			border: none;
			border-right: 2px solid #9CA3AF;
			border-radius: 0;
			background: linear-gradient(135deg, #4BA9C2 0%, #3a8ba5 100%);
			color: white;
			font-size: 15px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			white-space: nowrap;
			flex-shrink: 0;
		}

		@media (prefers-color-scheme: dark) {
			.file-select-button {
				border-right-color: #6B7280;
			}
		}

		.dark .file-select-button {
			border-right-color: #6B7280;
		}

		.file-select-button:hover {
			background: linear-gradient(135deg, #3a8ba5 0%, #2d6b82 100%);
			box-shadow: 0 4px 8px rgba(75, 169, 194, 0.3);
		}

		.file-select-button:active {
			background: linear-gradient(135deg, #2d6b82 0%, #1f5a6f 100%);
			transform: scale(0.98);
		}

		.file-display-area {
			flex: 1;
			padding: 8px 12px;
			display: flex;
			align-items: center;
			background-color: #FFFFFF;
			color: #374151;
			font-size: 15px;
		}

		@media (prefers-color-scheme: dark) {
			.file-display-area {
				background-color: #111827;
				color: #D1D5DB;
			}
		}

		.dark .file-display-area {
			background-color: #111827;
			color: #D1D5DB;
		}

		.file-placeholder {
			color: #9CA3AF;
		}

		.file-name {
			color: #374151;
			font-weight: 500;
		}

		.dark .file-name {
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
			const disposalTableBody = document.getElementById('disposalTableBody');
			const disposalTableContainer = document.getElementById('disposalTableContainer');
			const loadingIndicator = document.getElementById('loadingIndicator');
			const disposalTable = document.getElementById('disposalTable');
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

				// Show loading indicator
				if (disposalTable) disposalTable.style.opacity = '0.5';
				if (loadingIndicator) loadingIndicator.classList.remove('hidden');

				// Create abort controller for request cancellation
				const abortController = new AbortController();
				currentRequest = abortController;

				// Fetch results via AJAX
				fetch('{{ route("itdept.asset-disposal") }}?' + searchParams.toString(), {
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
					const newTableBody = doc.querySelector('#disposalTableBody');
					
					if (newTableBody) {
						// Update table body
						disposalTableBody.innerHTML = newTableBody.innerHTML;
						
						// Update URL without reload
						const newUrl = '{{ route("itdept.asset-disposal") }}?' + searchParams.toString();
						window.history.pushState({}, '', newUrl);
						
						// Re-initialize Alpine.js data if needed
						// Note: Alpine.js should handle the x-data automatically, but we may need to re-trigger
						if (window.Alpine) {
							window.Alpine.initTree(disposalTableContainer);
						}
					}
				})
				.catch(error => {
					if (error.name !== 'AbortError') {
						console.error('Search error:', error);
					}
				})
				.finally(() => {
					// Hide loading indicator
					if (disposalTable) disposalTable.style.opacity = '1';
					if (loadingIndicator) loadingIndicator.classList.add('hidden');
					currentRequest = null;
				});
			}

			// Search input event listener
			if (searchInput && filterForm && disposalTableBody) {
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

			// File input display handler
			const disposalFileInput = document.getElementById('disposalInvoiceFile');
			const disposalFileDisplay = document.getElementById('disposalFileDisplay');

			if (disposalFileInput && disposalFileDisplay) {
				disposalFileInput.addEventListener('change', function(e) {
					const file = e.target.files[0];
					if (file) {
						disposalFileDisplay.innerHTML = '<span class="file-name">' + file.name + '</span>';
					} else {
						disposalFileDisplay.innerHTML = '<span class="file-placeholder">{{ __('No file chosen') }}</span>';
					}
				});
			}
		});
	</script>
</x-app-layout>
