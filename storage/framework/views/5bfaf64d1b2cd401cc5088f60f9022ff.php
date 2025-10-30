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

    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">

    <?php echo $__env->yieldContent('head'); ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body>

    <?php echo $__env->yieldContent('content'); ?>

    <?php echo e($slot ?? ''); ?>



    

    <script>
        window.userId = <?php echo json_encode(auth()->check() ? auth()->id() : null, 15, 512) ?>;
    </script>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>


</html>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/layouts/auth.blade.php ENDPATH**/ ?>