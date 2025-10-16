<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Feature;

class SystemCounselor extends Component
{
    public $features = [];

    public function mount()
    {
        $this->loadFeatures();
    }

    protected function loadFeatures()
    {
        $this->features = Feature::where('role', 'counselor')->get()->toArray();
    }

    public function toggle($key)
    {
        $feature = Feature::where(['key' => $key, 'role' => 'counselor'])->first();
        if ($feature) {
            $feature->enabled = !$feature->enabled;
            $feature->save();
            $this->loadFeatures();
        }
    }

    public function render()
    {
        return view('livewire.settings.system-role', ['role' => 'counselor']);
    }
}
