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
                <div class="table-header">
                    <div class="table-col title">Case ID</div>
                    <div class="table-col category">Type</div>
                    <div class="table-col">Severity</div>
                    <div class="table-col status">Status</div>
                    <div class="table-col date">Filed Date</div>
                    <div class="table-col actions">Actions</div>
                </div>

                <div id="cases-list">
                    <div class="table">
                        @forelse($cases as $case)
                            <div class="table-card" data-id="{{ $case->case_id }}">
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
                                    <button type="button" class="view-btn" data-sid="{{ $case->case_id }}" data-bs-toggle="modal"
                                        data-bs-target="#viewCaseModal{{ $case->case_id }}"><i class='bx bx-show'></i></button>
                                    <button type="button" class="edit-btn" data-sid="{{ $case->case_id }}" data-bs-toggle="modal"
                                        data-bs-target="#editCaseModal{{ $case->case_id }}"><i class='bx bx-edit'></i></button>
                                    <button type="button" class="archive-btn" onclick="openArchiveCaseModal({{ $case->case_id }}, '{{ addslashes($case->caseType->type_name ?? 'Case') }}')"><i class='bx bx-archive'></i></button>
                                </div>
                            </div>
                        @empty
                            <div class="no-table-cell">No cases found.</div>
                        @endforelse
                    </div>

                    {{-- Pagination links (will show when cases > per-page) --}}
                    <div id="cases-pagination" style="padding:12px 18px;">
                        @if(method_exists($cases, 'links'))
                            @include('components.parent-pagination', ['paginator' => $cases])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="case-modals">
        @include('Head.Modal.caseModal')
    </div>
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
                    const archiveUrl = '{{ url("/Head/cases") }}' + '/' + currentArchiveCaseId + '/archive';

                    // Disable confirm button and show loading state
                    confirmBtn.disabled = true; confirmBtn.textContent = 'Archiving...';
                    console.log('Archiving case', currentArchiveCaseId, 'URL:', archiveUrl);

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
                        console.log('Archive response text:', text);
                        let data = {};
                        try { data = JSON.parse(text); } catch(e) { console.warn('Failed to parse JSON from archive response'); }
                        if (res.ok) {
                            createToast('success', (data.message || 'Case archived'));
                            // refresh list via AJAX same as search handler
                            $('#case-search-input').trigger('input');
                            closeArchiveModal();
                        } else {
                            console.error('Archive failed', data);
                            createToast('error', (data.message || 'Failed to archive case'));
                        }
                    })
                    .catch(err => {
                        console.error('Archive error', err);
                        createToast('error', 'Failed to archive case');
                    })
                    .finally(() => {
                        confirmBtn.disabled = false; confirmBtn.textContent = 'Yes, Archive';
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

        // Helper: safely replace the #case-modals content by disposing existing Bootstrap modal instances
        function replaceCaseModals(html) {
            try {
                // Dispose any existing Bootstrap Modal instances inside the container
                document.querySelectorAll('#case-modals .modal').forEach(function(modalEl){
                    try {
                        var inst = bootstrap.Modal.getInstance(modalEl);
                        if (inst && typeof inst.dispose === 'function') inst.dispose();
                    } catch(e) {
                        // ignore
                    }
                });
            } catch (e) {
                // bootstrap may be undefined; ignore
            }
            // Remove leftover backdrops
            document.querySelectorAll('.modal-backdrop').forEach(function(b){ b.remove(); });
            // Replace html
            var container = document.getElementById('case-modals');
            if (container) container.innerHTML = html || '';
            // initialize behaviors for any newly inserted modals
            try { initCaseModals(container); } catch(e) { console.warn('initCaseModals error', e); }
        }

                // Initialize modal behaviors for newly inserted modals (outside-click close, student tags, view-more)
                function initCaseModals(root) {
                    root = root || document.getElementById('case-modals');
                    if (!root) return;
                    root.querySelectorAll('.modal.case-modal').forEach(function(modal){
                        if (modal.dataset.initialized) return;
                        modal.dataset.initialized = '1';
                        // sanitize modal internals: remove data-bs attributes to prevent Bootstrap data-api from acting on removed nodes
                        try {
                            modal.querySelectorAll('[data-bs-toggle], [data-bs-target], [data-bs-dismiss]').forEach(function(el){ el.removeAttribute('data-bs-toggle'); el.removeAttribute('data-bs-target'); el.removeAttribute('data-bs-dismiss'); });
                        } catch(e){}

                        // clicking outside closes modal (Bootstrap-compatible)
                        modal.addEventListener('mousedown', function(e){
                            const content = modal.querySelector('.modal-content');
                            if (content && !content.contains(e.target)) {
                                try { bootstrap.Modal.getInstance(modal)?.hide(); } catch(err) { modal.style.display = 'none'; }
                            }
                        });

                        // Ensure any 'X' / close buttons still work even after we removed data-bs-* attributes
                        try {
                            modal.querySelectorAll('.add-modal-close, [data-role="modal-close"]').forEach(function(closeBtn){
                                closeBtn.removeEventListener('click', closeBtn._caseCloseHandler);
                                closeBtn._caseCloseHandler = function(ev){ ev.preventDefault(); ev.stopPropagation(); try { bootstrap.Modal.getInstance(modal)?.hide(); } catch(e) { modal.style.display = 'none'; } };
                                closeBtn.addEventListener('click', closeBtn._caseCloseHandler);
                            });
                        } catch(e) {}

                        // Render compact view tags for view modals
                        if (modal.id && modal.id.indexOf('viewCaseModal') === 0) {
                            var caseId = modal.id.replace('viewCaseModal','');
                            var $container = modal.querySelector('#view-student-tag-input'+caseId) || modal.querySelector('[id^="view-student-tag-input"]');
                            if ($container) {
                                // collect existing tags if present
                                var existing = Array.from($container.querySelectorAll('.student-tag')).map(function(s){ return { id: s.dataset && s.dataset.id ? s.dataset.id : '', text: s.textContent.trim() }; });
                                if (existing.length === 0) {
                                    // try to build from hidden input if present
                                    var hidden = document.getElementById('view_involved_students'+caseId);
                                    if (hidden && hidden.value) {
                                        existing = hidden.value.split(',').map(function(id){ id = id.trim(); return id ? { id: id, text: id } : null; }).filter(Boolean);
                                    }
                                }
                                // render compact
                                $container.innerHTML = '';
                                // ensure nothing inside modal is focused (prevents aria-hidden focus issue)
                                try { if (modal.contains(document.activeElement)) document.activeElement.blur(); } catch(_) {}
                                if (!existing || existing.length === 0) {
                                    var span = document.createElement('span'); span.className = 'student-tag'; span.textContent = 'No students'; $container.appendChild(span);
                                } else {
                                    var first = document.createElement('span'); first.className = 'student-tag'; first.textContent = existing[0].text; $container.appendChild(first);
                                    if (existing.length > 1) {
                                        var more = document.createElement('span'); more.className = 'student-more'; more.textContent = '+' + (existing.length - 1) + ' view more';
                                        $container.appendChild(more);
                                        more.addEventListener('click', function(ev){
                                            ev.stopPropagation();
                                            $container.querySelectorAll('.student-more-list').forEach(function(n){ n.remove(); });
                                            var list = document.createElement('div'); list.className = 'student-more-list';
                                            existing.slice(1).forEach(function(s){ var item = document.createElement('div'); item.className='student-more-item'; item.textContent = s.text; list.appendChild(item); });
                                            $container.appendChild(list);
                                            // close on outside
                                            var listener = function(evt){ if (!$container.contains(evt.target)) { list.remove(); document.removeEventListener('click', listener); } };
                                            document.addEventListener('click', listener);
                                        });
                                    }
                                }
                            }
                        }

                        // Render edit tags for edit modals
                        if (modal.id && modal.id.indexOf('editCaseModal') === 0) {
                            var caseId = modal.id.replace('editCaseModal','');
                            var tagInput = modal.querySelector('#edit-student-tag-input'+caseId) || modal.querySelector('[id^="edit-student-tag-input"]');
                            var inputEl = modal.querySelector('#edit_student_search'+caseId) || modal.querySelector('input[id^="edit_student_search"]');
                            var hidden = modal.querySelector('#edit_involved_students'+caseId) || modal.querySelector('input[id^="edit_involved_students"]');
                            if (tagInput && inputEl) {
                                // If server-rendered tags (with names) already exist, keep them and sync hidden input.
                                var existingTags = tagInput.querySelectorAll('.student-tag');
                                if (!existingTags || existingTags.length === 0) {
                                    // build tags from hidden ids (fallback)
                                    var ids = [];
                                    if (hidden && hidden.value) ids = hidden.value.split(',').map(function(s){ return s.trim(); }).filter(Boolean);
                                    ids.forEach(function(sid){
                                        var span = document.createElement('span'); span.className = 'student-tag'; span.dataset.id = sid; span.textContent = sid;
                                        var rem = document.createElement('span'); rem.className = 'remove-tag'; rem.title = 'Remove'; rem.innerHTML = '&times;'; span.appendChild(rem);
                                        tagInput.insertBefore(span, inputEl);
                                    });
                                    if (hidden) hidden.value = ids.join(',');
                                } else {
                                    // preserve server-rendered tags (which include names) and ensure hidden input matches their data-ids
                                    var ids = Array.from(existingTags).map(function(s){ return (s.dataset && s.dataset.id) ? s.dataset.id : s.textContent.trim(); });
                                    if (hidden) hidden.value = ids.join(',');
                                }
                            }
                        }
                    });
                }

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
                            // Also replace the modals block so View/Edit modals for the current page exist
                            let modalsHtml = $(response).find('#case-modals').html();
                            if (modalsHtml !== undefined) {
                                replaceCaseModals(modalsHtml);
                            }
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
                            let modalsHtml = $(response).find('#case-modals').html();
                            if (modalsHtml !== undefined) {
                                replaceCaseModals(modalsHtml);
                            }
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
                            let modalsHtml = $(response).find('#case-modals').html();
                            if (modalsHtml !== undefined) {
                                replaceCaseModals(modalsHtml);
                            }
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
                            // also update per-row modals
                            let modalsHtml = $(response).find('#case-modals').html();
                            if (modalsHtml !== undefined) {
                                replaceCaseModals(modalsHtml);
                            }
                        // scroll to top of list for better UX
                        $('html, body').animate({ scrollTop: $('#cases-list').offset().top - 80 }, 200);
                    },
                    error: function() {
                        createToast('error', 'Failed to load page');
                    }
                });
            });

            // Delegated handler: show modals programmatically to avoid Bootstrap data-api errors
            // This prevents Bootstrap from trying to use removed/stale instances/backdrops
                    // Delegated click handlers: prefer app-level openers so modals work after pagination/ajax
                    // Use explicit functions so other parts of the app can open modals reliably.
                    window.openViewCaseModal = function(caseId) {
                        if (!caseId) return;
                        var modalId = '#viewCaseModal' + caseId;
                        var el = document.querySelector(modalId);
                        if (el) {
                            try {
                                // ensure no lingering backdrop
                                document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                                // programmatically show
                                if (window.bootstrap && window.bootstrap.Modal) {
                                    var inst = new bootstrap.Modal(el);
                                    inst.show();
                                } else {
                                    // fallback: just set display block
                                    el.style.display = 'block';
                                }
                            } catch (err) { console.warn('openViewCaseModal show error', err); }
                            return;
                        }

                        // modal not present (likely after ajax pagination) -> fetch single-modal HTML and insert
                        var url = window.location.origin + '/Head/cases/' + caseId + '/modal';
                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(r => r.text())
                            .then(html => {
                                // replace entire modals block so add/edit/view modals are consistent
                                replaceCaseModals(html);
                                // now show target
                                var el2 = document.querySelector(modalId);
                                if (el2 && window.bootstrap && window.bootstrap.Modal) {
                                    var inst2 = new bootstrap.Modal(el2);
                                    inst2.show();
                                }
                            }).catch(err => console.warn('fetch modal failed', err));
                    };

                    window.openAddEditModal = function(action, payload) {
                        // action: 'add' | 'edit'
                        // payload: if edit, may be caseId or object { caseId }
                        if (action === 'add') {
                            var el = document.getElementById('addCaseModal');
                            if (!el) return;
                            if (window.bootstrap && window.bootstrap.Modal) {
                                new bootstrap.Modal(el).show();
                            } else { el.style.display = 'block'; }
                            return;
                        }

                        var caseId = (typeof payload === 'object' && payload.s_id) ? payload.s_id : payload;
                        if (!caseId) return;
                        var modalId = '#editCaseModal' + caseId;
                        var el = document.querySelector(modalId);
                        if (el) {
                            if (window.bootstrap && window.bootstrap.Modal) {
                                new bootstrap.Modal(el).show();
                            } else { el.style.display = 'block'; }
                            return;
                        }

                        var url = window.location.origin + '/Head/cases/' + caseId + '/modal';
                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(r => r.text())
                            .then(html => {
                                replaceCaseModals(html);
                                var el2 = document.querySelector(modalId);
                                if (el2 && window.bootstrap && window.bootstrap.Modal) new bootstrap.Modal(el2).show();
                            }).catch(err => console.warn('fetch modal failed', err));
                    };

                    // Delegate clicks from table cards to the app-level openers so pagination works
                    $(document).on('click', '.view-btn', function(e){
                        e.preventDefault(); e.stopImmediatePropagation();
                        var caseId = $(this).data('sid') || $(this).attr('data-sid') || $(this).closest('[data-id]').data('id');
                        if (!caseId) {
                            // try to read from onclick attribute (legacy)
                            var on = $(this).attr('onclick') || '';
                            var m = on.match(/openViewStudentModal\(['"]([^'"\)]+)['"]\)/);
                            if (m) caseId = m[1];
                        }
                        if (caseId) window.openViewCaseModal(caseId);
                    });

                    $(document).on('click', '.edit-btn', function(e){
                        e.preventDefault(); e.stopImmediatePropagation();
                        var caseId = $(this).data('sid') || $(this).attr('data-sid') || $(this).closest('[data-id]').data('id');
                        if (!caseId) {
                            var on = $(this).attr('onclick') || '';
                            var m = on.match(/openAddEditModal\(['"]edit['"],\s*\{\s*s_id:\s*'([^'"\}]+)'\s*\}\)/);
                            if (m) caseId = m[1];
                        }
                        if (caseId) window.openAddEditModal('edit', caseId);
                    });

        });
    </script>
@endsection
