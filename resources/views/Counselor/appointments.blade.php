@extends('layouts.counselor')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')

        <div class="wrapper">
            @if(session('error'))
                <div class="alert alert-danger" style="margin:12px 0; padding:10px; border-radius:6px; border:1px solid #fca5a5; background:#fff0f0; color:#b91c1c; font-weight:600;">
                    {{ session('error') }}
                </div>
            @endif
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
                            <a href="#" class="a-nav {{ request('status') == 'cancelled' ? 'active' : '' }}"
                                data-filter="cancelled">Cancelled</a>
                            <a href="#" class="a-nav {{ request('status') == 'completed' ? 'active' : '' }}"
                                data-filter="completed">Complete</a>
                        </li>
                    </div>
                    <a href="#" class="add-btn" onclick="openModal(); return false;">
                        <i class="fi fi-br-plus"></i>Create Appointment
                    </a>
                </div>
                <div class="search-bar">
                    <div class="table-search">
                        <form method="GET" action="{{ route('Counselor.appointments.index') }}">
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
                    @include('Counselor.partials.appointment-list', ['appointments' => $appointments])
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
    @include('Counselor.Modal.requestApppointmentModal', ['counselors' => $counselors, 
             'types' => $types, 'children' => $children])
    @include('Counselor.Modal.reviewModal')
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('appointment-search-input');
                const appointmentList = document.getElementById('appointments-list'); // table wrapper
                const calendarView = document.getElementById('calendar-view');
                const toggleBtn = document.getElementById('toggle-view-btn');
                const toggleIcon = document.getElementById('toggle-icon');
                const toggleLabel = document.getElementById('toggle-label');
                const filtersContainer = document.querySelector('.table-filter .filters');
                let timeout = null;
                let currentStatus = '{{ strtolower(request('status') ?? 'all') }}';
                let calendar = null;
                let isTableView = true;

                function setView(table) {
                    isTableView = table;
                    if (table) {
                        appointmentList.style.display = 'block';
                        calendarView.style.display = 'none';
                        filtersContainer.style.display = 'flex';
                        toggleIcon.className = 'fi fi-rr-table-layout';
                        // Refresh table when returning from calendar view
                        fetchAppointments(currentStatus, searchInput.value);
                    } else {
                        appointmentList.style.display = 'none';
                        calendarView.style.display = 'block';
                        filtersContainer.style.display = 'none';
                        toggleIcon.className = 'fi fi-rr-calendar-day';
                        setTimeout(renderCalendar, 0);
                    }
                }
                setView(true);

                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    setView(!isTableView);
                });

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
                    let url = `{{ route('Counselor.appointments.index') }}?` + params.toString();

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
                        // counselor avatar (if available)
                        counselorAvatar: (function(){
                            if (!item.counselor) return null;
                            if (item.counselor.profile_image) return `{{ asset('images/user') }}/${item.counselor.profile_image}`;
                            if (item.counselor.profile_photo_path) return item.counselor.profile_photo_path;
                            if (item.counselor.user && item.counselor.user.profile_image) return `{{ asset('images/user') }}/${item.counselor.user.profile_image}`;
                            return null;
                        })(),
                        requesterAvatar: null,
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
                            // Build a custom DOM node with avatar + title (Head-style)
                            const container = document.createElement('div');
                            container.className = 'fc-event-custom';

                            const bg = arg.event.backgroundColor || '#6b7280';
                            container.style.background = bg;
                            container.style.color = '#fff';
                            container.style.padding = '6px 8px';
                            container.style.borderRadius = '6px';

                            const avatarUrl = arg.event.extendedProps.counselorAvatar || arg.event.extendedProps.requesterAvatar || null;
                            if (avatarUrl) {
                                const img = document.createElement('img');
                                img.src = avatarUrl;
                                img.alt = arg.event.extendedProps.counselor || 'avatar';
                                img.className = 'fc-event-avatar';
                                container.appendChild(img);
                            }

                            const titleWrap = document.createElement('div');
                            titleWrap.className = 'fc-event-title-wrap';

                            const title = document.createElement('div');
                            title.className = 'fc-event-title-text';
                            title.textContent = arg.event.title || '';
                            titleWrap.appendChild(title);

                            const sub = document.createElement('div');
                            sub.className = 'fc-event-sub';
                            sub.textContent = arg.event.extendedProps.counselor || '';
                            titleWrap.appendChild(sub);

                            container.appendChild(titleWrap);

                            return { domNodes: [container] };
                        },
                        selectable: true,
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
                            fetch('/Counselor/appointments/' + info.event.id + '/move', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    appointment_datetime: info.event.start.toISOString()
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (!data.success) {
                                    alert('Failed to update appointment!');
                                    info.revert();
                                }
                            })
                            .catch(() => {
                                alert('Failed to update appointment!');
                                info.revert();
                            });
                        },
                        dateClick: function(info) {
                            openModal();
                            var dtInput = document.getElementById('appointment_datetime');
                            if (dtInput) {
                                let dateStr = info.dateStr;
                                dtInput.value = dateStr + 'T08:00';
                            }
                        }
                    });
                    calendar.render();
                }
            });
</script>
<script>
function openModal() {
    document.getElementById('requestAppointmentModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('requestAppointmentModal').style.display = 'none';
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

<style>
/* Calendar event avatar styles (match Head layout) */
.fc-event-custom { display:flex; align-items:center; gap:8px; }
.fc-event-avatar { width:28px; height:28px; border-radius:50%; object-fit:cover; border:1px solid rgba(255,255,255,0.12); flex:0 0 28px; }
.fc-event-title-wrap { display:flex; flex-direction:column; line-height:1; }
.fc-event-title-text { font-weight:600; font-size:0.9rem; color:inherit; }
.fc-event-sub { font-size:0.75rem; color:rgba(255,255,255,0.9); }
</style>
