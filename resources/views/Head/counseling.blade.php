@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')


    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="table-container">
                <div class="table-management">
                    <div class="table-nav">
                        <div class="table-filter">
                            <div class="filters">
                                <li>
                                    <a href="#" class="active">All</a>
                                    <a href="#">Alarming</a>
                                    <a href="#">Moderate</a>
                                    <a href="#">Low</a>
                                </li>
                            </div>
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addCaseModal"><i
                                    class="fi fi-br-plus"></i>Add Counseling Note</button>
                        </div>
                    </div>
                    <div class="table-bar">
                        <div class="table-search">
                            <form method="GET" action="">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search counseling..." id="counseling-search-input">
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
                <div class="table-list" id="counseling-list" style="margin-bottom:0;">
                    <div class="table-header">
                        <div class="table-col title">Note ID</div>
                        <div class="table-col category">Type</div>
                        <div class="table-col date">Filed Date</div>
                        <div class="table-col status">Remarks</div>
                        <div class="table-col actions">Actions</div>
                    </div>
                    <div class="table">
                        @forelse($counselings as $counseling)
                            <div class="table-card">
                                <div class="table-col title">{{ $counseling->note_id }}</div>
                                <div class="table-col category">{{ $counseling->caseType->type_name ?? 'N/A' }}</div>
                                <div class="table-col">{{ $counseling->created_at }}</div>
                                <div class="table-col status">
                                    <span class="status-label status-{{ strtolower($counseling->remarks) }}">
                                        <span class="status-dot status-{{ strtolower($counseling->remarks) }}"></span>
                                        {{ ucfirst($counseling->remarks) }}
                                    </span>
                                </div>
                        
                                <div class="table-col actions">
                                    <button type="button" class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#viewcounselingModal{{ $counseling->case_id }}"><i class='bx bx-show'></i></button>
                                    <button type="button" class="edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#editcounselingModal{{ $counseling->case_id }}"><i class='bx bx-edit'></i></button>
                                    <button type="button" class="archive-btn"><i class='bx bx-archive'></i></button>
                                </div>
                            </div>
                        @empty
                            <div class="no-table-cell">No counseling notes found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('Head.Modal.caseModal')
@endsection
