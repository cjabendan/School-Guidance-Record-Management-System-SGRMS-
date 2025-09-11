@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="table-container">
                <div class="table-management">
                    <div class="table-nav">
                        <div class="table-filter">
                            <div class="filters">
                                <li>
                                    <a href="#" class="active" data-type="all">All</a>
                                    <a href="#" data-type="link">Child Link</a>
                                    <a href="#" data-type="document">Documents</a>
                                </li>
                            </div>
                        </div>
                    </div>
                    <button class="toggle-btn" id="toggle-view-btn">
                        <i class="fi fi-br-bars-filter" id="toggle-icon"></i>
                        <span id="toggle-label"></span>
                    </button>
                </div>

                <div class="table-list">
                    <div class="table-header">
                        <div class="table-col type">Request Type</div>
                        <div class="table-col requested-by">Requested By</div>
                        <div class="table-col requested-at">Requested At</div>
                        <div class="table-col status">Status</div>
                        <div class="table-col actions">Actions</div>
                    </div>

                    <div class="table">
                        @foreach ($allRequests as $req)
                            <div class="table-card">

                                <div class="table-col type">{{ $req['type'] }}</div>
                                <div class="table-col requested-by">{{ $req['parent_name'] }}</div>
                                <div class="table-col requested-at">{{ $req['requested_at'] }}</div>
                                <div class="table-col status">
                                    @php
                                        $status = strtolower($req['status']);
                                        $dotClass = match ($status) {
                                            'active' => 'status-dot status-approved',
                                            'archived' => 'status-dot status-declined',
                                            'pending' => 'status-dot status-pending',
                                            default => 'status-dot',
                                        };
                                        $labelClass = match ($status) {
                                            'active' => 'status-label status-approved',
                                            'archived' => 'status-label status-declined',
                                            'pending' => 'status-label status-pending',
                                            default => 'status-label',
                                        };
                                    @endphp
                                    <span class="{{ $labelClass }}">
                                        <span class="{{ $dotClass }}"></span>
                                        {{ ucfirst($req['status']) }}
                                    </span>
                                </div>
                                <div class="table-col actions">
                                    @if ($req['status'] === 'Pending')
                                        <a href="{{ route('Head.requests.show', ['type' => strtolower($req['type']), 'id' => $req['id']]) }}"
                                            class="view-btn">Review</a>
                                        <button class="btn btn-danger btn-sm reject-btn"
                                            onclick="location.href='{{ route('Head.requests.show', ['type' => strtolower($req['type']), 'id' => $req['id']]) }}'"
                                            data-id="{{ $req['id'] }}" data-type="{{ $req['type'] }}">Reject</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('Head.Modal.caseModal')

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const filterLinks = document.querySelectorAll('.filters a');
                const rows = document.querySelectorAll('.table-row');

                filterLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const type = this.dataset.type;

                        filterLinks.forEach(l => l.classList.remove('active'));
                        this.classList.add('active');

                        rows.forEach(row => {
                            if (type === 'all') {
                                row.style.display = '';
                            } else {
                                row.style.display = row.dataset.type === type ? '' : 'none';
                            }
                        });
                    });
                });

                // Handle reject button click (open modal or prompt)
                document.querySelectorAll('.reject-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const type = this.dataset.type;
                        const reason = prompt("Enter rejection reason:");

                        if (reason) {
                            const url = type === 'link' ?
                                `/head/requests/reject/${id}` :
                                `/head/requests/rejectDocument/${id}`;

                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    reason
                                })
                            }).then(res => location.reload());
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection
