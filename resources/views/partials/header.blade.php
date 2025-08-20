<header>
    <div class="logo">
        <img src="{{ asset('images/logo/logo.svg') }}" class="brand-logo" alt="SGRMS Logo">
    </div>
    <nav class="navbar">
        <a class="nav-link" href="{{ url('/') }}">Home</a>
        <a class="nav-link" href="{{ url('/#about') }}">About</a>
        <a class="nav-link" href="{{ url('/#services') }}">Services</a>
        <a class="nav-link" href="{{ url('/#staff') }}">Meet Our Staff</a>
        <a class="nav-link" href="{{ url('announcements') }}">Announcements</a>
    </nav>
    <div class="navigation">
        <div class="navigation-links">
            <a href="#" class="btn-login" onclick="openLoginModal()">Log in</a>
            <a class="btn-primary" href="{{ url('register') }}">
                <span class="text">Sign Up</span>
            </a>
        </div>
    </div>
</header>
