<div class="settings-form">
    <div class="settings-form-header">
        <p class="settings-form-heading"><?php echo e(ucfirst($role)); ?> Settings</p>
        <p class="settings-form-subheading">
            Manage which system features are available to the <?php echo e($role); ?> account type.
        </p>
    </div>

    <div class="settings-flex-row">
        <div style="display: flex; flex-direction: column; gap: 1rem; width: 58%;">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <!--[if BLOCK]><![endif]--><?php if(!($role === 'counselor' && $feature['key'] === 'request')): ?>
                    <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                        <span class="text-base font-medium"><?php echo e($feature['name']); ?></span>
                        <button wire:click="toggle('<?php echo e($feature['key']); ?>')"
                            class="settings-form-button <?php echo e($feature['enabled'] ? '' : 'secondary'); ?>">
                            <!--[if BLOCK]><![endif]--><?php if($feature['enabled']): ?>
                                <!-- Shield Check (Enabled) -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="icons">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6
                                    11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29
                                    9 11.623 5.176-1.332 9-6.03
                                    9-11.622 0-1.31-.21-2.571-.598-3.751h-.152
                                    c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                </svg>
                                Enabled
                            <?php else: ?>
                                <!-- Lock (Disabled) -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="icons">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                                Disabled
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </button>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
    <div class="mt-3 text-sm text-gray-500" wire:loading>
        Saving changes...
    </div>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/settings/system-role.blade.php ENDPATH**/ ?>