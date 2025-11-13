<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Add User') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<form action="{{ route('itdept.manage-users.store') }}" method="POST" class="space-y-4">
						@csrf
						<div>
							<x-input-label for="userID" :value="__('Username')" />
							<x-text-input id="userID" name="userID" type="text" class="mt-1 block w-full" required />
							<x-input-error :messages="$errors->get('userID')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="fullName" :value="__('Full Name')" />
							<x-text-input id="fullName" name="fullName" type="text" class="mt-1 block w-full" required />
							<x-input-error :messages="$errors->get('fullName')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="email" :value="__('Email')" />
							<x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required />
							<x-input-error :messages="$errors->get('email')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="password" :value="__('Password')" />
							<x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
							<x-input-error :messages="$errors->get('password')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="department" :value="__('Department')" />
							<select id="department" name="department" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
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
							<div class="mt-2 space-y-2">
								<label class="inline-flex items-center">
									<input type="radio" name="role" value="HOD" class="me-2" required>
									<span>Head of Department (HOD)</span>
								</label>
								<label class="inline-flex items-center ms-6">
									<input type="radio" name="role" value="Employee" class="me-2" required>
									<span>Employee</span>
								</label>
							</div>
							<x-input-error :messages="$errors->get('role')" class="mt-2" />
						</div>


						<div class="flex items-center justify-end">
							<x-primary-button>{{ __('Create') }}</x-primary-button>
						</div>
					</form>

					<div class="mt-10" x-data="{ showBulkUpload: false }">
						<button type="button" @click="showBulkUpload = !showBulkUpload" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
							{{ __('Add User In Bulk') }}
						</button>

						<div x-show="showBulkUpload" x-transition class="mt-6">
							<h3 class="text-lg font-semibold mb-4">{{ __('Add users in bulk') }}</h3>
							<div class="flex items-center justify-between mb-4">
								<a href="{{ route('itdept.manage-users.template') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">{{ __('Download template') }}</a>
							</div>
							<form action="{{ route('itdept.manage-users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
								@csrf
								<input type="file" name="file" accept=".csv" required />
								<div>
									<x-primary-button>{{ __('Add users') }}</x-primary-button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>


