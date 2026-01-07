<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ExactAsset - Reset Password</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/exact2.png') }}?v=exactasset">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow: hidden;
        }

        .reset-container {
            position: relative;
            min-height: 100vh;
            width: 100vw;
            background-image: url('{{ asset('images/bg2.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 2rem;
        }

        /* Overlay for better readability */
        .reset-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.2) 100%);
            z-index: 1;
        }

        .reset-form-section {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 480px;
        }

        .reset-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
            padding: 2rem;
            animation: slideInUp 0.6s ease-out;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .reset-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reset-form-container {
            width: 100%;
        }

        /* Input styling */
        .input-field {
            width: 100%;
            padding: 14px 14px 14px 44px;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #FFFFFF;
            position: relative;
        }

        .input-field:hover {
            border-color: #4BA9C2;
            box-shadow: 0 4px 12px rgba(75, 169, 194, 0.15);
            transform: translateY(-1px);
            background-color: #FAFAFA;
        }

        .input-field:focus {
            outline: none;
            border-color: #4BA9C2;
            box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.15), 0 6px 16px rgba(75, 169, 194, 0.2);
            background-color: #FFFFFF;
            transform: translateY(-2px);
        }

        .input-field:read-only {
            background-color: #F3F4F6;
            color: #6B7280;
            cursor: not-allowed;
            border-color: #D1D5DB;
        }

        .input-field:read-only:hover {
            transform: none;
            box-shadow: none;
            border-color: #D1D5DB;
        }

        .input-field:read-only:focus {
            transform: none;
            box-shadow: none;
            border-color: #D1D5DB;
        }

        .input-with-icon {
            position: relative;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-with-icon:hover {
            transform: translateY(-1px);
        }

        .input-with-icon:has(.input-field:focus) {
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #6B7280;
            z-index: 2;
            pointer-events: none;
        }

        .input-with-icon:hover .input-icon {
            color: #4BA9C2;
            transform: translateY(-50%) scale(1.1);
        }

        .input-field:focus + .input-icon,
        .input-field:focus ~ .input-icon {
            color: #4BA9C2;
            transform: translateY(-50%) scale(1.15);
        }

        .input-field:read-only + .input-icon,
        .input-field:read-only ~ .input-icon {
            color: #9CA3AF;
        }

        .input-with-icon:has(.input-field:read-only):hover {
            transform: none;
        }

        .input-with-icon:has(.input-field:read-only):hover .input-icon {
            color: #9CA3AF;
            transform: translateY(-50%) scale(1);
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        .password-toggle:hover {
            background-color: rgba(75, 169, 194, 0.1);
        }

        .reset-button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4BA9C2 0%, #3a8ba5 100%);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(75, 169, 194, 0.3);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .reset-button::before {
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

        .reset-button:hover {
            background: linear-gradient(135deg, #3a8ba5 0%, #2d6b82 100%);
            box-shadow: 0 8px 20px rgba(75, 169, 194, 0.5);
            transform: translateY(-2px) scale(1.02);
        }

        .reset-button:active::before {
            width: 300px;
            height: 300px;
        }

        .reset-button:active {
            background: linear-gradient(135deg, #2d6b82 0%, #1f5a6f 100%);
            transform: translateY(0) scale(0.98);
            box-shadow: 0 2px 8px rgba(75, 169, 194, 0.3);
        }

        .reset-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .reset-button:disabled:hover {
            transform: none;
            box-shadow: 0 4px 12px rgba(75, 169, 194, 0.3);
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

        .reset-button.loading .button-spinner {
            display: block;
        }

        .reset-button.loading .button-text {
            opacity: 0.7;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 4px 12px rgba(75, 169, 194, 0.3);
            }
            50% {
                box-shadow: 0 4px 20px rgba(75, 169, 194, 0.6);
            }
        }

        .reset-button:focus {
            outline: none;
            animation: pulse 2s ease-in-out infinite;
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .reset-card {
                background: rgba(31, 41, 55, 0.95);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05);
            }

            .input-field {
                background-color: rgba(55, 65, 81, 0.8);
                border-color: #4B5563;
                color: #F9FAFB;
            }

            .input-field:hover {
                border-color: #4BA9C2;
                box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
                transform: translateY(-1px);
                background-color: rgba(55, 65, 81, 0.95);
            }

            .input-field:focus {
                border-color: #4BA9C2;
                box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
                background-color: rgba(55, 65, 81, 1);
                transform: translateY(-2px);
            }

            .input-field:read-only {
                background-color: rgba(31, 41, 55, 0.6);
                color: #9CA3AF;
                border-color: #4B5563;
            }

            .input-field:read-only:hover {
                transform: none;
                box-shadow: none;
                border-color: #4B5563;
            }

            .input-field:read-only:focus {
                transform: none;
                box-shadow: none;
                border-color: #4B5563;
            }

            .input-field:read-only + .input-icon,
            .input-field:read-only ~ .input-icon {
                color: #6B7280;
            }

            .input-with-icon:has(.input-field:read-only):hover {
                transform: none;
            }

            .input-with-icon:has(.input-field:read-only):hover .input-icon {
                color: #6B7280;
                transform: translateY(-50%) scale(1);
            }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .reset-container {
                padding: 0.5rem 1rem;
            }

            .reset-card {
                padding: 1.5rem;
            }

            .reset-form-section {
                max-width: 100%;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="reset-container">
        <!-- Reset Password Form Card -->
        <div class="reset-form-section">
            <div class="reset-card">
                <div class="reset-form-container">

                    <!-- Logo Section -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/exact2.png') }}" 
                             alt="Exact Logo" 
                             class="mx-auto mb-3"
                             style="max-height: 110px; object-fit: contain;">

                        <!-- Tagline -->
                        <p class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide mt-2">
                            Enhancing Accuracy and Efficiency in IT Asset Management
                        </p>
        </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                    {{ session('status') }}
                </div>
            @endif

                    <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Please enter your new password below.') }}
                        </div>

                <!-- Email Address -->
                        <div class="mb-4">
                            <div class="input-with-icon">
                                <svg class="input-icon text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                                    class="input-field bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="Email"
                        value="{{ old('email', $email) }}" 
                        required 
                                    readonly
                        autocomplete="username"
                    >
                            </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                        <div class="mb-4">
                            <div class="input-with-icon">
                                <svg class="input-icon text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                                    class="input-field bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="Password"
                        required 
                                    autofocus
                        autocomplete="new-password"
                    >
                                <span class="password-toggle text-gray-500 dark:text-gray-400" onclick="togglePassword('password')">
                                    <svg id="eye-icon-password" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </span>
                            </div>
                    @error('password')
                        <div class="mt-1 text-sm text-red-600 dark:text-red-400">
                            <p class="font-medium mb-1">{{ $message }}</p>
                            <p class="font-medium mb-1 mt-2">Password requirements:</p>
                            <ul class="list-disc list-inside space-y-0.5 text-xs">
                                <li>Minimum 8 characters</li>
                                <li>At least one number (0-9) or symbol (!@#$%^&*...)</li>
                            </ul>
                        </div>
                    @enderror
                    @if(!$errors->has('password'))
                        <div class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                            <p class="font-medium mb-1">Password requirements:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                <li>Minimum 8 characters</li>
                                <li>At least one number (0-9) or symbol (!@#$%^&*...)</li>
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Confirm Password -->
                        <div class="mb-6">
                            <div class="input-with-icon">
                                <svg class="input-icon text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                                    class="input-field bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="Confirm Password"
                        required 
                        autocomplete="new-password"
                    >
                                <span class="password-toggle text-gray-500 dark:text-gray-400" onclick="togglePassword('password_confirmation')">
                                    <svg id="eye-icon-password_confirmation" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </span>
                            </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                        <!-- Submit Button -->
                        <button type="submit" class="reset-button" id="resetButton">
                            <span class="button-content">
                                <span class="button-spinner"></span>
                                <span class="button-text">Reset Password</span>
                            </span>
                    </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeIcon = document.getElementById('eye-icon-' + fieldId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                `;
            }
        }

        // Enhanced reset button interactivity
        document.addEventListener('DOMContentLoaded', function() {
            const resetButton = document.getElementById('resetButton');
            const resetForm = resetButton ? resetButton.closest('form') : null;
            
            if (resetForm && resetButton) {
                resetForm.addEventListener('submit', function(e) {
                    resetButton.classList.add('loading');
                    resetButton.disabled = true;
                });

                // Add ripple effect on click
                resetButton.addEventListener('click', function(e) {
                    const rect = resetButton.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    const ripple = document.createElement('span');
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                    ripple.style.width = '20px';
                    ripple.style.height = '20px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.style.transform = 'translate(-50%, -50%)';
                    ripple.style.animation = 'ripple 0.6s ease-out';
                    ripple.style.pointerEvents = 'none';
                    
                    resetButton.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            }
        });

        // Add ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: translate(-50%, -50%) scale(20);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
