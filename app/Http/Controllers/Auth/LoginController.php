<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string', // email or role_id
            'password' => 'required|string|min:8',
        ]);

        $loginInput = $credentials['login'];
        $user = null;
        $role_id = null;

        // Check if input is email
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = \App\Models\User::where('email', $loginInput)->first();
        } else {
            // Try to find user by role-specific ID
            // Admin
            $admin = \App\Models\Admins::where('a_id', $loginInput)->first();
            if ($admin && $admin->user) {
                $user = $admin->user;
                $role_id = $admin->a_id;
            }
            // Student
            if (!$user) {
                $student = \App\Models\Student::where('s_id', $loginInput)->first();
                if ($student && $student->user) {
                    $user = $student->user;
                    $role_id = $student->s_id;
                }
            }
            // Parent
            if (!$user) {
                $parent = \App\Models\ParentModel::where('p_id', $loginInput)->first();
                if ($parent && $parent->user) {
                    $user = $parent->user;
                    $role_id = $parent->p_id;
                }
            }
            // Counselor
            if (!$user) {
                $counselor = \App\Models\Counselor::where('c_id', $loginInput)->first();
                if ($counselor && $counselor->user) {
                    $user = $counselor->user;
                    $role_id = $counselor->c_id;
                }
            }
        }

        if (!$user) {
            return back()->withErrors([
                'login' => 'No user found with this email or ID.',
            ]);
        }

        // Attempt login with found user's email and provided password
        if (Auth::attempt(['email' => $user->email, 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            // Check if user is active
            if ($user->status !== 'active') {
                return back()->withErrors([
                    'login' => 'Your account is not active.',
                ]);
            }

            // Determine role_id if not set
            if (!$role_id) {
                switch ($user->role) {
                    case 'admin':
                        $role_id = $user->admin ? $user->admin->a_id : null;
                        break;
                    case 'student':
                        $role_id = $user->student ? $user->student->s_id : null;
                        break;
                    case 'parent':
                        $role_id = $user->parentProfile ? $user->parentProfile->p_id : null;
                        break;
                    case 'counselor':
                        $role_id = $user->counselor ? $user->counselor->c_id : null;
                        break;
                }
            }
            session(['role_id' => $role_id, 'role' => $user->role]);

            // Redirect based on role
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/Head/dashboard')->with('success', 'Welcome back, Admin!');
                case 'student':
                    return redirect()->intended('/Student/dashboard')->with('success', 'Welcome back, Student!');
                case 'parent':
                    return redirect()->intended('/Parent/dashboard')->with('success', 'Welcome back, Parent!');
                case 'counselor':
                    return redirect()->intended('/Counselor/dashboard')->with('success', 'Welcome back, Counselor!');
                default:
                    return back()->withErrors([
                        'login' => 'Your role is not recognized.',
                    ]);
            }
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }
}


