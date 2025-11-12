<script>
    const imageBase = "{{ asset('images/user') }}";
    const defaultImage = "{{ asset('images/user/default.png') }}";
</script>

@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')

@section('content')
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="table-container">
                <div class="table-management">
                    <div class="table-nav">
                        <div class="table-filter">
                            <div class="filters">
                                <li>
                                    <a href="#" class="type-filter {{ $type == 'all' ? 'active' : '' }}"
                                        data-type="all">All</a>
                                    <a href="#" class="type-filter {{ $type == 'approved' ? 'active' : '' }}"
                                        data-type="approved">With Linked Students</a>
                                    <a href="#" class="type-filter {{ $type == 'pending' ? 'active' : '' }}"
                                        data-type="pending">With Pending Requests</a>
                            </div>
                        </div>
                    </div>
                    <div class="search-bar">
                        <div class="table-search">
                            <form method="GET" action="">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search parents..." id="student-search-input">
                                <button type="submit" style="display:none"></button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="table-list">
                    <div class="table-header">
                        <div class="table-col">Parent Name</div>
                        <div class="table-col">Linked Students</div>
                        <div class="table-col">Pending Requests</div>
                        <div class="table-col ">Last Updated</div>
                        <div class="table-col ">Actions</div>
                    </div>

                    <div class="table" id="requests-table"></div>
                </div>

                <div id="requests-pagination" class="pagination"></div>
            </div>
        </div>
    </section>

    @include('Head.Modal.requestModal')

    @push('scripts')
        <script src="{{ asset('js/requests.js') }}"></script>
    @endpush
@endsection
