<script>
    // Read sidebar collapse state from localStorage as early as possible
    document.documentElement.classList.toggle(
        'sidebar-hidden',
        localStorage.getItem('sidebarState') === 'hide'
    );
</script>

<section id="sidebar" class="sidebar <?php if(request()->cookie('sidebarState') === 'hide'): ?> hide <?php endif; ?>">
    <div class="flex-side">
        <div>
            <a href="#" class="brand">
                <img src="<?php echo e(asset('images/logo/1.png')); ?>" class="brand-logo" alt="SGRMS Logo">
            </a>

            <ul class="side-menu top">
                <!-- Dashboard -->
                <li class="<?php echo e(Request::is('Head/dashboard') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Head/dashboard')); ?>">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>

                <!-- Profiling (main link) -->
                <li
                    class="<?php echo e(Request::is('Head/counselors*') || Request::is('Head/parents*') || Request::is('Head/students*') ? 'active' : ''); ?>">
                    <a href="#" id="profiling-link">
                        <i class='bx bxs-user'></i>
                        <span class="text">Profiling</span>
                        <i class='bx bx-chevron-down' style="margin-left:auto;"></i>
                    </a>
                </li>

                <!-- Profiling submenu -->
                <ul class="submenu <?php echo e(Request::is('Head/counselors*') || Request::is('Head/parents*') || Request::is('Head/students*') ? 'active' : ''); ?>"
                    id="profiling-submenu">
                    <li class="<?php echo e(Request::is('Head/counselors*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(url('Head/counselors')); ?>">
                            <i class="fi fi-sr-review"></i>
                            <span class="text">Counselors</span>
                        </a>
                    </li>
                    <li class="<?php echo e(Request::is('Head/parents*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(url('Head/parents')); ?>">
                            <i class="fi fi-sr-users"></i>
                            <span class="text">Parents</span>
                        </a>
                    </li>
                    <li class="<?php echo e(Request::is('Head/students*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(url('Head/students')); ?>">
                            <i class="fi fi-sr-student"></i>
                            <span class="text">Students</span>
                        </a>
                    </li>
                </ul>

                <!-- Other menus -->
                <li class="<?php echo e(Request::is('Head/cases*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Head/cases')); ?>">
                        <i class='bx bxs-report'></i>
                        <span class="text">Case Reports</span>
                    </a>
                </li>
                   <li class="<?php echo e(Request::is('Head/counseling*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Head/counseling')); ?>">
                        <i class="fi fi-sr-journal-alt"></i>
                        <span class="text">Counseling</span>
                    </a>
                </li>
                <li class="<?php echo e(Request::is('Head/messages*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Head/messages')); ?>">
                        <i class="fi fi-sr-comment"></i>
                        <span class="text">Messages</span>
                    </a>
                </li>
             
                <li class="<?php echo e(Request::is('Head/requests*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Head/requests')); ?>">
                        <i class="fi fi-sr-inbox"></i>
                        <span class="text">Requests</span>
                    </a>
                </li>
                <li class="<?php echo e(Request::is('Head/appointments*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Head/appointments')); ?>">
                        <i class='bx bxs-calendar'></i>
                        <span class="text">Appointments</span>
                    </a>
                </li>
                <li class="<?php echo e(Request::is('Head/announcements*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Head/announcements')); ?>">
                        <i class='bx bxs-megaphone'></i>
                        <span class="text">Announcements</span>
                    </a>
                </li>


                <li class="<?php echo e(Request::is('Head/settings*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Head/settings')); ?>">
                        <i class='bx bxs-cog'></i>
                        <span class="text">Settings</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="bottom-side">
            <ul class="side-menu">
                <!-- Logout -->
                <li>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="logout-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit">
                            <i class="fi fi-br-exit"></i>
                            <span class="text">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

</section>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/partials/head-sidebar.blade.php ENDPATH**/ ?>