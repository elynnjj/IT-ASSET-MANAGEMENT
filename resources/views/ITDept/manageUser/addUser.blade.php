<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Add User') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			{{-- Breadcrumb --}}
			<div class="mb-6">
				<a href="{{ route('itdept.manage-users.index') }}" 
				   style="color: #4BA9C2;"
				   class="hover:opacity-80">
					← Users
				</a>
				<span class="text-gray-600 dark:text-gray-400"> > </span>
				<span class="text-gray-600 dark:text-gray-400">{{ __('Add User') }}</span>
			</div>

			{{-- Main Content Card --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Title --}}
					<div class="mb-6">
						<h1 class="text-xl font-semibold">{{ __('Add User') }}</h1>
					</div>

					{{-- Add User Manually Section --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-4">{{ __('Add User Manually') }}</h3>
						<form action="{{ route('itdept.manage-users.store') }}" method="POST">
							@csrf
							<div class="space-y-5">
								{{-- Row 1: Username --}}
								<div>
									<x-input-label for="userID" :value="__('Username')" />
									<x-text-input id="userID" name="userID" type="text" 
										class="mt-1 block w-full" 
										placeholder="Enter username"
										required />
									<x-input-error :messages="$errors->get('userID')" class="mt-2" />
								</div>

								{{-- Row 2: Full Name --}}
								<div>
									<x-input-label for="fullName" :value="__('Full Name')" />
									<x-text-input id="fullName" name="fullName" type="text" 
										class="mt-1 block w-full" 
										placeholder="Enter full name"
										required />
									<x-input-error :messages="$errors->get('fullName')" class="mt-2" />
								</div>

								{{-- Row 3: Email --}}
								<div>
									<x-input-label for="email" :value="__('Email')" />
									<x-text-input id="email" name="email" type="email" 
										class="mt-1 block w-full" 
										placeholder="Enter email address"
										required />
									<x-input-error :messages="$errors->get('email')" class="mt-2" />
								</div>

								{{-- Row 4: Department and Role --}}
								<div class="grid grid-cols-2 gap-4">
									<div>
										<x-input-label for="department" :value="__('Department')" />
										<select id="department" name="department" 
											class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
											required>
											<option value="HR & Admin">HR & Admin</option>
											<option value="Account">Account</option>
											<option value="Service">Service</option>
											<option value="Project">Project</option>
											<option value="Supply Chain">Supply Chain</option>
											<option value="Sales">Sales</option>
											<option value="Proposal">Proposal</option>
										</select>
										<x-input-error :messages="$errors->get('department')" class="mt-2" />
									</div>
									<div>
										<x-input-label :value="__('Role')" />
										<div class="mt-2 space-x-6">
											<label class="inline-flex items-center">
												<input type="radio" name="role" value="HOD" 
													class="me-2"
													style="accent-color: #4BA9C2;"
													required>
												<span class="text-gray-700 dark:text-gray-300">Head of Department (HOD)</span>
											</label>
											<label class="inline-flex items-center">
												<input type="radio" name="role" value="Employee" 
													class="me-2"
													style="accent-color: #4BA9C2;"
													required>
												<span class="text-gray-700 dark:text-gray-300">Employee</span>
											</label>
										</div>
										<x-input-error :messages="$errors->get('role')" class="mt-2" />
									</div>
								</div>
							</div>

							{{-- Buttons --}}
							<div class="flex items-center justify-end space-x-6 mt-6">
								<a href="{{ route('itdept.manage-users.index') }}" 
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
									{{ __('Add User') }}
								</button>
							</div>
						</form>
					</div>

					{{-- Bulk Upload Container --}}
					<div x-data="{ showBulkUpload: false }">
						{{-- Button to Show Bulk Upload Section --}}
						<div class="flex items-center justify-center mt-6">
							<button type="button" 
								@click="showBulkUpload = !showBulkUpload"
								class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
								style="background-color: #4BA9C2;"
								onmouseover="this.style.backgroundColor='#3a8ba5'"
								onmouseout="this.style.backgroundColor='#4BA9C2'">
								{{ __('Add User in Bulk') }}
							</button>
						</div>

						{{-- Divider Line --}}
						<div x-show="showBulkUpload" x-transition class="my-6 border-t border-gray-300 dark:border-gray-600" style="display: none;"></div>

						{{-- Add User in Bulk Section --}}
						<div x-show="showBulkUpload" x-transition class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md" style="display: none;">
							<h3 class="text-lg font-semibold mb-4">{{ __('Add User in Bulk') }}</h3>
							<form action="{{ route('itdept.manage-users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
								@csrf
								<div>
									<x-input-label for="bulkFile" :value="__('New User File')" />
									<input type="file" id="bulkFile" name="file" accept=".csv" 
										class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300
											border border-gray-300 dark:border-gray-700 rounded-md
											file:mr-4 file:py-2 file:px-4
											file:rounded-md file:border-0
											file:text-sm file:font-semibold
											file:bg-blue-50 dark:file:bg-blue-900
											file:text-blue-700 dark:file:text-blue-300
											hover:file:bg-blue-100 dark:hover:file:bg-blue-800
											cursor-pointer"
										required />
									<x-input-error :messages="$errors->get('file')" class="mt-2" />
								</div>

								<div class="flex items-center justify-end space-x-6 mt-6">
									<a href="{{ route('itdept.manage-users.template') }}" 
									   class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
									   style="background-color: #4BA9C2;"
									   onmouseover="this.style.backgroundColor='#3a8ba5'"
									   onmouseout="this.style.backgroundColor='#4BA9C2'">
										<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
										</svg>
										{{ __('Download Template') }}
									</a>
									<button type="submit" 
										class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90"
										style="background-color: #4BA9C2;"
										onmouseover="this.style.backgroundColor='#3a8ba5'"
										onmouseout="this.style.backgroundColor='#4BA9C2'">
										{{ __('Add User') }}
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>


