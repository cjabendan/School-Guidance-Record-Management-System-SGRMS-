<section class="home" id="home">

    {{-- Video Background --}}

    <div class="video-overlay">
        <video class="video-slide active" src="{{ asset('video/landing.mp4') }}" muted></video>
        <video class="video-slide" src="{{ asset('video/parent.mp4') }}" muted></video>
        <video class="video-slide" src="{{ asset('video/appointment.mp4') }}" muted></video>
        <video class="video-slide" src="{{ asset('video/gabi.mp4') }}" muted></video>
        <video class="video-slide" src="{{ asset('video/program.mp4') }}" muted></video>
    </div>

    {{-- Hero Section Content --}}
    <div class="content active">
        <h1>Student<br><span>Well-being</span></h1>
        <p>We care about your growth. Our Guidance Office offers a safe and welcoming space for students to talk,
            reflect, and seek help when needed.</p>
        <a href="#" onclick="openLoginModal()">Explore Our Services</a>
    </div>

    <div class="content">
        <h1>Parental<br><span>Involvement</span></h1>
        <p>Parents stay informed through real-time access to records, appointments, and messages from the guidance team.
        </p>
        <a href="#" onclick="openLoginModal()">Access Parent Portal</a>
    </div>

    <div class="content">
        <h1>Book<br><span>Appointments</span></h1>
        <p>Students and parents can easily schedule counseling sessions online without waiting in line or paperwork.</p>
        <a href="#" onclick="openLoginModal()">Schedule Now</a>
    </div>

    <div class="content">
        <h1>Always Accessible<br><span>Meet Gabby</span></h1>
        <p>Need help? Gabby, our built-in AI assistant, is here to guide you through FAQs, appointments, and more —
            anytime you need it.</p>
        <a href="#" onclick="openLoginModal()">Chat with Gabby</a>
    </div>

    <div class="content">
        <h1>Events &<br><span>Programs</span></h1>
        <p>Stay updated with upcoming guidance-led seminars, activities, and student-centered wellness programs.</p>
        <a href="#">View Calendar</a>
    </div>

    <div class="slider-navigation">
        <div class="nav-btn active"></div>
        <div class="nav-btn"></div>
        <div class="nav-btn"></div>
        <div class="nav-btn"></div>
        <div class="nav-btn"></div>
    </div>

    {{-- Highlight Section: Feature Blocks --}}
    <div class="highlight-overlay">
        <div class="highlight-box">
            <i class="fas fa-user-graduate"></i>
            <h3>Student Records</h3>

        </div>
        <div class="highlight-box">
            <i class="fas fa-comments"></i>
            <h3>Built-in Chat</h3>

        </div>
        <div class="highlight-box">
            <i class="fas fa-robot"></i>
            <h3>AI Assistant</h3>

        </div>
        <div class="highlight-box">
            <i class="fas fa-bullhorn"></i>
            <h3>Events & Programs</h3>

        </div>
    </div>

</section>
