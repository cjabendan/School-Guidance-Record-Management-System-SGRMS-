<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>

    <!-- MAIN CONTENT -->
    <section id="content">
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="dashboard-content">
            <div class="box-page">
                <section class="analytics">
                      <?php echo $__env->make('Head.dashboard-sections.stats', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </section>

                <!-- ACTIVITIES -->
                <section class="side-container">
                    <div class="flex-side">
                        <?php echo $__env->make('Head.dashboard-sections.events', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('Head.dashboard-sections.messages', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>

                </section>

                <!-- APPOINTMENTS -->
                <section class="bottom-container">
                    <div class="flex-bottom">
                        <?php echo $__env->make('Head.dashboard-sections.appointments', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('Head.dashboard-sections.requests', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </section>
            </div>
        </div>

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterDropdown = document.querySelector('.appointments-header .dropdown');
            filterDropdown.addEventListener('change', function() {
                showSmallLoader('#appointments-table-container');
                fetch(`<?php echo e(route('Head.dashboard')); ?>?filter=${this.value}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('appointments-table-container').innerHTML = data.html;
                        hideSmallLoader('#appointments-table-container');
                    })
                    .catch(() => hideSmallLoader('#appointments-table-container'));
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views\Head\dashboard.blade.php ENDPATH**/ ?>