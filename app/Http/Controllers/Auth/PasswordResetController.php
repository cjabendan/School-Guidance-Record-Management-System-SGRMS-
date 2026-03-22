<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Show the form where users can request a reset link.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle sending of the password reset link email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
        ]);

        if (function_exists('system_log')) {
            \system_log('auth.password_reset_link_requested', null, [
                'email' => $credentials['email'],
            ]);
        }

        $status = Password::sendResetLink($credentials);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        // Explicitly show error when email does not exist or cannot receive link
        return back()->withErrors([
            'email' => __($status),
        ]);
    }

    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Handle an incoming new password submission.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                if (function_exists('system_log')) {
                    \system_log('auth.password_reset', $user);
                }
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect('/?login=true')->with('status', __($status));
        }

        return back()->withErrors([
            'email' => __($status),
        ]);
    }
}

