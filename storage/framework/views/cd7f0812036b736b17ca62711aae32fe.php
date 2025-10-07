<section id="content">
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="wrapper">
        <div>
            <h2>Settings</h2>
            <p>Manage your profile and account settings</p>

            <nav>
                <button wire:click="$set('tab', 'profile')">Profile</button>
                <button wire:click="$set('tab', 'password')">Password</button>
                <button wire:click="$set('tab', 'twofactor')">Two-Factor Auth</button>
                <button wire:click="$set('tab', 'system')">System</button>
            </nav>

            <div>
                <!--[if BLOCK]><![endif]--><?php if($tab === 'profile'): ?>
                    <?php echo $__env->make('livewire.head.settings.profile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php elseif($tab === 'password'): ?>
                    <?php echo $__env->make('livewire.head.settings.password', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php elseif($tab === 'twofactor'): ?>
                    <?php echo $__env->make('livewire.head.settings.twofactor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php elseif($tab === 'systemsettings'): ?>
                    <?php echo $__env->make('livewire.head.settings.system', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/head/settings.blade.php ENDPATH**/ ?>