<section id="content">
    @include('partials.navbar')
    <div class="wrapper">
        <div class="settings-wrapper">
            
            <!-- Header -->
            <div class="settings-header">
                @if ($tab === 'system')
                    <h2>
                        <a wire:click="switchTab('profile')" class="reset-btn">Settings</a> > System Settings
                    </h2>
                    <p class="settings-subheading">Manage system settings and configuration.</p>
                @else
                    <h2>Settings</h2>
                    <p class="settings-subheading">Manage your profile and account settings</p>
                @endif
            </div>

            <div class="settings-flex-container">
                
                <!-- Sidebar -->
                <div class="settings-sidebar">
                    <ul class="settings-sidebar-list">
                        @if ($tab !== 'system')
                            <li><button wire:click="switchTab('profile')" class="{{ $tab === 'profile' ? 'active' : '' }}">Profile</button></li>
                            <li><button wire:click="switchTab('password')" class="{{ $tab === 'password' ? 'active' : '' }}">Password</button></li>
                            <li><button wire:click="switchTab('twofactor')" class="{{ $tab === 'twofactor' ? 'active' : '' }}">Two-Factor Auth</button></li>
                            @if ($role === 'admin')
                                <li><button wire:click="switchTab('system')" class="{{ $tab === 'system' ? 'active' : '' }}">System</button></li>
                            @endif
                        @else
                            <!-- System tabs -->
                            <li><button wire:click="switchTab('system', 'system')" class="{{ $subtab === 'system' ? 'active' : '' }}">System</button></li>
                            <li><button wire:click="switchTab('system', 'backup')" class="{{ $subtab === 'backup' ? 'active' : '' }}">Database Backup</button></li>
                            <li><button wire:click="switchTab('system', 'chatbot')" class="{{ $subtab === 'chatbot' ? 'active' : '' }}">Chat Bot</button></li>
                            <li><button wire:click="switchTab('system', 'counselor')" class="{{ $subtab === 'counselor' ? 'active' : '' }}">Counselor</button></li>
                            <li><button wire:click="switchTab('system', 'parent')" class="{{ $subtab === 'parent' ? 'active' : '' }}">Parent</button></li>
                            <li><button wire:click="switchTab('system', 'student')" class="{{ $subtab === 'student' ? 'active' : '' }}">Student</button></li>
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
                        @switch($subtab)
                            @case('system')
                                @livewire('settings.system-settings')
                                @break
                            @case('backup')
                                @livewire('settings.backup-database')
                                @break
                            @case('chatbot')
                                @livewire('settings.system-chatbot')
                                @break
                            @case('counselor')
                                @livewire('settings.system-counselor')
                                @break
                            @case('parent')
                                @livewire('settings.system-parent')
                                @break
                            @case('student')
                                @livewire('settings.system-student')
                                @break
                        @endswitch
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
