@extends('layouts.parent')
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
                                    <a href="#" class="active" data-type="all">All</a>
                                    <a href="#" data-type="link">Child Link</a>
                                    <a href="#" data-type="document">Documents</a>
                                </li>
                            </div>
                        </div>
                    </div>
                    <button class="toggle-btn" id="toggle-view-btn">
                        <i class="fi fi-br-bars-filter" id="toggle-icon"></i>
                        <span id="toggle-label"></span>
                    </button>
                </div>
                <div class="table-list">
                    <div class="table-header">
                        <div class="table-col type">Request Type</div>
                        <div class="table-col requested-for">Requested For</div>
                        <div class="table-col requested-at">Requested At</div>
                        <div class="table-col status">Status</div>
                        <div class="table-col actions">Actions</div>
                    </div>

                    <div class="table">
                        @foreach ($allRequests as $req)
                            <div class="table-card">

                                <div class="table-col type">{{ $req['type'] }}</div>
                                <div class="table-col requested-for" title="{{ implode(', ', $req['students']) }}">
                                    {{ $req['students'][0] ?? 'N/A' }}
                                    @if (count($req['students']) > 1)
                                        and {{ count($req['students']) - 1 }} more
                                    @endif
                                </div>
                                <div class="table-col requested-at">{{ $req['requested_at'] }}</div>
                                <div class="table-col status">
                                    @php
                                        $status = strtolower($req['status']);
                                        $dotClass = match ($status) {
                                            'active' => 'status-dot status-approved',
                                            'archived' => 'status-dot status-declined',
                                            'pending' => 'status-dot status-pending',
                                            default => 'status-dot',
                                        };
                                        $labelClass = match ($status) {
                                            'active' => 'status-label status-approved',
                                            'archived' => 'status-label status-declined',
                                            'pending' => 'status-label status-pending',
                                            default => 'status-label',
                                        };
                                    @endphp
                                    <span class="{{ $labelClass }}">
                                        <span class="{{ $dotClass }}"></span>
                                        {{ ucfirst($req['status']) }}
                                    </span>
                                </div>
                                <div class="table-col actions">
                                    @if ($req['status'] === 'Pending')
                                        <a href="#" title="View" class="view-btn"><i class='bx bx-show'></i></a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
