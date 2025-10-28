@if(session('error'))
    <div class="alert alert-danger" style="margin:8px 12px; padding:10px; border-radius:6px; border:1px solid #fca5a5; background:#fff0f0; color:#b91c1c; font-weight:600;">
        {{ session('error') }}
    </div>
@endif

@forelse($appointments as $appointment)
    <div class="table-card" 
         data-previous-schedule="{{ isset($appointment->previous_schedule) && $appointment->previous_schedule ? $appointment->previous_schedule->format('M d, Y h:i A') : '' }}"
         data-current-schedule="{{ $appointment->appointment_datetime ? $appointment->appointment_datetime->format('M d, Y h:i A') : '' }}">
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
        {{-- Student and datetime columns intentionally hidden for Head view per requirement --}}
        <div class="table-col counselor">
            @php
                $counselorName = $appointment->counselor 
                    ? $appointment->counselor->first_name . ' ' . $appointment->counselor->last_name 
                    : 'N/A';
                
                // Get previous schedule if this is a rescheduled appointment
                $previousSchedule = '';
                if ($appointment->previous_datetime) {
                    $previousSchedule = $appointment->previous_datetime->format('M d, Y h:i A');
                }
            @endphp
            <span data-previous-schedule="{{ $previousSchedule }}">{{ $counselorName }}</span>
        </div>
        <div class="table-col status">
            @php
                $status = strtolower($appointment->status);
                $dotClass = match ($status) {
                    'approved' => 'status-dot status-approved',
                    'declined' => 'status-dot status-declined',
                    'cancelled' => 'status-dot status-declined',
                    'missed' => 'status-dot status-declined',
                    'pending' => 'status-dot status-pending',
                    'ongoing' => 'status-dot status-ongoing',
                    'completed' => 'status-dot status-completed',
                    default => 'status-dot',
                };
                $labelClass = match ($status) {
                    'approved' => 'status-label status-approved',
                    'declined' => 'status-label status-declined',
                    'cancelled' => 'status-label status-declined',
                    'missed' => 'status-label status-declined',
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
                $status = strtolower($appointment->status);
                
                // Initialize times
                $prevText = '';
                $reqText = '';
                
                // Only set previous and preferred times if status is rescheduled
                if ($status === 'rescheduled') {
                    // Original appointment time becomes the "previous" time
                    $prevText = $appointment->appointment_datetime 
                        ? $appointment->appointment_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A') 
                        : '';
                    
                    // Get preferred time from reschedule_proposed_datetime
                    $reqText = $appointment->reschedule_proposed_datetime 
                        ? $appointment->reschedule_proposed_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A') 
                        : '';

                    // Log times for debugging
                    \Illuminate\Support\Facades\Log::info('Displaying rescheduled appointment times', [
                        'appointment_id' => $appointment->appointment_id,
                        'status' => $status,
                        'previous' => $prevText,
                        'preferred' => $reqText,
                        'has_proposed_datetime' => isset($appointment->reschedule_proposed_datetime)
                    ]);
                }
            @endphp
            <a href="#" title="View" class="view-btn" 
              data-reschedule-status="{{ $resStatus }}"
              data-prev="{{ $prevText }}"
              data-req="{{ $reqText }}"
              onclick="openReviewModal(
                {{ $appointment->appointment_id }}, 
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
                <strong>Date & Time:</strong> @if($appointment->status === 'Rescheduled' && $appointment->reschedule_proposed_datetime){{ $appointment->reschedule_proposed_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}@else{{ $appointment->appointment_datetime ? $appointment->appointment_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A') : 'N/A' }}@endif<br>
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
                '{{ route('Head.appointments.start', $appointment->appointment_id) }}',
                this
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
