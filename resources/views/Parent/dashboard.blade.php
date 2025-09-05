@extends('layouts.parent')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="dashboard-content">
                <div class="welcome-box">
                    <div class="welcome">
                        <h2>Hello, {{ Auth::user()->first_name }}!</h2>
                        <p>Here's a summary of your child's recent activities.</p>
                    </div>
                    <div class="announcement">
                        @if (isset($announcements) && count($announcements))
                            <div id="announcement-slideshow">
                                @foreach ($announcements as $i => $announcement)
                                    <div class="slide announcement-slide @if ($i === 0) active @endif"
                                        style="background: {{ $announcement->image ? 'url(' . asset('images/announcements/' . $announcement->image) . ') center center/cover no-repeat' : '#eaf6ff' }};">
                                        <div class="announcement-overlay"></div>
                                        <div class="announcement-content">
                                            <h5 class="title">{{ $announcement->title }}</h5>
                                            <div class="description">{!! nl2br(e($announcement->description)) !!}</div>
                                            <div class="posted-date">
                                                {{ date('M d, Y', strtotime($announcement->date_posted)) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="announcement-dots">
                                    @foreach ($announcements as $i => $announcement)
                                        <span class="dot @if ($i === 0) active @endif"
                                            data-slide="{{ $i }}"></span>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-muted p-3">No announcements at this time.</div>
                        @endif
                    </div>
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
                </div>
                <div class="child-box">

                </div>
            </div>


        </div>


    @endsection
