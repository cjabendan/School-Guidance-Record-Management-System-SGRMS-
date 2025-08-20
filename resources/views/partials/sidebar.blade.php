<section id="sidebar" class="sidebar">
    <div>
        <a href="#" class="brand">
           <!-- <img src="{{ asset('images/logo/logo.png') }}" class="brand-logo" alt="SGRMS Logo"> -->
        </a>
    </div>
    <div>
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
            <li>
                <a href="{{ url('Head/settings') }}">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
        </ul>
    </div>

    <div>
        <ul class="side-menu">
            <li>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit">
                        <i class="fi fi-br-exit"></i>
                        <span class="text">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

</section>
