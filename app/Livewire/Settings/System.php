<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Feature;
use App\Models\SystemSetting;

class System extends Component
{
    public $features = [];
    public $systemSettings = [];

    public function mount()
    {
        // Initialize default features
        $defaultFeatures = [
            ['key' => 'chat', 'name' => 'Chat'],
            ['key' => 'appointment', 'name' => 'Appointment'],
            ['key' => 'request', 'name' => 'Request'],
        ];

        foreach ($defaultFeatures as $feature) {
            Feature::firstOrCreate(['key' => $feature['key']], ['name' => $feature['name']]);
        }

        // Initialize default system settings
        $defaultSystemSettings = [
            'maintenance_mode' => 'off',
            'registration_open' => 'on',
        ];

        $roles = ['admin', 'counselor', 'parent', 'student'];

        foreach ($roles as $role) {
            foreach ($defaultFeatures as $feature) {
                Feature::firstOrCreate(
                    ['key' => $feature['key'], 'role' => $role],
                    ['name' => $feature['name'], 'enabled' => true]
                );
            }
        }

        $this->loadData();
    }

    protected function loadData()
    {
        $this->features = Feature::orderBy('role')->orderBy('id')->get()->toArray();
        $this->systemSettings = SystemSetting::pluck('value', 'key')->toArray();
    }

    public function toggleSystem($key)
    {
        $current = SystemSetting::getValue($key);
        $newValue = $current === 'on' ? 'off' : 'on';

        SystemSetting::setValue($key, $newValue);
        $this->loadData();

        $this->dispatch('system-toggled', key: $key, status: $newValue);
    }


    public function toggleFeature($key, $role)
    {
        $feature = Feature::where(['key' => $key, 'role' => $role])->firstOrFail();
        $feature->enabled = !$feature->enabled;
        $feature->save();

        cache()->forget("feature_{$role}_{$key}");
        $this->loadData();
    }


    public function render()
    {
        return view('livewire.settings.system');
    }
}
