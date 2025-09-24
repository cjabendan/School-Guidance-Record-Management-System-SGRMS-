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
                <li class="<?php echo e(Request::is('Counselor/dashboard') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Counselor/dashboard')); ?>">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li class="<?php echo e(Request::is('Counselor/messages*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Counselor/messages')); ?>">
                        <i class="fi fi-sr-comment"></i>
                        <span class="text">Messages</span>
                    </a>
                </li>
                <li class="<?php echo e(Request::is('Counselor/appointments*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Counselor/appointments')); ?>">
                        <i class='bx bxs-calendar'></i>
                        <span class="text">Appointments</span>
                    </a>
                </li>
                <li class="<?php echo e(Request::is('Counselor/settings*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Counselor/settings')); ?>">
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
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/partials/counselor-sidebar.blade.php ENDPATH**/ ?>