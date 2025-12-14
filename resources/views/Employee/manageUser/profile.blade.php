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

    <style>
        /* Interactive button styling */
        .interactive-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 28px;
            font-weight: 600;
            font-size: 13px;
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

        /* Apply interactive styles to all inputs in profile forms */
        section form input[type="text"],
        section form input[type="email"],
        section form input[type="password"] {
            width: 100%;
            padding: 14px;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #FFFFFF;
        }

        .dark section form input[type="text"],
        .dark section form input[type="email"],
        .dark section form input[type="password"] {
            background-color: rgba(55, 65, 81, 0.8);
            border-color: #4B5563;
            color: #F9FAFB;
        }

        section form input[type="text"]:hover,
        section form input[type="email"]:hover,
        section form input[type="password"]:hover {
            border-color: #4BA9C2;
            box-shadow: 0 4px 12px rgba(75, 169, 194, 0.15);
            transform: translateY(-1px);
            background-color: #FAFAFA;
        }

        .dark section form input[type="text"]:hover,
        .dark section form input[type="email"]:hover,
        .dark section form input[type="password"]:hover {
            border-color: #4BA9C2;
            box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
            background-color: rgba(55, 65, 81, 0.95);
        }

        section form input[type="text"]:focus,
        section form input[type="email"]:focus,
        section form input[type="password"]:focus {
            outline: none;
            border-color: #4BA9C2;
            box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.15), 0 6px 16px rgba(75, 169, 194, 0.2);
            background-color: #FFFFFF;
            transform: translateY(-2px);
        }

        .dark section form input[type="text"]:focus,
        .dark section form input[type="email"]:focus,
        .dark section form input[type="password"]:focus {
            border-color: #4BA9C2;
            box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
            background-color: rgba(55, 65, 81, 1);
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

