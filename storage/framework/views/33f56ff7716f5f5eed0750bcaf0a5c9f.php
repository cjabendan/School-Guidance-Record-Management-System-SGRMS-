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
            <?php
                $studentCount = $appointment->students->count();
                $firstStudent = $appointment->students->first();
            ?>
            <?php if($studentCount === 1): ?>
                <?php echo e($firstStudent->user->first_name ?? ''); ?> <?php echo e($firstStudent->user->last_name ?? ''); ?>

            <?php elseif($studentCount > 1): ?>
                <?php echo e($firstStudent->user->first_name ?? ''); ?> <?php echo e($firstStudent->user->last_name ?? ''); ?>&nbsp;
                <span class="see-more-text" style="color: #888;">see more</span>
            <?php else: ?>
                N/A
            <?php endif; ?>
        </div>
        <div class="table-col datetime">
            <?php if($appointment->appointment_datetime): ?>
                <?php
                    $dt = $appointment->appointment_datetime->setTimezone('Asia/Manila');
                ?>
                <?php echo e($dt->format('M d, Y h:i A')); ?>

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
                onclick="openParentReviewModal(`
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
                    <strong>Date & Time:</strong> <?php echo e($appointment->appointment_datetime ? $appointment->appointment_datetime->format('M d, Y h:i A') : 'N/A'); ?><br>
                    <strong>Counselor:</strong> <?php echo e($appointment->counselor ? $appointment->counselor->first_name . ' ' . $appointment->counselor->last_name : 'N/A'); ?><br>
                    <strong>Status:</strong> <?php echo e(ucfirst($appointment->status)); ?><br>
                    <?php if($appointment->rescheduled_count > 0): ?>
                        <span class='badge badge-warning'>Rescheduled (<?php echo e($appointment->rescheduled_count); ?>x)</span><br>
                    <?php endif; ?>
                    <?php if(strtolower($appointment->status) === 'declined' && !empty($appointment->decline_reason)): ?>
                        <div class='alert alert-danger mt-2' style='padding:4px 8px; font-size:0.95em;'><strong>Decline Reason:</strong> <?php echo e($appointment->decline_reason); ?></div>
                    <?php endif; ?>
                    </div>
                `)">
                <i class='bx bx-show'></i>
            </a>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="no-table-cell">
        No appointments to display.
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Parent/partials/appointment-list.blade.php ENDPATH**/ ?>