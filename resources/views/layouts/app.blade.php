<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'SGRMS - School Guidance Records Management System')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('1.png') }}">
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" />
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-chubby/css/uicons-solid-chubby.css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-straight/css/uicons-solid-straight.css'>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/announcements.css') }}">
    @yield('head')
     @include('partials.header')

     <script>
        window.addEventListener('pageshow', async () => {
            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'same-origin'
                });
                console.log('CSRF cookie refreshed');
            } catch (e) {
                console.warn('Failed to refresh CSRF:', e);
            }
        });
    </script>

</head>

<body>


    @yield('content')
    {{-- Main Content --}}
  
    @include('auth.login')
    @include('partials.footer')

    @stack('scripts')
    <script src="{{ asset('js/landing.js') }}"></script>
   
</body>

</html>



