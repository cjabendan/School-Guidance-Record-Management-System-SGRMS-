<div class="announcement-box" onclick="openAnnouncementModal(<?php echo e($announcement->id); ?>)">
    <div class="a-arrow">
        <i class="fi fi-rr-arrow-small-right"></i>
    </div>
    <div class="announcement-img">
        <img src="<?php echo e(asset('images/announcements/' . $announcement->image)); ?>" class="img-fluid" alt="image">
    </div>
    <div class="announcement-content">
        <div class="an-header">
            <h3><?php echo e($announcement->title); ?></h3>
            <p><?php echo e(\Carbon\Carbon::parse($announcement->date_posted)->format('F d, Y')); ?></p>
        </div>
        <div class="an-body">
            <p>
                <?php echo e(\Illuminate\Support\Str::limit($announcement->description, 180, '...')); ?>

                <?php if(strlen($announcement->description) > 180): ?>
                    <a href="javascript:void(0);" onclick="openAnnouncementModal(<?php echo e($announcement->id); ?>)">Read more</a>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/components/announcement-card.blade.php ENDPATH**/ ?>