<?php $__env->startSection('title', 'Reset Password - SGRMS'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('components.loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="auth-container">
        <div class="auth-content">
            <div class="auth-icon-wrapper">
                <i class="fas fa-key auth-icon"></i>
            </div>
            <h2 class="auth-header">Reset your password</h2>
            <p class="auth-subheader">
                Create a new password for your account.
            </p>

            <form method="POST" action="<?php echo e(route('password.update')); ?>" class="auth-form">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="token" value="<?php echo e($token); ?>">

                <div class="auth-flex">
                    <div class="auth-label-row">
                        <label for="email" class="auth-label">Email address</label>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="auth-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <input id="email"
                           type="email"
                           name="email"
                           value="<?php echo e(old('email', $email)); ?>"
                           class="auth-input"
                           required
                           autocomplete="email">
                </div>

                <div class="auth-flex">
                    <div class="auth-label-row">
                        <label for="password" class="auth-label">New password</label>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="auth-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <input id="password"
                           type="password"
                           name="password"
                           class="auth-input"
                           required
                           autocomplete="new-password">
                </div>

                <div class="auth-flex">
                    <div class="auth-label-row">
                        <label for="password_confirmation" class="auth-label">Confirm new password</label>
                    </div>
                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           class="auth-input"
                           required
                           autocomplete="new-password">
                </div>

                <button type="submit" class="auth-button">
                    Reset password
                </button>

                <p class="auth-subheader" style="margin-top: 0.75rem;">
                    Remembered your password?
                    <a href="<?php echo e(url('/?login=true')); ?>" class="auth-link">Back to login</a>
                </p>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/auth/reset-password.blade.php ENDPATH**/ ?>