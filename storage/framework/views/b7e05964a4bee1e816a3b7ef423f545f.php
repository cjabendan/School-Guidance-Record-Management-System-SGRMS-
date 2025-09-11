<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>

    <!-- MAIN CONTENT -->
    <section id="content">
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- COUNSELORS MANAGEMENT -->
        <div class="wrapper">
            <h2>Manage Counselors</h2>
            <div class="profiles-container">
                <!-- Add new profile box -->
                <div class="profile-box add-box" onclick="openAddCounselorModal()">
                    <i class='bx bx-plus add-profile-icon'></i>
                    <h2>Add Counselor</h2>
                </div>

                <?php echo $__env->make('components.counselor-card', ['counselors' => $counselors], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            </div>
        </div>
    </section>

    <?php echo $__env->make('Head.modal.counselModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script src="<?php echo e(asset('js/head.js')); ?>"></script>
    <script src="<?php echo e(asset('js/Modal/counselModal.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views\Head\profiling\counselors.blade.php ENDPATH**/ ?>