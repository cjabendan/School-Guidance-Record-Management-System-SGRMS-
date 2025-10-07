<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Settings extends Component
{
    public string $tab = 'profile';
    public string $role;

    public function mount()
    {
        $this->role = Auth::user()->role;
    }

    public function switchTab($tab)
    {
        $this->tab = $tab;
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
