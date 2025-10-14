<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Settings extends Component
{
    public string $tab = 'profile';
    public string $role;

    protected $queryString = ['tab' => ['except' => 'profile']];

    public function mount()
    {
        $this->role = Auth::user()->role;
    }

    public function switchTab($newTab)
    {
        $sensitiveTabs = ['twofactor', 'system'];

        if (in_array($newTab, $sensitiveTabs)) {
            $timeout = config('auth.password_timeout', 900);

            if (!Session::has('auth.password_confirmed_at') || time() - Session::get('auth.password_confirmed_at') > $timeout) {
                Session::put('url.intended', route('settings', ['tab' => $newTab]));
                return redirect()->route('confirm-password');
            }
        }

        $this->tab = $newTab;
    }

    public function render()
    {
        $layout = match ($this->role) {
            'admin' => 'layouts.main',
            'counselor' => 'layouts.counselor',
            'parent' => 'layouts.parent',
            'student' => 'layouts.student',
            default => 'layouts.main',
        };

        return view('livewire.settings')->layout($layout);
    }
}

