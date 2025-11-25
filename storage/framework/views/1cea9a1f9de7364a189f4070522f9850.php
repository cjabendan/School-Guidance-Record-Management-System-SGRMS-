
<?php if(!empty($user->is_signup)): ?>
    <div class="container">
        <h1>Congratulations, <?php echo e($user->first_name); ?>!</h1>
        <p>Your email has been successfully verified and your account is now active.</p>
        <p>You can now log in and access your account.</p>
        <p>Best regards,<br>The SGRMS Team</p>
    </div>
<?php else: ?>
    <div class="container">
        <h2>Welcome, <?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>!</h2>
        <p>Your parent account has been successfully created and is now active.</p>
        <p>You can now log in using your email: <strong><?php echo e($user->email); ?></strong></p>
        <p>If you have any questions, please contact the school guidance office.</p>
        <br>
        <p>Thank you!</p>
    </div>
<?php endif; ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/emails/success_email.blade.php ENDPATH**/ ?>