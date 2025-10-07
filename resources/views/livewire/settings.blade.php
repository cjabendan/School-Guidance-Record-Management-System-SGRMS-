<section id="content">
    @include('partials.navbar')
    <div class="wrapper">
        <div class="settings-wrapper">
            <div class="settings-header">
                <h2>Settings</h2>
                <p class="settings-subheading">Manage your profile and account settings</p>
            </div>

            <div class="settings-flex-container">
                <!-- Sidebar -->
                <div class="settings-sidebar">
                    <ul class="settings-sidebar-list">
                        <li><button wire:click="switchTab('profile')">Profile</button></li>
                        <li><button wire:click="switchTab('password')">Password</button></li>
                        <li><button wire:click="switchTab('twofactor')">Two-Factor Auth</button></li>

                        @if ($role === 'admin')
                            <li><button wire:click="switchTab('system')">System</button></li>
                        @endif
                    </ul>
                </div>

                <!-- Right Content -->
                <div class="settings-right-content">
                    @if ($tab === 'profile')
                        @livewire('settings.profile')
                    @elseif ($tab === 'password')
                        @livewire('settings.password')
                    @elseif ($tab === 'twofactor')
                        @livewire('settings.twofactor')
                    @elseif ($tab === 'system' && $role === 'admin')
                        @livewire('settings.system')
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
