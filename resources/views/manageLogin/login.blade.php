<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ExactAsset - Login</title>
    
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

        .login-container {
            position: relative;
            min-height: 100vh;
            width: 100vw;
            background-image: url('{{ asset('images/bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            overflow: hidden;
        }

        .login-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{ asset('images/bg2.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            opacity: 0;
            transition: opacity 0.6s ease-in-out;
            z-index: 0;
            pointer-events: none;
        }

        .login-container.bg-forgot::after {
            opacity: 1;
        }

        /* Overlay for better readability */
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.2) 100%);
            z-index: 1;
        }

        .login-form-section {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 100%;
            margin: 0;
        }

        .form-slider-container {
            position: relative;
            width: 100vw;
            overflow: hidden;
        }

        .form-slider-wrapper {
            display: flex;
            width: 200vw;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
        }

        .form-slider-wrapper.show-forgot {
            transform: translateX(-50%);
        }

        .form-slide {
            width: 50%;
            flex-shrink: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            min-height: 100vh;
        }

        .form-slide:first-child {
            justify-content: flex-start;
            padding-left: 4rem;
            padding-right: 2rem;
        }

        .form-slide:last-child {
            justify-content: flex-end;
            padding-left: 2rem;
            padding-right: 4rem;
        }

        .form-slide .login-card {
            width: 100%;
            max-width: 480px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
            padding: 3rem;
            animation: slideInLeft 0.6s ease-out;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .login-form-container {
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

        .login-button {
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

        .login-button::before {
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

        .login-button:hover {
            background: linear-gradient(135deg, #3a8ba5 0%, #2d6b82 100%);
            box-shadow: 0 8px 20px rgba(75, 169, 194, 0.5);
            transform: translateY(-2px) scale(1.02);
        }

        .login-button:active::before {
            width: 300px;
            height: 300px;
        }

        .login-button:active {
            background: linear-gradient(135deg, #2d6b82 0%, #1f5a6f 100%);
            transform: translateY(0) scale(0.98);
            box-shadow: 0 2px 8px rgba(75, 169, 194, 0.3);
        }

        .login-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .login-button:disabled:hover {
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

        .login-button.loading .button-spinner {
            display: block;
        }

        .login-button.loading .button-text {
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

        .login-button:focus {
            outline: none;
            animation: pulse 2s ease-in-out infinite;
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .login-card {
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
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .login-container {
                padding: 0;
            }

            .login-card {
                padding: 2rem;
            }

            .login-form-section {
                max-width: 100%;
            }

            .form-slide:first-child {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            .form-slide:last-child {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            .form-slide .login-card {
                max-width: 100%;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="login-container">
        <!-- Form Slider Container -->
        <div class="login-form-section">
            <div class="form-slider-container">
                <div class="form-slider-wrapper" id="formSlider">
                    <!-- Login Form Slide -->
                    <div class="form-slide">
            <div class="login-card">
                <div class="login-form-container">

                <!-- Logo Section -->
                <div class="text-center mb-8">
                    <img src="{{ asset('images/exact2.png') }}" 
                         alt="Exact Logo" 
                         class="mx-auto mb-6"
                         style="max-height: 130px; object-fit: contain;">

                    <!-- Tagline -->
                    <p class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide mt-4">
                        Enhancing Accuracy and Efficiency in IT Asset Management
                    </p>
                    <br><br>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Validation Errors (Login only) -->
                @if ($errors->has('userID') || $errors->has('password'))
                    <div class="mb-4">
                        <div class="text-sm text-red-600 dark:text-red-400">
                            @if ($errors->has('userID'))
                                @foreach ($errors->get('userID') as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            @endif
                            @if ($errors->has('password'))
                                @foreach ($errors->get('password') as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <!-- Username -->
                    <div class="mb-4">
                        <div class="input-with-icon">
                            <svg class="input-icon text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <input 
                                id="userID"
                                type="text"
                                name="userID"
                                class="input-field bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Username"
                                value="{{ old('userID') }}"
                                required
                                autofocus
                            >
                        </div>
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
                            >

                            <span class="password-toggle text-gray-500 dark:text-gray-400" onclick="togglePassword()">
                                <svg id="eye-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <label for="remember" class="inline-flex items-center">
                            <input 
                                id="remember" 
                                type="checkbox" 
                                name="remember" 
                                class="rounded border-gray-400 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700"
                            >
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="#" onclick="showForgotPassword(); return false;" class="text-sm hover:opacity-80" style="color: #4BA9C2; cursor: pointer;">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-button" id="loginButton">
                        <span class="button-content">
                            <span class="button-spinner"></span>
                        <span class="button-text">Login</span>
                        </span>
                    </button>
                </form>

                            </div>
                        </div>
                    </div>

                    <!-- Forgot Password Form Slide -->
                    <div class="form-slide">
                        <div class="login-card">
                            <div class="login-form-container">

                                <!-- Logo Section -->
                                <div class="text-center mb-8">
                                    <img src="{{ asset('images/exact2.png') }}" 
                                         alt="Exact Logo" 
                                         class="mx-auto mb-6"
                                         style="max-height: 130px; object-fit: contain;">

                                    <!-- Tagline -->
                                    <p class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide mt-4">
                                        Enhancing Accuracy and Efficiency in IT Asset Management
                                    </p>
                                    <br><br>
                                </div>

                                <!-- Session Status -->
                                @if (session('status'))
                                    <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <!-- Forgot Password Form -->
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf

                                    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('Forgot your password? No problem. Just enter your email and we will send a password reset link.') }}
                                    </div>

                                    <!-- Email Address -->
                                    <div class="mb-6">
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
                                                value="{{ old('email') }}"
                                                required 
                                                autofocus 
                                                autocomplete="email"
                                            >
                                        </div>

                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="login-button" id="forgotPasswordButton">
                                        <span class="button-content">
                                            <span class="button-spinner"></span>
                                            <span class="button-text">Email Password Reset Link</span>
                                        </span>
                                    </button>

                                    <!-- Back to Login -->
                                    <div class="flex items-center justify-center mt-6">
                                        <a href="#" onclick="showLogin(); return false;" class="text-sm hover:opacity-80" style="color: #4BA9C2; cursor: pointer;">
                                            < Back to Login
                                        </a>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
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

        // Enhanced login button interactivity
        document.addEventListener('DOMContentLoaded', function() {
            const loginButton = document.getElementById('loginButton');
            const loginForm = loginButton ? loginButton.closest('form') : null;
            
            if (loginForm && loginButton) {
                loginForm.addEventListener('submit', function(e) {
                    // Add loading state
                    loginButton.classList.add('loading');
                    loginButton.disabled = true;
                    
                    // Optional: Add a small delay to show the animation before form submission
                    // This gives visual feedback even if the form submits quickly
                    setTimeout(function() {
                        if (loginButton.disabled) {
                            // Form is still processing, keep loading state
                        }
                    }, 100);
                });
                
                // Add ripple effect on click
                loginButton.addEventListener('click', function(e) {
                    const rect = loginButton.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    // Create ripple element
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
                    
                    loginButton.appendChild(ripple);
                    
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

        // Slider functions
        function showForgotPassword() {
            const slider = document.getElementById('formSlider');
            const container = document.querySelector('.login-container');
            if (slider) {
                slider.classList.add('show-forgot');
            }
            if (container) {
                container.classList.add('bg-forgot');
            }
        }

        function showLogin() {
            const slider = document.getElementById('formSlider');
            const container = document.querySelector('.login-container');
            if (slider) {
                slider.classList.remove('show-forgot');
            }
            if (container) {
                container.classList.remove('bg-forgot');
            }
        }

        // Handle forgot password form submission and auto-show on errors
        document.addEventListener('DOMContentLoaded', function() {
            const forgotPasswordButton = document.getElementById('forgotPasswordButton');
            const forgotPasswordForm = forgotPasswordButton ? forgotPasswordButton.closest('form') : null;
            
            if (forgotPasswordForm && forgotPasswordButton) {
                forgotPasswordForm.addEventListener('submit', function(e) {
                    forgotPasswordButton.classList.add('loading');
                    forgotPasswordButton.disabled = true;
                });
            }

            // Auto-show forgot password form if there are email errors or old email input
            @php
                $hasEmailErrors = $errors->has('email');
                $hasOldEmail = old('email') !== null;
            @endphp
            const hasEmailErrors = @json($hasEmailErrors);
            const hasOldEmail = @json($hasOldEmail);
            const urlParams = new URLSearchParams(window.location.search);
            const showForgot = urlParams.get('forgot') === '1';

            if (hasEmailErrors || hasOldEmail || showForgot) {
                // Small delay to ensure DOM is ready
                setTimeout(function() {
                    showForgotPassword();
                }, 100);
                    }
        });
    </script>
</body>
</html>
