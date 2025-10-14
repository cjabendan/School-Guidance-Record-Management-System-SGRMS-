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

        // normalize displayed phone: strip +63 or any non-digits so input holds exactly 10 digits
        $raw = $user->contact_num ?? '';
        $digits = preg_replace('/\D+/', '', $raw);
        if (str_starts_with($digits, '63') && strlen($digits) > 10) {
            $digits = substr($digits, -10);
        }
        $this->num = $digits;
    }

    public function save()
    {
        $user = Auth::user();

        $rules = [
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'num' => ['required', 'regex:/^[0-9]{10}$/'],
        ];

        // Only admins may change name parts in the UI — validate if present
        if ($user->role === 'admin') {
            $rules['name'] = ['required','string','max:255'];
            $rules['middle_name'] = ['nullable','string','max:255'];
            $rules['last_name'] = ['nullable','string','max:255'];
        }

        $this->validate($rules);

        // Additional uniqueness check for stored format (+63XXXXXXXXXX)
        $fullNum = null;
        if ($this->num !== null && $this->num !== '') {
            $digits = preg_replace('/\D+/', '', $this->num);
            if (strlen($digits) !== 10) {
                $this->addError('num', 'Phone number must be exactly 10 digits.');
                return;
            }
            $fullNum = '+63' . $digits;

            $exists = \App\Models\User::where('contact_num', $fullNum)
                ->where('id', '!=', $user->id)
                ->exists();

            if ($exists) {
                $this->addError('num', 'The phone number has already been taken.');
                return;
            }
        }

        // Apply changes
        if ($user->role === 'admin') {
            $user->first_name = $this->name;
            $user->middle_name = $this->middle_name;
            $user->last_name = $this->last_name;
        }

        $user->email = $this->email;
        $user->contact_num = $fullNum;
        $user->save();

        session()->flash('success', 'Profile updated successfully.');
        $this->mount();
    }

    public function render()
    {
        return view('livewire.settings.profile');
    }
}

