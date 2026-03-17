<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admins;
use App\Models\Student;
use App\Models\ParentModel;
use App\Models\Counselor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB; // <-- ADDED: For sessions table access

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. SETUP: Define throttle key, max attempts, and lockout time (10 minutes)
        $loginInput = strtolower($request->input('login'));
        
        // Using SHA1 to generate a unique, clean throttling key based on login input and IP address.
        // This is standard practice in modern Laravel for custom limiters.
        $throttleKey = sha1($loginInput . '|' . $request->ip()); 

        $maxAttempts = 3;
        $decayMinutes = 10;

        // --- CENTRALIZED FAILURE HANDLER (Must throw ValidationException for AJAX to work) ---
        $handleFailure = function (string $message) use ($throttleKey, $maxAttempts, $decayMinutes, $loginInput) {
            // 2. INCREMENT ATTEMPTS: Record the failed attempt.
            RateLimiter::hit($throttleKey, $decayMinutes * 60);

            $remaining = $maxAttempts - RateLimiter::attempts($throttleKey);
            $waitSeconds = RateLimiter::availableIn($throttleKey);
            $waitMinutes = ceil($waitSeconds / 60);

            // Construct the failure message with attempt details
            if ($remaining > 0) {
                $message .= " You have {$remaining} attempts remaining before a {$decayMinutes}-minute lockout.";
            } elseif ($waitSeconds > 0) {
                $message = "Too many failed login attempts. Please try again in {$waitMinutes} minutes.";
            }
            
            // Throw exception, which returns a 422 JSON response
            // CRUCIAL: Explicitly set status to 422 to guarantee JSON response for AJAX failure
            // NOTE: Removed ->onlyInput('login') for compatibility with older/inconsistent Laravel versions.
            system_log('auth.login_failed', null, [
                'login' => $loginInput,
                'message' => $message,
            ]);
            throw ValidationException::withMessages([
                'login' => $message,
            ])->status(422);
        };

        // 3. CHECK RATE LIMIT: Check if the user is currently locked out.
        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            // Throw a ValidationException with a 429 status code for Too Many Requests
            throw ValidationException::withMessages([
                'login' => "Too many login attempts. Please try again in {$minutes} minutes.",
            ])->status(429);
        }

        // 4. VALIDATE CREDENTIALS
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = null;
        $role_id = null;

        // --- User Lookup Logic (unchanged) ---
        // Note: $loginInput is already lowercased from the initial setup, matching how Laravel typically handles lookups.
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
        } else {
            $admin = Admins::where('a_id', $loginInput)->first();
            if ($admin && $admin->user) {
                $user = $admin->user;
                $role_id = $admin->a_id;
            }
            if (!$user) {
                $student = Student::where('s_id', $loginInput)->first();
                if ($student && $student->user) {
                    $user = $student->user;
                    $role_id = $student->s_id;
                }
            }
            if (!$user) {
                $parent = ParentModel::where('p_id', $loginInput)->first();
                if ($parent && $parent->user) {
                    $user = $parent->user;
                    $role_id = $parent->p_id;
                }
            }
            if (!$user) {
                $counselor = Counselor::where('c_id', $loginInput)->first();
                if ($counselor && $counselor->user) {
                    $user = $counselor->user;
                    $role_id = $counselor->c_id;
                }
            }
        }

        // 5. Check if user exists
        if (!$user) {
            $handleFailure('No user found with this email or ID.');
        }

        // 6. Verify password
        if (!Hash::check($credentials['password'], $user->password)) {
            $handleFailure('The provided credentials do not match our records.');
        }

        // 7. Check account status (No rate limit hit here, as it's not a credentials failure)
        if (!is_null($user->activation_token)) {
            throw ValidationException::withMessages([
                'login' => 'Please verify your email before logging in. Check your inbox for the activation link.',
            ]);
        }
        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'login' => 'Your account is not active. Please contact the administrator.',
            ]);
        }
        
        // 8. Handle 2FA redirection
        if (!empty($user->two_factor_secret)) {
            RateLimiter::clear($throttleKey); // Clear attempts on successful credentials check
            $request->session()->regenerate();
            session(['2fa:user:id' => $user->id, 'pending_role_id' => $role_id, 'pending_role' => $user->role]);
            system_log('auth.login_2fa_challenge', $user, [
                'login' => $loginInput,
                'role' => $user->role,
            ]);
            
            // Return JSON response for client-side redirection
            // Assuming route('two-factor-challenge') is defined
            return response()->json(['redirect' => route('two-factor-challenge')], 200);
        }

        // 9. Final Login Attempt (No 2FA)
        if (Auth::attempt(['email' => $user->email, 'password' => $credentials['password']])) {
            
            // SINGLE SESSION CONTROL IMPLEMENTATION
            // Clear all existing sessions for this user ID. The new session will be created
            // by $request->session()->regenerate() below, making this the only active one.
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();

            RateLimiter::clear($throttleKey); // Clear attempts on successful login
            $request->session()->regenerate();

            // Determine role_id if not set
            if (!$role_id) {
                $role_id = match ($user->role) {
                    'admin' => $user->admin?->a_id,
                    'student' => $user->student?->s_id,
                    'parent' => $user->parentProfile?->p_id,
                    'counselor' => $user->counselor?->c_id,
                    default => null,
                };
            }
            session(['role_id' => $role_id, 'role' => $user->role]);

            // Determine redirect path
            $redirectPath = match ($user->role) {
                'admin' => '/Head/dashboard',
                'student' => '/Student/dashboard',
                'parent' => '/Parent/dashboard',
                'counselor' => '/Counselor/dashboard',
                default => null,
            };

            // 10. Final failure check for unrecognized role
            if (!$redirectPath) {
                Auth::logout();
                $handleFailure('Your role is not recognized.');
            }

            system_log('auth.login', $user, [
                'login' => $loginInput,
                'role' => $user->role,
            ]);

            // Return success JSON response with redirect path
            return response()->json([
                'redirect' => $redirectPath,
                'message' => 'Welcome back, ' . ucfirst($user->role) . '!',
            ], 200);
        }

        // Fallback for failed Auth::attempt (should be caught by Hash::check, but for safety)
        $handleFailure('The provided credentials do not match our records.');
    }
}
