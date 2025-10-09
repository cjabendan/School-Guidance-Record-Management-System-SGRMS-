<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Profile extends Component
{
    public $name = '';
    public $middle_name = '';
    public $last_name = '';
    public $email = '';
    public $num = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->first_name;
        $this->middle_name = $user->middle_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->num = $user->contact_num;
    }

    public function save()
    {
        $user = Auth::user();

        $rules = [
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'num' => ['nullable','string'],
        ];

        // Only admins may change name parts in the UI — validate if present
        if ($user->role === 'admin') {
            $rules['name'] = ['required','string','max:255'];
            $rules['middle_name'] = ['nullable','string','max:255'];
            $rules['last_name'] = ['nullable','string','max:255'];
        }

        $this->validate($rules);

        // Apply changes
        if ($user->role === 'admin') {
            $user->first_name = $this->name;
            $user->middle_name = $this->middle_name;
            $user->last_name = $this->last_name;
        }

        $user->email = $this->email;
        $user->contact_num = $this->num;
        $user->save();

        session()->flash('success', 'Profile updated successfully.');
        $this->mount();
    }

    public function render()
    {
        return view('livewire.settings.profile');
    }
}

