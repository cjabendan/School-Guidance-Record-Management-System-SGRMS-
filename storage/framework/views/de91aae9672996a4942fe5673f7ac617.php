<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>

    <!-- MAIN CONTENT -->
    <section id="content">
     <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="wrapper">
            <!-- HEAD -->
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                </div>
            </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.counselor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views\Counselor\dashboard.blade.php ENDPATH**/ ?>