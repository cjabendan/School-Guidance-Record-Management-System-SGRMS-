<script>
    const imageBase = "<?php echo e(asset('images/user')); ?>";
    const defaultImage = "<?php echo e(asset('images/user/default.png')); ?>";
</script>


<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>

<?php $__env->startSection('content'); ?>
    <section id="content">
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="wrapper">
            <div class="table-container">
                <div class="table-management">
                    <div class="table-nav">
                        <div class="table-filter">
                            <div class="filters">
                                <li>
                                    <a href="#" class="type-filter <?php echo e($type == 'all' ? 'active' : ''); ?>"
                                        data-type="all">All</a>
                                    <a href="#" class="type-filter <?php echo e($type == 'approved' ? 'active' : ''); ?>"
                                        data-type="approved">With Linked Students</a>
                                    <a href="#" class="type-filter <?php echo e($type == 'pending' ? 'active' : ''); ?>"
                                        data-type="pending">With Pending Requests</a>
                            </div>
                        </div>
                    </div>
                    <div class="search-bar">
                        <div class="table-search">
                            <form method="GET" action="">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                    placeholder="Search parents..." id="student-search-input">
                                <button type="submit" style="display:none"></button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="table-list">
                    <div class="table-header">
                        <div class="table-col">Parent Name</div>
                        <div class="table-col">Linked Students</div>
                        <div class="table-col">Pending Requests</div>
                        <div class="table-col ">Last Updated</div>
                        <div class="table-col ">Actions</div>
                    </div>

                    <div class="table" id="requests-table"></div>
                </div>

                <div id="requests-pagination" class="pagination"></div>
            </div>
        </div>
    </section>

    <?php echo $__env->make('Head.Modal.requestModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script src="<?php echo e(asset('js/requests.js')); ?>"></script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/requests.blade.php ENDPATH**/ ?>