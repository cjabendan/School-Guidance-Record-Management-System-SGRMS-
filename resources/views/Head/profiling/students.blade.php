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
                                <a href="{{ url('Head/students') }}" class="tab {{ request('status') == null ? 'active' : '' }}">All</a>
                                <a href="{{ url('Head/students') . '?status=seniorhigh' }}" class="tab {{ request('status') == 'seniorhigh' ? 'active' : '' }}">Senior Highschool</a>
                                <a href="{{ url('Head/students') . '?status=juniorhigh' }}" class="tab {{ request('status') == 'juniorhigh' ? 'active' : '' }}">Junior Highschool</a>
                                <a href="{{ url('Head/students') . '?status=elementary' }}" class="tab {{ request('status') == 'elementary' ? 'active' : '' }}">Elementary</a>
                                <a href="{{ url('Head/students') . '?status=kindergarten' }}" class="tab {{ request('status') == 'kindergarten' ? 'active' : '' }}">Kindergarten</a>
                            </li>
                        </div>
                        <button class="add-btn" onclick="openAddEditModal('add')"><i class="fi fi-br-plus"></i>Add Student</button>
                    </div>
                </div>
                <div class="search-bar">
                    <div class="table-search">
                        <form method="GET" action="">
                            <i class="fi fi-br-search"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search students..." id="student-search-input">
                            <button type="submit" style="display:none"></button>
                        </form>
                    </div>
                    <button class="toggle-btn" onclick="openImportModal()"><i class="fi fi-rr-document-circle-arrow-up"></i></i></button>
                    <button class="toggle-btn" onclick="openExportModal()"><i class="fi fi-rr-file-download"></i></button>
                </div>
            </div>
            <div class="table-list" id="student-list" style="margin-bottom:0;">
                <div class="table-header">
                    <div class="table-col title">Student ID</div>
                    <div class="table-col">Name</div>
                    <div class="table-col">Sex</div>
                    <div class="table-col">Educational Level</div>
                    <div class="table-col">Year Level</div>
                    <div class="table-col">Section</div>
                    <div class="table-col actions">Actions</div>
                </div>
                <div class="table">
                    @foreach ($students as $row)
                        @php
                            $caseCount = (int) $row->case_count;
                            $statusClass = $caseCount === 0 ? 'green' : ($caseCount <= 2 ? 'orange' : 'red');
                            $suffix = $row->suffix !== 'N/A' ? $row->suffix : '';
                            $mname = trim($row->mname);
                            $mname = $mname !== '' ? strtoupper(substr($mname, 0, 1)) . '.' : '';
                            $name = trim($row->lname . ', ' . $row->fname . ' ' . $mname . ' ' . $suffix);
                            $profileImage = $row->profile_image ?? 'default.png';
                        @endphp
                            <div class="table-card">
                                <div class="table-col title">
                                    <span class="status-circle {{ $statusClass }}" style="background: {{ $statusClass }} !important; vertical-align: middle; margin-right: 6px;"></span>
                                    {{ $row->s_id }}
                                </div>
                            <div class="table-col">{{ $name }}</div>
                            <div class="table-col">{{ $row->sex }}</div>
                            <div class="table-col">{{ $row->educ_level }}</div>
                            <div class="table-col">{{ $row->year_level }}</div>
                            <div class="table-col">{{ $row->section }}</div>
                            <div class="table-col actions">
                                <a href="javascript:void(0);" class="view-btn" title="View" onclick="openViewStudentModal('{{ $row->s_id }}')">
                                    <i class='bx bx-show'></i>
                                </a>
                                <a href="javascript:void(0);" class="edit-btn" title="Edit" onclick="openAddEditModal('edit', { s_id: '{{ $row->s_id }}' })">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button type="button" class="archive-btn" title="Archive" onclick="openArchiveModal('{{ $row->s_id }}')">
                                    <i class='bx bx-archive'></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <ul id="pagination-student" class="pagination"></ul>
            </div>
        </div>
        </div>
        
    </section>


    @include('Head.Modal.studentModal')

    <script src="{{ asset('js/head.js') }}"></script>
    <script src="{{ asset('js/Modal/studentModal.js') }}"></script>
@endsection
