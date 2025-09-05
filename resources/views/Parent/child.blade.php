@extends('layouts.parent')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="table-container">
                <div class="table-container">
                    <div class="table-list" id="student-list">
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
                            @foreach ($students as $student)
                                @php
                                    $user = $student->user;
                                    $name = $user
                                        ? "{$user->last_name}, {$user->first_name} {$user->middle_name}"
                                        : 'Unknown';
                                @endphp
                                <div class="table-card">
                                    <div class="table-col title">{{ $student->s_id }}</div>
                                    <div class="table-col">{{ $name }}</div>
                                    <div class="table-col">{{ $user->sex ?? '' }}</div>
                                    <div class="table-col">{{ $student->educ_level ?? '' }}</div>
                                    <div class="table-col">{{ $student->year_level ?? '' }}</div>
                                    <div class="table-col">{{ $student->section ?? '' }}</div>
                                    <div class="table-col actions">
                                        <a href="javascript:void(0);" class="view-btn" title="View"
                                            onclick="openViewStudentModal('{{ $student->s_id }}')">
                                            <i class='bx bx-show'></i>
                                        </a>
                                        <a href="javascript:void(0);" class="edit-btn" title="Edit"
                                            onclick="openAddEditModal('edit', { s_id: '{{ $student->s_id }}' })">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                        <button type="button" class="archive-btn" title="Archive"
                                            onclick="openArchiveModal('{{ $student->s_id }}')">
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
            
        </div>


    @endsection
