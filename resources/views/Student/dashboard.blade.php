@extends('layouts.student')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="box-page">
                <section class="analytics">
                    @include('Student.dashboard-sections.welcome-stats')
                </section>

                <!-- RIGHT COLUMN: Messages (spans both rows) -->
                <section class="side-container">
                    <div class="flex-side">
                        @include('Student.dashboard-sections.messages')
                    </div>
                </section>

                <!-- LEFT BOTTOM: Appointments -->
                <section class="bottom-container">
                    <div class="flex-bottom">
                        @include('Student.dashboard-sections.appointments')
                    </div>
                </section>
            </div>
        </div>

    </section>

@endsection
