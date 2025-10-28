<?php if(session('error')): ?>
    <div class="alert alert-danger" style="margin:8px 12px; padding:10px; border-radius:6px; border:1px solid #fca5a5; background:#fff0f0; color:#b91c1c; font-weight:600;">
        <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>

<?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="table-card" 
         data-previous-schedule="<?php echo e(isset($appointment->previous_schedule) && $appointment->previous_schedule ? $appointment->previous_schedule->format('M d, Y h:i A') : ''); ?>"
         data-current-schedule="<?php echo e($appointment->appointment_datetime ? $appointment->appointment_datetime->format('M d, Y h:i A') : ''); ?>">
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
            <?php
                $counselorName = $appointment->counselor 
                    ? $appointment->counselor->first_name . ' ' . $appointment->counselor->last_name 
                    : 'N/A';
                
                // Get previous schedule if this is a rescheduled appointment
                $previousSchedule = '';
                if ($appointment->previous_datetime) {
                    $previousSchedule = $appointment->previous_datetime->format('M d, Y h:i A');
                }
            ?>
            <span data-previous-schedule="<?php echo e($previousSchedule); ?>"><?php echo e($counselorName); ?></span>
        </div>
        <div class="table-col status">
            <?php
                $status = strtolower($appointment->status);
                $dotClass = match ($status) {
                    'approved' => 'status-dot status-approved',
                    'declined' => 'status-dot status-declined',
                    'cancelled' => 'status-dot status-declined',
                    'missed' => 'status-dot status-declined',
                    'pending' => 'status-dot status-pending',
                    'ongoing' => 'status-dot status-ongoing',
                    'completed' => 'status-dot status-completed',
                    default => 'status-dot',
                };
                $labelClass = match ($status) {
                    'approved' => 'status-label status-approved',
                    'declined' => 'status-label status-declined',
                    'cancelled' => 'status-label status-declined',
                    'missed' => 'status-label status-declined',
                    'pending' => 'status-label status-pending',
                    'ongoing' => 'status-label status-ongoing',
                    'completed' => 'status-label status-completed',
                    default => 'status-label',
                };
            ?>
            <span class="<?php echo e($labelClass); ?>" data-appointment-status="<?php echo e($appointment->appointment_id); ?>">
                <span class="<?php echo e($dotClass); ?>"></span>
                <?php echo e(ucfirst($appointment->status)); ?>

            </span>
            
        </div>
        <div class="table-col actions">
            <?php
                $latestReq = $appointment->reschedules()->first();
                $resStatus = $latestReq ? $latestReq->status : '';
                $status = strtolower($appointment->status);
                
                // Initialize times
                $prevText = '';
                $reqText = '';
                
                // Only set previous and preferred times if status is rescheduled
                if ($status === 'rescheduled') {
                    // Original appointment time becomes the "previous" time
                    $prevText = $appointment->appointment_datetime 
                        ? $appointment->appointment_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A') 
                        : '';
                    
                    // Get preferred time from reschedule_proposed_datetime
                    $reqText = $appointment->reschedule_proposed_datetime 
                        ? $appointment->reschedule_proposed_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A') 
                        : '';

                    // Log times for debugging
                    \Illuminate\Support\Facades\Log::info('Displaying rescheduled appointment times', [
                        'appointment_id' => $appointment->appointment_id,
                        'status' => $status,
                        'previous' => $prevText,
                        'preferred' => $reqText,
                        'has_proposed_datetime' => isset($appointment->reschedule_proposed_datetime)
                    ]);
                }
            ?>
            <a href="#" title="View" class="view-btn" 
              data-reschedule-status="<?php echo e($resStatus); ?>"
              data-prev="<?php echo e($prevText); ?>"
              data-req="<?php echo e($reqText); ?>"
              onclick="openReviewModal(
                <?php echo e($appointment->appointment_id); ?>, 
                `<div><strong>Type:</strong> <?php echo e($appointment->type ? $appointment->type->type_name : 'N/A'); ?><br>
                <strong>Requester:</strong> <?php echo e($appointment->requester ? $appointment->requester->first_name . ' ' . $appointment->requester->last_name : 'N/A'); ?><br>
                <strong>Student:</strong> <?php $studentCount = $appointment->students->count(); ?>
                <?php if($studentCount === 1): ?>
                    <?php echo e($appointment->students->first()->user->first_name ?? ''); ?> <?php echo e($appointment->students->first()->user->last_name ?? ''); ?>

                <?php elseif($studentCount > 1): ?>
                    <?php echo $appointment->students->map(fn($s) => e(($s->user->first_name ?? '') . ' ' . ($s->user->last_name ?? '')))->implode('<br>'); ?>

                <?php else: ?>
                    N/A
                <?php endif; ?><br>
                <strong>Date & Time:</strong> <?php if($appointment->status === 'Rescheduled' && $appointment->reschedule_proposed_datetime): ?><?php echo e($appointment->reschedule_proposed_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A')); ?><?php else: ?><?php echo e($appointment->appointment_datetime ? $appointment->appointment_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A') : 'N/A'); ?><?php endif; ?><br>
                <strong>Counselor:</strong> <?php echo e($appointment->counselor ? $appointment->counselor->first_name . ' ' . $appointment->counselor->last_name : 'N/A'); ?><br>
                <strong>Status:</strong> <?php echo e(ucfirst($appointment->status)); ?><br>
                
                <?php if(strtolower($appointment->status) === 'declined' && !empty($appointment->decline_reason)): ?>
                    <div class='alert alert-danger mt-2' style='padding:4px 8px; font-size:0.95em;'><strong>Decline Reason:</strong> <?php echo e($appointment->decline_reason); ?></div>
                <?php endif; ?>
                </div>`,
                '<?php echo e(route('Head.appointments.approve', $appointment->appointment_id)); ?>',
                '<?php echo e(route('Head.appointments.decline', $appointment->appointment_id)); ?>',
                '<?php echo e(route('Head.appointments.cancel', $appointment->appointment_id)); ?>',
                '<?php echo e(strtolower($appointment->status)); ?>',
                '<?php echo e(route('Head.appointments.start', $appointment->appointment_id)); ?>',
                this
              )">
              <i class='bx bx-show'></i>
            </a>
            <?php $st = strtolower($appointment->status ?? ''); ?>
            <?php if(in_array($st, ['cancelled', 'completed', 'declined'])): ?>
                
                <a href="#" title="This appointment cannot be edited" class="edit-btn edit-disabled" onclick="return false;" aria-disabled="true" tabindex="-1" style="cursor:not-allowed; color:#fdfdfd;">
                    <i class='bx bx-edit'></i>
                </a>
            <?php else: ?>
                <a href="#" title="Edit/Reschedule" class="edit-btn" onclick="openRescheduleModal(<?php echo e($appointment->appointment_id); ?>); return false;">
                    <i class='bx bx-edit'></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="no-table-cell">
        No appointments to display.
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/partials/appointment-list.blade.php ENDPATH**/ ?>