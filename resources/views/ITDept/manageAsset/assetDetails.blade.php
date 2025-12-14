<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Asset Details') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<style>
			/* Interactive button styling for icon button */
			.interactive-button-icon {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				width: 28px;
				height: 28px;
				padding: 0;
				font-weight: 600;
				border: none;
				border-radius: 6px;
				cursor: pointer;
				transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
				position: relative;
				overflow: hidden;
				text-decoration: none;
				background: linear-gradient(135deg, #4BA9C2 0%, #3a8ba5 100%);
				color: white;
				box-shadow: 0 2px 8px rgba(75, 169, 194, 0.3);
			}

			.interactive-button-icon::before {
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

			.interactive-button-icon:hover {
				background: linear-gradient(135deg, #3a8ba5 0%, #2d6b82 100%);
				box-shadow: 0 4px 12px rgba(75, 169, 194, 0.5);
				transform: translateY(-2px) scale(1.1);
			}

			.interactive-button-icon:active::before {
				width: 200px;
				height: 200px;
			}

			.interactive-button-icon:active {
				background: linear-gradient(135deg, #2d6b82 0%, #1f5a6f 100%);
				transform: translateY(0) scale(0.95);
				box-shadow: 0 1px 4px rgba(75, 169, 194, 0.3);
			}

			.interactive-button-icon svg {
				width: 16px;
				height: 16px;
				position: relative;
				z-index: 1;
			}

			/* Dark mode support */
			.dark .interactive-button-icon {
				box-shadow: 0 2px 8px rgba(75, 169, 194, 0.4);
			}

			.dark .interactive-button-icon:hover {
				box-shadow: 0 4px 12px rgba(75, 169, 194, 0.6);
			}

			/* Small interactive button for View Details */
			.interactive-button-small {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				padding: 6px 12px;
				font-weight: 600;
				font-size: 11px;
				border: none;
				border-radius: 6px;
				cursor: pointer;
				transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
				position: relative;
				overflow: hidden;
				text-decoration: none;
				background: linear-gradient(135deg, #4BA9C2 0%, #3a8ba5 100%);
				color: white;
				box-shadow: 0 2px 6px rgba(75, 169, 194, 0.3);
			}

			.interactive-button-small::before {
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

			.interactive-button-small:hover {
				background: linear-gradient(135deg, #3a8ba5 0%, #2d6b82 100%);
				box-shadow: 0 4px 10px rgba(75, 169, 194, 0.5);
				transform: translateY(-1px) scale(1.02);
			}

			.interactive-button-small:active::before {
				width: 200px;
				height: 200px;
			}

			.interactive-button-small:active {
				background: linear-gradient(135deg, #2d6b82 0%, #1f5a6f 100%);
				transform: translateY(0) scale(0.98);
				box-shadow: 0 1px 4px rgba(75, 169, 194, 0.3);
			}

			/* Dark mode support */
			.dark .interactive-button-small {
				box-shadow: 0 2px 6px rgba(75, 169, 194, 0.4);
			}

			.dark .interactive-button-small:hover {
				box-shadow: 0 4px 10px rgba(75, 169, 194, 0.6);
			}
		</style>
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
										<span class="text-gray-600 dark:text-gray-400 text-sm ml-3">Service Tag: {{ $asset->serialNum }}</span>
									@endif
								</div>
							</div>

							{{-- Asset Information Section --}}
							<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
								<h3 class="text-lg font-semibold mb-4">{{ __('Asset Information') }}</h3>
								<div class="space-y-3">
								{{-- Row 1: Model and Processor --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="grid gap-0 p-3 bg-white dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700" style="grid-template-columns: 30% 70%;">
										<div class="flex items-center">
											<label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Model') }}</label>
										</div>
										<div class="flex items-center border-l border-gray-300 dark:border-gray-600 pl-3">
											<p class="text-sm font-bold text-black dark:text-gray-300">{{ $asset->model ?? '-' }}</p>
										</div>
									</div>
									<div class="grid gap-0 p-3 bg-white dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700" style="grid-template-columns: 30% 70%;">
										<div class="flex items-center">
											<label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Processor') }}</label>
										</div>
										<div class="flex items-center border-l border-gray-300 dark:border-gray-600 pl-3">
											<p class="text-sm font-bold text-black dark:text-gray-300">{{ $asset->processor ?? '-' }}</p>
										</div>
									</div>
								</div>

								{{-- Row 2: RAM and Storage --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="grid gap-0 p-3 bg-white dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700" style="grid-template-columns: 30% 70%;">
										<div class="flex items-center">
											<label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('RAM') }}</label>
										</div>
										<div class="flex items-center border-l border-gray-300 dark:border-gray-600 pl-3">
											<p class="text-sm font-bold text-black dark:text-gray-300">{{ $asset->ram ?? '-' }}</p>
										</div>
									</div>
									<div class="grid gap-0 p-3 bg-white dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700" style="grid-template-columns: 30% 70%;">
										<div class="flex items-center">
											<label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Storage') }}</label>
										</div>
										<div class="flex items-center border-l border-gray-300 dark:border-gray-600 pl-3">
											<p class="text-sm font-bold text-black dark:text-gray-300">{{ $asset->storage ?? '-' }}</p>
										</div>
									</div>
								</div>

								{{-- Row 3: Purchase Date and OS Version --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="grid gap-0 p-3 bg-white dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700" style="grid-template-columns: 30% 70%;">
										<div class="flex items-center">
											<label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Purchase Date') }}</label>
										</div>
										<div class="flex items-center border-l border-gray-300 dark:border-gray-600 pl-3">
											<p class="text-sm font-bold text-black dark:text-gray-300">{{ $asset->purchaseDate ? $asset->purchaseDate->format('d/m/Y') : '-' }}</p>
										</div>
									</div>
									<div class="grid gap-0 p-3 bg-white dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700" style="grid-template-columns: 30% 70%;">
										<div class="flex items-center">
											<label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('OS Version') }}</label>
										</div>
										<div class="flex items-center border-l border-gray-300 dark:border-gray-600 pl-3">
											<p class="text-sm font-bold text-black dark:text-gray-300">{{ $asset->osVer ?? '-' }}</p>
										</div>
									</div>
								</div>

								{{-- Row 4: Status and Current User --}}
								<div class="grid grid-cols-2 gap-4">
									<div class="grid gap-0 p-3 bg-white dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700" style="grid-template-columns: 30% 70%;">
										<div class="flex items-center">
											<label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</label>
										</div>
										<div class="flex items-center border-l border-gray-300 dark:border-gray-600 pl-3">
											<p class="text-sm">
												<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
													{{ $asset->status === 'Available' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
													{{ $asset->status ?? 'Available' }}
												</span>
											</p>
										</div>
									</div>
									<div class="grid gap-0 p-3 bg-white dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700" style="grid-template-columns: 30% 70%;">
										<div class="flex items-center">
											<label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Current User') }}</label>
										</div>
										<div class="flex flex-col border-l border-gray-300 dark:border-gray-600 pl-3">
											@if($currentAssignment)
												<p class="text-sm font-bold text-black dark:text-gray-300">{{ $currentAssignment->user->fullName }}</p>
												@if($currentAssignment->user->department)
													<p class="text-sm font-bold text-black dark:text-gray-300 mt-1">({{ $currentAssignment->user->department }} Department)</p>
												@endif
											@else
												<p class="text-sm font-bold text-black dark:text-gray-300">With IT</p>
											@endif
										</div>
									</div>
								</div>

								{{-- Row 5: Installed Software (Full Width) --}}
								<div class="grid gap-0 p-3 bg-white dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700" style="grid-template-columns: 15% 85%;">
									<div class="flex items-center">
										<label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Installed Software') }}</label>
									</div>
									<div class="flex items-center justify-between border-l border-gray-300 dark:border-gray-600 pl-3">
										<p class="text-sm font-bold text-black dark:text-gray-300">{{ $asset->installedSoftware ?? '-' }}</p>
										<a href="{{ route('itdept.manage-assets.installed-software', $asset->assetID) }}" 
										   class="interactive-button-icon"
										   title="{{ __('Add/Edit Installed Software') }}">
											<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
											</svg>
										</a>
									</div>
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
									style="background-color: #1D9F26;"
									onmouseover="this.style.backgroundColor='#1A8F22'"
									onmouseout="this.style.backgroundColor='#1D9F26'">
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

							{{-- Asset Agreement Button --}}
							@if($asset->status === 'Checked Out' && $currentAssignment)
							<a href="{{ route('itdept.manage-assets.agreement', $asset->assetID) }}" 
							   target="_blank"
							   class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90 w-full"
							   style="background-color: #4BA9C2;"
							   onmouseover="this.style.backgroundColor='#3a8ba5'"
							   onmouseout="this.style.backgroundColor='#4BA9C2'">
								{{ __('Asset Agreement') }}
							</a>
							@else
							<button type="button" disabled
								class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed opacity-50 w-full"
								style="background-color: #B2B2B2;">
								{{ __('Asset Agreement') }}
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
							<form action="{{ route('itdept.manage-assets.dispose', $asset->assetID) }}" method="POST" class="inline w-full dispose-asset-form">
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
						@if($itRequests && $itRequests->count() > 0)
						<div class="overflow-x-auto">
							<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
								<thead class="bg-gray-50 dark:bg-gray-700">
									<tr>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Request Date') }}</th>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Requester Name') }}</th>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Request Title') }}</th>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Request Description') }}</th>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Action') }}</th>
									</tr>
								</thead>
								<tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
									@foreach($itRequests as $itRequest)
										<tr>
											<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
												{{ \Carbon\Carbon::parse($itRequest->requestDate)->format('d/m/Y') }}
											</td>
											<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
												{{ $itRequest->requester ? $itRequest->requester->fullName : 'N/A' }}
											</td>
											<td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
												{{ $itRequest->title }}
											</td>
											<td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
												{{ $itRequest->requestDesc }}
											</td>
											<td class="px-6 py-4 whitespace-nowrap text-sm">
												@php
													$statusColors = [
														'Pending' => 'text-yellow-600',
														'Approved' => 'text-green-600',
														'Rejected' => 'text-red-600',
														'Pending IT' => 'text-yellow-600',
														'Completed' => 'text-blue-600',
													];
													$statusColor = $statusColors[$itRequest->status] ?? 'text-gray-600';
												@endphp
												<span class="font-medium {{ $statusColor }}">
													{{ $itRequest->status }}
												</span>
											</td>
											<td class="px-6 py-4 whitespace-nowrap text-sm">
												<a href="{{ route('itdept.it-requests.show', $itRequest->requestID) }}" 
													class="interactive-button-small">
													{{ __('View Details') }}
												</a>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						@else
						<p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('No IT requests found for this asset.') }}</p>
						@endif
					</div>

					{{-- Maintenance History Tab --}}
					<div x-show="activeTab === 'maintenanceHistory'" x-transition style="display: none;">
						@if($maintenances && $maintenances->count() > 0)
						<div class="overflow-x-auto">
							<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
								<thead class="bg-gray-50 dark:bg-gray-700">
									<tr>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Maintenance Date') }}</th>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Maintenance Details') }}</th>
										<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Related Request') }}</th>
									</tr>
								</thead>
								<tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
									@foreach($maintenances as $maintenance)
										<tr>
											<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
												{{ \Carbon\Carbon::parse($maintenance->mainDate)->format('d/m/Y') }}
											</td>
											<td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
												{{ $maintenance->mainDesc }}
											</td>
											<td class="px-6 py-4 whitespace-nowrap text-sm">
												@if($maintenance->requestID)
													<a href="{{ route('itdept.it-requests.show', $maintenance->requestID) }}" 
														class="interactive-button-small w-fit">
														{{ __('View Details') }}
													</a>
												@else
													<span class="text-gray-500 dark:text-gray-400">{{ __('Manual Maintenance') }}</span>
												@endif
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						@else
						<p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('No maintenance records found for this asset.') }}</p>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Add confirmation for dispose asset form
			const disposeForm = document.querySelector('.dispose-asset-form');
			if (disposeForm) {
				disposeForm.addEventListener('submit', function(e) {
					if (!confirm('Are you sure you want to dispose this asset? This action cannot be undone.')) {
						e.preventDefault();
						return false;
					}
				});
			}
		});
	</script>
</x-app-layout>

