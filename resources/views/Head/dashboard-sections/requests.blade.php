<div class="requests-box">
    <div class="requests-header">
        <h2>Child Link Request</h2>
        <i class="fi fi-br-menu-dots"></i>
    </div>
    <div class="requests-table">
        @forelse($pendingRequests as $request)
            <div class="requests-item">
                <div class="request-content">
                    <img src="{{ asset('images/user/' . ($request['parent']->user->profile_image ?? 'default.jpg')) }}"
                        alt="User Photo" class="user-photo">
                    <div class="request-details">
                        <h2 class="request-sender">
                            @php
                                $sex = strtolower($request['parent']->user->sex ?? '');
                                $lastName = $request['parent']->user->last_name ?? 'Unknown';
                                $prefix = $sex === 'male' ? 'Mr.' : ($sex === 'female' ? 'Ms.' : '');
                            @endphp
                            {{ $prefix ? $prefix . ' ' : '' }}{{ $lastName }}
                        </h2>
                        <p class="request-preview">
                            Link to:
                            @foreach ($request['students'] as $s)
                                @if ($request['type'] === 'child-link')
                                    {{ $s->student->user->first_name ?? '' }} {{ $s->student->user->last_name ?? '' }}
                                @elseif ($request['type'] === 'document')
                                    {{ $s->student->user->first_name ?? '' }} {{ $s->student->user->last_name ?? '' }}
                                @endif

                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </p>
                    </div>
                </div>
                <div class="request-actions">
                    <a href="{{ route('Head.requests.show', ['type' => $request['type'], 'id' => $request['id']]) }}"
                        class="view-btn">Review</a>
                    <button class="btn btn-danger btn-sm reject-btn"
                        onclick="location.href='{{ route('Head.requests.show', ['type' => $request['type'], 'id' => $request['id']]) }}'"
                        data-id="{{ $request['id'] }}" data-type="{{ $request['type'] }}">
                        Reject
                    </button>
                </div>
            </div>
        @empty
            <div class="requests-item">
                <div class="request-content">
                    <div class="request-details">
                        <p>No pending requests.</p>
                    </div>
                </div>
            </div>
        @endforelse

    </div>
</div>
