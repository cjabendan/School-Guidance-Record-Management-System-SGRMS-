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
        <div class="table-col student">
            @php
                $studentCount = $appointment->students->count();
                $firstStudent = $appointment->students->first();

                // Fallback: resolve via pivot if relation is empty or missing
                if (!$firstStudent) {
                    $pivot = \App\Models\AppointmentStudent::where('appointment_id', $appointment->appointment_id)->first();
                    if ($pivot) {
                        $fallback = \App\Models\Student::where('s_id', $pivot->student_user_id)->with('user')->first();
                        if (! $fallback) {
                            $fallback = \App\Models\Student::where('user_id', $pivot->student_user_id)->with('user')->first();
                        }
                        $firstStudent = $fallback;
                        $studentCount = $firstStudent ? 1 : 0;
                    }
                }
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
            @if($appointment->appointment_datetime)
                @php
                    $dt = $appointment->appointment_datetime->setTimezone('Asia/Manila');
                @endphp
                {{ $dt->format('M d, Y h:i A') }}
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
            {{-- Reschedule details are shown in the review modal only. --}}
        </div>
        <div class="table-col actions">
            @php
                $status = strtolower($appointment->status);
                $latestReq = $appointment->reschedules()->first();
                $prevText = '';
                $reqText = '';
                
                // Only show previous and preferred times for rescheduled appointments
                if ($status === 'rescheduled') {
                    $prevText = $appointment->appointment_datetime ? $appointment->appointment_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A') : '';
                    if ($appointment->reschedule_proposed_datetime) {
                        $reqText = $appointment->reschedule_proposed_datetime->setTimezone('Asia/Manila')->format('M d, Y h:i A');
                    } elseif ($latestReq && !empty($latestReq->proposed_datetime)) {
                        $reqText = \Carbon\Carbon::parse($latestReq->proposed_datetime)->setTimezone('Asia/Manila')->format('M d, Y h:i A');
                    }
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
                            {{-- Reschedule details shown only in the review modal. --}}
                    @if (strtolower($appointment->status) === 'declined' && !empty($appointment->decline_reason))
                        <div class='alert alert-danger mt-2' style='padding:4px 8px; font-size:0.95em;'><strong>Decline Reason:</strong> {{ $appointment->decline_reason }}</div>
                    @endif
                    </div>
                `, '{{ route('Student.appointments.cancel', $appointment->appointment_id) }}', '{{ strtolower($appointment->status) }}', '{{ route('Student.appointments.start', $appointment->appointment_id) }}', this)">
                <i class='bx bx-show'></i>
            </a>
            {{-- Students are not allowed to edit/reschedule appointments. Edit button intentionally hidden. --}}
        </div>
    </div>
@empty
    <div class="no-table-cell">
        No appointments to display.
    </div>
@endforelse
