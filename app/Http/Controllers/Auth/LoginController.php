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
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Attempt login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user is active
            if ($user->status !== 'active') {

                return back()->withErrors([
                    'username' => 'Your account is not active.',
                ]);
            }

            // Redirect based on role
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/Head/dashboard')->with('success', 'Welcome back, Admin!');
                case 'student':
                    return redirect()->intended('/student/dashboard')->with('success', 'Welcome back, Student!');
                case 'parent':
                    return redirect()->intended('/parent/dashboard')->with('success', 'Welcome back, Parent!');
                case 'counselor':
                    return redirect()->intended('/counselor/dashboard')->with('success', 'Welcome back, Counselor!');
                default:
                    return back()->withErrors([
                        'email' => 'Your role is not recognized.',
                    ]);
            }
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }
}


