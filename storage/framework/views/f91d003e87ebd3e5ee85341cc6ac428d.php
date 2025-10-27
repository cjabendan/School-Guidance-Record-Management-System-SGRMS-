<section id="content">
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="wrapper">
        <div class="settings-wrapper">
            
            <!-- Header -->
            <div class="settings-header">
                <!--[if BLOCK]><![endif]--><?php if($tab === 'system'): ?>
                    <h2>
                        <a wire:click="switchTab('profile')" class="reset-btn">Settings</a> > System Settings
                    </h2>
                    <p class="settings-subheading">Manage system settings and configuration.</p>
                <?php else: ?>
                    <h2>Settings</h2>
                    <p class="settings-subheading">Manage your profile and account settings</p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="settings-flex-container">
                
                <!-- Sidebar -->
                <div class="settings-sidebar">
                    <ul class="settings-sidebar-list">
                        <!--[if BLOCK]><![endif]--><?php if($tab !== 'system'): ?>
                            <li><button wire:click="switchTab('profile')" class="<?php echo e($tab === 'profile' ? 'active' : ''); ?>">Profile</button></li>
                            <li><button wire:click="switchTab('password')" class="<?php echo e($tab === 'password' ? 'active' : ''); ?>">Password</button></li>
                            <li><button wire:click="switchTab('twofactor')" class="<?php echo e($tab === 'twofactor' ? 'active' : ''); ?>">Two-Factor Auth</button></li>
                            <!--[if BLOCK]><![endif]--><?php if($role === 'admin'): ?>
                                <li><button wire:click="switchTab('system')" class="<?php echo e($tab === 'system' ? 'active' : ''); ?>">System</button></li>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php else: ?>
                            <!-- System tabs -->
                            <li><button wire:click="switchTab('system', 'system')" class="<?php echo e($subtab === 'system' ? 'active' : ''); ?>">System</button></li>
                            <li><button wire:click="switchTab('system', 'backup')" class="<?php echo e($subtab === 'backup' ? 'active' : ''); ?>">Database Backup</button></li>
                            <li><button wire:click="switchTab('system', 'chatbot')" class="<?php echo e($subtab === 'chatbot' ? 'active' : ''); ?>">Chat Bot</button></li>
                            <li><button wire:click="switchTab('system', 'counselor')" class="<?php echo e($subtab === 'counselor' ? 'active' : ''); ?>">Counselor</button></li>
                            <li><button wire:click="switchTab('system', 'parent')" class="<?php echo e($subtab === 'parent' ? 'active' : ''); ?>">Parent</button></li>
                            <li><button wire:click="switchTab('system', 'student')" class="<?php echo e($subtab === 'student' ? 'active' : ''); ?>">Student</button></li>
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
                        <!--[if BLOCK]><![endif]--><?php switch($subtab):
                            case ('system'): ?>
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.system-settings');

$__html = app('livewire')->mount($__name, $__params, 'lw-1356032485-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                <?php break; ?>
                            <?php case ('backup'): ?>
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.backup-database');

$__html = app('livewire')->mount($__name, $__params, 'lw-1356032485-4', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                <?php break; ?>
                            <?php case ('chatbot'): ?>
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.system-chatbot');

$__html = app('livewire')->mount($__name, $__params, 'lw-1356032485-5', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                <?php break; ?>
                            <?php case ('counselor'): ?>
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.system-counselor');

$__html = app('livewire')->mount($__name, $__params, 'lw-1356032485-6', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                <?php break; ?>
                            <?php case ('parent'): ?>
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.system-parent');

$__html = app('livewire')->mount($__name, $__params, 'lw-1356032485-7', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                <?php break; ?>
                            <?php case ('student'): ?>
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.system-student');

$__html = app('livewire')->mount($__name, $__params, 'lw-1356032485-8', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                <?php break; ?>
                        <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

        </div>
    </div>
</section>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/settings.blade.php ENDPATH**/ ?>