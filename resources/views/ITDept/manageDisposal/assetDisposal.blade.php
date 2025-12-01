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
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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

                            <input type="text" name="q"
                                   value="{{ $search }}"
                                   placeholder="Search Serial, Asset ID, Model..."
                                   class="flex-1 min-w-[200px] rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                   x-on:input="clearTimeout(window.searchTimer); window.searchTimer=setTimeout(()=> document.getElementById('filterForm').submit(), 500)">
                            
                            <select name="assetType"
                                    class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    onchange="this.form.submit()">
                                <option value="">Filter Asset Type</option>
                                <option value="Laptop" {{ $assetType == 'Laptop' ? 'selected':'' }}>Laptop</option>
                                <option value="Desktop" {{ $assetType == 'Desktop' ? 'selected':'' }}>Desktop</option>
                            </select>
                        </form>

                        {{-- Dispose Button --}}
                        @if ($tab==='pending')
                        <button type="button"
                                x-bind:disabled="selectedAssets.length === 0"
                                x-on:click="
                                    if (selectedAssets.length === 0) return;
                                    const fileInput = document.getElementById('disposalInvoiceFile');
                                    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                                        alert('Please select a disposal invoice file before disposing assets.');
                                        return;
                                    }
                                    if (!confirm('Dispose ' + selectedAssets.length + ' asset(s)?')) return;

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
                                "
                                x-bind:class="selectedAssets.length === 0 
                                    ? 'inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed opacity-50' 
                                    : 'inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90 bg-red-600 hover:bg-red-700' "
                                x-bind:style="selectedAssets.length === 0 ? 'background-color: #B2B2B2;' : ''">
                            Dispose
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
                        <div>
                            <x-input-label for="disposalInvoiceFile" :value="__('Disposal Invoice File')" />
                            <input type="file" 
                                   id="disposalInvoiceFile" 
                                   name="disposalInvoiceFile" 
                                   form="disposeForm"
                                   accept=".pdf,.jpg,.jpeg,.png" 
                                   class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                   border border-gray-300 dark:border-gray-700 rounded-md
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-md file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-blue-50 dark:file:bg-blue-900
                                   file:text-blue-700 dark:file:text-blue-300
                                   hover:file:bg-blue-100 dark:hover:file:bg-blue-800
                                   cursor-pointer"
                                   required />
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
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full border border-gray-300 dark:border-gray-700 divide-y">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    @if($tab==='pending')
                                    <th class="px-4 py-4 text-center">
                                        <input id="selectAll" type="checkbox" 
                                               x-on:change="toggleAll($event)"
                                               class="rounded border-gray-300">
                                    </th>
                                    @endif

                                    <th class="px-8 py-4">Asset ID</th>
                                    <th class="px-8 py-4">Serial Number</th>
                                    <th class="px-8 py-4">Model</th>
                                    <th class="px-8 py-4">Processor</th>

                                    @if($tab==='disposed')
                                    <th class="px-8 py-4">Disposal Date</th>
                                    <th class="px-8 py-4">Action</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody class="divide-y dark:divide-gray-700">
                                @forelse($assets as $asset)
                                    <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-800/40' : 'bg-white dark:bg-gray-800' }}">

                                        @if($tab==='pending')
                                        <td class="px-4 py-4 text-center">
                                            <input type="checkbox"
                                                   value="{{ $asset->assetID }}"
                                                   class="asset-checkbox rounded border-gray-300"
                                                   x-on:change="toggleAsset('{{ $asset->assetID }}', $event.target.checked)">
                                        </td>
                                        @endif

                                        <td class="px-8 py-4">{{ $asset->assetID }}</td>
                                        <td class="px-8 py-4">{{ $asset->serialNum ?? '-' }}</td>
                                        <td class="px-8 py-4">{{ $asset->model ?? '-' }}</td>
                                        <td class="px-8 py-4">{{ $asset->processor ?? '-' }}</td>

                                        @if($tab==='disposed')
                                        <td class="px-8 py-4">
                                            {{ optional($asset->disposals->first())->dispDate?->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td class="px-8 py-4">
                                            @php($disposal = $asset->disposals->first())
                                            @if($disposal && $disposal->invoice)
                                                <a href="{{ route('itdept.asset-disposal.download-invoice', $disposal->disposeID) }}" 
                                                   class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold uppercase tracking-widest rounded-md border transition"
                                                   style="border-color: #4BA9C2; color: #4BA9C2; background-color: white;"
                                                   onmouseover="this.style.backgroundColor='#f0f9ff'"
                                                   onmouseout="this.style.backgroundColor='white'"
                                                   title="{{ __('Download Invoice') }}">
                                                    Download Invoice
                                                </a>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 text-sm">No invoice</span>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-8 py-6 text-center text-gray-500">
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
</x-app-layout>
