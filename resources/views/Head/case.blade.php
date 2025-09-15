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
                                    <div class="type-filter active">All</div>
                                    <div class="type-filter">Minor</div>
                                    <div class="type-filter">Major</div>
                                    <div class="type-filter">Grave</div>
                                </li>
                            </div>
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addCaseModal"
                            ><i class="fi fi-br-plus"></i>Add Case</button>
                            <form action="{{ route('Head.cases.import') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
                                @csrf
                                <input type="file" name="import_file" accept=".csv" style="display:none;" id="import-file-input">
                                <button type="button" class="pro-add-import" id="import-btn">Import</button>
                            </form>
                            <a href="{{ route('Head.cases.export') }}" class="pro-add-export">
                                Export
                            </a>
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
                                <span id="toggle-label">Filter Level</span>
                                <i class="fi fi-br-angle-down" style="margin-left:6px;"></i>
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
                                        data-bs-target="#viewCaseModal{{ $case->case_id }}"></button>
                                    <button type="button" class="edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#editCaseModal{{ $case->case_id }}"></button>
                                    <button type="button" class="archive-btn"
                                        onclick="if(confirm('Archive this case?')) { document.getElementById('archive-form-{{ $case->case_id }}').submit(); }"></button>
                                    <form id="archive-form-{{ $case->case_id }}"
                                        action="{{ route('Head.cases.archive', $case->case_id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="no-announcement-cell">No cases found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('Head.Modal.caseModal')

    <script>
$(document).ready(function() {
    // Existing live search
    $('#case-search-input').on('input', function() {
        let query = $(this).val();
        let level = $('.level-option.active').data('level') || '';
        $.ajax({
            url: "{{ route('Head.cases.index') }}",
            type: "GET",
            data: { search: query, level: level },
            success: function(response) {
                let html = $(response).find('#cases-list').html();
                $('#cases-list').html(html);
            }
        });
    });

    // Toggle dropdown
    $('#toggle-view-btn').on('click', function(e) {
        e.stopPropagation();
        $('#level-menu').toggle();
    });

    // Hide dropdown when clicking outside
    $(document).on('click', function() {
        $('#level-menu').hide();
    });

    // Filter by level
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

    // Import button triggers file input
    $('#import-btn').on('click', function() {
        $('#import-file-input').click();
    });
    $('#import-file-input').on('change', function() {
        $(this).closest('form').submit();
    });

    $('.severity-filter').on('click', function(e) {
        e.preventDefault();
        $('.severity-filter').removeClass('active');
        $(this).addClass('active');
        let severity = $(this).text().trim() === 'All' ? '' : $(this).text().trim();
        let search = $('#case-search-input').val();
        $.ajax({
            url: "{{ route('Head.cases.index') }}",
            type: "GET",
            data: { search: search, filter_severity: severity },
            success: function(response) {
                let html = $(response).find('#cases-list').html();
                $('#cases-list').html(html);
            }
        });
    });
});
</script>
@endsection
