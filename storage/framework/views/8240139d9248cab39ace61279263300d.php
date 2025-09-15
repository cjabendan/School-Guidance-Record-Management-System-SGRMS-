<div class="request-modal">
    <div class="request-modal-content">
        <h2><?php echo e(ucfirst($type)); ?> Request</h2>
        <div>
            <strong>Requested By:</strong>
            <?php echo e($request->parent->user->first_name ?? ''); ?> <?php echo e($request->parent->user->last_name ?? ''); ?>

        </div>
        <div>
        <strong>Email:</strong>
        <?php echo e($request->parent->user->email ?? ($request->email ?? 'N/A')); ?>

    </div>
    <div>
        <strong>Contact Number:</strong>
        <?php echo e($request->parent->user->contact_num ?? ($request->number ?? 'N/A')); ?>

    </div>
    <div>
        <strong>Requested At:</strong>
        <?php echo e($request->requested_at); ?>

    </div>
    <div>
        <strong>Status:</strong>
        <?php echo e(ucfirst($request->status)); ?>

    </div>
    <div>
        <strong>Students:</strong>
        <ul>
            <?php if($type === 'child link'): ?>
                <?php $__currentLoopData = $request->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <?php echo e($s->student->user->first_name ?? ''); ?> <?php echo e($s->student->user->last_name ?? ''); ?> (<?php echo e($s->student_id); ?>)
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php elseif($type === 'document'): ?>
                <?php $__currentLoopData = $request->drs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <?php echo e($d->student->user->first_name ?? ''); ?> <?php echo e($d->student->user->last_name ?? ''); ?> (<?php echo e($d->s_id); ?>)
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </ul>
    </div>
    <?php if($request->status === 'pending'): ?>
        <form method="POST" action="<?php echo e(route('Head.requests.approve', ['type' => $type, 'id' => $request->request_id])); ?>" style="display:inline;">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-success">Accept</button>
        </form>
        <form method="POST" action="<?php echo e(route('Head.requests.reject', ['type' => $type, 'id' => $request->request_id])); ?>" style="display:inline;">
            <?php echo csrf_field(); ?>
            <input type="text" name="reason" placeholder="Rejection reason" required>
            <button type="submit" class="btn btn-danger">Decline</button>
        </form>
    <?php else: ?>
        <div>
            <strong>Rejection Reason:</strong> <?php echo e($request->rejection_reason ?? 'N/A'); ?>

        </div>
    <?php endif; ?>
    <a href="<?php echo e(route('Head.requests.index')); ?>" class="btn btn-secondary">Back to Requests</a>
    </div>
    
</div><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/components/requestView.blade.php ENDPATH**/ ?>