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
    $data['role'] = 'parent'; 

    // 1. Create user
    $user = User::create($data);

    $user->login_token = Str::random(32);
    $user->token_expires_at = now()->addHours(2);
    $user->save();


    // 2. Create parent profile linked to user (save user_id in parent table)
    $parent = ParentModel::create([
        'user_id' => $user->id,
        'guardian_name' => $user->first_name . ' ' . $user->last_name,
        'relationship' => $request->guardian_relationship ?? '',
        'contact_num' => '', // You can add a field for this in the form if needed
        'email' => $user->email,
    ]);

    // 3. Send welcome email
    Mail::to($user->email)->send(new WelcomeEmail($user));

    return redirect('/')->with('success', 'Your parent account has been created!');
}
}
