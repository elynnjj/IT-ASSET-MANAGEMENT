<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Edit User') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<form action="{{ route('itdept.manage-users.update', $user->userID) }}" method="POST" class="space-y-4">
						@csrf
						@method('PUT')

						<div>
							<x-input-label for="userID" :value="__('Username')" />
							<x-text-input id="userID" name="userID" type="text" class="mt-1 block w-full" value="{{ $user->userID }}" disabled />
						</div>

						<div>
							<x-input-label for="fullName" :value="__('Full Name')" />
							<x-text-input id="fullName" name="fullName" type="text" class="mt-1 block w-full" value="{{ old('fullName', $user->fullName) }}" required />
							<x-input-error :messages="$errors->get('fullName')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="email" :value="__('Email')" />
							<x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $user->email) }}" required />
							<x-input-error :messages="$errors->get('email')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="password" :value="__('Password (leave blank to keep)')" />
							<x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
							<x-input-error :messages="$errors->get('password')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="department" :value="__('Department')" />
							<select id="department" name="department" class="mt-1 block w-full" required>
								@php($departments = ['HR & Admin','Account','Service','Project','Supply Chain','Sales','Proposal'])
								@foreach ($departments as $dept)
									<option value="{{ $dept }}" @selected(old('department', $user->department) === $dept)>{{ $dept }}</option>
								@endforeach
							</select>
							<x-input-error :messages="$errors->get('department')" class="mt-2" />
						</div>

						<div>
							<x-input-label :value="__('Role')" />
							<div class="mt-2 space-y-2">
								<label class="inline-flex items-center">
									<input type="radio" name="role" value="HOD" class="me-2" @checked(old('role', $user->role) === 'HOD')>
									<span>Head of Department (HOD)</span>
								</label>
								<label class="inline-flex items-center ms-6">
									<input type="radio" name="role" value="Employee" class="me-2" @checked(old('role', $user->role) === 'Employee')>
									<span>Employee</span>
								</label>
							</div>
							<x-input-error :messages="$errors->get('role')" class="mt-2" />
						</div>



						<div class="flex items-center justify-end">
							<x-primary-button>{{ __('Save Changes') }}</x-primary-button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>


