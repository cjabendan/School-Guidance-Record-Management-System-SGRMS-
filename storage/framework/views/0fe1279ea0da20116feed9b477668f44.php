<div class="auth-container">
    <div class="auth-content">
        <div class="auth-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="auth-icon">
                <path fill-rule="evenodd"
                    d="M11.484 2.17a.75.75 0 0 1 1.032 0 11.209 11.209 0 0 0 7.877 3.08.75.75 0 0 1 .722.515 12.74 12.74 0 0 1 .635 3.985c0 5.942-4.064 10.933-9.563 12.348a.749.749 0 0 1-.374 0C6.314 20.683 2.25 15.692 2.25 9.75c0-1.39.223-2.73.635-3.985a.75.75 0 0 1 .722-.516l.143.001c2.996 0 5.718-1.17 7.734-3.08ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75ZM12 15a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75v-.008a.75.75 0 0 0-.75-.75H12Z"
                    clip-rule="evenodd" />
            </svg>
        </div>

        <h2 class="auth-header">
            <?php echo e($recoveryCode ? 'Recovery Code' : 'Authentication Code'); ?>

        </h2>
        <p class="auth-subheader">
            <?php echo e($recoveryCode
                ? 'Please confirm access to your account by entering one of your emergency recovery codes.'
                : 'Enter the authentication code provided by your authenticator application.'); ?>

        </p>

        <!--[if BLOCK]><![endif]--><?php if($errorMessage): ?>
            <div class="auth-error"><?php echo e($errorMessage); ?></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <form wire:submit.prevent="verify" class="auth-flex">
            <div style="display:flex;gap:6px;justify-content:center;margin-top:.5rem;">
                <!--[if BLOCK]><![endif]--><?php if($recoveryCode): ?>
                    <input id="recoverycode" type="text" wire:model.defer="code"
                        autocomplete="current-password" class="auth-input" />
                <?php else: ?>
                    <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < 6; $i++): ?>
                        <input type="text" maxlength="1" wire:model.defer="verifyCodeDigits.<?php echo e($i); ?>"
                            oninput="moveNext(this)"
                            style="width:40px;height:50px;text-align:center;font-size:20px;border:1px solid #ccc;border-radius:8px;">
                    <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div style="margin-top: .5rem;">
                <button type="submit" class="auth-button" wire:loading.attr="disabled">
                    <span wire:loading.remove>Continue</span>
                    <span wire:loading>Verifying...</span>
                </button>
            </div>

            <div style="margin-top: .3rem; text-align: center; font-size: 0.9rem; color: #aaaaaa;">
                <!--[if BLOCK]><![endif]--><?php if($recoveryCode): ?>
                    <p>or you can <a wire:click.prevent="useAuthCode" href="#" class="auth-link">login using an authentication code</a></p>
                <?php else: ?>
                    <p>or you can <a wire:click.prevent="useRecoveryCode" href="#" class="auth-link">login using a recovery code</a></p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const firstInput = document.querySelector('.auth-flex input[type="text"]');
        if (firstInput) firstInput.focus();
    });

    function moveNext(el) {
        if (el.value.length === 1) {
            const next = el.nextElementSibling;
            if (next && next.tagName === 'INPUT') next.focus();
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && e.target.tagName === 'INPUT' && e.target.value === '') {
            const prev = e.target.previousElementSibling;
            if (prev && prev.tagName === 'INPUT') prev.focus();
        }
    });
</script>

<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/auth/two-factor-challenge.blade.php ENDPATH**/ ?>