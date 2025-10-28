<?php if(session('error')): ?>
    <div class="alert alert-danger" style="margin:8px 12px; padding:10px; border-radius:6px; border:1px solid #fca5a5; background:#fff0f0; color:#b91c1c; font-weight:600;">
        <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>

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
                    'missed' => 'status-dot status-declined',
                    default => 'status-dot',
                };
                $labelClass = match ($status) {
                    'approved' => 'status-label status-approved',
                    'declined' => 'status-label status-declined',
                    'cancelled' => 'status-label status-declined',
                    'pending' => 'status-label status-pending',
                    'missed' => 'status-label status-declined',
                    default => 'status-label',
                };
            ?>
            <span class="<?php echo e($labelClass); ?>">
                <span class="<?php echo e($dotClass); ?>"></span>
                <span data-appointment-status="<?php echo e($appointment->appointment_id); ?>"><?php echo e(ucfirst($appointment->status)); ?></span>
            </span>
        </div>
        <div class="table-col actions">
            <?php
                $latestReq = $appointment->reschedules()->first();
                $prevText = '';
                $reqText = '';
                if ($appointment->original_appointment_datetime) {
                    $prevText = $appointment->original_appointment_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A');
                }
                if ($latestReq && !empty($latestReq->proposed_datetime)) {
                    $reqText = \Carbon\Carbon::parse($latestReq->proposed_datetime)->setTimezone('Asia/Manila')->format('M d, Y h:i A');
                }
            ?>
            <a href="#" title="View" class="view-btn" data-prev="<?php echo e($prevText); ?>" data-req="<?php echo e($reqText); ?>"
                onclick="openStudentReviewModal(<?php echo e($appointment->appointment_id); ?>, `
                    <div><strong>Type:</strong> <?php echo e($appointment->type ? $appointment->type->type_name : 'N/A'); ?><br>
                    <strong>Requester:</strong> <?php echo e($appointment->requester ? $appointment->requester->first_name . ' ' . $appointment->requester->last_name : 'N/A'); ?><br>
                    <strong>Student:</strong> <?php $studentCount = $appointment->students->count(); ?>
                    <?php if($studentCount === 1): ?>
                        <?php echo e($appointment->students->first()->user->first_name ?? ''); ?> <?php echo e($appointment->students->first()->user->last_name ?? ''); ?>

                    <?php elseif($studentCount > 1): ?>
                        <?php echo $appointment->students->map(fn($s) => e(($s->user->first_name ?? '') . ' ' . ($s->user->last_name ?? '')))->implode('<br>'); ?>

                    <?php else: ?>
                        N/A
                    <?php endif; ?><br>
                    <strong>Date & Time:</strong> <?php echo e($appointment->appointment_datetime ? $appointment->appointment_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A') : 'N/A'); ?><br>
                    <strong>Counselor:</strong> <?php echo e($appointment->counselor ? $appointment->counselor->first_name . ' ' . $appointment->counselor->last_name : 'N/A'); ?><br>
                    <strong>Status:</strong> <?php echo e(ucfirst($appointment->status)); ?><br>
                    <?php if(strtolower($appointment->status) === 'declined' && !empty($appointment->decline_reason)): ?>
                        <div class='alert alert-danger mt-2' style='padding:4px 8px; font-size:0.95em;'><strong>Decline Reason:</strong> <?php echo e($appointment->decline_reason); ?></div>
                    <?php endif; ?>
                    </div>
                `, '<?php echo e(route('Student.appointments.cancel', $appointment->appointment_id)); ?>', '<?php echo e(strtolower($appointment->status)); ?>', '<?php echo e(route('Student.appointments.start', $appointment->appointment_id)); ?>', this)">
                <i class='bx bx-show'></i>
            </a>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="no-table-cell">
        No appointments to display.
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Student/partials/appointment-list.blade.php ENDPATH**/ ?>