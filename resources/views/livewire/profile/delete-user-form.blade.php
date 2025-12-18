<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section>
    <header class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h3>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="interactive-button interactive-button-danger"
        style="padding: 10px 16px; font-size: 11px;"
    >
        <span class="button-content">
            <span class="button-text">{{ __('Delete Account') }}</span>
        </span>
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6 input-container">
                <x-input-label for="password" value="{{ __('Password') }}" class="text-[15px]" />

                <x-text-input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full interactive-input"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex items-center justify-end space-x-6">
                <button 
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="interactive-button interactive-button-secondary"
                    style="padding: 10px 16px; font-size: 11px;"
                >
                    <span class="button-content">
                        {{ __('Cancel') }}
                    </span>
                </button>

                <button 
                    type="submit"
                    class="interactive-button interactive-button-danger"
                    style="padding: 10px 16px; font-size: 11px;"
                >
                    <span class="button-content">
                        <span class="button-text">{{ __('Delete Account') }}</span>
                        <span class="button-spinner"></span>
                    </span>
                </button>
            </div>
        </form>
    </x-modal>
</section>
