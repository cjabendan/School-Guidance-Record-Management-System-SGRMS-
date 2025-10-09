<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\ParentModel;


class RegisterController extends Controller
{

    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        User::where('token_expires_at', '<', now())->update([
            'login_token' => null,
            'token_expires_at' => null,
        ]);

        $data = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phonenumber' => [
                'required',
                'unique:users,contact_num',
                'regex:/^\d{10}$/',
            ],
            'gender' => 'required|in:Male,Female',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:16',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/',
                'confirmed',
            ],
        ]);

        // Force role to parent and status to pending
        $data['role'] = 'parent';
        $data['status'] = 'pending';
        $data['password'] = bcrypt($data['password']);
        $data['contact_num'] = '+63' . $data['phonenumber'];
        $data['sex'] = $data['gender'];


        unset($data['phonenumber'], $data['gender']);

        // 1. Create user (parent, pending)
        $user = User::create($data);

        if (empty($user->profile_image)) {
            $user->profile_image = $user->sex === 'Male'
                ? 'male.png'
                : 'female.png';
        }

        // Generate activation token for email verification
        $user->activation_token = Str::random(64);
        $user->activation_token_expires_at = now()->addHours(2);
        $user->save();

        // 2. Create parent profile linked to user (save user_id in parent table)
        $parent = ParentModel::create([
            'user_id' => $user->id,
            'relationship' => $request->guardian_relationship ?? '',
        ]);


        // 3. Send welcome email with activation link
        $activationLink = url('/activate/' . $user->activation_token);
        Mail::to($user->email)->send(new WelcomeEmail($user, $activationLink));

        session(['registered_email' => $user->email]);

        return redirect('/verify-email')
            ->with('success', 'Your parent account has been created! Please check your email for the activation link.')
            ->with('registered_email', $user->email);
    }

    public function showVerificationEmail()
    {
        return view('auth.verify-email');
    }

    // Handle activation link
    public function activate($token)
    {
        $user = User::where('activation_token', $token)
            ->where('activation_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect('/verify')->with('error', 'Invalid or expired activation link.');
        }

        $user->email_verified_at = now();
        $user->status = 'active';
        $user->activation_token = null;
        $user->activation_token_expires_at = null;
        $user->save();

        // Send success email
        Mail::to($user->email)->send(new \App\Mail\SuccessEmail($user));

        return redirect('/success-verification')->with('success', 'Your email has been verified! You can now log in.');
    }

    // Resend activation link
    public function resendActivationLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $user = User::where('email', $request->email)->first();

        // Only resend if not already active
        if ($user->status === 'active') {
            return back()->with('error', 'Account already activated.');
        }

        // Generate new activation token
        $user->activation_token = Str::random(64);
        $user->activation_token_expires_at = now()->addHours(2);
        $user->save();

        $activationLink = url('/activate/' . $user->activation_token);
        Mail::to($user->email)->send(new WelcomeEmail($user, $activationLink));

        return back()->with('success', 'A new activation link has been sent to your email.');
    }


    public function showSuccessEmail()
    {
        return view('auth.success-verification');
    }
}
