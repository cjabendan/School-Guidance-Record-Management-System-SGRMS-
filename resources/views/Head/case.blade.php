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
                                    <a href="#" class="a-nav active" data-filter="all">All</a>
                                    <a href="#" class="a-nav" data-filter="minor">Minor</a>
                                    <a href="#" class="a-nav" data-filter="major">Major</a>
                                    <a href="#" class="a-nav" data-filter="grave">Grave</a>
                                </li>
                            </div>
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addCaseModal"><i
                                    class="fi fi-br-plus"></i>Add case</button>
                        </div>
                    </div>
                    <div class="search-bar">
                        <div class="case-search">
                            <form method="GET" action="">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search cases..." id="case-search-input">
                                @if (request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                <button type="submit" style="display:none"></button>
                            </form>
                        </div>

                        <!-- Filter Button with Dropdown -->
                        <div class="filter-dropdown" style="position:relative; display:inline-block;">
                            <button class="toggle-btn" id="toggle-view-btn" type="button">
                                <i class="fi fi-br-bars-filter" id="toggle-icon"></i>
                            </button>
                            <div id="level-menu" class="level-menu">
                                <button class="level-option" data-level="" type="button">All Levels</button>
                                <button class="level-option" data-level="senior_high" type="button">Senior High School
                                </button>
                                <button class="level-option" data-level="high_school" type="button">High School</button>
                                <button class="level-option" data-level="elementary" type="button">Elementary</button>
                                <button class="level-option" data-level="kinder" type="button">Kinder</button>
                            </div>
                        </div>
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

                <!-- Table view -->
                <div class="table-list" id="cases-list" style="margin-bottom:0;">
                    <div class="table-header">
                        <div class="table-col title">Case ID</div>
                        <div class="table-col category">Type</div>
                        <div class="table-col">Severity</div>
                        <div class="table-col status">Status</div>
                        <div class="table-col date">Filed Date</div>
                        <div class="table-col actions">Actions</div>
                    </div>
                    <div class="table">
                        @forelse($cases as $case)
                            <div class="table-card">
                                <div class="table-col title">{{ $case->case_id }}</div>
                                <div class="table-col category">{{ $case->caseType->type_name ?? 'N/A' }}</div>
                                <div class="table-col">{{ $case->severity }}</div>
                                <div class="table-col status">
                                    <span class="status-label status-{{ strtolower($case->status) }}">
                                        <span class="status-dot status-{{ strtolower($case->status) }}"></span>
                                        {{ ucfirst($case->status) }}
                                    </span>
                                </div>
                                <div class="table-col date">{{ $case->filed_date }}</div>
                                <div class="table-col actions" style="display:flex; gap:8px;">
                                    <button type="button" class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#viewCaseModal{{ $case->case_id }}"><i class='bx bx-show'></i></button>
                                    <button type="button" class="edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#editCaseModal{{ $case->case_id }}"><i class='bx bx-edit'></i></button>
                                    <button type="button" class="archive-btn" onclick="openArchiveCaseModal({{ $case->case_id }}, '{{ addslashes($case->caseType->type_name ?? 'Case') }}')"><i class='bx bx-archive'></i></button>
                                </div>
                            </div>
                        @empty
                            <div class="no-table-cell">No cases found.</div>
                        @endforelse
                    </div>
                </div>
                {{-- Pagination links (will show when cases > per-page) --}}
                <div id="cases-pagination" style="padding:12px 18px;">
                    @if(method_exists($cases, 'links'))
                        @include('components.parent-pagination', ['paginator' => $cases])
                    @endif
                </div>
            </div>
        </div>
    </section>
    @include('Head.Modal.caseModal')
    <script>
        // Archive case modal logic: uses relative route and AJAX POST to avoid hardcoded host
        (function(){
            let currentArchiveCaseId = null;

            window.openArchiveCaseModal = function(caseId, title) {
                currentArchiveCaseId = caseId;
                const modal = document.getElementById('archiveCaseModal');
                const text = document.getElementById('archiveModalText');
                if (text) text.textContent = `Archive case ${caseId} — ${title}? This will mark the case as archived.`;
                if (modal) modal.style.display = 'block';
            }

            function closeArchiveModal() {
                const modal = document.getElementById('archiveCaseModal');
                if (modal) modal.style.display = 'none';
                currentArchiveCaseId = null;
            }

            document.addEventListener('DOMContentLoaded', function(){
                const confirmBtn = document.getElementById('archiveConfirmBtn');
                const cancelBtn = document.getElementById('archiveCancelBtn');
                const closeX = document.getElementById('archiveModalClose');

                if (cancelBtn) cancelBtn.addEventListener('click', closeArchiveModal);
                if (closeX) closeX.addEventListener('click', closeArchiveModal);

                if (confirmBtn) confirmBtn.addEventListener('click', function(){
                    if (!currentArchiveCaseId) return closeArchiveModal();
                    // Build relative URL using route helper output base path
                    const url = new URL(window.location.origin + '{{ url("/Head/cases") }}');
                    // send PUT to /Head/cases/{id}/archive or use named route if available
                    const archiveUrl = `${url.origin}${url.pathname}/${currentArchiveCaseId}/archive`;

                    fetch(archiveUrl, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({})
                    })
                    .then(async res => {
                        const text = await res.text();
                        let data = {};
                        try { data = JSON.parse(text); } catch(e) { /* ignore */ }
                        if (res.ok) {
                            createToast('success', (data.message || 'Case archived'));
                            // refresh list via AJAX same as search handler
                            $('#case-search-input').trigger('input');
                            closeArchiveModal();
                        } else {
                            createToast('error', (data.message || 'Failed to archive case'));
                        }
                    })
                    .catch(err => {
                        console.error('Archive error', err);
                        createToast('error', 'Failed to archive case');
                    });
                });

                // clicking outside should close (consistent with other modals)
                document.querySelectorAll('.modal.case-modal').forEach(function(modal){
                    modal.addEventListener('mousedown', function(e){
                        const content = modal.querySelector('.modal-content');
                        if (content && !content.contains(e.target)) modal.style.display = 'none';
                    });
                });
            });
        })();

        $(document).ready(function() {

            // Search input (AJAX replace table)
            $('#case-search-input').on('input', function() {
                let query = $(this).val();
                let level = $('.level-option.active').data('level') || '';
                $.ajax({
                    url: "{{ route('Head.cases.index') }}",
                    type: "GET",
                    data: {
                        search: query,
                        level: level
                    },
                    success: function(response) {
                        let html = $(response).find('#cases-list').html();
                        $('#cases-list').html(html);
                    }
                });
            });

            // Level menu toggle
            $('#toggle-view-btn').on('click', function(e) {
                e.stopPropagation();
                $('#level-menu').toggle();
            });
            $(document).on('click', function() { $('#level-menu').hide(); });

            // Level option change
            $('.level-option').on('click', function() {
                $('.level-option').removeClass('active');
                $(this).addClass('active');
                let level = $(this).data('level');
                let search = $('#case-search-input').val();
                $('#level-menu').hide();
                $('#toggle-label').text($(this).text());
                $.ajax({
                    url: "{{ route('Head.cases.index') }}",
                    type: "GET",
                    data: { search: search, level: level },
                    success: function(response) {
                        let html = $(response).find('#cases-list').html();
                        $('#cases-list').html(html);
                    }
                });
            });

            // Import trigger
            $('#import-btn').on('click', function() { $('#import-file-input').click(); });
            $('#import-file-input').on('change', function() { $(this).closest('form').submit(); });

            // Severity filters
            $('.filters .a-nav').on('click', function(e) {
                e.preventDefault();
                $('.filters .a-nav').removeClass('active');
                $(this).addClass('active');
                let severity = $(this).data('filter');
                let search = $('#case-search-input').val();
                $.ajax({
                    url: "{{ route('Head.cases.index') }}",
                    type: "GET",
                    data: { search: search, filter_severity: severity === 'all' ? '' : severity },
                    success: function(response) {
                        let html = $(response).find('#cases-list').html();
                        $('#cases-list').html(html);
                    }
                });
            });

            // Export dropdown init (single, robust instance)
            const exportBtn = document.getElementById('exportDropdownBtn');
            const exportMenu = document.getElementById('exportDropdownMenu');
            if (exportBtn && exportMenu) {
                // Ensure hidden by default (inline fallback if CSS missing)
                exportMenu.style.display = exportMenu.style.display || 'none';

                exportBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isShown = exportMenu.classList.contains('show') || exportMenu.style.display === 'block';
                    if (isShown) {
                        exportMenu.classList.remove('show');
                        exportMenu.style.display = 'none';
                    } else {
                        exportMenu.classList.add('show');
                        exportMenu.style.display = 'block';
                    }
                });

                document.addEventListener('mousedown', function(e) {
                    const target = e.target;
                    if (exportMenu.classList.contains('show') || exportMenu.style.display === 'block') {
                        if (!exportMenu.contains(target) && target !== exportBtn) {
                            exportMenu.classList.remove('show');
                            exportMenu.style.display = 'none';
                        }
                    }
                });

                exportMenu.querySelectorAll('.dropdown-item').forEach(function(item) {
                    item.addEventListener('mouseover', function() { this.style.background = '#f3f4f6'; });
                    item.addEventListener('mouseout', function() { this.style.background = 'none'; });
                    item.addEventListener('click', function() { exportMenu.classList.remove('show'); exportMenu.style.display = 'none'; });
                });
            }

            // Global export function
            window.downloadExport = function(format) {
                var exportMenuEl = document.getElementById('exportDropdownMenu');
                if (exportMenuEl) { exportMenuEl.classList.remove('show'); exportMenuEl.style.display = 'none'; }
                let url = new URL(window.location.origin + '/Head/cases/export');
                url.searchParams.set('format', format);
                // Preserve search and filters if present
                const searchVal = document.getElementById('case-search-input')?.value;
                if (searchVal) url.searchParams.set('search', searchVal);
                const activeFilter = document.querySelector('.filters .a-nav.active');
                if (activeFilter && activeFilter.dataset.filter) {
                    const severity = activeFilter.dataset.filter;
                    if (severity && severity !== 'all') url.searchParams.set('filter_severity', severity);
                }
                // Trigger browser download via an anchor click (avoids extra fetch handling)
                const a = document.createElement('a');
                a.href = url.toString();
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            };

            // AJAX pagination: intercept clicks on pagination links
            $(document).on('click', '#cases-pagination .pagination a', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');
                if (!href) return;

                // Build params to preserve filters/search/level
                var params = {};
                var searchVal = $('#case-search-input').val();
                if (searchVal) params.search = searchVal;
                var activeFilter = document.querySelector('.filters .a-nav.active');
                if (activeFilter && activeFilter.dataset.filter && activeFilter.dataset.filter !== 'all') {
                    params.filter_severity = activeFilter.dataset.filter;
                }
                var level = $('.level-option.active').data('level');
                if (level) params.level = level;

                // merge query params from href (page parameter)
                var url = new URL(href, window.location.origin);
                Object.keys(params).forEach(function(k){ url.searchParams.set(k, params[k]); });

                $.ajax({
                    url: url.toString(),
                    type: 'GET',
                    success: function(response) {
                        var html = $(response).find('#cases-list').html();
                        $('#cases-list').html(html);
                        var pag = $(response).find('#cases-pagination').html();
                        $('#cases-pagination').html(pag);
                        // scroll to top of list for better UX
                        $('html, body').animate({ scrollTop: $('#cases-list').offset().top - 80 }, 200);
                    },
                    error: function() {
                        createToast('error', 'Failed to load page');
                    }
                });
            });

        });
    </script>
@endsection
