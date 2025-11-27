<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Asset Details') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			{{-- Breadcrumb --}}
			<div class="mb-6">
				<a href="{{ route('itdept.manage-assets.index', ['assetType' => $asset->assetType]) }}" 
				   style="color: #4BA9C2;"
				   class="hover:opacity-80">
					← Assets
				</a>
				<span class="text-gray-600 dark:text-gray-400"> > </span>
				<span class="text-gray-600 dark:text-gray-400">{{ $asset->assetID }}</span>
			</div>

			{{-- Top Section: Asset Details --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
				<div class="p-6">
					<div class="flex justify-between items-start gap-6">
						{{-- Left Section: Asset Details --}}
						<div class="flex-1">
							{{-- Title and Service Tag --}}
							<div class="mb-6">
								<div class="flex items-baseline">
									<h1 class="text-xl font-semibold" style="color: #4BA9C2;">{{ $asset->assetID }}</h1>
									@if($asset->serialNum)
										<span class="text-gray-600 dark:text-gray-400 text-xs ml-3">Service Tag: {{ $asset->serialNum }}</span>
									@endif
								</div>
							</div>

							{{-- Asset Information Section --}}
							<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
								<h3 class="text-lg font-semibold mb-4">{{ __('Asset Information') }}</h3>
								<div class="space-y-6">
								{{-- Row 1: Model and Processor --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="border border-gray-200 dark:border-gray-700 rounded-md p-3 bg-gray-50 dark:bg-gray-900/50">
										<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Model') }}</label>
										<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $asset->model ?? '-' }}</p>
									</div>
									<div class="border border-gray-200 dark:border-gray-700 rounded-md p-3 bg-gray-50 dark:bg-gray-900/50">
										<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Processor') }}</label>
										<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $asset->processor ?? '-' }}</p>
									</div>
								</div>

								{{-- Row 2: RAM and Storage --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="border border-gray-200 dark:border-gray-700 rounded-md p-3 bg-gray-50 dark:bg-gray-900/50">
										<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('RAM') }}</label>
										<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $asset->ram ?? '-' }}</p>
									</div>
									<div class="border border-gray-200 dark:border-gray-700 rounded-md p-3 bg-gray-50 dark:bg-gray-900/50">
										<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Storage') }}</label>
										<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $asset->storage ?? '-' }}</p>
									</div>
								</div>

								{{-- Row 3: Purchase Date and OS Version --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="border border-gray-200 dark:border-gray-700 rounded-md p-3 bg-gray-50 dark:bg-gray-900/50">
										<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Purchase Date') }}</label>
										<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $asset->purchaseDate ? $asset->purchaseDate->format('d/m/Y') : '-' }}</p>
									</div>
									<div class="border border-gray-200 dark:border-gray-700 rounded-md p-3 bg-gray-50 dark:bg-gray-900/50">
										<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('OS Version') }}</label>
										<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $asset->osVer ?? '-' }}</p>
									</div>
								</div>

								{{-- Row 4: Status and Current User --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="border border-gray-200 dark:border-gray-700 rounded-md p-3 bg-gray-50 dark:bg-gray-900/50">
										<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Status') }}</label>
										<p class="text-sm">
											<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
												{{ $asset->status === 'Available' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
												{{ $asset->status ?? 'Available' }}
											</span>
										</p>
									</div>
									<div class="border border-gray-200 dark:border-gray-700 rounded-md p-3 bg-gray-50 dark:bg-gray-900/50">
										<label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Current User') }}</label>
										<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">
											@if($currentAssignment)
												{{ $currentAssignment->user->fullName }}
											@else
												With IT
											@endif
										</p>
									</div>
								</div>

								{{-- Row 5: Installed Software (Full Width) --}}
								<div class="border border-gray-200 dark:border-gray-700 rounded-md p-3 bg-gray-50 dark:bg-gray-900/50">
									<div class="flex items-center justify-between mb-1">
										<label class="block text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Installed Software') }}</label>
										<a href="{{ route('itdept.manage-assets.installed-software', $asset->assetID) }}" 
										   class="inline-flex items-center justify-center w-5 h-5 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 transition"
										   title="{{ __('Add/Edit Installed Software') }}">
											<svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
											</svg>
										</a>
									</div>
									<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $asset->installedSoftware ?? '-' }}</p>
								</div>
								</div>
							</div>
						</div>

						{{-- Right Section: Action Buttons --}}
						<div class="flex flex-col gap-2 flex-shrink-0">
							{{-- Check-Out Button --}}
							@if($asset->status !== 'Checked Out')
							<a href="{{ route('itdept.manage-assets.checkout', $asset->assetID) }}" 
							   class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90 w-full"
							   style="background-color: #4BA9C2;"
							   onmouseover="this.style.backgroundColor='#3a8ba5'"
							   onmouseout="this.style.backgroundColor='#4BA9C2'">
								{{ __('Check-Out') }}
							</a>
							@else
							<button type="button" disabled
								class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed opacity-50 w-full"
								style="background-color: #B2B2B2;">
								{{ __('Check-Out') }}
							</button>
							@endif

							{{-- Check-In Button --}}
							@if($asset->status === 'Checked Out' && $currentAssignment)
							<form action="{{ route('itdept.manage-assets.checkin', $asset->assetID) }}" method="POST" class="inline w-full">
								@csrf
								@method('PATCH')
								<button type="submit" 
									class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90 w-full"
									style="background-color: #4BA9C2;"
									onmouseover="this.style.backgroundColor='#3a8ba5'"
									onmouseout="this.style.backgroundColor='#4BA9C2'">
									{{ __('Check-In') }}
								</button>
							</form>
							@else
							<button type="button" disabled
								class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed opacity-50 w-full"
								style="background-color: #B2B2B2;">
								{{ __('Check-In') }}
							</button>
							@endif

							{{-- Invoice Button --}}
							@if($asset->invoice)
							<a href="{{ route('itdept.manage-assets.invoice.download', $asset->invoice->invoiceID) }}" 
							   target="_blank"
							   class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none transition ease-in-out duration-150 w-full">
								{{ __('Invoice') }}
							</a>
							@else
							<button type="button" disabled
								class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed opacity-50 w-full"
								style="background-color: #B2B2B2;">
								{{ __('Invoice') }}
							</button>
							@endif

							{{-- Dispose Asset Button --}}
							@if($asset->status !== 'Disposed')
							<form action="{{ route('itdept.manage-assets.dispose', $asset->assetID) }}" method="POST" class="inline w-full">
								@csrf
								@method('PATCH')
								<button type="submit" 
									class="inline-flex items-center justify-center px-4 py-2 bg-red-600 dark:bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-600 focus:bg-red-700 dark:focus:bg-red-600 active:bg-red-900 dark:active:bg-red-700 focus:outline-none transition ease-in-out duration-150 w-full">
									{{ __('Dispose Asset') }}
								</button>
							</form>
							@else
							<button type="button" disabled
								class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed opacity-50 w-full"
								style="background-color: #B2B2B2;">
								{{ __('Dispose Asset') }}
							</button>
							@endif
						</div>
					</div>
				</div>
			</div>

			{{-- Bottom Section: Tabs --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" x-data="{ activeTab: 'previousUsers' }">
				{{-- Tab Navigation --}}
				<div class="border-b border-gray-200 dark:border-gray-700">
					<nav class="flex -mb-px">
						<button @click="activeTab = 'previousUsers'" 
							:class="activeTab === 'previousUsers' ? 'border-b-2 font-medium' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
							:style="activeTab === 'previousUsers' ? 'border-color: #4BA9C2; color: #4BA9C2;' : ''"
							class="whitespace-nowrap py-4 px-6 text-sm transition">
							{{ __('Previous Users') }}
						</button>
						<button @click="activeTab = 'itRequestHistory'" 
							:class="activeTab === 'itRequestHistory' ? 'border-b-2 font-medium' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
							:style="activeTab === 'itRequestHistory' ? 'border-color: #4BA9C2; color: #4BA9C2;' : ''"
							class="whitespace-nowrap py-4 px-6 text-sm transition">
							{{ __('IT Request History') }}
						</button>
						<button @click="activeTab = 'maintenanceHistory'" 
							:class="activeTab === 'maintenanceHistory' ? 'border-b-2 font-medium' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
							:style="activeTab === 'maintenanceHistory' ? 'border-color: #4BA9C2; color: #4BA9C2;' : ''"
							class="whitespace-nowrap py-4 px-6 text-sm transition">
							{{ __('Maintenance History') }}
						</button>
					</nav>
				</div>

				{{-- Tab Content --}}
				<div class="p-6">
					{{-- Previous Users Tab --}}
					<div x-show="activeTab === 'previousUsers'" x-transition>
						@if($previousAssignments->count() > 0)
						<div class="overflow-x-auto">
							<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
								<thead class="bg-gray-50 dark:bg-gray-700">
									<tr>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Previous Users') }}</th>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Department') }}</th>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Date Check-Out From IT') }}</th>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Date Check-In to IT') }}</th>
									</tr>
								</thead>
								<tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
									@foreach($previousAssignments as $assignment)
									<tr>
										<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
											{{ $assignment->user->fullName }}
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
											{{ $assignment->user->department ?? '-' }}
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
											{{ $assignment->checkoutDate->format('d/m/Y') }}
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
											{{ $assignment->checkinDate->format('d/m/Y') }}
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						@else
						<p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('No previous users found.') }}</p>
						@endif
					</div>

					{{-- IT Request History Tab --}}
					<div x-show="activeTab === 'itRequestHistory'" x-transition style="display: none;">
						<p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('IT Request History will be displayed here.') }}</p>
					</div>

					{{-- Maintenance History Tab --}}
					<div x-show="activeTab === 'maintenanceHistory'" x-transition style="display: none;">
						<p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('Maintenance History will be displayed here.') }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

