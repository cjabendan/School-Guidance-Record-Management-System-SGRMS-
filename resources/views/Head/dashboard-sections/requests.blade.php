<div class="requests-box">
    <div class="requests-header">
        <h2>Child Link Request</h2>
        <i class="fi fi-br-menu-dots"></i>
    </div>
    <div class="requests-table">
        @forelse($pendingRequests as $request)
            <div class="requests-item">
                <div class="request-content">
                    <img src="{{ asset('images/user/' . ($request->parent->user->profile_image ?? 'default.jpg')) }}" alt="User Photo"
                        class="user-photo">
                    <div class="request-details">
                        <h2 class="request-sender">
                            @php
                                $sex = strtolower($request->parent->user->sex ?? '');
                                $lastName = $request->parent->user->last_name ?? 'Unknown';
                                if ($sex === 'male') {
                                    $prefix = 'Mr.';
                                } elseif ($sex === 'female') {
                                    $prefix = 'Ms.';
                                } else {
                                    $prefix = '';
                                }
                            @endphp
                            {{ $prefix ? $prefix . ' ' : '' }}{{ $lastName }}
                        </h2>
                        <p class="request-preview">
                            Link to:
                            @foreach($request->students as $pls)
                                {{ $pls->student_id }}
                                @if(!$loop->last), @endif
                            @endforeach
                        </p>
                    </div>
                </div>
                <div class="request-actions">
                    <button class="review-btn">Review</button>
                    <button class="reject-btn">Reject</button>
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
