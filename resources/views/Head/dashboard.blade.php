<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Guidance Record Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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

            <div class="wrapper">
                <!-- HEAD -->
                <div class="head-title">
                    <div class="left"><h1>Dashboard</h1></div>
                </div>


                <!-- STATS -->
                <section class="stats">
                @php
                    use Illuminate\Support\Facades\DB;

                    $entities = [
                        'case_records' => [
                            'label' => 'Cases',
                            'class' => 'stat-cases',
                            'icon'  => "<i class='bx bxs-folder-open' style='color:#004085; margin-right:8px;'></i>"
                        ],
                        'students' => [
                            'label' => 'Students',
                            'class' => 'stat-white',
                            'icon'  => "<i class='bx bxs-graduation' style='color:#004085; margin-right:8px;'></i>"
                        ],
                        'parents' => [
                            'label' => 'Parents',
                            'class' => 'stat-white',
                            'icon'  => "<i class='bx bxs-user-detail' style='color:#004085; margin-right:8px;'></i>"
                        ],
                        'counselors' => [
                            'label' => 'Counselors',
                            'class' => 'stat-white',
                            'icon'  => "<i class='bx bxs-user-voice' style='color:#004085; margin-right:8px;'></i>"
                        ]
                    ];
                @endphp

                @foreach ($entities as $table => $info)
                    @php
                        $count = DB::table($table)->count();
                    @endphp
                    <div class="stat-box {{ $info['class'] }}">
                        <h2>{!! $info['icon'] !!}{{ $info['label'] }}</h2>
                        <p>{{ $count }}</p>
                    </div>
                @endforeach

                </section>


                <!-- CHART + ACTIVITIES -->
                <div class="box-page">
                    <!-- CHART -->
                <section class="analytics">
                        <h2>Case Report Analytics</h2>
                        <canvas id="caseChart"></canvas>
                        @php
                            $caseTypes = ['Behavioral', 'Emotional', 'Peer Conflict', 'Academic'];
                            $caseTypeData = [];
                            foreach ($caseTypes as $type) {
                                $caseTypeData[$type] = array_fill(1, 12, 0);
                            }
                            $caseTypeData['Total'] = array_fill(1, 12, 0);

                            $results = DB::table('case_records')
                                ->selectRaw('case_type, MONTH(filed_date) as month, COUNT(*) as total')
                                ->whereNotNull('filed_date')
                                ->groupBy('case_type', DB::raw('MONTH(filed_date)'))
                                ->get();

                            foreach ($results as $row) {
                                $type = $row->case_type;
                                $month = intval($row->month);
                                if (isset($caseTypeData[$type])) {
                                    $caseTypeData[$type][$month] = intval($row->total);
                                    $caseTypeData['Total'][$month] += intval($row->total);
                                }
                            }
                        @endphp
                        <script>
                            const caseTypeData = @json($caseTypeData);
                            // You can add code here to generate chart using Chart.js
                        </script>
                    </section>

                    <!-- ACTIVITIES -->
                    <section class="activities">
                        <div class="activities-box">
                            <h2>Case Distribution</h2>
                            <div class="pie-flex">
                                <div class="pie-chart-container">
                                    <canvas id="caseTypePie"></canvas>
                                </div>
                                <div id="caseTypeLegend" class="pie-legend"></div>
                            </div>
                        </div>
                    </section>

                    <!-- APPOINTMENTS -->
                    <section class="appointment">
                        <div class="appointment-box">
                            <h2>Upcoming Appointments</h2>
                            <ul>
                                @php
                                    $appointments = DB::table('appointments')
                                        ->where('appointment_date', '>=', now())
                                        ->orderBy('appointment_date', 'asc')
                                        ->limit(5)
                                        ->get();
                                @endphp
                                @forelse ($appointments as $appt)
                                    <li><strong>{{ $appt->date }}</strong>: {{ $appt->description ?? 'Appointment' }}</li>
                                @empty
                                    <li>No upcoming counseling sessions.</li>
                                @endforelse
                            </ul>
                        </div>
                    </section>
                </div>


                <!-- TABLE + TODO -->
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Recent Counselings</h3>
                            <i class='bx bx-search'></i>
                            <i class='bx bx-filter'></i>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Date Scheduled</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example rows -->
                                <tr><td><img src="{{ asset('img/people.png') }}"><p>Juan Dela Cruz</p></td><td>05-13-2025</td><td><span class="status completed">Completed</span></td></tr>
                                <tr><td><img src="{{ asset('img/people.png') }}"><p>Maria Santos</p></td><td>05-14-2025</td><td><span class="status pending">Pending</span></td></tr>
                                <tr><td><img src="{{ asset('img/people.png') }}"><p>Carlos Reyes</p></td><td>05-15-2025</td><td><span class="status process">Ongoing</span></td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="todo">
                        <div class="head">
                            <h3>Counselor's Tasks</h3>
                            <i class='bx bx-plus'></i>
                            <i class='bx bx-filter'></i>
                        </div>
                        <ul class="todo-list">
                            <li class="completed"><p>Review case reports</p><i class='bx bx-check-circle'></i></li>
                            <li class="not-completed"><p>Follow up with parents</p><i class='bx bx-time-five'></i></li>
                            <li class="not-completed"><p>Prepare student profile forms</p><i class='bx bx-file'></i></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- SCRIPTS -->
        <script src="../../js/head.js"></script>
        <script src="../../js/linechart.js"></script>
        <script src="../../js/piechart.js"></script>
    </body>
</html>

