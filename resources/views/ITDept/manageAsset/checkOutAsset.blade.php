<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Check-Out Asset') }}
		</h2>
	</x-slot>

	<div class="py-12">
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
				padding: 8px 12px;
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

			/* Interactive select styling */
			.interactive-select {
				width: 100%;
				padding: 8px 12px;
				border: 2px solid #9CA3AF;
				border-radius: 8px;
				font-size: 15px;
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
				padding: 10px 16px;
				font-weight: 600;
				font-size: 11px;
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
				box-shadow: 0 4px 12px rgba(121, 121, 121, 0.4);
			}

			.dark .interactive-button-secondary:hover {
				box-shadow: 0 8px 20px rgba(121, 121, 121, 0.6);
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
				<a href="{{ route('itdept.manage-assets.show', $asset->assetID) }}" 
				   style="color: #4BA9C2;"
				   class="hover:opacity-80">
					{{ $asset->assetID }}
				</a>
				<span class="text-gray-600 dark:text-gray-400"> > </span>
				<span class="text-gray-600 dark:text-gray-400">{{ __('Check-Out Asset') }}</span>
			</div>

			{{-- Main Content Card --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Title --}}
					<div class="mb-6">
						<h1 class="text-xl font-semibold">{{ __('Check-Out Asset') }}</h1>
					</div>

					{{-- Asset Information --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-3">{{ __('Asset Information') }}</h3>
						<div class="grid grid-cols-2 gap-4 text-sm">
							<div>
								<label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Asset ID') }}:</label>
								<p class="text-gray-900 dark:text-gray-100 font-bold">{{ $asset->assetID }}</p>
							</div>
							<div>
								<label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Model') }}:</label>
								<p class="text-gray-900 dark:text-gray-100 font-bold">{{ $asset->model ?? '-' }}</p>
							</div>
						</div>
					</div>

					<form action="{{ route('itdept.manage-assets.checkout.store', $asset->assetID) }}" method="POST" 
						x-data="{ 
							selectedDepartment: '', 
							users: @js($users->toArray()),
							userAssignments: @js($userAssignments->mapWithKeys(function($assignment) {
								return [$assignment->userID => [
									'fullName' => $assignment->user->fullName,
									'assetID' => $assignment->asset->assetID
								]];
							})->toArray()),
							selectedUserID: '',
							showNotification: false,
							notificationMessage: '',
							checkUserAssignment(userID) {
								if (!userID) {
									this.selectedUserID = '';
									return true;
								}
								
								if (this.userAssignments[userID]) {
									const assignment = this.userAssignments[userID];
									this.notificationMessage = assignment.fullName + ' has been assigned to ' + assignment.assetID;
									this.showNotification = true;
									this.selectedUserID = '';
									// Reset the select dropdown
									this.$refs.userSelect.value = '';
									return false;
								}
								
								this.selectedUserID = userID;
								return true;
							},
							handleSubmit(event) {
								const userID = this.selectedUserID || this.$refs.userSelect.value;
								if (!this.checkUserAssignment(userID)) {
									event.preventDefault();
									return false;
								}
								// Allow form submission
								event.target.submit();
							}
						}"
						@submit.prevent="handleSubmit($event)">
						@csrf

						{{-- Notification Pop-up --}}
						<div x-show="showNotification" 
							x-transition:enter="transition ease-out duration-300"
							x-transition:enter-start="opacity-0 transform scale-95"
							x-transition:enter-end="opacity-100 transform scale-100"
							x-transition:leave="transition ease-in duration-200"
							x-transition:leave-start="opacity-100 transform scale-100"
							x-transition:leave-end="opacity-0 transform scale-95"
							class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
							@click.away="showNotification = false"
							style="display: none;">
							<div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
								<div class="flex items-center justify-between mb-4">
									<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">User Already Assigned</h3>
									<button type="button" @click="showNotification = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
										<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
										</svg>
									</button>
								</div>
								<p class="text-gray-700 dark:text-gray-300 mb-4" x-text="notificationMessage"></p>
								<div class="flex justify-end">
									<button type="button" @click="showNotification = false" 
										class="px-4 py-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 rounded-md hover:bg-gray-700 dark:hover:bg-white transition">
										OK
									</button>
								</div>
							</div>
						</div>

						{{-- Check-Out Information Section --}}
						<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
							<h3 class="text-lg font-semibold mb-4">{{ __('Check-Out Asset To:') }}</h3>
							<div class="space-y-4">
								<div class="input-container">
									<x-input-label for="department" :value="__('Department')" class="text-[15px]" />
									<select id="department" 
										x-model="selectedDepartment"
										x-on:change="$refs.userSelect.value = ''; selectedUserID = '';"
										class="interactive-select mt-1 block w-full">
										<option value="">All Departments</option>
										@php($departments = ['HR & Admin','Account','Service','Project','Supply Chain','Sales','Proposal'])
										@foreach ($departments as $dept)
											<option value="{{ $dept }}">{{ $dept }}</option>
										@endforeach
									</select>
								</div>

								<div class="input-container">
									<x-input-label for="userID" :value="__('Assign To User')" class="text-[15px]" />
									<select id="userID" name="userID" 
										x-ref="userSelect"
										x-model="selectedUserID"
										x-on:change="checkUserAssignment($event.target.value)"
										class="interactive-select mt-1 block w-full" required>
										<option value="">Select User</option>
										<template x-for="user in users.filter(u => !selectedDepartment || u.department === selectedDepartment)" :key="user.userID">
											<option :value="user.userID" x-text="user.fullName + ' (' + user.userID + ')'"></option>
										</template>
									</select>
									<x-input-error :messages="$errors->get('userID')" class="mt-2" />
								</div>

								<div class="input-container">
									<x-input-label for="checkoutDate" :value="__('Checkout Date')" class="text-[15px]" />
									<x-text-input id="checkoutDate" name="checkoutDate" type="date" class="interactive-input mt-1 block w-full" value="{{ old('checkoutDate', Carbon\Carbon::now()->format('Y-m-d')) }}" required />
									<x-input-error :messages="$errors->get('checkoutDate')" class="mt-2" />
								</div>
							</div>
						</div>

						<div class="flex items-center justify-end space-x-6 mt-6">
							<a href="{{ route('itdept.manage-assets.show', $asset->assetID) }}" 
							   class="interactive-button interactive-button-secondary">
								<span class="button-content">
									<span class="button-text">{{ __('Cancel') }}</span>
								</span>
							</a>
							<button type="submit" 
								class="interactive-button interactive-button-primary">
								<span class="button-content">
									<span class="button-text">{{ __('Check-Out Asset') }}</span>
								</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

