<div class="appointments-box">
    <div class="appointments-header">
        <h2>Upcoming Appointments</h2>
        <form method="GET" id="appointmentFilterForm">
            <select class="dropdown" name="filter" onchange="document.getElementById('appointmentFilterForm').submit()">
                <option value="today" <?php echo e(request('filter') == 'today' ? 'selected' : ''); ?>>Today
                </option>
                <option value="tomorrow" <?php echo e(request('filter') == 'tomorrow' ? 'selected' : ''); ?>>
                    Tomorrow</option>
                <option value="week" <?php echo e(request('filter') == 'week' ? 'selected' : ''); ?>>This
                    Week
                </option>
            </select>
        </form>
    </div>
    <div class="appointments-table" id="appointments-table-container" style="position:relative;">
        <?php echo $__env->make('components.small-loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <table>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="appointment-card">
                        <td class="appointment-time-col">
                            <?php echo e($appointment->appointment_datetime->format('h:i A')); ?>

                        </td>
                        <td class="appointment-details-col">
                            <div class="appointment-details-flex">
                                <p class="appointment-type"><?php echo e($appointment->type->type_name ?? 'N/A'); ?>

                                </p>
                                <span class="appointment-requester">
                                    <?php echo e($appointment->requester_name); ?>

                                </span>
                            </div>
                        </td>   
                        <td class="appointment-actions-col">
                            <div class="appointment-date">
                                <?php echo e($appointment->appointment_datetime->format('M d, Y')); ?>

                            </div>
                            <div class="appointment-action">
                                <a href="" class="appointment-link">View details</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" class="no-appointments-cell">
                            No upcoming appointments.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views\Head\dashboard-sections\appointments.blade.php ENDPATH**/ ?>