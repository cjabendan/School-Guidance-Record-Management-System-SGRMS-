<div class="table">
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $status = strtolower($user->status ?? '');
            $dotClass = $status === 'active' ? 'status-dot status-approved' : 'status-dot status-pending';
            $labelClass = $status === 'active' ? 'status-label status-approved' : 'status-label status-pending';
        ?>
        <div class="table-card" data-id="<?php echo e($user->id); ?>">
            <div class="table-col title">
                <img src="<?php echo e(asset('images/user/' . ($user->profile_image ?? 'default.jpg'))); ?>" class="profile-thumb">
                <?php echo e($user->first_name ?? ''); ?> <?php echo e($user->last_name ?? ''); ?>

            </div>
            <div class="table-col"><?php echo e($user->email ?? 'N/A'); ?></div>
            <div class="table-col"><?php echo e($user->contact_num ?? 'N/A'); ?></div>
            <div class="table-col"><?php echo e(ucfirst($user->role ?? 'N/A')); ?></div>
            <div class="table-col status">
                <span class="<?php echo e($labelClass); ?>"><span class="<?php echo e($dotClass); ?>"></span><?php echo e(ucfirst($status) ?: 'N/A'); ?></span>
            </div>
            <div class="table-col actions">
                <a href="javascript:void(0);" class="view-btn" data-id="<?php echo e($user->id); ?>"><i class='bx bx-show'></i></a>
                <a href="javascript:void(0);" class="edit-btn" data-id="<?php echo e($user->id); ?>"><i class='bx bx-edit'></i></a>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php if($users instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
    <?php $__env->startComponent('components.parent-pagination', ['paginator' => $users]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/partials/users_table.blade.php ENDPATH**/ ?>