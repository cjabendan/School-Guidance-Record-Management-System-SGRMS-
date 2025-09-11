<section class="staff-section" id="staff">
    <div class="staff-content">
        <h2>Meet Our Staff</h2>
        <p class=p>We’re more than just counselors—we’re here to support, listen,
            <br>and guide you every step of the way.
        </p>

        <div class="staff-list">
            <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staffMember): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('components.staff-card', ['staff' => (object) $staffMember], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>
</section>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views\landing-sections\staff.blade.php ENDPATH**/ ?>