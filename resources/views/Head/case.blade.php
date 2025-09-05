@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')


    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="case-container">
                <div class="case-management">
                    <div class="case-nav">
                        <div class="case-filter">
                            <div class="filters">
                                <li>
                                    <a href="#" class="active">All</a>
                                    <a href="#">Pending</a>
                                    <a href="#">Under Investigation</a>
                                    <a href="#">Resolved</a>
                                </li>
                            </div>
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addCaseModal"
                            ><i class="fi fi-br-plus"></i>Add Case</button>
                        </div>
                    </div>
                    <div class="search-bar">
                        <div class="case-search">
                            <form method="GET" action="">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search cases..." id="announcement-search-input">
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
                <div class="case-list" id="cases-list" style="margin-bottom:0;">
                    <div class="case-header">
                        <div class="case-col title">Case ID</div>
                        <div class="case-col category">Type</div>
                        <div class="case-col">Severity</div>
                        <div class="case-col status">Status</div>
                        <div class="case-col date">Filed Date</div>
                        <div class="case-col actions">Actions</div>
                    </div>
                    <div class="case-table">
                        @forelse($cases as $case)
                            <div class="case-card">
                                <div class="case-col title">{{ $case->case_id }}</div>
                                <div class="case-col category">{{ $case->caseType->type_name ?? 'N/A' }}</div>
                                <div class="case-col">{{ $case->severity }}</div>
                                <div class="case-col status">
                                    <span class="status-label status-{{ strtolower($case->status) }}">
                                        <span class="status-dot status-{{ strtolower($case->status) }}"></span>
                                        {{ ucfirst($case->status) }}
                                    </span>
                                </div>
                                <div class="case-col date">{{ $case->filed_date }}</div>
                                <div class="case-col actions" style="display:flex; gap:8px;">
                                    <button type="button" class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#viewCaseModal{{ $case->case_id }}">View</button>
                                    <button type="button" class="edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#editCaseModal{{ $case->case_id }}">Edit</button>
                                    <button type="button" class="archive-btn"
                                        onclick="if(confirm('Archive this case?')) { document.getElementById('archive-form-{{ $case->case_id }}').submit(); }">Archive</button>
                                    <form id="archive-form-{{ $case->case_id }}"
                                        action="{{ route('Head.cases.archive', $case->case_id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="no-case-cell">No cases found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('Head.Modal.caseModal')
@endsection
