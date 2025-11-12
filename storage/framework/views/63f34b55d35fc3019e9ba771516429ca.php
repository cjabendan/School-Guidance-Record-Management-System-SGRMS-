<div class="view-announcement-container">
    <div class="view-announcement-item">
        <div class="view-announcement-card">
            <div class="view-announcement-image">
                <img src="<?php echo e(asset('images/announcements/' . $announcement->image)); ?>" alt="image">
            </div>
            <div class="view-announcement-content">

                <div class="view-announcement-title-section">
                    <div class="view-announcement-logo">
                        <img src="<?php echo e(asset('images/logo/school-logo.png')); ?>" alt="School Logo">
                    </div>
                    <div class="view-announcement-text">
                        <h3><?php echo e($announcement->title); ?></h3>
                        <p class="view-announcement-date">
                            <?php echo e(\Carbon\Carbon::parse($announcement->created_at)->format('F d, Y \a\t h:i A')); ?>

                        </p>

                    </div>
                </div>


                <div class="view-announcement-description">
                    <p><?php echo e($announcement->description); ?></p>
                </div>
                <?php if($announcement->link): ?>
                    <div class="announcement-btn-link">
                        <a href="<?php echo e($announcement->link); ?>" class="announcement-btn-link" target="_blank">See more
                            information</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/components/announcement-view.blade.php ENDPATH**/ ?>