<nav class="navbar">
    <div class="nav-right">
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Welcome, {{ Auth::user()->first_name ?? 'Admin' }}</a>
    </div>
    <div class="nav-left">
        <a href="#" id="notificationBell" class="notification">
            <i class='bx bxs-bell'></i>
            @if (isset($notifCount) && $notifCount > 0)
                <span class="num">{{ $notifCount }}</span>
            @endif
        </a>
        <a href="#" class="profile">
            <img src="{{ asset('images/user/boy.png') }}" alt="Profile">
        </a>
    </div>
</nav>
