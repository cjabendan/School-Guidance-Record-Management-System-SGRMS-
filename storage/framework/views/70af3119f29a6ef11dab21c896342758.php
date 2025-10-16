<?php use App\Helpers\NotificationHelper; ?>

<div class="notify">
    <h3 class="notify-title">Notifications</h3>
    <div class="notify-list">
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $notify): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e($notify['link'] ? $notify['link'] . (str_contains($notify['link'], '?') ? '&' : '?') . 'notify_id=' . $notify['id'] : '#'); ?>"
               class="notify-item"
               style="text-decoration:none; color:inherit;"
               data-notify-index="<?php echo e($index); ?>"
               data-notify-id="<?php echo e($notify['id'] ?? ''); ?>">
                <span class="notify-icon">
                    <?php echo e($notify['icon'] ?? NotificationHelper::getIcon($notify['text'] ?? '')); ?>

                </span>
                <div>
                    <p class="notify-text<?php echo e(isset($notify['is_read']) && $notify['is_read'] ? ' notify-text-read' : ''); ?>"><?php echo e($notify['text'] ?? 'Notification'); ?></p>
                    <small class="notify-time"><?php echo e($notify['time'] ?? ''); ?></small>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="notify-empty">
                <span class="notify-icon">🔔</span>
                <p class="notify-text">You're all caught up!</p>
                <small class="notify-time">No new notifications at this time.</small>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.notification-dropdown .notify-item[data-notify-id]').forEach(function(item) {
        item.addEventListener('click', function(e) {
            const notifyId = this.getAttribute('data-notify-id');
            if (notifyId) {
                fetch("<?php echo e(route('notify.markAsRead')); ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({ id: notifyId })
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        window.location.href = this.href;
                    }
                });
                e.preventDefault();
            }
        });
    });
});
</script><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/partials/notify.blade.php ENDPATH**/ ?>