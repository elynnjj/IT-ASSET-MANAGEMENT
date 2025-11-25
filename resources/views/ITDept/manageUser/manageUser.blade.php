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
                    <div class="flex border-b mb-6" style="border-color: #4BA9C2;">
                        <a href="{{ route('itdept.manage-users.index', ['role' => 'HOD']) }}"
                           class="flex-1 text-center py-2 font-medium 
                           {{ $role === 'HOD' ? 'border-b-2' : '' }}"
                           style="{{ $role === 'HOD' ? 'color: #4BA9C2; border-bottom-color: #4BA9C2;' : 'color: #6B7280;' }}">
                            {{ __('Head Of Department (HOD)') }}
                        </a>
                        <a href="{{ route('itdept.manage-users.index', ['role' => 'Employee']) }}"
                           class="flex-1 text-center py-2 font-medium 
                           {{ $role === 'Employee' ? 'border-b-2' : '' }}"
                           style="{{ $role === 'Employee' ? 'color: #4BA9C2; border-bottom-color: #4BA9C2;' : 'color: #6B7280;' }}">
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
								class="inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:opacity-90 ml-auto"
								style="background-color: #4BA9C2;"
								onmouseover="this.style.backgroundColor='#3a8ba5'"
								onmouseout="this.style.backgroundColor='#4BA9C2'">
									<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
									</svg>
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
									['key' => 'email', 'label' => 'Email'],
									['key' => 'department', 'label' => 'Department'],
								])
								<tr>
									@foreach ($columns as $c)
										<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
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
									@php($statusActive = ($sort ?? null) === 'accStat')
									<th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
										<a href="{{ request()->fullUrlWithQuery([
											'sort' => 'accStat', 
											'dir' => ($statusActive && ($dir ?? 'asc') === 'asc') ? 'desc' : 'asc'
										]) }}" 
										class="inline-flex items-center gap-1">
											<span>{{ __('Status') }}</span>
											<span class="text-xs">
												@if ($statusActive)
													{{ ($dir ?? 'asc') === 'asc' ? '▲' : '▼' }}
												@else
													▲▼
												@endif
											</span>
										</a>
									</th>
									<th class="px-4 py-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 w-auto">{{ __('Action') }}</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
								@forelse ($users as $user)
									<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-800' }}">
										<td class="px-8 py-4">{{ $user->userID }}</td>
										<td class="px-8 py-4">{{ $user->fullName }}</td>
										<td class="px-8 py-4">{{ $user->email ?? '-' }}</td>
										<td class="px-8 py-4">{{ $user->department ?? '-' }}</td>
										<td class="px-8 py-4">
											<span class="text-sm font-medium {{ $user->accStat === 'active' ? 'text-green-600' : 'text-red-600' }}">
												{{ ucfirst($user->accStat ?? 'active') }}
											</span>
										</td>
										<td class="px-4 py-4">
											<div class="flex items-center justify-center space-x-2">
												<form action="{{ route('itdept.manage-users.deactivate', $user->userID) }}" method="POST" class="inline">
													@csrf
													@method('PATCH')
													<button type="submit" 
														class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold uppercase tracking-widest rounded-md border transition"
														style="border-color: #4BA9C2; color: #4BA9C2; background-color: white;"
														onmouseover="this.style.backgroundColor='#f0f9ff'"
														onmouseout="this.style.backgroundColor='white'"
														title="{{ __('Deactivate') }}">
														{{ __('Deactivate') }}
													</button>
												</form>
												<a href="{{ route('itdept.manage-users.edit', $user->userID) }}" 
												   class="inline-flex items-center justify-center px-4 py-2 rounded-md border transition"
												   style="border-color: #4BA9C2; color: #4BA9C2; background-color: white;"
												   onmouseover="this.style.backgroundColor='#f0f9ff'"
												   onmouseout="this.style.backgroundColor='white'"
												   title="{{ __('Edit') }}">
													<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
													</svg>
												</a>
												<form action="{{ route('itdept.manage-users.destroy', $user->userID) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?');">
													@csrf
													@method('DELETE')
													<button type="submit" 
														class="inline-flex items-center justify-center px-4 py-2 rounded-md border transition"
														style="border-color: #4BA9C2; color: #4BA9C2; background-color: white;"
														onmouseover="this.style.backgroundColor='#f0f9ff'"
														onmouseout="this.style.backgroundColor='white'"
														title="{{ __('Delete') }}">
														<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
														</svg>
													</button>
												</form>
											</div>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="px-8 py-6 text-center text-gray-500">
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
