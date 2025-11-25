<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Check-Out Asset') }}
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
								<p class="text-gray-900 dark:text-gray-100">{{ $asset->assetID }}</p>
							</div>
							<div>
								<label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Model') }}:</label>
								<p class="text-gray-900 dark:text-gray-100">{{ $asset->model ?? '-' }}</p>
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
							<div class="space-y-5">
								<div>
									<x-input-label for="department" :value="__('Department')" />
									<select id="department" 
										x-model="selectedDepartment"
										x-on:change="$refs.userSelect.value = ''; selectedUserID = '';"
										class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
										<option value="">All Departments</option>
										@php($departments = ['HR & Admin','Account','Service','Project','Supply Chain','Sales','Proposal'])
										@foreach ($departments as $dept)
											<option value="{{ $dept }}">{{ $dept }}</option>
										@endforeach
									</select>
								</div>

								<div>
									<x-input-label for="userID" :value="__('Assign To User')" />
									<select id="userID" name="userID" 
										x-ref="userSelect"
										x-model="selectedUserID"
										x-on:change="checkUserAssignment($event.target.value)"
										class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
										<option value="">Select User</option>
										<template x-for="user in users.filter(u => !selectedDepartment || u.department === selectedDepartment)" :key="user.userID">
											<option :value="user.userID" x-text="user.fullName + ' (' + user.userID + ')'"></option>
										</template>
									</select>
									<x-input-error :messages="$errors->get('userID')" class="mt-2" />
								</div>

								<div>
									<x-input-label for="checkoutDate" :value="__('Checkout Date')" />
									<x-text-input id="checkoutDate" name="checkoutDate" type="date" class="mt-1 block w-full" value="{{ old('checkoutDate', Carbon\Carbon::now()->format('Y-m-d')) }}" required />
									<x-input-error :messages="$errors->get('checkoutDate')" class="mt-2" />
								</div>
							</div>
						</div>

						<div class="flex items-center justify-end space-x-6 mt-6">
							<a href="{{ route('itdept.manage-assets.show', $asset->assetID) }}" 
							   class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
							   style="background-color: #797979;"
							   onmouseover="this.style.backgroundColor='#666666'"
							   onmouseout="this.style.backgroundColor='#797979'">
								{{ __('Cancel') }}
							</a>
							<button type="submit" 
								class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
								style="background-color: #4BA9C2;"
								onmouseover="this.style.backgroundColor='#3a8ba5'"
								onmouseout="this.style.backgroundColor='#4BA9C2'">
								{{ __('Check-Out Asset') }}
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

