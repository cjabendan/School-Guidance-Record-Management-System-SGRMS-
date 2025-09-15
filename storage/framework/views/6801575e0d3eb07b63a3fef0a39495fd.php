
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
                                    <a href="#" class="type-filter <?php echo e($type == 'child-link' ? 'active' : ''); ?>"
                                        data-type="child-link">Child Link</a>
                                    <a href="#" class="type-filter <?php echo e($type == 'document' ? 'active' : ''); ?>"
                                        data-type="document">Documents</a>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="filter-wrapper">
                        <!-- Dropdown Filter -->
                        <div class="filter-dropdown">
                            <button class="toggle-btn" id="toggle-view-btn">
                                <i class="fi fi-br-bars-filter" id="toggle-icon"></i>
                            </button>

                            <ul class="dropdown-menu" id="status-dropdown">
                                <li data-status="approved">Approved</li>
                                <li data-status="pending">Pending</li>
                                <li data-status="rejected">Rejected</li>
                                <li data-status="all">All</li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="table-list">
                    <div class="table-header">
                        <div class="table-col type">Request Type</div>
                        <div class="table-col requested-by">Requested By</div>
                        <div class="table-col requested-at">Requested At</div>
                        <div class="table-col status">Status</div>
                        <div class="table-col actions">Actions</div>
                    </div>

                    <div class="table">
                        <?php $__currentLoopData = $allRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="table-card">

                                <div class="table-col type"><?php echo e($req['display_type']); ?></div>
                                <div class="table-col requested-by"><?php echo e($req['parent_name']); ?></div>
                                <div class="table-col requested-at"><?php echo e($req['requested_at']); ?></div>
                                <div class="table-col status">
                                    <?php
                                        $status = strtolower($req['status']);
                                        $dotClass = match ($status) {
                                            'approved' => 'status-dot status-approved',
                                            'rejected' => 'status-dot status-declined',
                                            'pending' => 'status-dot status-pending',
                                            default => 'status-dot',
                                        };
                                        $labelClass = match ($status) {
                                            'approved' => 'status-label status-approved',
                                            'rejected' => 'status-label status-declined',
                                            'pending' => 'status-label status-pending',
                                            default => 'status-label',
                                        };
                                    ?>
                                    <span class="<?php echo e($labelClass); ?>">
                                        <span class="<?php echo e($dotClass); ?>"></span>
                                        <?php echo e(ucfirst($req['status'])); ?>

                                    </span>
                                </div>
                                <div class="table-col actions">
                                    <?php if($req['status'] === 'Pending'): ?>
                                        <a href="#" class="review-btn" data-id="<?php echo e($req['id']); ?>"
                                            data-type="<?php echo e(strtolower($req['type'])); ?>">Review</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php echo $__env->make('Head.Modal.requestModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php $__env->startPush('scripts'); ?>
        <script src="<?php echo e(asset('js/requests.js')); ?>"></script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/Head/requests.blade.php ENDPATH**/ ?>