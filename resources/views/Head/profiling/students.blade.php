@php
    use Illuminate\Support\Facades\DB;
@endphp

@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

<section id="content">

        <script>
        window.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                createToast('success', "{{ session('success') }}");
            @endif
            @if(session('import_errors'))
                createToast('error', `{!! is_array(session('import_errors')) ? implode('<br>', session('import_errors')) : session('import_errors') !!}`);
            @endif
            @if(session('error'))
                createToast('error', "{{ session('error') }}");
            @endif
        });
        </script>

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
                        <form id="importForm" action="{{ route('Head.students.import') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
                            @csrf
                            <input type="file" id="importFileInput" name="students_file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" style="display:none" required>
                            <button type="button" class="toggle-btn import-btn" id="importBtn">
                                <i class="fi fi-rr-document-circle-arrow-up"></i>
                            </button>

                        </form>
                    <div class="dropdown">
                        <button class="export-btn toggle-btn" id="exportDropdownBtn">
                            <i class="fi fi-rr-file-download"></i>
                        </button>

                        <div id="exportDropdownMenu" class="dropdown-menu">
                            <a href="#" class="dropdown-item" onclick="downloadExport('pdf')">Export as PDF</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('xlsx')">Export as Excel (.xlsx)</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('xls')">Export as Excel (.xls)</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('csv')">Export as CSV</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-header">
                <div class="table-col">Student ID</div>
                <div class="table-col">Student Name</div>
                <div class="table-col">Educational Level</div>
                <div class="table-col">Year Level</div>
                <div class="table-col">Status</div>
                <div class="table-col">Actions</div>
            </div>
            <div id="student-list">
                @include('Head.partials.student_table')
            </div>

        </div>
    </div>
</section>

@include('Head.Modal.studentModal')

<script src="{{ asset('js/head.js') }}"></script>
<script src="{{ asset('js/Modal/studentModal.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('student-search-input');
    const tableList = document.getElementById('student-list');
    let searchTimeout = null;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            const query = searchInput.value;
            const url = new URL(window.location.href);
            url.searchParams.set('search', query);
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.getElementById('student-list');
                if (newTable && tableList) {
                    tableList.innerHTML = newTable.innerHTML;
                }
            });
        }, 300);
    });
});
</script>
@endsection
