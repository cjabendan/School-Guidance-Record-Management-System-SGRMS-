@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')


    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')


        <main class="wrapper">
            <div class="card">
                <section class="parent-list">
                    <div class="search-flex">
                        <h2>Parent List</h2>
                        <div class="search-bar">
                            <input type="text" id="parentSearch" name="parentSearch" class="search"
                                placeholder="Search by Parent Name">
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="styled-table" id="parentTable">
                            <thead>
                                <tr>
                                    <th>Parent Name</th>
                                    <th>Contact Number</th>
                                    <th>Email</th>
                                    <th>Account Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="parentTableBody">
                                @forelse($parents as $parent)
                                    <tr>
                                        <td>{{ $parent->guardian_name }}</td>
                                        <td>{{ $parent->contact_num }}</td>
                                        <td>{{ $parent->email }}</td>
                                        <td>
                                            @php
                                                $status = strtolower($parent->account_status ?? '');
                                            @endphp
                                            @if (is_null($parent->account_status))
                                                <span class='badge badge-gray'>No Account</span>
                                            @elseif ($status === 'active')
                                                <span class='badge badge-green'>Has Account</span>
                                            @else
                                                <span class='badge badge-orange'>Inactive Account</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class='btn btn-view' data-id='{{ $parent->p_id }}'>View</button>
                                            <button class='btn btn-edit' data-id='{{ $parent->p_id }}'>Edit</button>
                                            <button class='btn btn-delete' data-id='{{ $parent->p_id }}'>Archive</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No parent accounts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </section>
            </div>
        </main>
    </section>


@endsection
