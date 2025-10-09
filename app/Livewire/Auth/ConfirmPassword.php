<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ConfirmPassword extends Component
{
    public $password = '';
    public $showPassword = false;
    public $attempts = 0;
    public $maxAttempts = 3;

    public function mount()
    {
        $this->attempts = Session::get('confirm_attempts', 0);
    }

    public function confirm()
    {
        $user = Auth::user();

        $this->attempts++;
        Session::put('confirm_attempts', $this->attempts);

        if ($this->attempts > $this->maxAttempts) {
            Session::forget('confirm_attempts');
            Auth::logout();
            Session::invalidate();
            Session::regenerateToken();
            session()->flash('error', 'Too many failed attempts. Please log in again.');

            Session::forget('url.intended');
            return redirect()->to('/?login=true');
        }

        if (! $user || ! Hash::check($this->password, $user->password)) {
             $this->reset('password');
            $this->addError('password', "Incorrect password. Attempt {$this->attempts} of {$this->maxAttempts}.");
            return;
        }

        // success
        Session::forget('confirm_attempts');
        Session::put('auth.password_confirmed_at', time());

        return redirect()->intended(route('settings'));
    }

    public function render()
    {
        return view('livewire.auth.confirm-password')->layout('layouts.auth');
    }
}
