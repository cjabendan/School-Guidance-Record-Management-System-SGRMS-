<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Feature;

class SystemStudent extends Component
{
    public $features = [];

    public function mount()
    {
        $this->loadFeatures();
    }

    protected function loadFeatures()
    {
        $this->features = Feature::where('role', 'student')->get()->toArray();
    }

    public function toggle($key)
    {
        $feature = Feature::where(['key' => $key, 'role' => 'student'])->first();
        if ($feature) {
            $feature->enabled = !$feature->enabled;
            $feature->save();
            $this->loadFeatures();
        }
    }

    public function render()
    {
        return view('livewire.settings.system-role', ['role' => 'student']);
    }
}
