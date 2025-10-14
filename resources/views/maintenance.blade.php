<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SGRMS - School Guidance Records Management System')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('1.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-brands/css/uicons-brands.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/maintenance.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    @yield('content')

    <div class="maintenance">
        <div class="maintenance-content">
            <div class="maintenance-logo-wrapper">
                <img src="{{ asset('images/logo/logo.png') }}" alt="system-logo" class="system-logo">
            </div>

            <div class="maintenance-text-block">
                <h1 class="maintenance-title">We'll Be Back Soon!</h1>
                <p class="maintenance-text">
                    Our system is under maintenance to improve your experience. Please check back soon. Thank you.
                </p>
                <a href="{{ url('/') }}" class="maintenance-btn">Return to Landing Page</a>
            </div>

            <div class="maintenance-contact">
                <p class="maintenance-text">
                    For urgent concerns, please visit the <strong>Guidance Office</strong> or you can contact us below:
                </p>
                <div class="contact-links">
                    <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&tf=1&to=sgrms.guidance@gmail.com"
                        target="_blank" class="contact-link">
                       sgrms.guidance@gmail.com
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
