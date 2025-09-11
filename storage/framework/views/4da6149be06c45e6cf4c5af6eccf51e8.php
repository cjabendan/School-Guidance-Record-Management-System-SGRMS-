
<?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="table-card">
        <div class="table-col type">
            <?php echo e($appointment->type ? $appointment->type->type_name : 'N/A'); ?>

        </div>
        <div class="table-col requester">
            <?php if($appointment->requester): ?>
                <?php echo e($appointment->requester->first_name); ?> <?php echo e($appointment->requester->last_name); ?>

            <?php else: ?>
                N/A
            <?php endif; ?>
        </div>
        <div class="table-col student">
            <?php if($appointment->student): ?>
                <?php echo e($appointment->student->first_name); ?> <?php echo e($appointment->student->last_name); ?>

            <?php else: ?>
                N/A
            <?php endif; ?>
        </div>
        <div class="table-col datetime">
            <?php echo e($appointment->appointment_datetime ? $appointment->appointment_datetime->format('M d, Y h:i A') : 'N/A'); ?>

        </div>
        <div class="table-col counselor">
            <?php if($appointment->counselor): ?>
                <?php echo e($appointment->counselor->first_name); ?> <?php echo e($appointment->counselor->last_name); ?>

            <?php else: ?>
                N/A
            <?php endif; ?>
        </div>
        <div class="table-col status">
            <?php
                $status = strtolower($appointment->status);
                $dotClass = match ($status) {
                    'approved' => 'status-dot status-approved',
                    'declined' => 'status-dot status-declined',
                    'cancelled' => 'status-dot status-declined',
                    'pending' => 'status-dot status-pending',
                    default => 'status-dot',
                };
                $labelClass = match ($status) {
                    'approved' => 'status-label status-approved',
                    'declined' => 'status-label status-declined',
                    'cancelled' => 'status-label status-declined',
                    'pending' => 'status-label status-pending',
                    default => 'status-label',
                };
            ?>
            <span class="<?php echo e($labelClass); ?>">
                <span class="<?php echo e($dotClass); ?>"></span>
                <?php echo e(ucfirst($appointment->status)); ?>

            </span>
            <?php if($appointment->rescheduled_count > 0): ?>
                <span class="badge badge-warning">
                    Rescheduled (<?php echo e($appointment->rescheduled_count); ?>x)
                </span>
            <?php endif; ?>
        </div>
        <div class="table-col actions">
            <a href="#" title="View" class="view-btn"
                onclick="openAppointmentModal(<?php echo e($appointment->appointment_id); ?>, 'view')">
                <i class='bx bx-show'></i>
            </a>
            <a href="#" title="Reschedule" class="edit-btn"
                onclick="openAppointmentModal(<?php echo e($appointment->appointment_id); ?>, 'reschedule')">
                <i class='bx bx-edit'></i>
            </a>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="no-table-cell">
        No appointments to display.
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views\Head\partials\appointment-list.blade.php ENDPATH**/ ?>