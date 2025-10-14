@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')

        <div class="wrapper">
            <div class="table-management">
                <div class="table-filter">
                    <div class="filters">
                        <li>
                            <a href="#"
                                class="a-nav {{ request('status') == 'all' || !request()->has('status') ? 'active' : '' }}"
                                data-filter="all">All</a>
                            <a href="#" class="a-nav {{ request('status') == 'pending' ? 'active' : '' }}"
                                data-filter="pending">Pending</a>
                            <a href="#" class="a-nav {{ request('status') == 'approved' ? 'active' : '' }}"
                                data-filter="approved">Approved</a>
                            <a href="#" class="a-nav {{ request('status') == 'declined' ? 'active' : '' }}"
                                data-filter="declined">Declined</a>
                        </li>
                    </div>
                    <a href="#" class="add-btn" onclick="openModal(); return false;">
                        <i class="fi fi-br-plus"></i>Create appointment
                    </a>
                </div>
                <div class="search-bar">
                    <div class="table-search">
                        <form method="GET" action="{{ route('Head.appointments.index') }}">
                            <i class="fi fi-br-search"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search appointments..." id="appointment-search-input">
                            @if (request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <button type="submit" style="display:none"></button>
                        </form>
                    </div>
                    <button class="toggle-btn" id="toggle-view-btn">
                        <i class="fi fi-rr-table-layout" id="toggle-icon"></i>
                        <span id="toggle-label"></span>
                    </button>
                </div>
            </div>
            <div class="table-list" id="appointments-list">
                <div class="table-header">
                    <div class="table-col type">Type</div>
                    <div class="table-col requester">Requester</div>
                    <div class="table-col student">Student</div>
                    <div class="table-col datetime">Date & Time</div>
                    <div class="table-col counselor">Counselor</div>
                    <div class="table-col status">Status</div>
                    <div class="table-col actions">Actions</div>
                </div>
                <div class="table">
                    @include('Head.partials.appointment-list', ['appointments' => $appointments])
                </div>
            </div>

            <!-- Calendar view -->
            <div id="calendar-view" style="display:none; width:100%; margin-bottom:0;">
                <div id="calendar"></div>
                <div id="calendar-empty-message"
                    style="display:none; text-align:center; color:#8888884b; font-size:1.1rem; padding:32px;">
                    No data to display.
                </div>
            </div>
        </div>
        </div>
    </section>
    @include('Head.modal.requestApppointmentModal', ['counselors' => $counselors, 
             'types' => $types, 'children' => $children])
    @include('Head.Modal.reviewModal')
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('appointment-search-input');
                const appointmentList = document.getElementById('appointments-list'); // table wrapper
                const calendarView = document.getElementById('calendar-view');
                const toggleBtn = document.getElementById('toggle-view-btn');
                const toggleIcon = document.getElementById('toggle-icon');
                const toggleLabel = document.getElementById('toggle-label');
                // filters container may not exist on all pages
                const filtersContainer = document.querySelector('.table-filter .filters');
                let timeout = null;
                let currentStatus = '{{ strtolower(request('status') ?? 'all') }}';
                let calendar = null;
                let isTableView = true;

                function setView(table) {
                    isTableView = table;
                    if (table) {
                        appointmentList.style.display = 'block';
                        if (calendarView) calendarView.style.display = 'none';
                        if (filtersContainer) filtersContainer.style.display = 'flex';
                        if (toggleIcon) toggleIcon.className = 'fi fi-rr-table-layout';
                        // Refresh table when returning from calendar view
                        fetchAppointments(currentStatus, searchInput.value);
                    } else {
                        appointmentList.style.display = 'none';
                        if (calendarView) calendarView.style.display = 'block';
                        if (filtersContainer) filtersContainer.style.display = 'none';
                        if (toggleIcon) toggleIcon.className = 'fi fi-rr-calendar-day';
                        // Render calendar after a tick to ensure container is visible
                        setTimeout(renderCalendar, 0);
                    }
                }
                setView(true);

                // Only attach toggle handler if the toggle button exists
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        setView(!isTableView);
                    });
                }

                // AJAX search
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(timeout);
                        timeout = setTimeout(function() {
                            fetchAppointments(currentStatus, searchInput.value);
                        }, 400);
                    });
                }

                // AJAX filters
                filtersContainer.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        if (!isTableView) return;
                        e.preventDefault();
                        filtersContainer.querySelectorAll('a').forEach(l => l.classList.remove(
                            'active'));
                        this.classList.add('active');
                        currentStatus = this.dataset.filter; // uses data-filter from Blade
                        fetchAppointments(currentStatus, searchInput.value);
                    });
                });

                function fetchAppointments(status = 'all', search = '') {
                    let params = new URLSearchParams();
                    if (status && status !== 'all') params.append('status', status);
                    if (search && search.trim() !== '') params.append('search', search.trim());
                    let url = `{{ route('Head.appointments.index') }}?` + params.toString();

                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newList = doc.querySelector('.table');
                            const currentList = appointmentList.querySelector('.table');
                            if (newList && currentList) {
                                currentList.innerHTML = newList.innerHTML;
                            }
                        });
                }

                function renderCalendar() {
                    const calendarEl = document.getElementById('calendar');
                    if (!calendarEl) return;
                    if (calendar) calendar.destroy();

                    let rawAppointments = @json($appointments);

                    let formattedAppointments = rawAppointments.map(item => ({
                        id: item.appointment_id, // Ensure event has correct id
                        title: item.type?.type_name ?? "Appointment",
                        start: item.appointment_datetime ? new Date(item.appointment_datetime) : null,
                        allDay: false,
                        type: item.type?.type_name ?? "N/A",
                        requester: item.requester ?
                            `${item.requester.first_name} ${item.requester.last_name}` : "N/A",
                        student: item.students && item.students.length > 0
                            ? item.students.map(s => `${s.user?.first_name ?? ''} ${s.user?.last_name ?? ''}`).join(', ')
                            : "N/A",
                        counselor: item.counselor ?
                            `${item.counselor.first_name} ${item.counselor.last_name}` : "N/A",
                        status: item.status ?? "N/A",
                        color: item.status?.toLowerCase() === 'approved' ? '#10b981' :
                            item.status?.toLowerCase() === 'pending' ? '#f59e0b' :
                            item.status?.toLowerCase() === 'declined' ? '#ef4444' :
                            '#6b7280'
                    }));

                    calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                        },
                        events: formattedAppointments,
                        eventContent: function(arg) {
                            return {
                                html: `<div style="
                                    background: ${arg.event.backgroundColor}; 
                                    color: #fff; padding: 6px 10px; 
                                    border-radius: 6px; font-size: 0.9rem; 
                                    font-weight: 600; white-space: nowrap; 
                                    overflow: hidden; text-overflow: ellipsis;">
                                    ${arg.event.title}
                                </div>`
                            };
                        },
                        selectable: false,
                        editable: true, // Enable drag and drop
                        slotMinTime: "06:00:00",
                        slotMaxTime: "20:00:00",
                        allDaySlot: false,
                        nowIndicator: true,
                        eventClick: function(info) {
                            let props = info.event.extendedProps;
                            alert(
                                "Type: " + props.type + "\n" +
                                "Requester: " + props.requester + "\n" +
                                "Student: " + props.student + "\n" +
                                "Counselor: " + props.counselor + "\n" +
                                "Status: " + props.status
                            );
                        },
                        eventDrop: function(info) {
                            const url = `/Head/appointments/${info.event.id}/move`;
                            fetch(url, {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    appointment_datetime: info.event.start ? info.event.start.toISOString() : null
                                })
                            })
                            .then(async res => {
                                if (!res.ok) {
                                    const txt = await res.text();
                                    console.error('Move failed', res.status, txt);
                                    info.revert();
                                    return;
                                }
                                const data = await res.json();
                                if (!data.success) {
                                    console.error('Move error', data);
                                    info.revert();
                                }
                            })
                            .catch(err => {
                                console.error('Move request error', err);
                                info.revert();
                            });
                        }
                    });
                    calendar.render();
                }

                // If calendar is initialized later, add dateClick handler in the calendar config.
                // Example calendar initialization (where you're creating `calendar`) should include:
                if (calendar) {
                    // ensure calendar allows clicking a date
                    calendar.setOption('selectable', true);

                    // add handler for clicking a date to open the request modal
                    calendar.setOption('dateClick', function(info) {
                        // info.dateStr is YYYY-MM-DD or YYYY-MM-DDTHH:mm:ss depending on view
                        const clickedIso = info.dateStr || (info.date && info.date.toISOString && info.date.toISOString());
                        // set modal input if it exists (try id then name)
                        const inputById = document.getElementById('appointment_datetime');
                        const inputByName = document.querySelector('input[name="appointment_datetime"]');
                        const inputByAlt = document.getElementById('appointment-datetime'); // common alt id
                        const valToSet = clickedIso ? clickedIso : '';

                        if (inputById) inputById.value = valToSet;
                        else if (inputByAlt) inputByAlt.value = valToSet;
                        else if (inputByName) inputByName.value = valToSet;

                        // open modal (openModal updated below accepts an optional date)
                        openModal(valToSet);
                    });
                }
            });
</script>
<script>
function openModal(dateValue) {
    const modal = document.getElementById('requestAppointmentModal');
    if (!modal) return;
    // If a date value was provided, set the form field(s)
    if (dateValue) {
        const inputById = document.getElementById('appointment_datetime');
        const inputByName = document.querySelector('input[name="appointment_datetime"]');
        const inputByAlt = document.getElementById('appointment-datetime');

        if (inputById) inputById.value = dateValue;
        else if (inputByAlt) inputByAlt.value = dateValue;
        else if (inputByName) inputByName.value = dateValue;
    }

    modal.style.display = 'flex';
}
function closeModal() {
    const modal = document.getElementById('requestAppointmentModal');
    if (!modal) return;
    modal.style.display = 'none';
}

// Optional: Close modal when clicking outside content
document.addEventListener('click', function(e) {
    const modal = document.getElementById('requestAppointmentModal');
    if (modal.style.display === 'flex' && e.target === modal) {
        closeModal();
    }
});
</script>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
@endpush
