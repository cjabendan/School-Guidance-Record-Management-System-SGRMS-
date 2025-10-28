<div class="table">
    @foreach ($users as $user)
        @php
            $status = strtolower($user->status ?? '');
            $dotClass = $status === 'active' ? 'status-dot status-approved' : 'status-dot status-pending';
            $labelClass = $status === 'active' ? 'status-label status-approved' : 'status-label status-pending';
        @endphp
        <div class="table-card" data-id="{{ $user->id }}">
            <div class="table-col title">
                <img src="{{ asset('images/user/' . ($user->profile_image ?? 'default.jpg')) }}" class="profile-thumb">
                {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}
            </div>
            <div class="table-col">{{ $user->email ?? 'N/A' }}</div>
            <div class="table-col">{{ $user->contact_num ?? 'N/A' }}</div>
            <div class="table-col">{{ ucfirst($user->role ?? 'N/A') }}</div>
            <div class="table-col status">
                <span class="{{ $labelClass }}"><span class="{{ $dotClass }}"></span>{{ ucfirst($status) ?: 'N/A' }}</span>
            </div>
            <div class="table-col actions">
                <a href="javascript:void(0);" class="view-btn" data-id="{{ $user->id }}"><i class='bx bx-show'></i></a>
                <a href="javascript:void(0);" class="edit-btn" data-id="{{ $user->id }}"><i class='bx bx-edit'></i></a>
            </div>
        </div>
    @endforeach
</div>
@if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
    @component('components.parent-pagination', ['paginator' => $users]) @endcomponent
@endif
