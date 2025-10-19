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
                                <a href="#" class="type-filter {{ $type == 'all' ? 'active' : '' }}" data-type="all">All</a>
                                <a href="#" class="type-filter {{ $type == 'approved' ? 'active' : '' }}" data-type="approved">Approved</a>
                                <a href="#" class="type-filter {{ $type == 'pending' ? 'active' : '' }}" data-type="pending">Pending</a>
                                <a href="#" class="type-filter {{ $type == 'rejected' ? 'active' : '' }}" data-type="rejected">Rejected</a>
                            </li>
                        </div>
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
