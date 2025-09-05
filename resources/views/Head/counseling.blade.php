@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')


    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="counseling-container">
                <div class="counseling-management">
                    <div class="counseling-nav">
                        <div class="counseling-filter">
                            <div class="filters">
                                <li>
                                    <a href="#" class="active">All</a>
                                    <a href="#">Pending</a>
                                    <a href="#">Under Investigation</a>
                                    <a href="#">Resolved</a>
                                </li>
                            </div>
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addCaseModal"
                            ><i class="fi fi-br-plus"></i>Add Counseling Note</button>
                        </div>
                    </div>
                    <div class="search-bar">
                        <div class="counseling-search">
                            <form method="GET" action="">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search counseling notes..." id="counseling-search-input">
                                @if (request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                <button type="submit" style="display:none"></button>
                            </form>
                        </div>

                        <button class="toggle-btn" id="toggle-view-btn">
                              <i class="fi fi-br-bars-filter" id="toggle-icon"></i>
                            <span id="toggle-label"></span>
                        </button>
                    </div>
                </div>

                <!-- Table view -->
                <div class="counseling-list" id="counseling-list" style="margin-bottom:0;">
                    <div class="counseling-header">
                        <div class="counseling-col title">Case ID</div>
                        <div class="counseling-col category">Type</div>
                        <div class="counseling-col">Severity</div>
                        <div class="counseling-col status">Status</div>
                        <div class="counseling-col date">Filed Date</div>
                        <div class="counseling-col">Notes</div>
                        <div class="counseling-col actions">Actions</div>
                    </div>
                    <div class="counseling-table">
                        @forelse($counselings as $counseling)
                            <div class="counseling-card">
                                <div class="counseling-col title">{{ $counseling->case_id }}</div>
                                <div class="counseling-col category">{{ $counseling->caseType->type_name ?? 'N/A' }}</div>
                                <div class="counseling-col">{{ $counseling->severity }}</div>
                                <div class="counseling-col status">
                                    <span class="status-label status-{{ strtolower($counseling->status) }}">
                                        <span class="status-dot status-{{ strtolower($counseling->status) }}"></span>
                                        {{ ucfirst($counseling->status) }}
                                    </span>
                                </div>
                                <div class="counseling-col date">{{ $counseling->filed_date }}</div>
                                <div class="counseling-col">
                                    @php
                                        $notes = $counseling->notes ?? [];
                                    @endphp
                                    @if(count($notes) > 0)
                                        <span style="font-weight:500; color:#1ea7ff;">{{ count($notes) }} note{{ count($notes) > 1 ? 's' : '' }}</span>
                                        <br>
                                        <span style="font-size:0.95em; color:#555;">
                                            {{ Str::limit($notes[0]->observations ?? '', 40) }}
                                        </span>
                                    @else
                                        <span style="color:#888;">No notes</span>
                                    @endif
                                </div>
                                <div class="counseling-col actions" style="display:flex; gap:8px;">
                                    <button type="button" class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#viewcounselingModal{{ $counseling->case_id }}">View</button>
                                    <button type="button" class="edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#editcounselingModal{{ $counseling->case_id }}">Edit</button>
                                    <button type="button" class="archive-btn">Archive</button>
                                </div>
                            </div>
                        @empty
                            <div class="no-case-cell">No counseling notes found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('Head.Modal.caseModal')
@endsection
