    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
   

    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/bar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/table.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/counsel.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('css/counseling.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/announcements.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/appointments.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/caseModal.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/case.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/student.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/requests.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/messages.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/notify.css')); ?>">
    <link href="<?php echo e(asset('css/cropper.min.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldContent('head'); ?>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
</head>
<body>

    <!-- SIDEBAR -->

    <?php echo $__env->make('partials.head-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldContent('content'); ?>



</body>

<?php echo $__env->yieldPushContent('scripts'); ?>

       
</body>

<?php echo $__env->yieldPushContent('scripts'); ?>

<script src="<?php echo e(asset('js/cropper.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/head.js')); ?>"></script>
<script src="<?php echo e(asset('js/dashboard.js')); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/layouts/main.blade.php ENDPATH**/ ?>