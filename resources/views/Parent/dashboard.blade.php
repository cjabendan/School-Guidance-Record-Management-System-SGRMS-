@extends('layouts.parent')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="dashboard-content">
                <div class="welcome-box">
                    @include('Parent.dashboard-sections.welcome-stats')
                    @include('Parent.dashboard-sections.announcements')
                </div>
                <div class="appointment-container">
                    <div class="flex-bottom">
                        <div class="appointments-box">
                            <div class="appointments-header">
                                <h2>Your Child's Upcoming Appointments</h2>
                                <form method="GET" id="appointmentFilterForm">
                                    <select class="dropdown" name="filter"
                                        onchange="document.getElementById('appointmentFilterForm').submit()">
                                        <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today
                                        </option>
                                        <option value="tomorrow" {{ request('filter') == 'tomorrow' ? 'selected' : '' }}>
                                            Tomorrow</option>
                                        <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>This
                                            Week
                                        </option>
                                    </select>
                                </form>
                            </div>
                            <div class="appointments-table" id="appointments-table-container" style="position:relative;">
                                @include('components.small-loader')
                                <table>
                                    <tbody>
                                        @forelse($upcomingAppointments as $appointment)
                                            <tr class="appointment-card">
                                                <td class="appointment-time-col">
                                                    {{ $appointment->appointment_datetime->format('h:i A') }}
                                                </td>
                                                <td class="appointment-details-col">
                                                    <div class="appointment-details-flex">
                                                        <p class="appointment-type">
                                                            {{ $appointment->type->type_name ?? 'N/A' }}
                                                        </p>
                                                        <span class="appointment-requester">
                                                            {{ $appointment->student->first_name ?? 'N/A' }}
                                                            {{ $appointment->student->last_name ?? '' }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="appointment-actions-col">
                                                    <div class="appointment-date">
                                                        {{ $appointment->appointment_datetime->format('M d, Y') }}
                                                    </div>
                                                    <div class="appointment-action">
                                                        <a href="" class="appointment-link">View details</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="no-appointments-cell">
                                                    No upcoming appointments.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="notifications-container">
                            @include('Parent.dashboard-sections.messages')
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
