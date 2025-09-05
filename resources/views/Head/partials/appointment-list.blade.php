
@forelse($appointments as $appointment)
    <div class="appointment-item">
        <div class="appointment-col type">
            {{ $appointment->type ? $appointment->type->type_name : 'N/A' }}
        </div>
        <div class="appointment-col requester">
            @if ($appointment->requester)
                {{ $appointment->requester->first_name }} {{ $appointment->requester->last_name }}
            @else
                N/A
            @endif
        </div>
        <div class="appointment-col student">
            @if ($appointment->student)
                {{ $appointment->student->first_name }} {{ $appointment->student->last_name }}
            @else
                N/A
            @endif
        </div>
        <div class="appointment-col datetime">
            {{ $appointment->appointment_datetime ? $appointment->appointment_datetime->format('M d, Y h:i A') : 'N/A' }}
        </div>
        <div class="appointment-col counselor">
            @if ($appointment->counselor)
                {{ $appointment->counselor->first_name }} {{ $appointment->counselor->last_name }}
            @else
                N/A
            @endif
        </div>
        <div class="appointment-col status">
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
            </span>
            @if ($appointment->rescheduled_count > 0)
                <span class="badge badge-warning">
                    Rescheduled ({{ $appointment->rescheduled_count }}x)
                </span>
            @endif
        </div>
        <div class="appointment-col actions">
            <a href="#" title="View" class="view-btn"
                onclick="openAppointmentModal({{ $appointment->appointment_id }}, 'view')">
                <i class='bx bx-show'></i>
            </a>
            <a href="#" title="Reschedule" class="edit-btn"
                onclick="openAppointmentModal({{ $appointment->appointment_id }}, 'reschedule')">
                <i class='bx bx-edit'></i>
            </a>
        </div>
    </div>
@empty
    <div class="no-appointments-cell">
        No appointments to display.
    </div>
@endforelse
