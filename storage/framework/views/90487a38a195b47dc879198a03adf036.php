<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?php echo $__env->yieldContent('title', 'SGRMS - School Guidance Records Management System'); ?></title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('1.png')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/tailwind.css')); ?>">
    <link href="<?php echo e(asset('css/font-awesome.min.css')); ?>" rel="stylesheet" />
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-chubby/css/uicons-solid-chubby.css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-straight/css/uicons-solid-straight.css'>

    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/landing.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/announcements.css')); ?>">
    <?php echo $__env->yieldContent('head'); ?>
     <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

     <script>
        window.addEventListener('pageshow', async () => {
            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'same-origin'
                });
                console.log('CSRF cookie refreshed');
            } catch (e) {
                console.warn('Failed to refresh CSRF:', e);
            }
        });
    </script>

</head>

<body>


    <?php echo $__env->yieldContent('content'); ?>
    

    <?php if(session('status')): ?>
        <div id="global-status-banner" style="
            position: fixed;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            background-color: #16a34a;
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 999px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            z-index: 9999;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        ">
            <i class="fas fa-check-circle"></i>
            <span><?php echo e(session('status')); ?></span>
        </div>
        <script>
            // Auto-open login (if available) after 3 seconds when a status message exists,
            // e.g., after a successful password reset.
            window.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    var banner = document.getElementById('global-status-banner');
                    if (banner) {
                        banner.style.opacity = '0';
                        banner.style.transition = 'opacity 0.3s ease';
                        setTimeout(function() {
                            banner.remove();
                        }, 300);
                    }

                    if (typeof openLoginModal === 'function') {
                        openLoginModal();
                    }
                }, 3000);
            });
        </script>
    <?php endif; ?>

    <?php echo $__env->make('auth.login', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <script src="<?php echo e(asset('js/landing.js')); ?>"></script>
   
</body>

</html>



<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/layouts/app.blade.php ENDPATH**/ ?>