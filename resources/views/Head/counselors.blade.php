<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Guidance Record Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/counsel.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
</head>
<body>
<!-- SIDEBAR -->
<section id="sidebar">
    <a href="#" class="brand">
        <img src="{{ asset('images/logo/logo.svg') }}" class="brand-logo" alt="SGRMS Logo">
    </a>
    <ul class="side-menu top">
        <li class="active">
            <a href="{{ url('Head/dashboard') }}">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="#" id="profiling-link">
                <i class='bx bxs-user'></i>
                <span class="text">Profiling</span>
                <i class='bx bx-chevron-down' style="margin-left:auto;"></i>
            </a>
        </li>
        <ul class="submenu" id="profiling-submenu">
            <li>
                <a href="{{ url('Head/counselors') }}">
                    <i class='bx bxs-user-voice'></i>
                    <span class="text">Counselors</span>
                </a>
            </li>
            <li>
                <a href="{{ url('Head/parents') }}">
                    <i class='bx bxs-chalkboard'></i>
                    <span class="text">Parents</span>
                </a>
            </li>
            <li>
                <a href="{{ url('Head/students') }}">
                    <i class='bx bxs-graduation'></i>
                    <span class="text">Students</span>
                </a>
            </li>
        </ul>
        <li>
            <a href="{{ url('Head/reports') }}">
                <i class='bx bxs-report'></i>
                <span class="text">Reports</span>
            </a>
        </li>
        <li>
            <a href="{{ url('Head/appointments') }}">
                <i class='bx bxs-calendar'></i>
                <span class="text">Appointments</span>
            </a>
        </li>
    </ul>
    <ul class="side-menu">
        <li>
            <a href="{{ url('Head/settings') }}">
                <i class='bx bxs-cog'></i>
                <span class="text">Settings</span>
            </a>
        </li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                <a href="{{ route('logout') }}" class="logout">
                <i class='bx bxs-log-out-circle'></i>
                <span class="text">Logout</span>
            </a>
            </form>

        </li>
    </ul>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profilingLink = document.getElementById('profiling-link');
        const profilingSubmenu = document.getElementById('profiling-submenu');
        profilingLink.addEventListener('click', function(e) {
            e.preventDefault();
            profilingSubmenu.classList.toggle('active');
        });
    });
</script>

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
            @if(isset($notifCount) && $notifCount > 0)
                <span class="num">{{ $notifCount }}</span>
            @endif
        </a>
        <a href="#" class="profile">
            <img src="{{ asset('images/people.png') }}" alt="Profile">
        </a>
    </nav>

    <!-- COUNSELORS MANAGEMENT -->
    <main class="wrapper">
        <h2>Manage Counselors</h2>
        <div class="profiles-container">
            <!-- Add new profile box -->
            <div class="profile-box add-box" onclick="console.log('Clicked'); openFormModal()">
                <i class='bx bx-plus add-profile-icon'></i>
                <h2>Add Counselor</h2>
            </div>

            @forelse($counselors as $counselor)
                @php
                    $fullName = "{$counselor->lname}, {$counselor->fname} {$counselor->mname}";
                    $img = $counselor->c_image ? asset('uploads/counselors/' . $counselor->c_image) : asset('images/user.img/people.png');
                @endphp
                <div class="profile-box" onclick="openViewCounselModal('{{ $counselor->c_id }}')">
                    <img src="{{ $img }}" alt="Profile Picture">
                    <h2>{{ $fullName }}</h2>
                </div>
            @empty
                <p>No counselors found.</p>
            @endforelse

        </div>
    </main>
</section>

@include('Head.Modal.counselModal')

<script src="{{ asset('js/head.js') }}"></script>
<script src="{{ asset('js/Modal/counselModal.js') }}"></script>
</body>
</html>
