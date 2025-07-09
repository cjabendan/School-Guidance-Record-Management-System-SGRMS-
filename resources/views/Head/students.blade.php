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
            <img src="{{ asset('images/user.img/people.png') }}" alt="Profile">
        </a>
    </nav>

    <main class="wrapper">
        <div class="card">
            <section class="student-list">
                <div class="search-flex">
                    <h2>Student List</h2>
                    <div class="search-bar">
                        <input type="text" id="search" name="search" class="search" placeholder="Search by ID or Name">
                        <button class="btn btn-add" onclick="openAddModal()">Add Student</button>
                    </div>
                </div>

                <!-- Education Level Tabs -->
                <div class="tab-container">
                    <a href="{{ url('Head/students') }}" class="tab {{ request('status') == null ? 'active' : '' }}">All Students</a>
                    <a href="{{ url('Head/students') . '?status=college' }}" class="tab {{ request('status') == 'college' ? 'active' : '' }}">College</a>
                    <a href="{{ url('Head/students') . '?status=highschool' }}" class="tab {{ request('status') == 'highschool' ? 'active' : '' }}">Highschool</a>
                    <a href="{{ url('Head/students') . '?status=elementary' }}" class="tab {{ request('status') == 'elementary' ? 'active' : '' }}">Elementary</a>
                    <a href="{{ url('Head/students') . '?status=inactive' }}" class="tab {{ request('status') == 'inactive' ? 'active' : '' }}">Inactive</a>
                    <a href="{{ url('Head/students') . '?status=archived' }}" class="tab {{ request('status') == 'archived' ? 'active' : '' }}">Archived</a>
                </div>

                <div class="table-container">
                    <table id="studentTable">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Educational Level</th>
                                <th>Section/Program</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                        @foreach ($students as $row)
                            @php
                                $caseCount = (int)$row->case_count;
                                $statusClass = $caseCount === 0 ? 'green' : ($caseCount <= 2 ? 'orange' : 'red');

                                $birthdate = new DateTime($row->bod);
                                $today = new DateTime();
                                $age = $today->diff($birthdate)->y;

                                $suffix = ($row->suffix !== 'N/A') ? $row->suffix : '';
                                $mname = trim($row->mname);
                                $mname = ($mname !== '') ? strtoupper(substr($mname, 0, 1)) . '.' : '';
                                $name = trim($row->lname . ", " . $row->fname . " " . $mname . " " . $suffix);
                            @endphp

                            <tr data-status="{{ strtolower($row->status) }}">
                                <td><span class="status-circle {{ $statusClass }}" style="background: {{ $statusClass }} !important;"></span></td>
                                <td>{{ $row->id_num }}</td>
                                <td>{{ $name }}</td>
                                <td>{{ $age }}</td>
                                <td>{{ $row->educ_level }}</td>
                                <td>{{ $row->section ?? $row->program }}</td>
                                <td> <!-- Actions if any --> </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <ul id="pagination-student" class="pagination"></ul>
            </section>
        </div>
    </main>
</section>

@include('Head.Modal.studentModal')

<script src="{{ asset('js/head.js') }}"></script>
<script src="{{ asset('js/Modal/studentModal.js') }}"></script>
</body>
</html>
