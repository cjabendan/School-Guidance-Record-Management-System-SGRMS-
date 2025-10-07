<section id="content">
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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

                        <!--[if BLOCK]><![endif]--><?php if($role === 'admin'): ?>
                            <li><button wire:click="switchTab('system')">System</button></li>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </ul>
                </div>

                <!-- Right Content -->
                <div class="settings-right-content">
                    <!--[if BLOCK]><![endif]--><?php if($tab === 'profile'): ?>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.profile');

$__html = app('livewire')->mount($__name, $__params, 'lw-1356032485-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    <?php elseif($tab === 'password'): ?>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.password');

$__html = app('livewire')->mount($__name, $__params, 'lw-1356032485-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    <?php elseif($tab === 'twofactor'): ?>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.twofactor');

$__html = app('livewire')->mount($__name, $__params, 'lw-1356032485-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    <?php elseif($tab === 'system' && $role === 'admin'): ?>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.system');

$__html = app('livewire')->mount($__name, $__params, 'lw-1356032485-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

        </div>
    </div>
</section>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/settings.blade.php ENDPATH**/ ?>