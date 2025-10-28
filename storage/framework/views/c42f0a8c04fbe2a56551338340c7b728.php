<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>

    <!-- MAIN CONTENT -->
    <section id="content">
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="wrapper">
            <div class="dashboard-content">
                <div class="welcome-box">
                    <?php echo $__env->make('Student.dashboard-sections.welcome-stats', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('Student.dashboard-sections.announcements', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <div class="appointment-container">
                    <div class="flex-bottom">
                        <?php echo $__env->make('Student.dashboard-sections.appointments', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <div class="notifications-container">
                            <?php echo $__env->make('Student.dashboard-sections.messages', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>  
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

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Student/dashboard.blade.php ENDPATH**/ ?>