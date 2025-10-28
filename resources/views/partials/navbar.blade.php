<nav class="navbar">
    <div class="nav-left">
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">
            @php
                $pages = [
                    // Notifications
                    'Head.notify.notification' => 'Notifications',
                    'Counselor.notify.notification' => 'Notifications',
                    'Parent.notify.notification' => 'Notifications',
                    'Student.notify.notification' => 'Notifications',

                    // Head
                    'Head.dashboard' => 'Dashboard',
                    'Head.counselors.index' => 'Counselors',
                    'Head.counselors.show' => 'Counselor Details',
                    'Head.students.index' => 'Students Management',
                    'Head.parents.index' => 'Parents',
                    'Head.users.index' => 'User Management',
                    'Head.cases.index' => 'Case Reports',
                    'Head.messages.index' => 'Messages',
                    'Head.counseling.index' => 'Counseling Notes',
                    'Head.requests.index' => 'Child Links',
                    'Head.appointments.index' => 'Appointments',
                    'Head.announcements.index' => 'Announcements',
                    'Head.settings.index' => 'Settings',

                    // Counselor
                    'Counselor.dashboard' => 'Dashboard',
                    'Counselor.counseling.index' => 'Counseling Notes',
                    'Counselor.message.index' => 'Messages',
                    'Counselor.appointment.index' => 'Appointments',
                    'Counselor.settings.index' => 'Settings',

                    // Parent
                    'Parent.dashboard' => 'Dashboard',
                    'Parent.child.index' => 'My Children',
                    'Parent.messages.index' => 'Messages',
                    'Parent.requests.index' => 'Requests',
                    'Parent.appointments.index' => 'Appointments',
                    'Parent.settings.index' => 'Settings',

                    // Student
                    'Student.dashboard' => 'Dashboard',
                    'Student.messages.index' => 'Messages',
                    'Student.settings.index' => 'Settings',
                ];

                $page = $pages[Route::currentRouteName()] ?? '';
            @endphp
            {{ $page }}
        </a>
    </div>

    <div class="nav-right">
        @php
            use App\Models\Notification;
            $notifCount = Notification::where('user_id', Auth::id())
                ->where('is_read', 0)
                ->count();
            $userRole = Auth::user()->role;
            $userId = Auth::id();
        @endphp

        <script>
            window.userId = {{ $userId }};
            window.userRole = "{{ $userRole }}";
        </script>

        <!-- Notifications (Livewire real-time) -->
        <div class="nav-right" style="display:flex;align-items:center;gap:16px;">
            @livewire('notification-dropdown')
            <a href="#" class="user-profile" style="vertical-align:middle;"> 
                <img src="{{ asset('images/user/' . Auth::user()->profile_image) }}" alt=""> 
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
