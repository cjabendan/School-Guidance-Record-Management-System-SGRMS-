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
       <div class="table-col student">
            @php
                $studentCount = $appointment->students->count();
                $firstStudent = $appointment->students->first();
            @endphp
            @if($studentCount === 1)
                {{ $firstStudent->user->first_name ?? '' }} {{ $firstStudent->user->last_name ?? '' }}
            @elseif($studentCount > 1)
                {{ $firstStudent->user->first_name ?? '' }} {{ $firstStudent->user->last_name ?? '' }}&nbsp;
                <span class="see-more-text" style="color: #888;">see more</span>
            @else
                N/A
            @endif
        </div>
        <div class="table-col datetime">
            {{ $appointment->appointment_datetime ? $appointment->appointment_datetime->format('M d, Y h:i A') : 'N/A' }}
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
                    'ongoing' => 'status-dot status-ongoing',
                    'completed' => 'status-dot status-completed',
                    default => 'status-dot',
                };
                $labelClass = match ($status) {
                    'approved' => 'status-label status-approved',
                    'declined' => 'status-label status-declined',
                    'cancelled' => 'status-label status-declined',
                    'pending' => 'status-label status-pending',
                    'ongoing' => 'status-label status-ongoing',
                    'completed' => 'status-label status-completed',
                    default => 'status-label',
                };
            @endphp
            <span class="{{ $labelClass }}" data-appointment-status="{{ $appointment->appointment_id }}">
                <span class="{{ $dotClass }}"></span>
                {{ ucfirst($appointment->status) }}
            </span>
            {{-- reschedule count removed from table; show details inside modal --}}
        </div>
        <div class="table-col actions">
            @php
                $latestReq = $appointment->reschedules()->first();
                $resStatus = $latestReq ? $latestReq->status : '';
                $prevText = $appointment->last_rescheduled_at ? $appointment->last_rescheduled_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') : '';
                $reqText = ($latestReq && !empty($latestReq->proposed_datetime)) ? \Carbon\Carbon::parse($latestReq->proposed_datetime)->setTimezone('Asia/Manila')->format('M d, Y h:i A') : '';
            @endphp
            <a href="#" title="View" class="view-btn" data-reschedule-status="{{ $resStatus }}" data-prev="{{ $prevText }}" data-req="{{ $reqText }}"
                onclick="openReviewModal({{ $appointment->appointment_id }},
                    `<div><strong>Type:</strong> {{ $appointment->type ? $appointment->type->type_name : 'N/A' }}<br>
                    <strong>Requester:</strong> {{ $appointment->requester ? $appointment->requester->first_name . ' ' . $appointment->requester->last_name : 'N/A' }}<br>
                    <strong>Student:</strong> @php $studentCount = $appointment->students->count(); @endphp
                    @if($studentCount === 1)
                        {{ $appointment->students->first()->user->first_name ?? '' }} {{ $appointment->students->first()->user->last_name ?? '' }}
                    @elseif($studentCount > 1)
                        {!! $appointment->students->map(fn($s) => e(($s->user->first_name ?? '') . ' ' . ($s->user->last_name ?? '')))->implode('<br>') !!}
                    @else
                        N/A
                    @endif<br>
                    <strong>Date & Time:</strong> {{ $appointment->appointment_datetime ? $appointment->appointment_datetime->format('M d, Y h:i A') : 'N/A' }}<br>
                    <strong>Counselor:</strong> {{ $appointment->counselor ? $appointment->counselor->first_name . ' ' . $appointment->counselor->last_name : 'N/A' }}<br>
                    <strong>Status:</strong> {{ ucfirst($appointment->status) }}<br>
                    {{-- Reschedule details shown only in the review modal. --}}
                    @if (strtolower($appointment->status) === 'declined' && !empty($appointment->decline_reason))
                        <div class='alert alert-danger mt-2' style='padding:4px 8px; font-size:0.95em;'><strong>Decline Reason:</strong> {{ $appointment->decline_reason }}</div>
                    @endif
                    </div>`,
                    '{{ route('Head.appointments.approve', $appointment->appointment_id) }}',
                    '{{ route('Head.appointments.decline', $appointment->appointment_id) }}',
                    '{{ route('Head.appointments.cancel', $appointment->appointment_id) }}',
                    '{{ strtolower($appointment->status) }}',
                    '{{ route('Head.appointments.start', $appointment->appointment_id) }}', this
                )">
                <i class='bx bx-show'></i>
            </a>
            @php $st = strtolower($appointment->status ?? ''); @endphp
            @if(in_array($st, ['cancelled', 'completed', 'declined']))
                {{-- Keep the same edit anchor visually, but make it non-interactive and add an explanatory title/aria attribute --}}
                <a href="#" title="This appointment cannot be edited" class="edit-btn edit-disabled" onclick="return false;" aria-disabled="true" tabindex="-1" style="cursor:not-allowed; color:#fdfdfd;">
                    <i class='bx bx-edit'></i>
                </a>
            @else
                <a href="#" title="Edit/Reschedule" class="edit-btn" onclick="openRescheduleModal({{ $appointment->appointment_id }}); return false;">
                    <i class='bx bx-edit'></i>
                </a>
            @endif
        </div>
    </div>
@empty
    <div class="no-table-cell">
        No appointments to display.
    </div>
@endforelse
