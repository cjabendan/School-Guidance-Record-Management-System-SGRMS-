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
                <li class="{{ Request::is('Counselor/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('Counselor/dashboard') }}">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
               @if (\App\Models\Feature::isEnabled('chat', 'counselor'))
                    <li class="{{ Request::is('Counselor/messages*') ? 'active' : '' }}">
                        <a href="{{ url('Counselor/messages') }}">
                            <i class="fi fi-sr-comment"></i>
                            <span class="text">Messages</span>
                        </a>
                    </li>
                @endif
                @if (\App\Models\Feature::isEnabled('appointment', 'counselor'))
                    <li class="{{ Request::is('Counselor/appointments*') ? 'active' : '' }}">
                        <a href="{{ url('Counselor/appointments') }}">
                            <i class='bx bxs-calendar'></i>
                            <span class="text">Appointments</span>
                        </a>
                    </li>
                @endif
                <li class="{{ Request::is('/settings*') ? 'active' : '' }}">
                    <a href="{{ url('/settings') }}">
                        <i class='bx bxs-cog'></i>
                        <span class="text">Settings</span>
                    </a>
                </li>
            </ul>

        </div>

        <div class="bottom-side">
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
    </div>
</section>
