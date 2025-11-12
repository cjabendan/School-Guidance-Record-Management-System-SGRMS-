<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $__env->yieldContent('title', 'SGRMS - School Guidance Records Management System'); ?></title>

    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('1.png')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-brands/css/uicons-brands.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/tailwind.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/font-awesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/maintenance.css')); ?>">
</head>

<body>
    <?php echo $__env->yieldContent('content'); ?>

    <div class="maintenance-container">
        <div class="maintenance-content">

            <div class="maintenance-icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="maintenance-icon">
                    <path fill-rule="evenodd"
                        d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0 1 12 6.75ZM4.117 19.125a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z"
                        clip-rule="evenodd" />
                    <path
                        d="m10.076 8.64-2.201-2.2V4.874a.75.75 0 0 0-.364-.643l-3.75-2.25a.75.75 0 0 0-.916.113l-.75.75a.75.75 0 0 0-.113.916l2.25 3.75a.75.75 0 0 0 .643.364h1.564l2.062 2.062 1.575-1.297Z" />
                    <path fill-rule="evenodd"
                        d="m12.556 17.329 4.183 4.182a3.375 3.375 0 0 0 4.773-4.773l-3.306-3.305a6.803 6.803 0 0 1-1.53.043c-.394-.034-.682-.006-.867.042a.589.589 0 0 0-.167.063l-3.086 3.748Zm3.414-1.36a.75.75 0 0 1 1.06 0l1.875 1.876a.75.75 0 1 1-1.06 1.06L15.97 17.03a.75.75 0 0 1 0-1.06Z"
                        clip-rule="evenodd" />
                </svg>

            </div>

            <h2 class="maintenance-header">We will be back soon!</h2>

            <div class="maintenance-subheader">
                Our system is currently under maintenance to improve your experience. Please check back soon. Thank you.
            </div>

            <div class="maintenance-flex" style="margin-top: .8rem;">
                <a href="<?php echo e(url('/')); ?>" class="maintenance-button" style="text-align: center;">
                    Return to Landing Page
                </a>
            </div>

        </div>
    </div>


</body>

</html>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/maintenance.blade.php ENDPATH**/ ?>