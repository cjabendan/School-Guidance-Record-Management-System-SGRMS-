<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>

    <!-- MAIN CONTENT -->
    <section id="content">
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="wrapper">
            <div class="dashboard-content">
                <section class="welcome-box">
                    <?php echo $__env->make('Counselor.dashboard-sections.welcome-stats', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('Counselor.dashboard-sections.todo', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </section>

                <!-- RIGHT COLUMN: Messages (spans both rows) -->
                <section class="side-container">
                    <div class="flex-side">
                        <?php echo $__env->make('Counselor.dashboard-sections.messages', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </section>

                <!-- LEFT BOTTOM: Appointments -->
                <section class="bottom-container">
                    <div class="flex-bottom">
                        <?php echo $__env->make('Counselor.dashboard-sections.appointments', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </section>
            </div>
        </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.counselor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Counselor/dashboard.blade.php ENDPATH**/ ?>