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


class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $loginInput = $credentials['login'];
        $user = null;
        $role_id = null;

        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = \App\Models\User::where('email', $loginInput)->first();
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

        if (!$user) {
            return back()->withErrors([
                'login' => 'No user found with this email or ID.',
            ]);
        }

        if (Auth::attempt(['email' => $user->email, 'password' => $credentials['password']])) {
            $request->session()->regenerate();

          
            if (!is_null($user->activation_token)) {
                Auth::logout(); // log them out immediately
                return back()->withErrors([
                    'login' => 'Please verify your email before logging in. Check your inbox for the activation link.',
                ]);
            }

            // 🔒 Check if user is active
            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Your account is not active. Please contact the administrator.',
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
