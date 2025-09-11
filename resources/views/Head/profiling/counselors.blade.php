@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <!-- COUNSELORS MANAGEMENT -->
        <div class="wrapper">
            <h2>Manage Counselors</h2>
            <div class="profiles-container">
                <!-- Add new profile box -->
                <div class="profile-box add-box" onclick="openAddCounselorModal()">
                    <i class='bx bx-plus add-profile-icon'></i>
                    <h2>Add Counselor</h2>
                </div>

                @include('components.counselor-card', ['counselors' => $counselors])

            </div>
        </div>
    </section>

    @include('Head.modal.counselModal')

    <script src="{{ asset('js/head.js') }}"></script>
    <script src="{{ asset('js/Modal/counselModal.js') }}"></script>

@endsection
