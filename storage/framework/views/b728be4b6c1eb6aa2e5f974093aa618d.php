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
                <li class="<?php echo e(Request::is('Parent/dashboard') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Parent/dashboard')); ?>">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li class="<?php echo e(Request::is('Parent/child*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('Parent/child')); ?>">
                        <i class="fi fi-sr-student"></i>
                        <span class="text">My Children</span>
                    </a>
                </li>
                <?php if(\App\Models\Feature::isEnabled('chat', 'parent')): ?>
                    <li class="<?php echo e(Request::is('Parent/messages*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(url('Parent/messages')); ?>">
                            <i class="fi fi-sr-comment"></i>
                            <span class="text">Messages</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(\App\Models\Feature::isEnabled('request', 'parent')): ?>
                    <li class="<?php echo e(Request::is('Parent/requests*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(url('Parent/requests')); ?>">
                            <i class="fi fi-sr-inbox"></i>
                            <span class="text">Requests</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(\App\Models\Feature::isEnabled('appointment', 'parent')): ?>
                    <li class="<?php echo e(Request::is('Parent/appointments*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(url('Parent/appointments')); ?>">
                            <i class='bx bxs-calendar'></i>
                            <span class="text">Appointments</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="<?php echo e(Request::is('settings*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('settings')); ?>">
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
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/partials/parent-sidebar.blade.php ENDPATH**/ ?>