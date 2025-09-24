<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Users') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- View HOD / Employee --}}
                    <div class="flex border-b border-sky-400 mb-6">
                        <a href="{{ route('itdept.manage-users.index', ['role' => 'HOD']) }}"
                           class="flex-1 text-center py-2 font-medium 
                           {{ $role === 'HOD' ? 'text-sky-600 border-b-2 border-sky-400' : 'text-gray-600' }}">
                            {{ __('Head Of Department (HOD)') }}
                        </a>
                        <a href="{{ route('itdept.manage-users.index', ['role' => 'Employee']) }}"
                           class="flex-1 text-center py-2 font-medium 
                           {{ $role === 'Employee' ? 'text-sky-600 border-b-2 border-sky-400' : 'text-gray-600' }}">
                            {{ __('Employee') }}
                        </a>
                    </div>

                    {{-- Search / Filter / Add --}}
					<div class="mb-6">
						<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
							<form id="filterForm" method="GET" action="{{ route('itdept.manage-users.index') }}" 
								class="flex flex-wrap items-center gap-2 flex-1"
								x-data="{ timeout: null }">
								<input type="hidden" name="role" value="{{ $role }}" />

								{{-- Search input with auto-submit --}}
								<input type="text" name="q" value="{{ $q }}" 
									placeholder="{{ __('Search name or username') }}"
									class="flex-1 min-w-[200px] rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
									x-on:input="
											clearTimeout(timeout);
											timeout = setTimeout(() => $root.querySelector('#filterForm').submit(), 500);
									" />

								{{-- Department dropdown --}}
								<select name="department" 
										class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
										onchange="this.form.submit()">
									<option value="">{{ __('All departments') }}</option>
									@php($departments = ['HR & Admin','Account','Service','Project','Supply Chain','Sales','Proposal'])
									@foreach ($departments as $dept)
										<option value="{{ $dept }}" @selected($filterDepartment === $dept)>{{ $dept }}</option>
									@endforeach
								</select>

								{{-- Status dropdown --}}
								<select name="status" 
										class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
										onchange="this.form.submit()">
									<option value="">{{ __('All status') }}</option>
									<option value="active" @selected($filterStatus === 'active')>{{ __('Active') }}</option>
									<option value="inactive" @selected($filterStatus === 'inactive')>{{ __('Inactive') }}</option>
								</select>

								{{-- Add User button inline with form --}}
								<a href="{{ route('itdept.manage-users.create') }}" 
								class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 ml-auto">
									{{ __('Add User') }}
								</a>
							</form>
						</div>
					</div>

                    {{-- Status message --}}
                    @if (session('status'))
                        <div class="mb-4 text-green-500 font-medium">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Users Table -->
					<div class="overflow-x-auto">
						<table class="table-auto w-full border border-gray-300 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
							<thead class="bg-gray-100 dark:bg-gray-700">
								@php($columns = [
									['key' => 'userID', 'label' => 'Username'],
									['key' => 'fullName', 'label' => 'Full Name'],
									['key' => 'department', 'label' => 'Department'],
									['key' => 'accStat', 'label' => 'Status'],
								])
								<tr>
									@foreach ($columns as $c)
										<th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
											@php($isActive = ($sort ?? null) === $c['key'])
											<a href="{{ request()->fullUrlWithQuery([
												'sort' => $c['key'], 
												'dir' => ($isActive && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc'
											]) }}" 
											class="inline-flex items-center gap-1">
												<span>{{ __($c['label']) }}</span>
												<span class="text-xs">
													@if ($isActive)
														{{ ($dir ?? 'asc') === 'asc' ? '▲' : '▼' }}
													@else
														▲▼
													@endif
												</span>
											</a>
										</th>
									@endforeach
									<th class="px-6 py-3"></th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
								@forelse ($users as $user)
									<tr>
										<td class="px-6 py-3">{{ $user->userID }}</td>
										<td class="px-6 py-3">{{ $user->fullName }}</td>
										<td class="px-6 py-3">{{ $user->department ?? '-' }}</td>
										<td class="px-6 py-3">{{ $user->accStat ?? '-' }}</td>
										<td class="px-6 py-3 text-right space-x-2">
											<a href="{{ route('itdept.manage-users.edit', $user->userID) }}" class="underline">{{ __('Edit') }}</a>
											<form action="{{ route('itdept.manage-users.deactivate', $user->userID) }}" method="POST" class="inline">
												@csrf
												@method('PATCH')
												<button type="submit" class="underline text-yellow-500">{{ __('Deactivate') }}</button>
											</form>
											<form action="{{ route('itdept.manage-users.destroy', $user->userID) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?');">
												@csrf
												@method('DELETE')
												<button type="submit" class="underline text-red-600">{{ __('Delete') }}</button>
											</form>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="5" class="px-6 py-4 text-center text-gray-500">
											No users found.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
