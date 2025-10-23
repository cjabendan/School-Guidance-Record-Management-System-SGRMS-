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
                                    <a href="#" class="active" data-type="all">All</a>
                                    <a href="#" data-type="link">Child Link</a>
        
                                </li>
                            </div>
                        </div>
                    </div>
                    <button class="toggle-btn" id="toggle-view-btn">
                        <i class="fi fi-br-bars-filter" id="toggle-icon"></i>
                        <span id="toggle-label"></span>
                    </button>
                </div>
                <div class="table-list">
                    <div class="table-header">
                        <div class="table-col type">Request Type</div>
                        <div class="table-col requested-for">Requested For</div>
                        <div class="table-col requested-at">Requested At</div>
                        <div class="table-col status">Status</div>
                        <div class="table-col actions">Actions</div>
                    </div>

                    <div class="table">
                        <?php $__currentLoopData = $allRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="table-card">

                                <div class="table-col type"><?php echo e($req['type']); ?></div>
                                <div class="table-col requested-for" title="<?php echo e(implode(', ', $req['students'])); ?>">
                                    <?php echo e($req['students'][0] ?? 'N/A'); ?>

                                    <?php if(count($req['students']) > 1): ?>
                                        and <?php echo e(count($req['students']) - 1); ?> more
                                    <?php endif; ?>
                                </div>
                                <div class="table-col requested-at"><?php echo e($req['requested_at']); ?></div>
                                <div class="table-col status">
                                    <?php
                                        $status = strtolower($req['status']);
                                        $dotClass = match ($status) {
                                            'active' => 'status-dot status-approved',
                                            'archived' => 'status-dot status-declined',
                                            'pending' => 'status-dot status-pending',
                                            default => 'status-dot',
                                        };
                                        $labelClass = match ($status) {
                                            'active' => 'status-label status-approved',
                                            'archived' => 'status-label status-declined',
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
                                        <a href="#" title="View" class="view-btn"><i class='bx bx-show'></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.parent', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Parent/requests.blade.php ENDPATH**/ ?>