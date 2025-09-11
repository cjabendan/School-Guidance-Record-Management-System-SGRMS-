<div class="request-detail-box">
    <h2>{{ ucfirst($type) }} Request</h2>
    <div>
        <strong>Requested By:</strong>
        {{ $request->parent->user->first_name ?? '' }} {{ $request->parent->user->last_name ?? '' }}
    </div>
    <div>
        <strong>Email:</strong>
        {{ $request->parent->user->email ?? ($request->email ?? 'N/A') }}
    </div>
    <div>
        <strong>Contact Number:</strong>
        {{ $request->parent->user->contact_num ?? ($request->number ?? 'N/A') }}
    </div>
    <div>
        <strong>Requested At:</strong>
        {{ $request->requested_at }}
    </div>
    <div>
        <strong>Status:</strong>
        {{ ucfirst($request->status) }}
    </div>
    <div>
        <strong>Students:</strong>
        <ul>
            @if($type === 'child link')
                @foreach($request->students as $s)
                    <li>
                        {{ $s->student->user->first_name ?? '' }} {{ $s->student->user->last_name ?? '' }} ({{ $s->student_id }})
                    </li>
                @endforeach
            @elseif($type === 'document')
                @foreach($request->drs as $d)
                    <li>
                        {{ $d->student->user->first_name ?? '' }} {{ $d->student->user->last_name ?? '' }} ({{ $d->s_id }})
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
    @if($request->status === 'pending')
        <form method="POST" action="{{ route('Head.requests.approve', ['type' => $type, 'id' => $request->request_id]) }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-success">Accept</button>
        </form>
        <form method="POST" action="{{ route('Head.requests.reject', ['type' => $type, 'id' => $request->request_id]) }}" style="display:inline;">
            @csrf
            <input type="text" name="reason" placeholder="Rejection reason" required>
            <button type="submit" class="btn btn-danger">Decline</button>
        </form>
    @else
        <div>
            <strong>Rejection Reason:</strong> {{ $request->rejection_reason ?? 'N/A' }}
        </div>
    @endif
    <a href="{{ route('Head.requests.index') }}" class="btn btn-secondary">Back to Requests</a>
</div>