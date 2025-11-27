<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Information (Read-only) -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- Profile Information --}}
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <h3 class="text-lg font-semibold mb-3">{{ __('Profile Information') }}</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Username') }}:</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ auth()->user()->userID }}</p>
                            </div>
                            <div>
                                <label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Full Name') }}:</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ auth()->user()->fullName }}</p>
                            </div>
                            <div>
                                <label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}:</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ auth()->user()->email }}</p>
                            </div>
                            <div>
                                <label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Department') }}:</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ auth()->user()->department ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Role') }}:</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ auth()->user()->role }}</p>
                            </div>
                            <div>
                                <label class="font-medium text-gray-700 dark:text-gray-300">{{ __('Account Status') }}:</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ auth()->user()->accStat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

