<div class="appointments-box">
    <div class="appointments-header">
        <h2>Upcoming Appointments</h2>
        <form method="GET" id="appointmentFilterForm">
            <select class="dropdown" name="filter" onchange="document.getElementById('appointmentFilterForm').submit()">
                <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today
                </option>
                <option value="tomorrow" {{ request('filter') == 'tomorrow' ? 'selected' : '' }}>
                    Tomorrow</option>
                <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>This
                    Week
                </option>
            </select>
        </form>
    </div>
    <div class="appointments-table" id="appointments-table-container" style="position:relative;">
        @include('components.small-loader')
        <table>
            <tbody>
                @forelse($upcomingAppointments as $appointment)
                    <tr class="appointment-card">
                        <td class="appointment-time-col">
                            {{ $appointment->appointment_datetime->format('h:i A') }}
                        </td>
                        <td class="appointment-details-col">
                            <div class="appointment-details-flex">
                                <p class="appointment-type">{{ $appointment->appointment_type }}
                                </p>
                                <span class="appointment-requester">
                                    {{ $appointment->requester_name }}
                                </span>
                            </div>
                        </td>
                        <td class="appointment-actions-col">
                            <div class="appointment-date">
                                {{ $appointment->appointment_datetime->format('M d, Y') }}
                            </div>
                            <div class="appointment-action">
                                <a href="" class="appointment-link">View details</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="no-appointments-cell">
                            No upcoming appointments.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
