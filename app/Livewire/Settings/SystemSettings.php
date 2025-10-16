<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\SystemSetting;

class SystemSettings extends Component
{
    public $systemSettings = [];

    public function mount()
    {
        $this->systemSettings = SystemSetting::pluck('value', 'key')->toArray();
    }

    public function toggle($key)
    {
        $current = SystemSetting::getValue($key);
        $new = $current === 'on' ? 'off' : 'on';
        SystemSetting::setValue($key, $new);
        $this->systemSettings[$key] = $new;
    }

    public function render()
    {
        return view('livewire.settings.system-settings');
    }
}
