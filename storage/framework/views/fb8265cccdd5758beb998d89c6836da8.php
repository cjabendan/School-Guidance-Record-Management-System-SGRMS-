<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>


    <!-- MAIN CONTENT -->
    <section id="content">
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="wrapper">
            <div class="table-container">
                <div class="table-management">
                    <div class="table-nav">
                        <div class="table-filter">
                            <div class="filters">
                                <li>
                                    <a href="#" class="active">All</a>
                                    <a href="#">Alarming</a>
                                    <a href="#">Moderate</a>
                                    <a href="#">Low</a>
                                </li>
                            </div>
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addCaseModal"><i
                                    class="fi fi-br-plus"></i>Add counseling note</button>
                        </div>
                    </div>
                    <div class="table-bar">
                        <div class="table-search">
                            <form method="GET" action="">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                    placeholder="Search counseling..." id="counseling-search-input">
                                <?php if(request('category')): ?>
                                    <input type="hidden" name="category" value="<?php echo e(request('category')); ?>">
                                <?php endif; ?>
                                <button type="submit" style="display:none"></button>
                            </form>
                        </div>

                        <button class="toggle-btn" id="toggle-view-btn">
                            <i class="fi fi-br-bars-filter" id="toggle-icon"></i>
                            <span id="toggle-label"></span>
                        </button>
                    </div>
                </div>

                <!-- Table view -->
                <div class="table-list" id="counseling-list" style="margin-bottom:0;">
                    <div class="table-header">
                        <div class="table-col title">Note ID</div>
                        <div class="table-col category">Type</div>
                        <div class="table-col date">Filed Date</div>
                        <div class="table-col status">Remarks</div>
                        <div class="table-col actions">Actions</div>
                    </div>
                    <div class="table">
                        <?php $__empty_1 = true; $__currentLoopData = $counselings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $counseling): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="table-card">
                                <div class="table-col title"><?php echo e($counseling->note_id); ?></div>
                                <div class="table-col category"><?php echo e($counseling->caseType->type_name ?? 'N/A'); ?></div>
                                <div class="table-col"><?php echo e($counseling->created_at); ?></div>
                                <div class="table-col status">
                                    <span class="status-label status-<?php echo e(strtolower($counseling->remarks)); ?>">
                                        <span class="status-dot status-<?php echo e(strtolower($counseling->remarks)); ?>"></span>
                                        <?php echo e(ucfirst($counseling->remarks)); ?>

                                    </span>
                                </div>
                        
                                <div class="table-col actions">
                                    <button type="button" class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#viewcounselingModal<?php echo e($counseling->case_id); ?>"><i class='bx bx-show'></i></button>
                                    <button type="button" class="edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#editcounselingModal<?php echo e($counseling->case_id); ?>"><i class='bx bx-edit'></i></button>
                                    <button type="button" class="archive-btn"><i class='bx bx-archive'></i></button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="no-table-cell">No counseling notes found.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php echo $__env->make('Head.Modal.caseModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/counseling.blade.php ENDPATH**/ ?>