<div class="view-announcement-container">
    <div class="view-announcement-item">
        <div class="view-announcement-card">
            <div class="view-announcement-image">
                <img src="<?php echo e(asset('images/announcements/' . $announcement->image)); ?>" alt="image">
            </div>
            <div class="view-announcement-content">
                <h3 class="view-announcement-title"><?php echo e($announcement->title); ?></h3>
                <p class="view-announcement-date"><?php echo e(\Carbon\Carbon::parse($announcement->date_posted)->format('F d, Y')); ?></p>
                <div class="view-announcement-description">
                    <p><?php echo e($announcement->description); ?></p>
                </div>
                <?php if($announcement->link): ?>
                    <a href="<?php echo e($announcement->link); ?>" class="btn btn-primary" target="_blank">View More</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/components/announcement-view.blade.php ENDPATH**/ ?>