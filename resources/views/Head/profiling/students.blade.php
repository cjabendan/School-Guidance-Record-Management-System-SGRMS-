@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')


    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')

        <main class="wrapper">
            <div class="card">
                <section class="student-list">
                    <button class="btn btn-add" style="background:#22c55e;color:#fff;margin-right:8px;" onclick="openAddEditModal('add')">Add Student</button>
                    <button class="btn btn-add" style="background:#22c55e;color:#fff;margin-right:8px;" onclick="openImportModal()">Import</button>
                    <button class="btn btn-add" style="background:#22c55e;color:#fff;" onclick="openExportModal()">Export</button>


                    <!-- Education Level Tabs -->
                    <div class="tab-container">
                        <a href="{{ url('Head/students') }}" class="tab {{ request('status') == null ? 'active' : '' }}">All</a>
                        <a href="{{ url('Head/students') . '?status=seniorhigh' }}" class="tab {{ request('status') == 'seniorhigh' ? 'active' : '' }}">Senior Highschool</a>
                        <a href="{{ url('Head/students') . '?status=juniorhigh' }}" class="tab {{ request('status') == 'juniorhigh' ? 'active' : '' }}">Junior Highschool</a>
                        <a href="{{ url('Head/students') . '?status=elementary' }}" class="tab {{ request('status') == 'elementary' ? 'active' : '' }}">Elementary</a>
                        <a href="{{ url('Head/students') . '?status=kindergarten' }}" class="tab {{ request('status') == 'kindergarten' ? 'active' : '' }}">Kindergarten</a>
                        <a href="{{ url('Head/students') . '?status=inactive' }}" class="tab {{ request('status') == 'inactive' ? 'active' : '' }}">Inactive</a>
                    </div>

                    <div class="table-container">
                        <table id="studentTable">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Sex</th>
                                    <th>Educational Level</th>
                                    <th>Year Level</th>
                                    <th>Section</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="studentTableBody">
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
                                    <tr data-status="{{ strtolower($row->status) }}">
                                        <td><span class="status-circle {{ $statusClass }}" style="background: {{ $statusClass }} !important;"></span></td>
                                        <td>{{ $row->s_id }}</td>
                                        <td>{{ $name }}</td>
                                        <td>{{ $row->sex }}</td>
                                        <td>{{ $row->educ_level }}</td>
                                        <td>{{ $row->year_level }}</td>
                                        <td>{{ $row->section }}</td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-view" title="View" onclick="openViewStudentModal('{{ $row->s_id }}')">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <a href="javascript:void(0);" class="btn btn-edit" title="Edit" onclick="openAddEditModal('edit', { s_id: '{{ $row->s_id }}' })">
                                                <i class='bx bx-edit'></i>
                                            </a>
                                            <button type="button" class="btn btn-delete" title="Archive" onclick="openArchiveModal('{{ $row->s_id }}')">
                                                <i class='bx bx-archive'></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <ul id="pagination-student" class="pagination"></ul>
                </section>
            </div>
        </main>
    </section>


    @include('Head.Modal.studentModal')

    <script src="{{ asset('js/head.js') }}"></script>
    <script src="{{ asset('js/Modal/studentModal.js') }}"></script>
@endsection
