<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Guidance Record Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
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

    <main class="wrapper">
        <div class="card">
            <section class="parent-list">
                <div class="search-flex">
                    <h2>Parent List</h2>
                    <div class="search-bar">
                        <input type="text" id="parentSearch" name="parentSearch" class="search" placeholder="Search by Parent Name">
                    </div>
                </div>
                <div class="table-container">
                    <table class="styled-table" id="parentTable">
                        <thead>
                            <tr>
                                <th>Parent Name</th>
                                <th>Contact Number</th>
                                <th>Email</th>
                                <th>Account Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="parentTableBody">
                            @forelse($parents as $parent)
                                <tr>
                                    <td>{{ $parent->guardian_name }}</td>
                                    <td>{{ $parent->contact_num }}</td>
                                    <td>{{ $parent->email }}</td>
                                    <td>
                                        @php
                                            $status = strtolower($parent->account_status ?? '');
                                        @endphp
                                        @if (is_null($parent->account_status))
                                            <span class='badge badge-gray'>No Account</span>
                                        @elseif ($status === 'active')
                                            <span class='badge badge-green'>Has Account</span>
                                        @else
                                            <span class='badge badge-orange'>Inactive Account</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class='btn btn-view' data-id='{{ $parent->p_id }}'>View</button>
                                        <button class='btn btn-edit' data-id='{{ $parent->p_id }}'>Edit</button>
                                        <button class='btn btn-delete' data-id='{{ $parent->p_id }}'>Archive</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5">No parent accounts found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Optional Pagination -->
                    {{-- {{ $parents->links() }} --}}
                </div>
            </section>
        </div>
    </main>
</section>

<script src="{{ asset('js/head.js') }}"></script>
</body>
</html>
