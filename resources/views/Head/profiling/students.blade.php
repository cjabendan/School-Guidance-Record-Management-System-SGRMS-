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
                            <button type="button" class="toggle-btn" id="importBtn"><i class="fi fi-rr-document-circle-arrow-up"></i></button>
                        </form>
                    <div class="dropdown" style="display:inline-block;position:relative;">
                        <button class="toggle-btn" id="exportDropdownBtn" style="padding:8px 12px;border-radius:6px;background:#2563eb;color:#fff;border:none;box-shadow:0 1px 4px rgba(0,0,0,0.05);"><i class="fi fi-rr-file-download"></i></button>
                        <div id="exportDropdownMenu" class="dropdown-menu" style="display:none;position:absolute;right:0;top:110%;z-index:1000;background:#fff;border-radius:8px;border:1px solid #e5e7eb;padding:4px 0;min-width:180px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                            <a href="#" class="dropdown-item" onclick="downloadExport('pdf')" style="display:block;padding:10px 18px;color:#222;text-decoration:none;font-size:15px;transition:background 0.2s;border:none;background:none;cursor:pointer;">Export as PDF</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('xlsx')" style="display:block;padding:10px 18px;color:#222;text-decoration:none;font-size:15px;transition:background 0.2s;border:none;background:none;cursor:pointer;">Export as Excel (.xlsx)</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('xls')" style="display:block;padding:10px 18px;color:#222;text-decoration:none;font-size:15px;transition:background 0.2s;border:none;background:none;cursor:pointer;">Export as Excel (.xls)</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('csv')" style="display:block;padding:10px 18px;color:#222;text-decoration:none;font-size:15px;transition:background 0.2s;border:none;background:none;cursor:pointer;">Export as CSV</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-header">
                <div class="table-col title">Student ID</div>
                <div class="table-col">Name</div>
                <div class="table-col">Sex</div>
                <div class="table-col">Educational Level</div>
                <div class="table-col">Year Level</div>
                <div class="table-col">Enrollment Status</div>
                <div class="table-col actions">Actions</div>
            </div>
            <div id="student-list">
                @include('partials.student_table')
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
