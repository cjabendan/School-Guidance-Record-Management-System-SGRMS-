
<!-- MAIN CONTENT -->
<section id="content">
    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Welcome, {{ Auth::user()->fname ?? 'Admin' }}</a>
        <form action="#">
            <div class="form-input">
                <input type="search" placeholder="Search...">
                <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
            </div>
        </form>
        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode" aria-label="Switch Dark/Light Mode"></label>

        <a href="#" id="notificationBell" class="notification">
            <i class='bx bxs-bell'></i>
            @if (isset($notifCount) && $notifCount > 0)
                <span class="num">{{ $notifCount }}</span>
            @endif
        </a>
        <a href="#" class="profile">
            <img src="{{ asset('images/people.png') }}" alt="Profile">
        </a>
    </nav>

    <div class="wrapper">
        <div class="head-title">
            <div class="left">
                <h1>Dashboard</h1>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('js/head.js') }}"></script>
