<script>
    // Read sidebar collapse state from localStorage as early as possible
    document.documentElement.classList.toggle(
        'sidebar-hidden',
        localStorage.getItem('sidebarState') === 'hide'
    );
</script>

<section id="sidebar" class="sidebar @if(request()->cookie('sidebarState') === 'hide') hide @endif">    
    <div>
        <a href="#" class="brand">
            <img src="{{ asset('images/logo/1.png') }}" class="brand-logo" alt="SGRMS Logo">
        </a>
    </div>

    <div>
        <ul class="side-menu top">
            <!-- Dashboard -->
            <li class="{{ Request::is('Head/dashboard') ? 'active' : '' }}">
                <a href="{{ url('Head/dashboard') }}">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>

            <!-- Profiling (main link) -->
            <li class="{{ Request::is('Head/counselors*') || Request::is('Head/parents*') || Request::is('Head/students*') ? 'active' : '' }}">
                <a href="#" id="profiling-link">
                    <i class='bx bxs-user'></i>
                    <span class="text">Profiling</span>
                    <i class='bx bx-chevron-down' style="margin-left:auto;"></i>
                </a>
            </li>

            <!-- Profiling submenu -->
            <ul class="submenu {{ Request::is('Head/counselors*') || Request::is('Head/parents*') || Request::is('Head/students*') ? 'active' : '' }}" id="profiling-submenu">
                <li class="{{ Request::is('Head/counselors*') ? 'active' : '' }}">
                    <a href="{{ url('Head/counselors') }}">
                        <i class='bx bxs-user-voice'></i>
                        <span class="text">Counselors</span>
                    </a>
                </li>
                <li class="{{ Request::is('Head/parents*') ? 'active' : '' }}">
                    <a href="{{ url('Head/parents') }}">
                        <i class="fi fi-sr-users"></i>
                        <span class="text">Parents</span>
                    </a>
                </li>
                <li class="{{ Request::is('Head/students*') ? 'active' : '' }}">
                    <a href="{{ url('Head/students') }}">
                        <i class="fi fi-sr-student"></i>
                        <span class="text">Students</span>
                    </a>
                </li>
            </ul>

            <!-- Other menus -->
            <li class="{{ Request::is('Head/reports*') ? 'active' : '' }}">
                <a href="{{ url('Head/reports') }}">
                    <i class='bx bxs-report'></i>
                    <span class="text">Reports</span>
                </a>
            </li>
            <li class="{{ Request::is('Head/appointments*') ? 'active' : '' }}">
                <a href="{{ url('Head/appointments') }}">
                    <i class='bx bxs-calendar'></i>
                    <span class="text">Appointments</span>
                </a>
            </li>
            <li class="{{ Request::is('Head/settings*') ? 'active' : '' }}">
                <a href="{{ url('Head/settings') }}">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
        </ul>
    </div>

    <div>
        <ul class="side-menu">
            <!-- Logout -->
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
