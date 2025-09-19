@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="box-page">
                <section class="analytics">
                      @include('Head.dashboard-sections.stats')
                </section>

                <!-- ACTIVITIES -->
                <section class="side-container">
                    <div class="flex-side">
                        @include('Head.dashboard-sections.events')
                        @include('Head.dashboard-sections.messages')
                    </div>
                </section>

                <!-- APPOINTMENTS -->
                <section class="bottom-container">
                    <div class="flex-bottom">
                        @include('Head.dashboard-sections.appointments')
                    </div>
                </section>
            </div>
        </div>

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterDropdown = document.querySelector('.appointments-header .dropdown');
            filterDropdown.addEventListener('change', function() {
                showSmallLoader('#appointments-table-container');
                fetch(`{{ route('Head.dashboard') }}?filter=${this.value}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('appointments-table-container').innerHTML = data.html;
                        hideSmallLoader('#appointments-table-container');
                    })
                    .catch(() => hideSmallLoader('#appointments-table-container'));
            });
        });
    </script>
@endsection
