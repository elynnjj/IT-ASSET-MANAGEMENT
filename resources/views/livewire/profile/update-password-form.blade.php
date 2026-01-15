<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[0-9!@#$%^&*(),.?":{}|<>]).+$/',
                ],
            ], [
                'password.min' => 'The password must be at least 8 characters.',
                'password.regex' => 'The password must contain at least one number or symbol.',
            ]);
        } catch (ValidationException $e) {
            // Don't reset fields on validation error - keep user input
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        // Dispatch browser event for Alpine.js to catch
        $this->dispatch('password-updated');
    }
}; ?>

<div>
<section>
    <header class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h3>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Ensure your account is using a long, random password to stay secure (mix 8 characters & at least one number or symbol).') }}
        </p>
    </header>

        {{-- Success message --}}
        <div x-data="{ shown: false }"
             @password-updated.window="shown = true; handlePasswordUpdated(); setTimeout(() => { shown = false; }, 5000);"
             x-show="shown"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             style="display: none;"
             class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-700 dark:text-green-300 font-medium">
                    {{ __('Password updated successfully.') }}
                </p>
            </div>
        </div>

    <form wire:submit="updatePassword" class="space-y-4">
        <div class="input-container">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-[15px]" />
                <div class="relative mt-1">
                    <x-text-input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" class="block w-full interactive-input password-input" autocomplete="current-password" />
                    <span class="password-toggle-profile text-gray-500 dark:text-gray-400" onclick="togglePasswordProfile('update_password_current_password')" style="pointer-events: auto;">
                        <svg id="eye-icon-update_password_current_password" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </span>
                </div>
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <div class="input-container">
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-[15px]" />
                <div class="relative mt-1">
                    <x-text-input wire:model="password" id="update_password_password" name="password" type="password" class="block w-full interactive-input password-input" autocomplete="new-password" />
                    <span class="password-toggle-profile text-gray-500 dark:text-gray-400" onclick="togglePasswordProfile('update_password_password')" style="pointer-events: auto;">
                        <svg id="eye-icon-update_password_password" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <div class="input-container">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-[15px]" />
                <div class="relative mt-1">
                    <x-text-input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full interactive-input password-input" autocomplete="new-password" />
                    <span class="password-toggle-profile text-gray-500 dark:text-gray-400" onclick="togglePasswordProfile('update_password_password_confirmation')" style="pointer-events: auto;">
                        <svg id="eye-icon-update_password_password_confirmation" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </span>
                </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <button type="submit" 
                class="interactive-button interactive-button-primary"
                style="padding: 10px 16px; font-size: 11px;">
                <span class="button-content">
                    <span class="button-text">{{ __('Save') }}</span>
                    <span class="button-spinner"></span>
                </span>
            </button>
        </div>
    </form>
</section>

    <style>
        .password-toggle-profile {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: background-color 0.2s ease;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: auto;
        }

        .password-toggle-profile:hover {
            background-color: rgba(75, 169, 194, 0.1);
        }

        .password-input {
            padding-right: 40px !important;
        }
    </style>

    <script>
        // Define toggle function globally so it's always available
        window.togglePasswordProfile = function(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            if (!passwordInput) {
                console.warn('Password input not found:', fieldId);
                return;
            }
            
            const eyeIcon = document.getElementById('eye-icon-' + fieldId);
            if (!eyeIcon) {
                console.warn('Eye icon not found:', 'eye-icon-' + fieldId);
                return;
            }
            
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
        };

        // Handle password updated event - clear fields
        window.handlePasswordUpdated = function() {
            // Clear all password input fields
            const currentPasswordInput = document.getElementById('update_password_current_password');
            const passwordInput = document.getElementById('update_password_password');
            const passwordConfirmationInput = document.getElementById('update_password_password_confirmation');

            if (currentPasswordInput) currentPasswordInput.value = '';
            if (passwordInput) passwordInput.value = '';
            if (passwordConfirmationInput) passwordConfirmationInput.value = '';

            // Reset password visibility to hidden
            const passwordInputs = ['update_password_current_password', 'update_password_password', 'update_password_password_confirmation'];
            passwordInputs.forEach(id => {
                const input = document.getElementById(id);
                if (input && input.type === 'text') {
                    input.type = 'password';
                    const eyeIcon = document.getElementById('eye-icon-' + id);
                    if (eyeIcon) {
                        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
                    }
                }
            });
        };

        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                // Functions are already defined globally above
            });
        }

        // Re-initialize after Livewire updates
        document.addEventListener('livewire:init', function() {
            // Function is already available globally
        });

        // Also handle Livewire morph updates
        document.addEventListener('livewire:morph-updated', function() {
            // Function is already available globally, no need to re-attach
        });
    </script>
</div>
