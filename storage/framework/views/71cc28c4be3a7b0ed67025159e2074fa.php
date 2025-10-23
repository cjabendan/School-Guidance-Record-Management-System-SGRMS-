<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>

    <!-- MAIN CONTENT -->
    <section id="content">
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="wrapper">
            <div class="dashboard-content">
                <div class="welcome-box">
                    <?php echo $__env->make('Parent.dashboard-sections.welcome-stats', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('Parent.dashboard-sections.announcements', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="appointment-container">
                    <div class="flex-bottom">
                        <div class="appointments-box">
                            <div class="appointments-header">
                                <h2>Your Child's Upcoming Appointments</h2>
                                <form method="GET" id="appointmentFilterForm">
                                    <select class="dropdown" name="filter"
                                        onchange="document.getElementById('appointmentFilterForm').submit()">
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
                                                        <p class="appointment-type">
                                                            <?php echo e($appointment->type->type_name ?? 'N/A'); ?>

                                                        </p>
                                                        <span class="appointment-requester">
                                                            <?php echo e($appointment->student->first_name ?? 'N/A'); ?>

                                                            <?php echo e($appointment->student->last_name ?? ''); ?>

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
                        <div class="notifications-container">
                            <?php echo $__env->make('Parent.dashboard-sections.messages', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let current = 0;
                const slides = document.querySelectorAll('#announcement-slideshow .slide');
                const dots = document.querySelectorAll('.announcement-dots .dot');
                if (!slides.length) return;

                function showSlide(idx) {
                    slides.forEach((s, i) => {
                        s.classList.toggle('active', i === idx);
                    });
                    dots.forEach((d, i) => {
                        d.classList.toggle('active', i === idx);
                    });
                }

                dots.forEach((dot, i) => {
                    dot.addEventListener('click', function() {
                        current = i;
                        showSlide(current);
                    });
                });

                setInterval(function() {
                    current = (current + 1) % slides.length;
                    showSlide(current);
                }, 7000);
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.parent', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Parent/dashboard.blade.php ENDPATH**/ ?>