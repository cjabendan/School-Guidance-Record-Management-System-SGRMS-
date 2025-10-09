<nav class="navbar">
    <div class="nav-left">
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">
            <?php
                $pages = [

                    // Notifications
                    'Head.notify.notification' => 'Notifications',

                    // Head
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
            

                    // Counselor
                    'Counselor.dashboard' => 'Dashboard',
                    'Counselor.counseling.index' => 'Counseling',
                    'Counselor.message.index' => 'Messages',
                    'Counselor.appointment.index' => 'Appointments',
                    

                    // Parent
                    'Parent.dashboard' => 'Dashboard',
                    'Parent.child.index' => 'My Children',
                    'Parent.messages.index' => 'Messages',
                    'Parent.requests.index' => 'Requests',
                    'Parent.appointments.index' => 'Appointments',
                    

                    // Student
                    'Student.dashboard' => 'Dashboard',
                    'Student.counseling.index' => 'Counseling',
                    'Student.messages.index' => 'Messages',
                    'Student.appointments.index' => 'Appointments',
                ];

                $page = $pages[Route::currentRouteName()] ?? '';
            ?>
            <?php echo e($page); ?>

        </a>
    </div>

    <div class="nav-right">
        <?php
            use App\Models\Notification;
            $notifCount = Notification::where('user_id', Auth::id())
                ->where('is_read', 0)
                ->count();
        ?>

    <!-- Notifications -->
    <div class="nav-right" style="display:flex;align-items:center;gap:16px;">
        <div class="notification-wrapper">
            <a href="#" id="notificationBell" class="notification-bell">
                <i class='bx bxs-bell'></i>
                <?php if($notifCount > 0): ?>
                    <span class="badge"><?php echo e($notifCount); ?></span>
                <?php endif; ?>
            </a>
            <div id="notificationDropdown" class="notification-dropdown" style="display:none;">
                <div id="notificationDropdownContent"><!-- loaded by AJAX --></div>
            </div>
        </div>    
        <a href="#" class="user-profile" style="vertical-align:middle;"> 
            <img src="<?php echo e(asset('images/user/' . Auth::user()->profile_image)); ?>" alt=""> 
        </a>
    </div>    
</nav>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const bell = document.getElementById('notificationBell');
    const dropdown = document.getElementById('notificationDropdown');
    const dropdownContent = document.getElementById('notificationDropdownContent');

    if (bell && dropdown && dropdownContent) {
        bell.addEventListener('click', e => {
            e.preventDefault();
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';

            if (dropdown.style.display === 'block') {
                fetch('/notify/fetch', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.json())
                    .then(data => {
                        dropdownContent.innerHTML = data.html || `
                            <div class="notify-empty">
                                <span class="notify-icon">🔔</span>
                                <p class="notify-text">You're all caught up!</p>
                                <small class="notify-time">No new notifications at this time.</small>
                            </div>`;
                    })
                    .catch(() => {
                        dropdownContent.innerHTML = `
                            <div class="notify-empty error">
                                <span class="notify-icon">❌</span>
                                <p class="notify-text">Could not load notifications.</p>
                            </div>`;
                    });
            }
        });

        // Close when clicking outside
        document.addEventListener('mousedown', e => {
            if (!dropdown.contains(e.target) && !bell.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    }
});
</script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/partials/navbar.blade.php ENDPATH**/ ?>