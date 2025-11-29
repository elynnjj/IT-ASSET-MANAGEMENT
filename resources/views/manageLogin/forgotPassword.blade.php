<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Forgot Password</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .forgot-password-container {
            display: flex;
            min-height: 100vh;
        }
        .forgot-password-form-section {
            flex: 0 0 40%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .forgot-password-image-section {
            flex: 0 0 60%;
            background-image: url('{{ asset('images/bg2.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .forgot-password-form-container {
            width: 100%;
            max-width: 420px;
        }
        .input-with-icon {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
        }
        .input-field {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1px solid rgba(229, 231, 235, 0.47);
            border-radius: 6px;
            font-size: 14px;
        }
        .input-field:focus {
            outline: none;
            border-color: #4BA9C2;
            ring: 2px;
            ring-color: #4BA9C2;
        }
        .submit-button {
            width: 100%;
            padding: 12px;
            background-color: #4BA9C2;
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .submit-button:hover {
            background-color: #3a8ba5;
        }
        .submit-button:active {
            background-color: #2d6b82;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="forgot-password-container">
        <!-- Left Section - Forgot Password Form -->
        <div class="forgot-password-form-section bg-white dark:bg-gray-800">
            <div class="forgot-password-form-container">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <img src="{{ asset('images/exact2.png') }}" alt="Exact Logo" class="mx-auto mb-4" style="max-height: 80px; object-fit: contain;">
                    <div class="w-24 h-1 mx-auto mb-4" style="background-color: #4BA9C2;"></div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide mt-4">Enhancing Accuracy and Efficiency in IT Asset Management</p><br><br>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4">
                        <div class="text-sm text-red-600 dark:text-red-400">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Forgot Password Form -->
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>

                    <!-- Email Address -->
                    <div class="mb-6">
                        <div class="input-with-icon">
                            <svg class="input-icon text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                class="input-field bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600" 
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
                    <button type="submit" class="submit-button">
                        Email Password Reset Link
                    </button><br><br>

                    <!-- Back to Login -->
                    <div class="flex items-center justify-center mb-6">
                        <a href="{{ route('login') }}" class="text-sm hover:opacity-80" style="color: #4BA9C2;">
                            Back to login
                        </a>
                    </div>
                    
                </form>
            </div>
        </div>
        
        <!-- Right Section - Image -->
        <div class="forgot-password-image-section"></div>
    </div>
</body>
</html>

