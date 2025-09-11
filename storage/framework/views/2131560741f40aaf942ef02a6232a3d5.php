<div class="container">
    <div style="text-align:center; margin-bottom:20px;">
        <img src="<?php echo e(asset('images/logo/logo.png')); ?>" alt="SGRMS Logo" style="max-width:120px;">
    </div>
    <h1>Welcome to SGRMS, <?php echo e($user->first_name); ?>!</h1>
    <p>Your account has been created using your email (<strong><?php echo e($user->email); ?></strong>).</p>
    <p>To activate your account, please click the link below:</p>
    <div class="verifBox">
        <a href="<?php echo e($activationLink); ?>">Activate My Account</a>
    </div>
    <p><strong>Note:</strong> This link is valid for only 2 hours.</p>
    <p>If you did not request this account, please ignore this email.</p>
    <p>Best regards,<br>The SGRMS Team</p>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views\emails\welcome_verification.blade.php ENDPATH**/ ?>