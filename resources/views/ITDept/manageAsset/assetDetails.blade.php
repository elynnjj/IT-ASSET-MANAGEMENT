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

		/* Full interactive button styling */
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

		.interactive-button-neutral {
			background: linear-gradient(135deg, #374151 0%, #1F2937 100%);
			color: white;
			box-shadow: 0 4px 12px rgba(55, 65, 81, 0.3);
		}

		.interactive-button-neutral::before {
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

		.interactive-button-neutral:hover {
			background: linear-gradient(135deg, #1F2937 0%, #111827 100%);
			box-shadow: 0 8px 20px rgba(55, 65, 81, 0.5);
			transform: translateY(-2px) scale(1.02);
		}

		.interactive-button-neutral:active::before {
			width: 300px;
			height: 300px;
		}

		.interactive-button-neutral:active {
			background: linear-gradient(135deg, #111827 0%, #030712 100%);
			transform: translateY(0) scale(0.98);
			box-shadow: 0 2px 8px rgba(55, 65, 81, 0.3);
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

		.dark .interactive-button-delete {
			box-shadow: 0 4px 12px rgba(180, 8, 20, 0.4);
		}

		.dark .interactive-button-delete:hover {
			box-shadow: 0 8px 20px rgba(180, 8, 20, 0.6);
		}

		.dark .interactive-button-neutral {
			background: linear-gradient(135deg, #E5E7EB 0%, #D1D5DB 100%);
			color: #111827;
			box-shadow: 0 4px 12px rgba(229, 231, 235, 0.3);
		}

		.dark .interactive-button-neutral:hover {
			background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%);
			box-shadow: 0 8px 20px rgba(229, 231, 235, 0.5);
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

			{{-- Status message --}}
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

			{{-- Error message popup --}}
			@if ($errors->has('asset'))
				<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
					 class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-lg">
					<div class="flex items-center justify-between">
						<div class="flex items-center">
							<svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
							</svg>
							<p class="text-red-700 dark:text-red-300 font-medium">
								{{ $errors->first('asset') }}
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
							<div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
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
												<p class="text-sm font-bold text-black dark:text-gray-300">{{ $currentAssignment->userFullName ?? ($currentAssignment->user->fullName ?? 'User Deleted') }}</p>
												@php
													$dept = $currentAssignment->userDepartment ?? ($currentAssignment->user->department ?? null);
												@endphp
												@if($dept)
													<p class="text-sm font-bold text-black dark:text-gray-300 mt-1">({{ $dept }} Department)</p>
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
							{{-- Edit Button --}}
							<a href="{{ route('itdept.manage-assets.edit', $asset->assetID) }}" 
							   class="interactive-button interactive-button-secondary w-full"
							   style="padding: 10px 16px; font-size: 11px;">
								<span class="button-content">
									{{ __('Edit') }}
								</span>
							</a>

							{{-- Asset Agreement Button --}}
							@if($asset->status === 'Checked Out' && $currentAssignment)
							<a href="{{ route('itdept.manage-assets.agreement.view', $asset->assetID) }}" 
							   target="_blank"
							   class="interactive-button interactive-button-primary w-full"
							   style="padding: 10px 16px; font-size: 11px;">
								<span class="button-content">
									{{ __('Asset Agreement') }}
								</span>
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
							<a href="{{ route('itdept.manage-assets.invoice.view', $asset->invoice->invoiceID) }}" 
							   target="_blank"
							   class="interactive-button interactive-button-neutral w-full"
							   style="padding: 10px 16px; font-size: 11px;">
								<span class="button-content">
									{{ __('Invoice') }}
								</span>
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
									class="interactive-button interactive-button-delete w-full"
									style="padding: 10px 16px; font-size: 11px;">
									<span class="button-content">
										{{ __('Dispose Asset') }}
									</span>
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
											{{ $assignment->userFullName ?? ($assignment->user->fullName ?? 'User Deleted') }}
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
											{{ $assignment->userDepartment ?? ($assignment->user->department ?? '-') }}
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
				disposeForm.addEventListener('submit', async function(e) {
					e.preventDefault();
					
					const confirmed = await window.showConfirmation(
						'Are you sure you want to dispose this asset? This action cannot be undone.',
						'Dispose Asset'
					);
					
					if (confirmed) {
						disposeForm.submit();
					}
				});
			}
		});
	</script>
</x-app-layout>