<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGRMS</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <div class="logo">
            <img src="{{ asset('images/logo/logo.svg') }}" class="brand-logo" alt="SGRMS Logo">
        </div>
        <div class="menu-btn">
            <span class="solar--hamburger-menu-linear"></span>
        </div>
        <div class="navigation">
            <div class="navigation-links">
                <button type="button" class="btn-login" onclick="openLoginModal()"><a href="#">Log in</a></button>
                <a class="btn-primary" href="{{ url('register') }}">
                    <span class="text">Sign Up</span>
                </a>
            </div>
        </div>
    </header>

    <section class="home">
        <div class="video-overlay">
            <video class="video-slide active" src="{{ asset('video/landing.mp4') }}" muted></video>
            <video class="video-slide" src="{{ asset('video/parent.mp4') }}" muted></video>
            <video class="video-slide" src="{{ asset('video/appointment.mp4') }}" muted></video>
            <video class="video-slide" src="{{ asset('video/counseling.mp4') }}" muted></video>
            <video class="video-slide" src="{{ asset('video/program.mp4') }}" muted></video>
        </div>
        <div class="content active">
            <h1>Student<br><span>Well-being</span></h1>
            <p>We care about your growth. Our Guidance Office offers a safe and welcoming space for students to talk, reflect, and seek help when needed.</p>
            <a href="#">Learn More</a>
        </div>

        <div class="content">
            <h1>Parental<br><span>Involvement</span></h1>
            <p>Parents can stay informed and connected. Our system allows guardians to monitor their child's guidance records and progress securely.</p>
            <a href="#">Parent Portal</a>
        </div>

        <div class="content">
            <h1>Request<br><span>Appointment</span></h1>
            <p>Need to talk? Both students and parents can easily schedule a counseling session through our online appointment system.</p>
            <a href="#">Book Now</a>
        </div>

        <div class="content">
            <h1>Counseling<br><span>Support</span></h1>
            <p>Our licensed counselors are here to guide students through emotional challenges, academic stress, peer conflicts, and future planning.</p>
            <a href="#">Meet the Counselors</a>
        </div>

        <div class="content">
            <h1>Guidance<br><span>Programs</span></h1>
            <p>We offer seminars, group sessions, and workshops to promote self-awareness, communication skills, and a healthy student environment.</p>
            <a href="#">View Programs</a>
        </div>

        <div class="slider-navigation">
            <div class="nav-btn active"></div>
            <div class="nav-btn"></div>
            <div class="nav-btn"></div>
            <div class="nav-btn"></div>
            <div class="nav-btn"></div>
        </div>

    </section>

    @include('auth.login')
    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>
