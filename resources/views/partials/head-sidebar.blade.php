<script>
    // Read sidebar collapse state from localStorage as early as possible
    document.documentElement.classList.toggle(
        'sidebar-hidden',
        localStorage.getItem('sidebarState') === 'hide'
    );
</script>

<section id="sidebar" class="sidebar @if (request()->cookie('sidebarState') === 'hide') hide @endif">
    <div class="flex-side">
        <div>
            <a href="#" class="brand">
                <img src="{{ asset('images/logo/1.png') }}" class="brand-logo" alt="SGRMS Logo">
            </a>

            <ul class="side-menu top">
                <!-- Dashboard -->
                <li class="{{ Request::is('Head/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('Head/dashboard') }}">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>

                <!-- Profiling (main link) -->
                <li
                    class="{{ Request::is('Head/counselors*') || Request::is('Head/users*') || Request::is('Head/students*') ? 'active' : '' }}">
                    <a href="#" id="profiling-link">
                        <i class='bx bxs-user'></i>
                        <span class="text">Profiling</span>
                        <i class='bx bx-chevron-down' style="margin-left:auto;"></i>
                    </a>
                </li>

                <!-- Profiling submenu -->
                <ul class="submenu {{ Request::is('Head/counselors*') || Request::is('Head/users*') || Request::is('Head/students*') ? 'active' : '' }}"
                    id="profiling-submenu">
                       <!--   
                    <li class="{{ Request::is('Head/counselors*') ? 'active' : '' }}">
                        <a href="{{ url('Head/counselors') }}">
                            <i class="fi fi-sr-review"></i>
                            <span class="text">Counselors</span>
                        </a>
                    </li>
                   
                    <li class="{{ Request::is('Head/parents*') ? 'active' : '' }}">
                        <a href="{{ url('Head/parents') }}">
                            <i class="fi fi-sr-users"></i>
                            <span class="text">Parents</span>
                        </a>
                    </li> -->
                    
                    <li class="{{ Request::is('Head/students*') ? 'active' : '' }}">
                        <a href="{{ url('Head/students') }}">
                            <i class="fi fi-sr-student"></i>
                            <span class="text">Students</span>
                        </a>
                    </li>
                     <li class="{{ Request::is('Head/users*') ? 'active' : '' }}">
                        <a href="{{ url('Head/users') }}">
                            <i class="fi fi-sr-users"></i>
                            <span class="text">Users</span>
                        </a>
                    </li>
                </ul>

                <!-- Other menus -->
               
                 <li class="{{ Request::is('Head/requests*') ? 'active' : '' }}">
                    <a href="{{ url('Head/requests') }}">
                       <i class="fi fi-sr-user-link"></i>
                        <span class="text">Child Links</span>
                    </a>
                </li>
                 <li class="{{ Request::is('Head/cases*') ? 'active' : '' }}">
                    <a href="{{ url('Head/cases') }}">
                        <i class='bx bxs-report'></i>
                        <span class="text">Case Reports</span>
                    </a>
                </li>
                <li class="{{ Request::is('Head/counseling*') ? 'active' : '' }}">
                    <a href="{{ url('Head/counseling') }}">
                        <i class="fi fi-sr-journal-alt"></i>
                        <span class="text">Counseling Notes</span>
                    </a>
                </li>
                <li class="{{ Request::is('Head/messages*') ? 'active' : '' }}">
                    <a href="{{ url('Head/messages') }}">
                        <i class="fi fi-sr-comment"></i>
                        <span class="text">Messages</span>
                    </a>
                </li>
             
               
                <li class="{{ Request::is('Head/appointments*') ? 'active' : '' }}">
                    <a href="{{ url('Head/appointments') }}">
                        <i class='bx bxs-calendar'></i>
                        <span class="text">Appointments</span>
                    </a>
                </li>
                <li class="{{ Request::is('Head/announcements*') ? 'active' : '' }}">
                    <a href="{{ url('Head/announcements') }}">
                        <i class='bx bxs-megaphone'></i>
                        <span class="text">Announcements</span>
                    </a>
                </li>


                <li class="{{ Request::is('settings*') ? 'active' : '' }}">
                    <a href="{{ url('settings') }}">
                        <i class='bx bxs-cog'></i>
                        <span class="text">Settings</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="bottom-side">
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
    </div>

</section>
