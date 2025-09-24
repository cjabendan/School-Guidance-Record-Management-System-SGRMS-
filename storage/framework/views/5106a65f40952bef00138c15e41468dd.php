<!-- Loader Overlay -->
<?php echo $__env->make('components.loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="login-modal">
    <div class="modal">
        <div class="modal-left">
            <img class="modal-image" alt="Image" src="<?php echo e(asset('images/img/2.png')); ?>" />
        </div>
        <div class="modal-right">
            <!-- Close Button -->
            <button type="button" class="close-modal-btn" onclick="closeLoginModal()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
            <div class="logo">
                <img src="<?php echo e(asset('images/logo/logo.svg')); ?>" class="brand-logo" alt="SGRMS Logo">
            </div>
            <h2>Log in to your Account</h2>
            <p>Welcome! Please enter your credentials to log in.</p>
            <form action="<?php echo e(url('login')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="input-box" style="position:relative;">
                    <input type="text" name="login" class="input-box" placeholder="Email or Student ID"
                        value="<?php echo e(old('login')); ?>">
                    <span class="input-icon">
                        <i class="fas fa-user"></i>
                    </span>
                    <?php $__errorArgs = ['login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class='error'><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="input-box password-box" style="position:relative;">
                    <input type="password" name="password" class="input-box" id="login-password" placeholder="Password">
                    <span class="toggle-password" onclick="togglePassword()" tabindex="0">
                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                    </span>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class='error'><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="forgot-link">
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" class="log-btn">Login</button>
                <div class="signup-link">
                    <p>No account or a parent?<a href="<?php echo e(url('/register')); ?>">Sign up</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/auth/login.blade.php ENDPATH**/ ?>