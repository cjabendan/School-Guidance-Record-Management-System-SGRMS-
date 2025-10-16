@extends('layouts.counselor')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="dashboard-content">
                <div class="welcome-box">
                    @include('Counselor.dashboard-sections.welcome-stats')
                    @include('Counselor.dashboard-sections.todo')
                </div>

            {{-- <div class="appointment-container">
                    <div class="flex-bottom">
                        <div class="appointments-box">
                           @include('Counselor.dashboard-sections.appointments') 
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>


@endsection
