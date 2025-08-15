@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')


    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')

        <main class="wrapper">
            <div class="card">
                <section class="student-list">
                    <div class="search-flex">
                        <h2>Student List</h2>
                        <div class="search-bar">
                            <input type="text" id="search" name="search" class="search"
                                placeholder="Search by ID or Name">
                            <button class="btn btn-add" onclick="openChooseAddModal()">Add Student</button>
                        </div>
                    </div>

                    <!-- Education Level Tabs -->
                    <div class="tab-container">
                        <a href="{{ url('Head/students') }}" class="tab {{ request('status') == null ? 'active' : '' }}">All
                            Students</a>
                        <a href="{{ url('Head/students') . '?status=college' }}"
                            class="tab {{ request('status') == 'college' ? 'active' : '' }}">College</a>
                        <a href="{{ url('Head/students') . '?status=highschool' }}"
                            class="tab {{ request('status') == 'highschool' ? 'active' : '' }}">Highschool</a>
                        <a href="{{ url('Head/students') . '?status=elementary' }}"
                            class="tab {{ request('status') == 'elementary' ? 'active' : '' }}">Elementary</a>
                        <a href="{{ url('Head/students') . '?status=inactive' }}"
                            class="tab {{ request('status') == 'inactive' ? 'active' : '' }}">Inactive</a>
                        <a href="{{ url('Head/students') . '?status=archived' }}"
                            class="tab {{ request('status') == 'archived' ? 'active' : '' }}">Archived</a>
                    </div>

                    <div class="table-container">
                        <table id="studentTable">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Educational Level</th>
                                    <th>Section/Program</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="studentTableBody">
                                @foreach ($students as $row)
                                    @php
                                        $caseCount = (int) $row->case_count;
                                        $statusClass =
                                            $caseCount === 0 ? 'green' : ($caseCount <= 2 ? 'orange' : 'red');

                                        $birthdate = new DateTime($row->bod);
                                        $today = new DateTime();
                                        $age = $today->diff($birthdate)->y;

                                        $suffix = $row->suffix !== 'N/A' ? $row->suffix : '';
                                        $mname = trim($row->mname);
                                        $mname = $mname !== '' ? strtoupper(substr($mname, 0, 1)) . '.' : '';
                                        $name = trim($row->lname . ', ' . $row->fname . ' ' . $mname . ' ' . $suffix);
                                    @endphp

                                    <tr data-status="{{ strtolower($row->status) }}">
                                        <td><span class="status-circle {{ $statusClass }}"
                                                style="background: {{ $statusClass }} !important;"></span></td>
                                        <td>{{ $row->id_num }}</td>
                                        <td>{{ $name }}</td>
                                        <td>{{ $age }}</td>
                                        <td>{{ $row->educ_level }}</td>
                                        <td>{{ $row->section ?? $row->program }}</td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-view" title="View"
                                                onclick="openViewStudentModal('{{ $row->id_num }}')">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <form action="{{ url('Head/students/' . $row->id_num . '/archive') }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-delete" title="Archive"
                                                    onclick="return confirm('Are you sure you want to archive this student?');">
                                                    <i class='bx bx-archive'></i>
                                                </button>
                                            </form>
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

    <!-- Choose Action Modal -->
    <div id="chooseAddModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h3>Add Students</h3>
            <button onclick="openImportModal()">Import Excel</button>
            <button onclick="openAddStudentModal()">Add Single Student</button>
            <button onclick="closeChooseAddModal()">Cancel</button>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h3>Import Students from Excel</h3>
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="students_file" accept=".xlsx,.xls,.csv" required>
                <button type="submit">Import</button>
                <button type="button" onclick="closeImportModal()">Cancel</button>
            </form>
        </div>
    </div>

    @include('Head.Modal.studentModal')

    <script src="{{ asset('js/head.js') }}"></script>
    <script src="{{ asset('js/Modal/studentModal.js') }}"></script>
@endsection
