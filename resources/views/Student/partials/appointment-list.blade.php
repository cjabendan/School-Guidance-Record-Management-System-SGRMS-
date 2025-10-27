@if(session('error'))
    <div class="alert alert-danger" style="margin:8px 12px; padding:10px; border-radius:6px; border:1px solid #fca5a5; background:#fff0f0; color:#b91c1c; font-weight:600;">
        {{ session('error') }}
    </div>
@endif

@forelse($appointments as $appointment)
    <div class="table-card">
        <div class="table-col type">
            {{ $appointment->type ? $appointment->type->type_name : 'N/A' }}
        </div>
        <div class="table-col requester">
            @if ($appointment->requester)
                {{ $appointment->requester->first_name }} {{ $appointment->requester->last_name }}
            @else
                N/A
            @endif
        </div>
        <div class="table-col counselor">
            @if ($appointment->counselor)
                {{ $appointment->counselor->first_name }} {{ $appointment->counselor->last_name }}
            @else
                N/A
            @endif
        </div>
        <div class="table-col status">
            @php
                $status = strtolower($appointment->status);
                $dotClass = match ($status) {
                    'approved' => 'status-dot status-approved',
                    'declined' => 'status-dot status-declined',
                    'cancelled' => 'status-dot status-declined',
                    'pending' => 'status-dot status-pending',
                    default => 'status-dot',
                };
                $labelClass = match ($status) {
                    'approved' => 'status-label status-approved',
                    'declined' => 'status-label status-declined',
                    'cancelled' => 'status-label status-declined',
                    'pending' => 'status-label status-pending',
                    default => 'status-label',
                };
            @endphp
            <span class="{{ $labelClass }}">
                <span class="{{ $dotClass }}"></span>
                <span data-appointment-status="{{ $appointment->appointment_id }}">{{ ucfirst($appointment->status) }}</span>
            </span>
        </div>
        <div class="table-col actions">
            @php
                $latestReq = $appointment->reschedules()->first();
                $prevText = '';
                $reqText = '';
                if ($appointment->original_appointment_datetime) {
                    $prevText = $appointment->original_appointment_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A');
                }
                if ($latestReq && !empty($latestReq->proposed_datetime)) {
                    $reqText = \Carbon\Carbon::parse($latestReq->proposed_datetime)->setTimezone('Asia/Manila')->format('M d, Y h:i A');
                }
            @endphp
            <a href="#" title="View" class="view-btn" data-prev="{{ $prevText }}" data-req="{{ $reqText }}"
                onclick="openStudentReviewModal({{ $appointment->appointment_id }}, `
                    <div><strong>Type:</strong> {{ $appointment->type ? $appointment->type->type_name : 'N/A' }}<br>
                    <strong>Requester:</strong> {{ $appointment->requester ? $appointment->requester->first_name . ' ' . $appointment->requester->last_name : 'N/A' }}<br>
                    <strong>Student:</strong> @php $studentCount = $appointment->students->count(); @endphp
                    @if($studentCount === 1)
                        {{ $appointment->students->first()->user->first_name ?? '' }} {{ $appointment->students->first()->user->last_name ?? '' }}
                    @elseif($studentCount > 1)
                        {!! $appointment->students->map(fn($s) => e(($s->user->first_name ?? '') . ' ' . ($s->user->last_name ?? '')))->implode('<br>') !!}
                    @else
                        N/A
                    @endif<br>
                    <strong>Date & Time:</strong> {{ $appointment->appointment_datetime ? $appointment->appointment_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A') : 'N/A' }}<br>
                    <strong>Counselor:</strong> {{ $appointment->counselor ? $appointment->counselor->first_name . ' ' . $appointment->counselor->last_name : 'N/A' }}<br>
                    <strong>Status:</strong> {{ ucfirst($appointment->status) }}<br>
                    @if (strtolower($appointment->status) === 'declined' && !empty($appointment->decline_reason))
                        <div class='alert alert-danger mt-2' style='padding:4px 8px; font-size:0.95em;'><strong>Decline Reason:</strong> {{ $appointment->decline_reason }}</div>
                    @endif
                    </div>
                `, '{{ route('Student.appointments.cancel', $appointment->appointment_id) }}', '{{ strtolower($appointment->status) }}', '{{ route('Student.appointments.start', $appointment->appointment_id) }}', this)">
                <i class='bx bx-show'></i>
            </a>
        </div>
    </div>
@empty
    <div class="no-table-cell">
        No appointments to display.
    </div>
@endforelse
