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
                    <div class="dropdown" style="display:inline-block;position:relative;">
                        <button class="toggle-btn" id="exportDropdownBtn" style="padding:8px 12px;border-radius:6px;background:#2563eb;color:#fff;border:none;box-shadow:0 1px 4px rgba(0,0,0,0.05);"><i class="fi fi-rr-file-download"></i></button>
                        <div id="exportDropdownMenu" class="dropdown-menu" style="display:none;position:absolute;right:0;top:110%;z-index:1000;background:#fff;border-radius:8px;border:1px solid #e5e7eb;padding:4px 0;min-width:140px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                            <a href="#" class="dropdown-item" onclick="downloadExport('pdf')" style="display:block;padding:10px 18px;color:#222;text-decoration:none;font-size:15px;transition:background 0.2s;border:none;background:none;cursor:pointer;">Export as PDF</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('excel')" style="display:block;padding:10px 18px;color:#222;text-decoration:none;font-size:15px;transition:background 0.2s;border:none;background:none;cursor:pointer;">Export as Excel</a>
                        </div>
                    </div>
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
                            // Default: green circle for all students
                            $statusClass = 'green';
                            $statusColor = 'green';
                            // If student has a case record, use severity for color
                            if (!empty($row->case_severity)) {
                                if (strtolower($row->case_severity) === 'low') {
                                    $statusClass = 'green';
                                    $statusColor = 'green';
                                } elseif (strtolower($row->case_severity) === 'intermediate') {
                                    $statusClass = 'yellow';
                                    $statusColor = 'yellow';
                                } elseif (strtolower($row->case_severity) === 'severe') {
                                    $statusClass = 'red';
                                    $statusColor = 'red';
                                }
                            }
                            $suffix = $row->suffix !== 'N/A' ? $row->suffix : '';
                            $mname = trim($row->mname);
                            $mname = $mname !== '' ? strtoupper(substr($mname, 0, 1)) . '.' : '';
                            $name = trim($row->lname . ', ' . $row->fname . ' ' . $mname . ' ' . $suffix);
                            $profileImage = $row->profile_image ?? 'default.png';
                        @endphp
                            <div class="table-card">
                                <div class="table-col title">
                                    <span class="status-circle {{ $statusClass }}" style="background: {{ $statusColor }} !important; vertical-align: middle; margin-right: 6px;"></span>
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
    <script src="{{ asset('js/Modal/studentModal.js') }}"></script>
@endsection
