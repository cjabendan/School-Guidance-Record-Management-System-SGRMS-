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
                <?php
                    // Only show Approved and Ongoing (in-session) appointments on the Head dashboard
                    $visibleAppointments = collect($upcomingAppointments)->filter(function($appointment) {
                        $status = strtolower($appointment->status ?? '');
                        return in_array($status, ['approved', 'ongoing']);
                    });
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $visibleAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="appointment-card" data-appointment-id="<?php echo e($appointment->appointment_id); ?>">
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
                            <div class="appointment-countdown-wrapper" style="text-align:right; margin-right:65px;">
                                <span class="appointment-countdown" data-datetime="<?php echo e($appointment->appointment_datetime->toIso8601String()); ?>"></span>
                            </div>
                            <div class="appointment-action" style="text-align:right; margin-right:47px;">
                                <a href="" class="appointment-link" style="margin-left:8px;">View details</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" class="no-appointments-cell">
                            <img src="<?php echo e(asset ('images/icons/appointments.png')); ?>" alt="no-appointments" class="no-appointments-img">
                            You have no upcoming appointments.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    <script>
    function startHeadAppointmentCountdowns() {
        const els = document.querySelectorAll('.appointment-countdown');
        if (!els.length) return;

        function formatDiff(ms) {
            if (ms <= 0) return 'In session';
            const totalMinutes = Math.ceil(ms / 60000);
            if (totalMinutes < 60) return totalMinutes + ' min';
            const hours = Math.floor(totalMinutes / 60);
            if (hours < 24) {
                const minutes = totalMinutes % 60;
                return hours + 'h ' + minutes + 'm';
            }
            const days = Math.floor(hours / 24);
            const remHours = hours % 24;
            return days + 'd ' + remHours + 'h';
        }

        function updateOne(el) {
            const dt = el.dataset.datetime;
            if (!dt) return;
            const when = new Date(dt);
            const now = new Date();
            const diff = when - now;
            el.textContent = formatDiff(diff);
            // also update tooltip/scheduled if present
            const tr = el.closest('tr');
            if (tr) {
                const scheduled = tr.querySelector('.appointment-scheduled');
                if (scheduled) scheduled.title = when.toLocaleString();
            }
        }

        function updateAll() {
            els.forEach(updateOne);
        }

        // initial update
        updateAll();
        // clear previous interval if any
        if (window._headCountdownInterval) clearInterval(window._headCountdownInterval);
        // update every 30 seconds so minutes are kept accurate
        window._headCountdownInterval = setInterval(updateAll, 30000);
    }

    // Initialize on load (and allow external calls after AJAX replacement)
    document.addEventListener('DOMContentLoaded', startHeadAppointmentCountdowns);
    </script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/dashboard-sections/appointments.blade.php ENDPATH**/ ?>