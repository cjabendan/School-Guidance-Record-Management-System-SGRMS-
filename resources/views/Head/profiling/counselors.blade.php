@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <!-- COUNSELORS MANAGEMENT -->
        <main class="wrapper">
            <h2>Manage Counselors</h2>
            <div class="profiles-container">
                <!-- Add new profile box -->
                <div class="profile-box add-box" onclick="console.log('Clicked'); openFormModal()">
                    <i class='bx bx-plus add-profile-icon'></i>
                    <h2>Add Counselor</h2>
                </div>

                @forelse($counselors as $counselor)
                    @php
                        $fullName = trim("{$counselor->last_name}, {$counselor->first_name} {$counselor->middle_name}");
                        $img = $counselor->profile_image
                            ? asset('uploads/users/' . $counselor->profile_image)
                            : asset('images/user.img/people.png');
                    @endphp
                    <div class="profile-box" onclick="openViewCounselModal('{{ $counselor->c_id }}')">
                        <img src="{{ $img }}" alt="Profile Picture">
                        <h2>{{ $fullName }}</h2>
                    </div>
                @empty
                    <p>No counselors found.</p>
                @endforelse

            </div>
        </main>
    </section>

    @include('Head.Modal.counselModal')

    <script src="{{ asset('js/head.js') }}"></script>
    <script src="{{ asset('js/Modal/counselModal.js') }}"></script>

@endsection
