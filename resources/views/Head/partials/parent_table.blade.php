<div class="table">
    @foreach ($parents as $parent)
        @php
            $status = strtolower($parent->user->status ?? '');
            $dotClass = $status === 'active' ? 'status-dot status-approved' : 'status-dot status-pending';
            $labelClass = $status === 'active' ? 'status-label status-approved' : 'status-label status-pending';
        @endphp
        <div class="table-card" data-id="{{ $parent->p_id }}">
            <div class="table-col title">
                <img src="{{ asset('images/user/' . ($parent->user->profile_image ?? 'default.jpg')) }}" class="profile-thumb">
                {{ $parent->user->first_name ?? '' }} {{ $parent->user->last_name ?? '' }}
            </div>
            <div class="table-col">{{ $parent->user->contact_num ?? 'N/A' }}</div>
            <div class="table-col">{{ $parent->user->email ?? 'N/A' }}</div>
            <div class="table-col status">
                <span class="{{ $labelClass }}"><span class="{{ $dotClass }}"></span>{{ ucfirst($status) ?: 'N/A' }}</span>
            </div>
            <div class="table-col actions">
                <a href="javascript:void(0);" class="view-btn" data-id="{{ $parent->p_id }}"><i class='bx bx-show'></i></a>
                <a href="javascript:void(0);" class="edit-btn" data-id="{{ $parent->p_id }}"><i class='bx bx-edit'></i></a>
                <a href="javascript:void(0);" class="archive-btn" data-id="{{ $parent->p_id }}"><i class='bx bx-archive'></i></a>
            </div>
        </div>
    @endforeach
</div>
@if ($parents instanceof \Illuminate\Pagination\LengthAwarePaginator)
    @component('components.parent-pagination', ['paginator' => $parents]) @endcomponent
@endif
