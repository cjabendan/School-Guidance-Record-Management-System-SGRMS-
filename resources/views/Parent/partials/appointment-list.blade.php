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

                // Fallback: if relation did not resolve (e.g. mixed pivot values), try resolving via pivot table
                if (!$firstStudent) {
                    $pivot = \App\Models\AppointmentStudent::where('appointment_id', $appointment->appointment_id)->first();
                    if ($pivot) {
                        // Try s_id first
                        $fallback = \App\Models\Student::where('s_id', $pivot->student_user_id)->with('user')->first();
                        if (! $fallback) {
                            // Then try matching by user_id
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
                    {{ ucfirst($appointment->status) }}
                    <span data-appointment-status="{{ $appointment->appointment_id }}"></span>
            </span>
            @if ($appointment->rescheduled_count > 0)
                <span class="badge badge-warning">
                    Rescheduled ({{ $appointment->rescheduled_count }}x)
                </span>
            @endif
            {{-- Decline reason removed from table, only shown in modal --}}
        </div>
        <div class="table-col actions">
            <a href="#" title="View" class="view-btn"
                onclick="openParentReviewModal(`
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
                    <strong>Date & Time:</strong> {{ $appointment->appointment_datetime ? $appointment->appointment_datetime->format('M d, Y h:i A') : 'N/A' }}<br>
                    <strong>Counselor:</strong> {{ $appointment->counselor ? $appointment->counselor->first_name . ' ' . $appointment->counselor->last_name : 'N/A' }}<br>
                    <strong>Status:</strong> {{ ucfirst($appointment->status) }}<br>
                    @if ($appointment->rescheduled_count > 0)
                        <span class='badge badge-warning'>Rescheduled ({{ $appointment->rescheduled_count }}x)</span><br>
                    @endif
                    @if (strtolower($appointment->status) === 'declined' && !empty($appointment->decline_reason))
                        <div class='alert alert-danger mt-2' style='padding:4px 8px; font-size:0.95em;'><strong>Decline Reason:</strong> {{ $appointment->decline_reason }}</div>
                    @endif
                    </div>
                `)">
                <i class='bx bx-show'></i>
            </a>
        </div>
    </div>
@empty
    <div class="no-table-cell">
        No appointments to display.
    </div>
@endforelse
