<div class="table">
    <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $status = strtolower($parent->user->status ?? '');
            $dotClass = $status === 'active' ? 'status-dot status-approved' : 'status-dot status-pending';
            $labelClass = $status === 'active' ? 'status-label status-approved' : 'status-label status-pending';
        ?>
        <div class="table-card" data-id="<?php echo e($parent->p_id); ?>">
            <div class="table-col title">
                <img src="<?php echo e(asset('images/user/' . ($parent->user->profile_image ?? 'default.jpg'))); ?>" style="width:32px;height:32px;border-radius:50%;margin-right:8px;">
                <?php echo e($parent->user->first_name ?? ''); ?> <?php echo e($parent->user->last_name ?? ''); ?>

            </div>
            <div class="table-col"><?php echo e($parent->user->contact_num ?? 'N/A'); ?></div>
            <div class="table-col"><?php echo e($parent->user->email ?? 'N/A'); ?></div>
            <div class="table-col status">
                <span class="<?php echo e($labelClass); ?>"><span class="<?php echo e($dotClass); ?>"></span><?php echo e(ucfirst($status) ?: 'N/A'); ?></span>
            </div>
            <div class="table-col actions">
                <a href="javascript:void(0);" class="view-btn" data-id="<?php echo e($parent->p_id); ?>"><i class='bx bx-show'></i></a>
                <a href="javascript:void(0);" class="edit-btn" data-id="<?php echo e($parent->p_id); ?>"><i class='bx bx-edit'></i></a>
                <a href="javascript:void(0);" class="archive-btn" data-id="<?php echo e($parent->p_id); ?>"><i class='bx bx-archive'></i></a>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php if($parents instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
    <?php $__env->startComponent('components.parent-pagination', ['paginator' => $parents]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/partials/parent_table.blade.php ENDPATH**/ ?>