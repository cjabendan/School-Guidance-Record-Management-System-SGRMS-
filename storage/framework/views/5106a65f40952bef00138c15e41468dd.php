<?php echo $__env->make('components.loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="login-modal">
    <div class="modal">
        <div class="modal-left">
         
            <img class="modal-image" alt="Image" src="<?php echo e(asset('images/img/2.png')); ?>" />
        </div>
        <div class="modal-right">
          
            <button type="button" class="close-modal-btn" onclick="closeLoginModal()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
            <div class="logo">
                <img src="<?php echo e(asset('images/logo/logo.svg')); ?>" class="brand-logo" alt="SGRMS Logo">
            </div>
            <h2>Log in to your Account</h2>
            <p>Welcome! Please enter your credentials to log in.</p>
           
            <form id="loginForm">
                <?php echo csrf_field(); ?>
                <div class="input-box" style="position:relative;">
                    <!-- Added id for easy JS targeting -->
                    <input type="text" name="login" class="input-box" id="loginInput" placeholder="Email or Student ID"
                        value="<?php echo e(old('login')); ?>">
                    <span class="input-icon">
                        <i class="fas fa-user"></i>
                    </span>
                 
                </div>
                 <span class='error' id="loginError">
                        <?php $__errorArgs = ['login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </span>
                <div class="input-box password-box" style="position:relative;">
                    <!-- Added id for easy JS targeting -->
                    <input type="password" name="password" class="input-box" id="login-password" placeholder="Password">
                    <span class="toggle-password" onclick="togglePassword()" tabindex="0">
                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                    </span>
                
                    <span class='error' id="passwordError">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </span>
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

<script>
    // Utility function to toggle password visibility (assuming this was already defined elsewhere)
    function togglePassword() {
        const passwordInput = document.getElementById('login-password');
        const icon = document.getElementById('togglePasswordIcon');
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !isPassword);
        icon.classList.toggle('fa-eye-slash', isPassword);
    }

    // Function to clear all previous error messages
    function clearErrors() {
        document.getElementById('loginError').textContent = '';
        document.getElementById('passwordError').textContent = '';
        document.getElementById('loginInput').classList.remove('is-invalid');
        document.getElementById('login-password').classList.remove('is-invalid');
        // You might need to add/remove 'is-invalid' class if you use it for styling
    }

    // Function to show the loader (assuming 'showLoader' is defined in the included component)
    function showLoader() {
        // Implement or ensure this function exists, e.g., document.getElementById('loader').style.display = 'flex';
        console.log('Loader shown.');
    }

    // Function to hide the loader (assuming 'hideLoader' is defined in the included component)
    function hideLoader() {
        // Implement or ensure this function exists, e.g., document.getElementById('loader').style.display = 'none';
        console.log('Loader hidden.');
    }

    document.getElementById('loginForm').addEventListener('submit', async function (e) {
        e.preventDefault(); // Stop the default page refresh
        clearErrors();
        showLoader();

        const form = e.target;
        const formData = new FormData(form);

        try {
            const response = await fetch('<?php echo e(url('login')); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'), // Ensure CSRF token is sent
                    'Accept': 'application/json', // Request a JSON response
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) { // Status 200 - Successful login or 2FA redirection
                // Success: Redirect the user
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // Fallback to intended dashboard (shouldn't happen with the controller logic)
                    window.location.reload();
                }

            } else if (response.status === 422 || response.status === 429) { // Status 422 (Validation) or 429 (Rate Limit)
                // Failure: Display validation errors
                if (data.errors) {
                    // Handle 'login' field errors (for credentials, rate limiting, and account status)
                    if (data.errors.login) {
                        document.getElementById('loginError').textContent = data.errors.login[0];
                        document.getElementById('loginInput').classList.add('is-invalid');
                    }
                    // Handle 'password' field errors (less likely with the current controller, but good practice)
                    if (data.errors.password) {
                        document.getElementById('passwordError').textContent = data.errors.password[0];
                        document.getElementById('login-password').classList.add('is-invalid');
                    }
                }
                
            } else { // Handle other non-200 errors (e.g., 500 server error)
                document.getElementById('loginError').textContent = 'An unexpected error occurred. Please try again.';
            }

        } catch (error) {
            console.error('Login Error:', error);
            document.getElementById('loginError').textContent = 'Could not connect to the server.';
        } finally {
            hideLoader();
        }
    });
</script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/auth/login.blade.php ENDPATH**/ ?>