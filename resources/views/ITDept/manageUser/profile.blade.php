<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Main Content Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Title --}}
                    <div class="mb-6">
                        <h1 class="text-xl font-semibold">{{ __('Profile') }}</h1>
                    </div>

                    {{-- Profile Information Section --}}
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Profile Information') }}</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-white dark:bg-gray-800">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Username') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ auth()->user()->userID }}</p>
                            </div>
                            <div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-white dark:bg-gray-800">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Full Name') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ auth()->user()->fullName }}</p>
                            </div>
                            <div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-white dark:bg-gray-800">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Email') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-white dark:bg-gray-800">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Department') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ auth()->user()->department ?? '-' }}</p>
                            </div>
                            <div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-white dark:bg-gray-800">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Role') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ auth()->user()->role }}</p>
                            </div>
                            <div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-white dark:bg-gray-800">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Account Status') }}</label>
                                <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ auth()->user()->accStat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Update Password Section --}}
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <livewire:profile.update-password-form />
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        .input-container:has(.interactive-textarea:focus) {
            transform: translateY(-2px);
        }

        /* Interactive input styling */
        .interactive-input,
        .interactive-textarea {
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
            .interactive-input,
            .interactive-textarea {
                background-color: #111827;
                border-color: #6B7280;
                color: #D1D5DB;
            }
        }

        .dark .interactive-input,
        .dark .interactive-textarea {
            background-color: #111827;
            border-color: #6B7280;
            color: #D1D5DB;
        }

        .interactive-input:hover,
        .interactive-textarea:hover {
            border-color: #4BA9C2;
            box-shadow: 0 4px 12px rgba(75, 169, 194, 0.15);
            transform: translateY(-1px);
            background-color: #FAFAFA;
        }

        @media (prefers-color-scheme: dark) {
            .interactive-input:hover,
            .interactive-textarea:hover {
                border-color: #4BA9C2;
                box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
                background-color: #1F2937;
            }
        }

        .dark .interactive-input:hover,
        .dark .interactive-textarea:hover {
            border-color: #4BA9C2;
            box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
            background-color: #1F2937;
        }

        .interactive-input:focus,
        .interactive-textarea:focus {
            outline: none;
            border-color: #4BA9C2;
            box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.15), 0 6px 16px rgba(75, 169, 194, 0.2);
            background-color: #FFFFFF;
            transform: translateY(-2px);
        }

        @media (prefers-color-scheme: dark) {
            .interactive-input:focus,
            .interactive-textarea:focus {
                border-color: #4BA9C2;
                box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
                background-color: #111827;
                color: #D1D5DB;
            }
        }

        .dark .interactive-input:focus,
        .dark .interactive-textarea:focus {
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
            font-weight: 600;
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

        .button-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            z-index: 1;
        }

        .button-spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .interactive-button.loading .button-spinner {
            display: block;
        }

        .interactive-button.loading .button-text {
            opacity: 0.7;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading state to submit buttons on form submission
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton && submitButton.classList.contains('interactive-button')) {
                    form.addEventListener('submit', function() {
                        submitButton.classList.add('loading');
                        submitButton.disabled = true;
                    });
                }
            });
        });
    </script>
</x-app-layout>

