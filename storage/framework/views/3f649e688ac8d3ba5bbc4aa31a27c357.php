<div wire:poll.3s="refreshNotifications">
    <?php
        $sections = [
            'New Notifications' => $new,
            'Recent Today' => $today,
            'Earlier Updates' => $earlier,
        ];
    ?>

    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <!--[if BLOCK]><![endif]--><?php if($list->count()): ?>
            <h3 class="notif-section-title"><?php echo e($title); ?></h3>
            <div class="notifications-list">
             <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="notification-item <?php echo e($notification->is_read ? 'read' : 'unread'); ?>"
                    wire:click="markAsReadAndShowModal(<?php echo e($notification->id); ?>)"
                    wire:key="notification-<?php echo e($notification->id); ?>"
                    style="cursor:pointer;"
                >
                        <span class="notification-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="notification-svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M14.857 17.082a23.848 23.848 0 0 0 
                                        5.454-1.31A8.967 8.967 0 0 1 
                                        18 9.75V9A6 6 0 0 0 6 9v.75a8.967 
                                        8.967 0 0 1-2.312 6.022c1.733.64 
                                        3.56 1.085 5.455 1.31m5.714 0a24.255 
                                        24.255 0 0 1-5.714 0m5.714 0a3 3 
                                        0 1 1-5.714 0" />
                            </svg>
                        </span>
                        <p class="notification-message"><?php echo e($notification->message); ?></p>
                        <small class="notification-time">
                            <?php echo e(\Carbon\Carbon::parse($notification->timestamp)->diffForHumans()); ?>

                        </small>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if(!$new->count() && !$today->count() && !$earlier->count()): ?>
        <p style="text-align:center; color:#6b7280; padding:30px 0;">
            You're all caught up — no notifications right now 🎉
        </p>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/notification-board.blade.php ENDPATH**/ ?>