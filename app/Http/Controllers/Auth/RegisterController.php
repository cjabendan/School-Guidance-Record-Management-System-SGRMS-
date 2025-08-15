<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


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
            'username' => 'required|string|max:255|unique:users,username',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:16',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/',
                'confirmed',
            ],
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        $user->login_token = Str::random(32);
        $user->token_expires_at = now()->addHours(2);
        $user->save();

        Mail::to($user->email)->send(new WelcomeEmail($user));

        return redirect('/');
    }
}
