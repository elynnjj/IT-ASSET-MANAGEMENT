<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ManageLoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    // Main login route (standalone, no Livewire)
    Route::get('login', [ManageLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ManageLoginController::class, 'login'])->name('login.submit');
    
    // Backup login route (Livewire - kept for backup)
    Volt::route('login-backup', 'pages.auth.login')
        ->name('login.backup');

    Volt::route('register', 'pages.auth.register')
        ->name('register');

    // Main forgot password route (standalone, no Livewire)
    Route::get('forgot-password', [ManageLoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [ManageLoginController::class, 'sendPasswordResetLink'])->name('password.email');
    
    // Backup forgot password route (Livewire - kept for backup)
    Volt::route('forgot-password-backup', 'pages.auth.forgot-password')
        ->name('password.request.backup');

    // Main reset password route (standalone, no Livewire)
    Route::get('reset-password/{token}', [ManageLoginController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('reset-password', [ManageLoginController::class, 'resetPassword'])->name('password.update');
    
    // Backup reset password route (Livewire - kept for backup)
    Volt::route('reset-password-backup/{token}', 'pages.auth.reset-password')
        ->name('password.reset.backup');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});

// Logout route
Route::post('logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');
