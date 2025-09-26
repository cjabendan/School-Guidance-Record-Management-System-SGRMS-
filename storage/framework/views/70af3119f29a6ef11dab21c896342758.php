<?php use App\Helpers\NotificationHelper; ?>

<div class="notify">
    <h3 class="notify-title">Notifications</h3>
    <div class="notify-list">
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notify): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e($notify['link'] ? $notify['link'] . (str_contains($notify['link'], '?') ? '&' : '?') . 'notify=1' : '#'); ?>" class="notify-item" style="text-decoration:none; color:inherit;">
                <span class="notify-icon">
                    <?php echo e($notify['icon'] ?? NotificationHelper::getIcon($notify['text'] ?? '')); ?>

                </span>
                <div>
                    <p class="notify-text"><?php echo e($notify['text'] ?? 'Notification'); ?></p>
                    <small class="notify-time"><?php echo e($notify['time'] ?? ''); ?></small>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="notify-item notify-empty">
                <span class="notify-icon">🔔</span>
                <p class="notify-text">You're all caught up!</p>
                <small class="notify-time">No new notifications at this time.</small>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/partials/notify.blade.php ENDPATH**/ ?>