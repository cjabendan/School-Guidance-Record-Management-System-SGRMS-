<?php $__env->startSection('title', 'Forgot Password - SGRMS'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('components.loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="auth-container">
        <div class="auth-content">
            <div class="auth-icon-wrapper">
                <i class="fas fa-unlock-alt auth-icon"></i>
            </div>
            <h2 class="auth-header">Forgot your password?</h2>
            <p class="auth-subheader">
                Enter your email address and we will send you a link to reset your password.
            </p>

            <?php if(session('status')): ?>
                <p class="auth-subheader" style="color: #16a34a; font-weight: 500;">
                    <?php echo e(session('status')); ?>

                </p>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.email')); ?>" class="auth-form">
                <?php echo csrf_field(); ?>

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
                           value="<?php echo e(old('email')); ?>"
                           class="auth-input"
                           required
                           autofocus
                           autocomplete="email">
                </div>

                <button type="submit" class="auth-button">
                    Send reset link
                </button>

                <p class="auth-subheader" style="margin-top: 0.75rem;">
                    Remembered your password?
                    <a href="<?php echo e(url('/?login=true')); ?>" class="auth-link">Back to login</a>
                </p>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>