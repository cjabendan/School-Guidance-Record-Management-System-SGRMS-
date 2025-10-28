<div class="settings-form">
    <div class="settings-form-header">
        <p class="settings-form-heading">System</p>
        <p class="settings-form-subheading">
            Manage core configurations and toggle system-wide functionalities.
        </p>
    </div>

    
    <div class="recoveryCodes-container" style="display: flex; flex-direction: column; gap: 1.5rem; width: 70%;">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = array_intersect_key($systemSettings, array_flip(['maintenance_mode', 'registration'])); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="settings-flex-row" style="align-items: center; justify-content: space-between;">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <p class="settings-form-heading">
                            <?php echo e(Str::title(str_replace('_', ' ', $key))); ?>

                        </p>
                        <p class="settings-form-subheading">
                            Manage the <?php echo e(str_replace('_', ' ', $key)); ?> functionality across the system.
                        </p>
                    </div>

                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <button wire:click="toggle('<?php echo e($key); ?>')"
                            class="settings-form-button <?php echo e($value === 'on' ? 'Enabled' : 'Disabled'); ?>">
                            <!--[if BLOCK]><![endif]--><?php if($value === 'on'): ?>
                                <!-- Enabled -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="icons">
                                    <path fill-rule="evenodd"
                                        d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0 1 12 6.75ZM4.117 19.125a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z"
                                        clip-rule="evenodd" />
                                </svg>
                                Enabled
                            <?php else: ?>
                                <!-- Disabled -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="icons">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A3.75 3.75 0 0 0 12
                                        1.5a3.75 3.75 0 0 0-3.75 3.75V9m-3
                                        0h13.5a2.25 2.25 0 0 1 2.25 2.25v9
                                        a2.25 2.25 0 0 1-2.25 2.25H3
                                        A2.25 2.25 0 0 1 .75 20.25v-9
                                        A2.25 2.25 0 0 1 3 9Z" />
                                </svg>
                                Disabled
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </button>

                        <p class="settings-form-subheading" style="font-size: 14px;">
                            <!--[if BLOCK]><![endif]--><?php if($key === 'maintenance_mode'): ?>
                                <!--[if BLOCK]><![endif]--><?php if($value === 'on'): ?>
                                    When enabled, the system enters maintenance mode, restricting access for non-admin
                                    users.
                                <?php else: ?>
                                    When disabled, the system operates normally and remains accessible to all users.
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php elseif($key === 'registration'): ?>
                                <!--[if BLOCK]><![endif]--><?php if($value === 'on'): ?>
                                    When enabled, new users can register and create accounts through the registration
                                    page.
                                <?php else: ?>
                                    When disabled, new user registration is closed to the public.
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/settings/system-settings.blade.php ENDPATH**/ ?>