<nav class="navbar">
    <div class="nav-left">
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">
            <?php
                $routeName = Route::currentRouteName();

                // Head sidebar mapping
                $headPages = [
                    'Head.dashboard' => 'Dashboard',
                    'Head.counselors.index' => 'Counselors',
                    'Head.counselors.show' => 'Counselor Details',
                    'Head.students.index' => 'Students',
                    'Head.parents.index' => 'Parents',
                    'Head.cases.index' => 'Case Reports',
                    'Head.messages.index' => 'Messages',
                    'Head.counseling.index' => 'Counseling',
                    'Head.requests.index' => 'Requests',
                    'Head.appointments.index' => 'Appointments',
                    'Head.announcements.index' => 'Announcements',
                    'Head.settings.index' => 'Settings',
                ];

                // Counselor sidebar mapping
                $counselorPages = [
                    'Counselor.dashboard' => 'Dashboard',
                    'Counselor.appointments.index' => 'Appointments',
                    'Counselor.settings.index' => 'Settings',
                ];

                // Parent sidebar mapping
                $parentPages = [
                    'Parent.dashboard' => 'Dashboard',
                    'Parent.child.index' => 'My Children',
                    'Parent.messages.index' => 'Messages',
                    'Parent.requests.index' => 'Requests',
                    'Parent.appointments.index' => 'Appointments',
                    'Parent.settings.index' => 'Settings',
                ];

                $allPages = array_merge($headPages, $counselorPages, $parentPages);

                $page = $allPages[$routeName] ?? 'Welcome';
            ?>
            <?php echo e($page); ?>

        </a>
    </div>
    <div class="nav-right">
        <a href="#" id="notificationBell" class="notification">
            <i class='bx bxs-bell'></i>
            <?php if(isset($notifCount) && $notifCount > 0): ?>
                <span class="num"><?php echo e($notifCount); ?></span>
            <?php endif; ?>
        </a>
        <a href="#" class="user-profile">
            <img src="<?php echo e(asset('images/user/' . Auth::user()->profile_image)); ?>" alt="">
        </a>
    </div>
</nav>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/partials/navbar.blade.php ENDPATH**/ ?>