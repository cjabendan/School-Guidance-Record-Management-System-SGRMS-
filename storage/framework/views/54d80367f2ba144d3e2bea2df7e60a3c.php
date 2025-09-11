<div class="staff-card">
    <img src="<?php echo e(asset('images/user/' . $staff->image)); ?>" alt="<?php echo e($staff->name); ?>">
    <div class="staff-name">
        <h3><?php echo e($staff->name); ?></h3>
        <i class="fi fi-sr-badge-check"></i>
    </div>
    <p><?php echo e($staff->role); ?></p>
    <div class="staff-btn">
        <button class="mail-btn"><i class="fi fi-sr-envelope"></i></button>
        <button class="book-btn">Book An Appointment</button>
    </div>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/components/staff-card.blade.php ENDPATH**/ ?>