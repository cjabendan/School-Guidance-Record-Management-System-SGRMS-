<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class TwoFactor extends Component
{
    public bool $enabled = false;

    public function toggle()
    {
        $this->enabled = !$this->enabled;
      
    }


    public function render()
    {
        return view('livewire.settings.two-factor');
    }
}
