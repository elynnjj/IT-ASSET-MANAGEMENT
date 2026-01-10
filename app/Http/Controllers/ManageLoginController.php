<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ManageLoginController
{
    /**
     * Display the login form.
     */
    public function showLoginForm(): View
    {
        return view('manageLogin.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'userID' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $this->ensureIsNotRateLimited($request);

        try {
            if (!Auth::attempt($request->only('userID', 'password'), $request->boolean('remember'))) {
                RateLimiter::hit($this->throttleKey($request));

                throw ValidationException::withMessages([
                    'userID' => trans('auth.failed'),
                ]);
            }
        } catch (\RuntimeException $e) {
            // Handle invalid password hash format (e.g., non-Bcrypt passwords)
            if (str_contains($e->getMessage(), 'This password does not use the Bcrypt algorithm')) {
                RateLimiter::hit($this->throttleKey($request));
                
                // Try to find the user to check if they exist
                $user = User::where('userID', $request->userID)->first();
                
                if ($user) {
                    // User exists but has invalid password hash - redirect to password reset
                    throw ValidationException::withMessages([
                        'userID' => 'Your password needs to be reset. Please use the "Forgot Password" link to reset your password.',
                    ]);
                } else {
                    throw ValidationException::withMessages([
                        'userID' => trans('auth.failed'),
                    ]);
                }
            }
            // Re-throw if it's a different RuntimeException
            throw $e;
        }

        // Check if user is active
        $user = Auth::user();
        if ($user && $user->accStat !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'userID' => 'Your account has been deactivated. Please contact the administrator.',
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        $request->session()->regenerate();

        // Check if user must change password on first login
        if ($user && $user->firstLogin) {
            return redirect()->route('password.change');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'userID' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('userID')).'|'.$request->ip());
    }

    /**
     * Display the forgot password form.
     */
    public function showForgotPasswordForm(): View
    {
        return view('manageLogin.forgotPassword');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function sendPasswordResetLink(Request $request): RedirectResponse
    {
        try {
        $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);
        } catch (ValidationException $e) {
            // Redirect to login page with errors to keep forgot password form visible
            return redirect()->route('login')
                ->withErrors($e->errors())
                ->withInput($request->only('email'));
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            // Redirect to login page with errors to keep forgot password form visible
            return redirect()->route('login')
                ->withErrors(['email' => __($status)])
                ->withInput($request->only('email'));
        }

        return redirect()->route('login')->with('status', __($status));
    }

    /**
     * Display the password reset form.
     */
    public function showResetPasswordForm(Request $request, string $token): View
    {
        return view('manageLogin.resetPassword', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
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

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return redirect()->route('login')->with('status', __($status));
    }

    /**
     * Display the force password change form.
     */
    public function showChangePasswordForm(): View
    {
        $user = Auth::user();
        
        if (!$user || !$user->firstLogin) {
            return redirect()->route('dashboard');
        }

        return view('manageLogin.changePassword');
    }

    /**
     * Handle an incoming password change request (for first login).
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user || !$user->firstLogin) {
            return redirect()->route('dashboard');
        }

        $request->validate([
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

        // Update password and clear firstLogin flag
        $user->forceFill([
            'password' => Hash::make($request->password),
            'firstLogin' => false,
        ])->save();

        return redirect()->route('dashboard')
            ->with('status', 'Password changed successfully. You can now access the system.');
    }
}

