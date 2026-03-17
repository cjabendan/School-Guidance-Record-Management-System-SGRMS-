<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Password extends Component
{
    public $current_password = '';
    public $new_password = '';
    public $confirm_password = '';

    public function save()
    {
        $this->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'string', 'same:new_password'],
        ], [
            'confirm_password.same' => 'The password confirmation does not match.',
        ]);

        $user = Auth::user();

        if (! Hash::check($this->current_password, $user->password)) {
            session()->flash('error', 'Current password is incorrect.');
            return;
        }

        $user->password = Hash::make($this->new_password);
        $user->save();
        system_log('auth.password_changed', $user);

        // Clear fields and flash success
        $this->current_password = $this->new_password = $this->confirm_password = '';
        session()->flash('success', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.settings.password');
    }
}
