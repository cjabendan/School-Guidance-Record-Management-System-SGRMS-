@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="head-title">
                <!-- Removed the upper right Add Case button -->

                <!-- Filter dropdowns and Add Case button in one row -->
                <form method="GET" action="{{ route('Head.cases.index') }}">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="filter_type" class="form-label">Case Type</label>
                            <select name="filter_type" id="filter_type" class="form-select" onchange="this.form.submit()">
                                <option value="">All Types</option>
                                @foreach(\App\Models\CaseType::all() as $type)
                                    <option value="{{ $type->type_id }}" {{ request('filter_type') == $type->type_id ? 'selected' : '' }}>
                                        {{ $type->type_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="filter_status" class="form-label">Status</label>
                            <select name="filter_status" id="filter_status" class="form-select" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                @foreach($statusOptions as $status)
                                    <option value="{{ $status }}" {{ request('filter_status') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="filter_severity" class="form-label">Severity</label>
                            <select name="filter_severity" id="filter_severity" class="form-select" onchange="this.form.submit()">
                                <option value="">All Severities</option>
                                @foreach($severityOptions as $severity)
                                    <option value="{{ $severity }}" {{ request('filter_severity') == $severity ? 'selected' : '' }}>
                                        {{ $severity }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-checkbox">
                            <input class="form-check-input" type="checkbox" name="archived" value="1" id="archived"
                                {{ request('archived') == '1' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label ms-2" for="archived">
                                Archived Only
                            </label>
                        </div>
                        <div class="filter-add-btn">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCaseModal">
                                Add Case
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Scrollable table -->
            <div class="table-responsive mt-4 scrollable-table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Case ID</th>
                            <th>Type</th>
                            <th>Presenting Problem</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Filed Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cases as $case)
                            <tr>
                                <td>{{ $case->case_id }}</td>
                                <td>{{ $case->caseType->type_name ?? 'N/A' }}</td>
                                <td>{{ $case->presenting_problem }}</td>
                                <td>{{ $case->severity }}</td>
                                <td>{{ $case->status }}</td>
                                <td>{{ $case->filed_date }}</td>
                                <td>
                                    <button type="button"
                                            class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewCaseModal{{ $case->case_id }}">
                                        View
                                    </button>
                                    <button type="button"
                                            class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCaseModal{{ $case->case_id }}">
                                        Edit
                                    </button>
                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            onclick="if(confirm('Archive this case?')) { document.getElementById('archive-form-{{ $case->case_id }}').submit(); }">
                                        Archive
                                    </button>
                                    <form id="archive-form-{{ $case->case_id }}"
                                          action="{{ route('Head.cases.archive', $case->case_id) }}"
                                          method="POST"
                                          style="display:none;">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No cases found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @include('Head.Modal.caseModal')
@endsection
