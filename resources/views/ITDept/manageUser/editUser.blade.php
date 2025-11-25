<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Edit User') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			{{-- Breadcrumb --}}
			<div class="mb-6">
				<a href="{{ route('itdept.manage-users.index', ['role' => $user->role]) }}" 
				   style="color: #4BA9C2;"
				   class="hover:opacity-80">
					← Users
				</a>
				<span class="text-gray-600 dark:text-gray-400"> > </span>
				<span class="text-gray-600 dark:text-gray-400">{{ $user->userID }}</span>
				<span class="text-gray-600 dark:text-gray-400"> > </span>
				<span class="text-gray-600 dark:text-gray-400">{{ __('Edit User') }}</span>
			</div>

			{{-- Main Content Card --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Title --}}
					<div class="mb-6">
						<h1 class="text-xl font-semibold">{{ __('Edit User') }}</h1>
					</div>

					{{-- Edit User Section --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-4">{{ __('User Information') }}</h3>
						<form action="{{ route('itdept.manage-users.update', $user->userID) }}" method="POST">
							@csrf
							@method('PUT')

							<div class="space-y-5">
								{{-- Row 1: Username and Password --}}
								<div class="grid grid-cols-2 gap-4">
									<div>
										<x-input-label for="userID" :value="__('Username')" />
										<x-text-input id="userID" name="userID" type="text" 
											class="mt-1 block w-full bg-gray-100 dark:bg-gray-800 cursor-not-allowed" 
											value="{{ $user->userID }}" 
											placeholder="Username"
											disabled />
										<x-input-error :messages="$errors->get('userID')" class="mt-2" />
									</div>
									<div>
										<x-input-label for="password" :value="__('Password (leave blank to keep)')" />
										<x-text-input id="password" name="password" type="password" 
											class="mt-1 block w-full" 
											placeholder="Enter new password" />
										<x-input-error :messages="$errors->get('password')" class="mt-2" />
									</div>
								</div>

								{{-- Row 2: Full Name --}}
								<div>
									<x-input-label for="fullName" :value="__('Full Name')" />
									<x-text-input id="fullName" name="fullName" type="text" 
										class="mt-1 block w-full" 
										value="{{ old('fullName', $user->fullName) }}"
										placeholder="Enter full name"
										required />
									<x-input-error :messages="$errors->get('fullName')" class="mt-2" />
								</div>

								{{-- Row 3: Email --}}
								<div>
									<x-input-label for="email" :value="__('Email')" />
									<x-text-input id="email" name="email" type="email" 
										class="mt-1 block w-full" 
										value="{{ old('email', $user->email) }}"
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
											@php($departments = ['HR & Admin','Account','Service','Project','Supply Chain','Sales','Proposal'])
											@foreach ($departments as $dept)
												<option value="{{ $dept }}" @selected(old('department', $user->department) === $dept)>{{ $dept }}</option>
											@endforeach
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
													@checked(old('role', $user->role) === 'HOD')>
												<span class="text-gray-700 dark:text-gray-300">Head of Department (HOD)</span>
											</label>
											<label class="inline-flex items-center">
												<input type="radio" name="role" value="Employee" 
													class="me-2"
													style="accent-color: #4BA9C2;"
													@checked(old('role', $user->role) === 'Employee')>
												<span class="text-gray-700 dark:text-gray-300">Employee</span>
											</label>
										</div>
										<x-input-error :messages="$errors->get('role')" class="mt-2" />
									</div>
								</div>
							</div>

							{{-- Buttons --}}
							<div class="flex items-center justify-end space-x-6 mt-6">
								<a href="{{ route('itdept.manage-users.index', ['role' => $user->role]) }}" 
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
									{{ __('Save Changes') }}
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>


