<div class="requests-box">
    <div class="requests-header">
        <h2>Child Link Request</h2>
        <i class="fi fi-br-menu-dots"></i>
    </div>
    <div class="requests-table">
        <?php $__empty_1 = true; $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="requests-item">
                <div class="request-content">
                    <img src="<?php echo e(asset('images/user/' . ($request->parent->user->profile_image ?? 'default.jpg'))); ?>"
                        alt="User Photo" class="user-photo">
                    <div class="request-details">
                        <h2 class="request-sender">
                            <?php
                                $sex = strtolower($request->parent->user->sex ?? '');
                                $lastName = $request->parent->user->last_name ?? 'Unknown';
                                if ($sex === 'male') {
                                    $prefix = 'Mr.';
                                } elseif ($sex === 'female') {
                                    $prefix = 'Ms.';
                                } else {
                                    $prefix = '';
                                }
                            ?>
                            <?php echo e($prefix ? $prefix . ' ' : ''); ?><?php echo e($lastName); ?>

                        </h2>
                        <p class="request-preview">
                            Link to:
                            <?php $__currentLoopData = $request->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($pls->student_id); ?>

                                <?php if(!$loop->last): ?>
                                    ,
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </p>
                    </div>
                </div>
                <div class="request-actions">
                    <a href="<?php echo e(route('head.requests.show', ['type' => strtolower($req['type']), 'id' => $req['id']])); ?>"
                        class="view-btn">Review</a>
                    <button class="btn btn-danger btn-sm reject-btn"
                        onclick="location.href='<?php echo e(route('head.requests.show', ['type' => strtolower($req['type']), 'id' => $req['id']])); ?>'"
                        data-id="<?php echo e($req['id']); ?>" data-type="<?php echo e($req['type']); ?>">Reject</button>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="requests-item">
                <div class="request-content">
                    <div class="request-details">
                        <p>No pending requests.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/Head/dashboard-sections/requests.blade.php ENDPATH**/ ?>