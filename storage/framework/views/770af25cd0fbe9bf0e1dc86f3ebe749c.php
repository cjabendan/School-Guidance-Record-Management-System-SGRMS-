<div class="settings-form">
    <div class="settings-form-header">
        <p class="settings-form-heading">Two-Factor Authentication</p>
        <p class="settings-form-subheading">Add an extra layer of security to your account.</p>
    </div>

    <div class="settings-flex-row">
        <p>
            <span class="twoFactor-status <?php echo e($enabled ? 'Enabled' : 'Disabled'); ?>">
                <?php echo e($enabled ? 'Enabled' : 'Disabled'); ?>

            </span>
        </p>
    </div>

    <?php echo $__env->make('livewire.modals.qr-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('livewire.modals.auth-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div>
        <!--[if BLOCK]><![endif]--><?php if($enabled): ?>
            <div style="display: flex; flex-direction: column; gap: 1.5rem; width: 58%;">
                <div>
                    <p class="settings-form-subheading" style="font-size: 16px;">
                        When two-factor authentication is enabled, you will be prompted for a secure,
                        random pin during login, which you can retrieve from your authenticator app.
                    </p>
                </div>

                
                <div class="recoverycodes-container">
                    <div class="recoverycodes-header">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="icons">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <p class="settings-form-heading" style="font-size: 18px;">2FA Recovery Codes</p>
                    </div>

                    <p class="settings-form-subheading" style="font-size: 14px;">
                        Recovery codes let you regain access if you lose your 2FA device. Store them in a secure
                        password manager.
                    </p>

                    <div
                        style="margin-top: 1rem; display: flex; gap: 1rem; align-items: center; justify-content: space-between;">
                        <button wire:click="toggleRecoveryCodes" class="settings-form-button">
                            <!--[if BLOCK]><![endif]--><?php if($showRecoveryCodes): ?>
                                <!-- Eye Slash (Hide) -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="icons">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12
                19.5c.993 0 1.953-.138 2.863-.395M6.228
                6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773
                3.162 10.065 7.498a10.522 10.522 0 0
                1-4.293 5.774M6.228 6.228 3 3m3.228
                3.228 3.65 3.65m7.894 7.894L21
                21m-3.228-3.228-3.65-3.65m0
                0a3 3 0 1 0-4.243-4.243m4.242
                4.242L9.88 9.88" />
                                </svg>
                                Hide Recovery Codes
                            <?php else: ?>
                                <!-- Eye (Show) -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="icons">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36
                4.5 12 4.5c4.638 0 8.573 3.007
                9.963 7.178.07.207.07.431 0
                .639C20.577 16.49 16.64 19.5
                12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                View Recovery Codes
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </button>


                        <!--[if BLOCK]><![endif]--><?php if($showRecoveryCodes): ?>
                            <button wire:click="regenerateRecoveryCodes" class="settings-form-button secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="icons">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Regenerate Codes
                            </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!--[if BLOCK]><![endif]--><?php if($showRecoveryCodes): ?>
                        <div class="recoverycodes-list">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $recoveryCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p><?php echo e($code); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <p class="settings-form-subheading" style="font-size: 14px;">
                            Each recovery code can be used once to access your account and will be removed after use. If
                            you need more, click Regenerate Codes above.
                        </p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        <?php else: ?>
            <p class="settings-form-subheading" style="width: 48%;">
                When you enable two-factor authentication, you will be prompted to scan a QR code or set up your account
                using a Google Authenticator app.
            </p>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <div>
        <button wire:click="toggle" class="settings-form-button <?php echo e($enabled ? 'Disabled' : ''); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="icons">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
            </svg>
            <p><?php echo e($enabled ? 'Disable' : 'Enable'); ?> 2FA</p>
        </button>

    </div>

</div>

<script>
    function copySecret() {
        const text = <?php echo \Illuminate\Support\Js::from($secret)->toHtml() ?>;
        navigator.clipboard.writeText(text)
            .then(() => alert('Copied to clipboard'))
            .catch(() => alert('Copy failed'));
    }
</script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/settings/two-factor.blade.php ENDPATH**/ ?>