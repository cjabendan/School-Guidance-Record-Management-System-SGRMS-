<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>


    <!-- MAIN CONTENT -->
    <section id="content">
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="wrapper">
            <div class="table-container">
                <div class="table-management">
                    <div class="table-nav">
                        <div class="table-filter" id="parent-filters">
                            <div class="filters" id="table-filters">
                                <li>
                                    <a href="#" class="a-nav active" data-filter="all">All</a>
                                    <a href="#" class="a-nav" data-filter="active">Active</a>
                                    <a href="#" class="a-nav" data-filter="inactive">Inactive</a>
                                </li>
                            </div>
                            <button class="add-btn" id="addParentBtn"><i class="fi fi-br-plus"></i>Add Parent</button>
                        </div>
                    </div>
                    <div class="search-bar">
                        <div class="table-search">
                            <form method="GET" action="">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                    placeholder="Search by Parent.." id="parent-search-input">
                                <button type="submit" style="display:none"></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-list" id="parent-list" style="margin-bottom:0;">
                    <div class="table-header">
                        <div class="table-col title">Parent Name</div>
                        <div class="table-col">Contact Number</div>
                        <div class="table-col">Email</div>
                        <div class="table-col status">Account Status</div>
                        <div class="table-col actions">Actions</div>
                    </div>
                    <div class="table-table">
                        <?php $__empty_1 = true; $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $status = strtolower($parent->status ?? '');
                                $dotClass =
                                    $status === 'active'
                                        ? 'status-dot status-approved'
                                        : ($status === 'inactive'
                                            ? 'status-dot status-pending'
                                            : 'status-dot status-declined');
                                $labelClass =
                                    $status === 'active'
                                        ? 'status-label status-approved'
                                        : ($status === 'inactive'
                                            ? 'status-label status-pending'
                                            : 'status-label status-declined');
                            ?>
                            <div class="table-card">
                                <div class="table-col title">
                                    <?php if($parent->user && $parent->user->profile_image): ?>
                                        <img src="<?php echo e(asset('images/user/' . $parent->user->profile_image)); ?>"
                                            alt="Parent Image" class="parent-photo"
                                            style="width:32px;height:32px;border-radius:50%;margin-right:12px;">
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('images/user/default.jpg')); ?>" alt="Default Image"
                                            class="parent-photo"
                                            style="width:32px;height:32px;border-radius:50%;margin-right:8px;">
                                    <?php endif; ?>
                                    <?php echo e($parent->user->first_name ?? $parent->first_name); ?>

                                    <?php echo e($parent->user->last_name ?? $parent->last_name); ?>

                                </div>
                                <div class="table-col"><?php echo e($parent->user->contact_num ?? 'N/A'); ?></div>
                                <div class="table-col"><?php echo e($parent->user->email ?? 'N/A'); ?></div>
                                <div class="table-col status">
                                    <?php if(is_null($parent->user->status)): ?>
                                        <span class="status-label status-declined"><span
                                                class="status-dot status-declined"></span>Banned</span>
                                    <?php elseif($parent->user->status === 'active'): ?>
                                        <span class="status-label status-approved"><span
                                                class="status-dot status-approved"></span>Active</span>
                                    <?php else: ?>
                                        <span class="status-label status-pending"><span
                                                class="status-dot status-pending"></span>Pending</span>
                                    <?php endif; ?>
                                </div>
                                <div class="table-col actions">
                                    <button class="view-btn" data-id="<?php echo e($parent->p_id); ?>"> <i
                                            class='bx bx-show'></i></button>
                                    <button class="edit-btn" data-id="<?php echo e($parent->p_id); ?>"> <i
                                            class='bx bx-edit'></i></button>
                                    <button class="archive-btn" data-id="<?php echo e($parent->p_id); ?>"> <i
                                            class='bx bx-archive'></i></button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="no-table-cell">No parent accounts found.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Parent Modal -->
    <?php echo $__env->make('Head.modal.parentModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script src="<?php echo e(asset('js/Modal/parentModal.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/Head/profiling/parents.blade.php ENDPATH**/ ?>