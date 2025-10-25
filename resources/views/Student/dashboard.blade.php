@extends('layouts.student')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="dashboard-content">
                <div class="welcome-box">
                    @include('Student.dashboard-sections.welcome-stats')
                    @include('Student.dashboard-sections.announcements')
                </div>

                <div class="appointment-container">
                    <div class="flex-bottom">
                        @include('Student.dashboard-sections.appointments')
                        <div class="notifications-container">
                            @include('Student.dashboard-sections.messages')  
                        </div>      
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let current = 0;
                const slides = document.querySelectorAll('#announcement-slideshow .slide');
                const dots = document.querySelectorAll('.announcement-dots .dot');
                if (!slides.length) return;

                function showSlide(idx) {
                    slides.forEach((s, i) => {
                        s.classList.toggle('active', i === idx);
                    });
                    dots.forEach((d, i) => {
                        d.classList.toggle('active', i === idx);
                    });
                }

                dots.forEach((dot, i) => {
                    dot.addEventListener('click', function() {
                        current = i;
                        showSlide(current);
                    });
                });

                setInterval(function() {
                    current = (current + 1) % slides.length;
                    showSlide(current);
                }, 7000);
            });
        </script>
    @endpush

@endsection
