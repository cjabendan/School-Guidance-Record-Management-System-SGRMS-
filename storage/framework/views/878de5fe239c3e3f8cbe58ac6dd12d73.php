<div class="announcement">
      <?php if(isset($announcements) && count($announcements)): ?>
          <div id="announcement-slideshow">
              <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="slide announcement-slide <?php if($i === 0): ?> active <?php endif; ?>"
                      style="background: <?php echo e($announcement->image ? 'url(' . asset('images/announcements/' . $announcement->image) . ') center center/cover no-repeat' : '#eaf6ff'); ?>;">
                      <div class="announcement-overlay"></div>
                      <div class="announcement-content">
                          <h5 class="title"><?php echo e($announcement->title); ?></h5>
                          <div class="description"><?php echo nl2br(e($announcement->description)); ?></div>
                          <div class="posted-date">
                              <?php echo e(date('M d, Y', strtotime($announcement->date_posted))); ?></div>
                      </div>
                  </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <div class="announcement-dots">
                  <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <span class="dot <?php if($i === 0): ?> active <?php endif; ?>"
                          data-slide="<?php echo e($i); ?>"></span>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
          </div>
      <?php else: ?>
          <div class="text-muted p-3">No announcements at this time.</div>
      <?php endif; ?>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/Parent/dashboard-sections/announcements.blade.php ENDPATH**/ ?>