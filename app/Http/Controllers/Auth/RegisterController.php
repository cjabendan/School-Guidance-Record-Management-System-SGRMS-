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

        // 1. Create user (parent, pending)
        $user = User::create($data);

        $user->login_token = Str::random(32);
        $user->token_expires_at = now()->addHours(2);
        // Generate a 6-digit verification code
        $user->verification_code = random_int(100000, 999999);
        $user->verification_code_expires_at = now()->addHours(2);
        $user->save();

        // 2. Create parent profile linked to user (save user_id in parent table)
        $parent = ParentModel::create([
            'user_id' => $user->id,
            'relationship' => $request->guardian_relationship ?? '',
        ]);

        // 3. Send welcome email with verification code
        Mail::to($user->email)->send(new WelcomeEmail($user));

        return redirect('/verify')->with('success', 'Your parent account has been created! Please check your email for the verification code.');
    }
}
