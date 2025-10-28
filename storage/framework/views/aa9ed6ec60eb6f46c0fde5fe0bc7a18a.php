<?php $__empty_1 = true; $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        // $child is an array: ['id', 'name', 'email', 'grade_level']
        $fullName = $child['name'];
        $img = asset('images/user/default.jpg');
        // If you want to show student ID, use $child['id']
    ?>
    <div class="profile-box" onclick="openViewChildModal('<?php echo e($child['id']); ?>')">
      
        <h2><?php echo e($fullName); ?></h2>
        <p>Student ID: <?php echo e($child['id']); ?></p>
        <p>Email: <?php echo e($child['email']); ?></p>
       
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    
<?php endif; ?>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/components/child-card.blade.php ENDPATH**/ ?>