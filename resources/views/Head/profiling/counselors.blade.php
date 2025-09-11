@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <!-- COUNSELORS MANAGEMENT -->
        <main class="wrapper">
            <h2>Manage Counselors</h2>
            <div class="profiles-container">
                <!-- Add new profile box -->
                <div class="profile-box add-box" onclick="openAddCounselorModal()">
                    <i class='bx bx-plus add-profile-icon'></i>
                    <h2>Add Counselor</h2>
                </div>

                @php
                    $activeCounselors = $counselors->filter(function($c) {
                        return isset($c->status) ? strtolower($c->status) === 'active' : true;
                    });
                @endphp
                @include('components.counselor-card', ['counselors' => $activeCounselors])
            </div>

            <!-- Past Counselors Table -->
            <div class="past-counselor-table-container" style="margin-top: 32px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin-bottom: 16px; color: #1e3a8a;">Past Counselor</h3>
                    <div class="table-search" style="margin-bottom: 8px;">
                        <form method="GET" action="">
                            <i class="fi fi-br-search"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search counselors..." id="counselor-search-input">
                            <button type="submit" style="display:none"></button>
                        </form>
                    </div>
                </div>
                <div class="table-header">
                    <div class="table-col title">Counselor ID</div>
                    <div class="table-col">Name</div>
                    <div class="table-col">Email</div>
                    <div class="table-col">Contact No.</div>
                    <div class="table-col">Actions</div>
                </div>
                <div class="table">
                    @php
                        $search = request('search');
                        $inactiveCounselorsQuery = \DB::table('counselors')
                            ->leftJoin('users', 'counselors.user_id', '=', 'users.id')
                            ->where('users.status', 'inactive')
                            ->select(
                                'counselors.c_id',
                                'users.first_name',
                                'users.middle_name',
                                'users.last_name',
                                'users.email',
                                'users.contact_num'
                            );
                        if ($search) {
                            $inactiveCounselorsQuery->where(function($query) use ($search) {
                                $query->where('counselors.c_id', 'like', "%$search%")
                                      ->orWhere('users.first_name', 'like', "%$search%")
                                      ->orWhere('users.middle_name', 'like', "%$search%")
                                      ->orWhere('users.last_name', 'like', "%$search%");
                            });
                        }
                        $inactiveCounselors = $inactiveCounselorsQuery->paginate(5);
                    @endphp
                    @forelse($inactiveCounselors as $row)
                        @php
                            $mname = trim($row->middle_name);
                            $mname = $mname !== '' ? strtoupper(substr($mname, 0, 1)) . '.' : '';
                            $name = trim($row->last_name . ', ' . $row->first_name . ' ' . $mname);
                        @endphp
                        <div class="table-card">
                            <div class="table-col title">{{ $row->c_id }}</div>
                            <div class="table-col">{{ $name }}</div>
                            <div class="table-col">{{ $row->email }}</div>
                            <div class="table-col">{{ $row->contact_num }}</div>
                            <div class="table-col actions">
                                <a href="javascript:void(0);" class="view-btn" title="View" onclick="openViewCounselModalReadonly('{{ $row->c_id }}')">
                                    <i class='bx bx-show'></i>
                                </a>
                                <a href="javascript:void(0);" class="edit-btn" title="Edit" onclick="editCounselorFromView('{{ $row->c_id }}', true)">
                                    <i class='bx bx-edit'></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="no-table-cell">No past counselors found.</div>
                    @endforelse
                </div>

                <!-- Counsel Pagination links -->
                <div style="margin-top: 16px; text-align: center;">
                    @component('components.counsel-pagination', ['paginator' => $inactiveCounselors])
                    @endcomponent
                </div>
                </div>
            </div>
        </main>
    </section>

    @include('Head.Modal.counselModal')

    <script src="{{ asset('js/head.js') }}"></script>
    <script src="{{ asset('js/Modal/counselModal.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('counselor-search-input');
            const tableList = document.querySelector('.past-counselor-table-container .table');
            let searchTimeout = null;
            if (searchInput && tableList) {
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
                            const newTable = doc.querySelector('.past-counselor-table-container .table');
                            if (newTable && tableList) {
                                tableList.innerHTML = newTable.innerHTML;
                            }
                        });
                    }, 300);
                });
            }
        });
    </script>

@endsection
