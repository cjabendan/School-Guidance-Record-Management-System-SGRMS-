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
                                    <a href="#" class="type-filter {{ $type == 'child-link' ? 'active' : '' }}"
                                        data-type="child-link">Child Link</a>
                                    <a href="#" class="type-filter {{ $type == 'document' ? 'active' : '' }}"
                                        data-type="document">Documents</a>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="filter-wrapper">
                        <!-- Dropdown Filter -->
                        <div class="filter-dropdown">
                            <button class="toggle-btn" id="toggle-view-btn">
                                <i class="fi fi-br-bars-filter" id="toggle-icon"></i>
                            </button>

                            <ul class="dropdown-menu" id="status-dropdown">
                                <li data-status="approved">Approved</li>
                                <li data-status="pending">Pending</li>
                                <li data-status="rejected">Rejected</li>
                                <li data-status="all">All</li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="table-list">
                    <div class="table-header">
                        <div class="table-col type">Request Type</div>
                        <div class="table-col requested-by">Requested By</div>
                        <div class="table-col requested-at">Requested At</div>
                        <div class="table-col status">Status</div>
                        <div class="table-col actions">Actions</div>
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
