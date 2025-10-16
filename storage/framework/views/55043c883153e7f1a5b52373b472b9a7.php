
<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>

<?php $__env->startSection('content'); ?>
<section id="content">
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="notification-container">
        <div class="notification-header">
            <p>Your Notifications — Stay Updated</p>
        </div>

        <div class="notification-board">

            
            <?php if($new->count()): ?>
                <h3 class="notif-section-title">New</h3>
                <div class="notifications-list">
                    <?php $__currentLoopData = $new; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="notification-item unread">
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

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            
            <?php if($today->count()): ?>
                <h3 class="notif-section-title">Today</h3>
                <div class="notifications-list">
                    <?php $__currentLoopData = $today; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="notification-item read">
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
                                Today • <?php echo e(\Carbon\Carbon::parse($notification->timestamp)->diffForHumans()); ?>

                            </small>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            
            <?php if($earlier->count()): ?>
                <h3 class="notif-section-title">Earlier</h3>
                <div class="notifications-list">
                    <?php $__currentLoopData = $earlier; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="notification-item read">
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
                                <?php echo e(\Carbon\Carbon::parse($notification->timestamp)->format('M d, Y')); ?>

                                • <?php echo e(\Carbon\Carbon::parse($notification->timestamp)->diffForHumans()); ?>

                            </small>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/Notify/notification.blade.php ENDPATH**/ ?>