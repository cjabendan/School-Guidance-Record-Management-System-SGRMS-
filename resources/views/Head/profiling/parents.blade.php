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
                        <div class="table-filter" id="parent-filters">
                            <div class="filters" id="table-filters">
                                <li>
                                    <a href="#" class="a-nav active" data-filter="all">All</a>
                                    <a href="#" class="a-nav" data-filter="active">Active</a>
                                    <a href="#" class="a-nav" data-filter="inactive">Inactive</a>
                                </li>
                            </div>
                            <button class="add-btn" id="addParentBtn"><i class="fi fi-br-plus"></i>Add Parent</button>
                        </div>
                    </div>
                    <div class="search-bar">
                        <div class="table-search">
                            <form method="GET" action="">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search by Parent.." id="parent-search-input">
                                <button type="submit" style="display:none"></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-list" id="parent-list" style="margin-bottom:0;">
                    <div class="table-header">
                        <div class="table-col title">Parent Name</div>
                        <div class="table-col">Contact Number</div>
                        <div class="table-col">Email</div>
                        <div class="table-col status">Account Status</div>
                        <div class="table-col actions">Actions</div>
                    </div>
                    <div class="table-table">
                        @forelse($parents as $parent)
                            @php
                                $status = strtolower($parent->status ?? '');
                                $dotClass =
                                    $status === 'active'
                                        ? 'status-dot status-approved'
                                        : ($status === 'inactive'
                                            ? 'status-dot status-pending'
                                            : 'status-dot status-declined');
                                $labelClass =
                                    $status === 'active'
                                        ? 'status-label status-approved'
                                        : ($status === 'inactive'
                                            ? 'status-label status-pending'
                                            : 'status-label status-declined');
                            @endphp
                            <div class="table-card">
                                <div class="table-col title">{{ $parent->first_name }} {{ $parent->last_name }}</div>
                                <div class="table-col">{{ $parent->contact_num ?? 'N/A'}}</div>
                                <div class="table-col">{{ $parent->email }}</div>
                                <div class="table-col status">
                                    @if (is_null($parent->status))
                                        <span class="status-label status-declined"><span
                                                class="status-dot status-declined"></span>Banned</span>
                                    @elseif ($status === 'active')
                                        <span class="status-label status-approved"><span
                                                class="status-dot status-approved"></span>Active</span>
                                    @else
                                        <span class="status-label status-pending"><span
                                                class="status-dot status-pending"></span>Inactive</span>
                                    @endif
                                </div>
                                <div class="table-col actions">
                                    <button class="view-btn" data-id="{{ $parent->p_id }}">  <i class='bx bx-show'></i></button>
                                    <button class="edit-btn" data-id="{{ $parent->p_id }}">  <i class='bx bx-edit'></i></button>
                                    <button class="archive-btn" data-id="{{ $parent->p_id }}">  <i class='bx bx-archive'></i></button>
                                </div>
                            </div>
                        @empty
                            <div class="no-table-cell">No parent accounts found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- Add Parent Modal -->
    @include('Head.Modal.parentModal')

    <script src="{{ asset('js/Modal/parentModal.js') }}"></script>

@endsection
