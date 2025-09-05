    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'SGRMS - School Guidance Records Management System')</title>

    {{-- Styles --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('1.png') }}">
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
   

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/counsel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/counseling.css') }}">
    <link rel="stylesheet" href="{{ asset('css/announcements.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appointments.css') }}">
    <link rel="stylesheet" href="{{ asset('css/caseModal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/case.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    @yield('head')
</head>
<body>

    <!-- SIDEBAR -->

    @include('partials.head-sidebar')
    @yield('content')



</body>

@stack('scripts')

       
</body>

@stack('scripts')

<script src="{{ asset('js/head.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
