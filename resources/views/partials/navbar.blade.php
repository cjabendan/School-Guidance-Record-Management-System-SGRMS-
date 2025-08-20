<nav class="navbar">
    <div class="nav-left">
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Welcome, {{ Auth::user()->first_name ?? 'Admin' }}</a>
    </div>
    <div class="nav-right">
        <a href="#" id="notificationBell" class="notification">
            <i class='bx bxs-bell'></i>
            @if (isset($notifCount) && $notifCount > 0)
                <span class="num">{{ $notifCount }}</span>
            @endif
        </a>
        <a href="#" class="user-profile">
                <img src="{{ asset('images/user/' . Auth::user()->profile_image) }}" alt="">
        </a>
    </div>
</nav>
