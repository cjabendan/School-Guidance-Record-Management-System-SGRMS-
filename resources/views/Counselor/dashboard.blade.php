@extends('layouts.counselor')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="dashboard-content">
                <section class="welcome-box">
                    @include('Counselor.dashboard-sections.welcome-stats')
                    @include('Counselor.dashboard-sections.todo')
                </section>

                <!-- RIGHT COLUMN: Messages (spans both rows) -->
                <section class="side-container">
                    <div class="flex-side">
                        @include('Counselor.dashboard-sections.messages')
                    </div>
                </section>

                <!-- LEFT BOTTOM: Appointments -->
                <section class="bottom-container">
                    <div class="flex-bottom">
                        @include('Counselor.dashboard-sections.appointments')
                    </div>
                </section>
            </div>
        </div>


@endsection
