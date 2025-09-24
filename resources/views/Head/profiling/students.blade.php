@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')


    <!-- MAIN CONTENT -->
    <section id="content">
        @if (session('error'))
            <div class="alert alert-danger" style="margin: 16px 0; padding: 12px; background: #fee2e2; color: #b91c1c; border-radius: 6px; border: 1px solid #fca5a5;">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success" style="margin: 16px 0; padding: 12px; background: #dcfce7; color: #166534; border-radius: 6px; border: 1px solid #86efac;">{{ session('success') }}</div>
        @endif
        
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
                <div class="table-col">Status</div>
                <div class="table-col actions">Actions</div>
            </div>
            <div id="student-list">
                <div class="table">
                    @foreach ($students as $row)
                        @php
                            $statusClass = 'green';
                            $statusColor = 'green';
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
                            $program = ($row->educ_level === 'Senior High School' && !empty($row->program)) ? $row->program : 'N/A';
                            $status = $row->enrollment_status ?? 'N/A';
                            $statusColor = [
                                'Enrolled'     => '#16a34a', // green 
                                'Pending'      => '#f97316', // orange 
                                'Probation'    => '#eab308', // yellow 
                                'Suspended'    => '#dc2626', // red 
                                'Dropped'      => '#475569', // dark gray 
                                'Transferred'  => '#3b82f6', // blue 
                                'Graduated'    => '#8b5cf6', // purple 
                                'Inactive'     => '#94a3b8', // light gray 
                                'Expelled'     => '#b91c1c', // dark red 
                            ][$status] ?? '#64748b';
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
                            <div class="table-col">
                                <span style="display:inline-block;padding:4px 14px;border-radius:16px;font-weight:600;background:{{ $statusColor }}20;color:{{ $statusColor }};border:1px solid {{ $statusColor }};min-width:90px;text-align:center;">
                                    {{ $status }}
                                </span>
                            </div>
                            <div class="table-col actions">
                                <a href="javascript:void(0);" class="view-btn" title="View" onclick="openViewStudentModal('{{ $row->s_id }}')">
                                    <i class='bx bx-show'></i>
                                </a>
                                <a href="javascript:void(0);" class="edit-btn" title="Edit" onclick="openAddEditModal('edit', { s_id: '{{ $row->s_id }}' })">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button type="button" class="archive-btn" title="Archive" onclick="openArchiveStudentModal('{{ $row->s_id }}')">
                                    <i class='bx bx-archive'></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($students instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    @component('components.student-pagination', ['paginator' => $students]) @endcomponent
                @endif
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

            // --- Search Input Logic ---
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    const query = searchInput.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', query);
                    url.searchParams.delete('page'); // reset to page 1 when typing
                    fetchAndUpdate(url);
                }, 300);
            });

            // --- Pagination Click Logic ---
            document.addEventListener('click', function(e) {
                if (e.target.closest('.pagination-links')) {
                    const link = e.target.closest('.pagination-links');
                    if (link.tagName === 'A') {
                        e.preventDefault();
                        const url = new URL(link.href);

                        // keep current search/filter
                        if (searchInput.value) {
                            url.searchParams.set('search', searchInput.value);
                        }
                        const activeTab = document.querySelector('.tab.active');
                        if (activeTab && activeTab.href.includes('status=')) {
                            const status = new URL(activeTab.href).searchParams.get('status');
                            if (status) url.searchParams.set('status', status);
                        }

                        fetchAndUpdate(url);
                    }
                }
            });

            // --- Helper to fetch and replace table ---
            function fetchAndUpdate(url) {
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTable = doc.getElementById('student-list');
                        if (newTable && tableList) {
                            tableList.innerHTML = newTable.innerHTML;
                        }
                    });
            }
        });
    </script>
@endsection
