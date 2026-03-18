@extends('layouts.counselor')
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
                                    <a href="{{ url()->current() }}?remarks=all" class="{{ request('remarks', 'all') == 'all' ? 'active' : '' }}">All</a>
                                    <a href="{{ url()->current() }}?remarks=Alarming" class="{{ request('remarks') == 'Alarming' ? 'active' : '' }}">Alarming</a>
                                    <a href="{{ url()->current() }}?remarks=Moderate" class="{{ request('remarks') == 'Moderate' ? 'active' : '' }}">Moderate</a>
                                    <a href="{{ url()->current() }}?remarks=Low" class="{{ request('remarks') == 'Low' ? 'active' : '' }}">Low</a>
                                </li>
                            </div>
                            <button class="add-btn" type="button" onclick="openCounselingNotesModal('add')"><i
                                    class="fi fi-br-plus"></i>Add counseling note</button>
                        </div>
                    </div>
                    <div class="table-bar">
                        <div class="table-search">
                            <form method="GET" action="">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search counseling..." id="counseling-search-input">
                                @if (request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                <button type="submit" style="display:none"></button>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- Table view -->
                <div class="table-list" id="counseling-list" style="margin-bottom:0;">
                    <div class="table-header">
                        <div class="table-col title">Note ID</div>
                        <div class="table-col category">Student</div>
                        <div class="table-col date">Filed Date</div>
                        <div class="table-col">Follow-up</div>
                        <div class="table-col status">Remarks</div>
                        <div class="table-col actions">Actions</div>
                    </div>
                    <div class="table">
                        @forelse($counselings as $counseling)
                            <div class="table-card">
                                @php
                                    $data = [
                                        'note_id' => $counseling->note_id,
                                        'student_name' => $counseling->student_name,
                                        'remarks' => $counseling->remarks,
                                        'observations' => $counseling->observations,
                                        'recommendations' => $counseling->recommendations,
                                        'follow_up_needed' => $counseling->follow_up_needed,
                                        'follow_up_date' => $counseling->follow_up_date ? $counseling->follow_up_date->format('Y-m-d H:i:s') : null,
                                    ];
                                @endphp

                                <div class="table-col title">{{ $counseling->note_id }}</div>
                                <div class="table-col category">{{ $counseling->student_name }}</div>
                                <div class="table-col">{{ $counseling->created_at }}</div>
                                <div class="table-col">{{ $counseling->follow_up_date ? $counseling->follow_up_date->format('Y-m-d H:i') : '-' }}</div>
                                <div class="table-col status">
                                    <span class="status-label status-{{ strtolower($counseling->remarks) }}">
                                        <span class="status-dot status-{{ strtolower($counseling->remarks) }}"></span>
                                        {{ ucfirst($counseling->remarks) }}
                                    </span>
                                </div>

                                <div class="table-col actions">
                                    <button type="button" class="view-btn" onclick='openCounselingNotesModal("view", @json($data))'><i class='bx bx-show'></i></button>
                                    <button type="button" class="edit-btn" onclick='openCounselingNotesModal("edit", @json($data))'><i class='bx bx-edit'></i></button>
                                    <form method="POST" action="{{ url('Counselor/counseling/' . $counseling->note_id) }}" style="display:inline-block;" class="archive-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="archive-btn archive-trigger" data-note-id="{{ $counseling->note_id }}"><i class='bx bx-archive'></i></button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="no-table-cell">No counseling notes found.</div>
                        @endforelse
                    </div>

                </div>

                @if ($counselings instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    @component('components.student-pagination', ['paginator' => $counselings]) @endcomponent
                @endif
            </div>
        </div>
    </section>
    @include('Counselor.Modal.counselingModal')

    <!-- Archive confirmation modal -->
    <div id="archiveConfirmModal" class="modal-counseling" style="display:none;">
        <div class="modal-content-counseling">
            <div class="modal-header-counseling">
                <h2>Confirm Archive</h2>
                <span class="close-btn" onclick="closeArchiveModal()">&times;</span>
            </div>
            <div style="padding:16px;">
                <p>Are you sure you want to archive this counseling note? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn cancel" onclick="closeArchiveModal()">Cancel</button>
                <button type="button" class="btn save" id="confirmArchiveBtn">Archive</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const searchInput = document.getElementById('counseling-search-input');
    const listWrapper = document.getElementById('counseling-list');
    let timeout = null;

    function fetchResults(query){
        const url = new URL(window.location.href);
        if (query && query.trim() !== '') url.searchParams.set('search', query.trim()); else url.searchParams.delete('search');
        // keep remarks if present
        url.searchParams.set('t', Date.now());

        fetch(url.toString())
            .then(r => r.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const newList = doc.querySelector('#counseling-list');
                if (newList && listWrapper) listWrapper.innerHTML = newList.innerHTML;
            })
            .catch(err => console.error(err));
    }

    if (searchInput){
        searchInput.addEventListener('input', function(e){
            clearTimeout(timeout);
            timeout = setTimeout(()=> fetchResults(e.target.value), 250);
        });
    }
});
</script>
@endpush

@push('scripts')
<script>
// Archive modal wiring
document.addEventListener('DOMContentLoaded', function(){
    const archiveModal = document.getElementById('archiveConfirmModal');
    const confirmBtn = document.getElementById('confirmArchiveBtn');
    let activeForm = null;

    function openArchiveModal(form){
        activeForm = form;
        if (archiveModal) archiveModal.style.display = 'flex';
    }

    function closeArchiveModal(){
        if (archiveModal) archiveModal.style.display = 'none';
        activeForm = null;
    }

    // delegate clicks
    document.body.addEventListener('click', function(e){
        const trg = e.target.closest && e.target.closest('.archive-trigger');
        if (trg) {
            const form = trg.closest('form');
            e.preventDefault();
            openArchiveModal(form);
        }
    });

    if (confirmBtn){
        confirmBtn.addEventListener('click', function(){
            if (!activeForm) return closeArchiveModal();
            activeForm.submit();
        });
    }

    // expose close function to inline handlers
    window.closeArchiveModal = closeArchiveModal;
});
</script>
@endpush
