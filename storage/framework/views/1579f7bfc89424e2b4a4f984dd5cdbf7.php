<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>

    <!-- MAIN CONTENT -->
    <section id="content">
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="wrapper">
            <?php if(session('error')): ?>
                <div class="alert alert-danger" style="margin:12px 0; padding:10px; border-radius:6px; border:1px solid #fca5a5; background:#fff0f0; color:#b91c1c; font-weight:600;">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>
            <div class="table-management">
                <div class="table-filter">
                    <div class="filters">
                        <li>
                            <a href="#"
                                class="a-nav <?php echo e(request('status') == 'all' || !request()->has('status') ? 'active' : ''); ?>"
                                data-filter="all">All</a>
                            <a href="#" class="a-nav <?php echo e(request('status') == 'pending' ? 'active' : ''); ?>"
                                data-filter="pending">Pending</a>
                            <a href="#" class="a-nav <?php echo e(request('status') == 'approved' ? 'active' : ''); ?>"
                                data-filter="approved">Approved</a>
                            <a href="#" class="a-nav <?php echo e(request('status') == 'declined' ? 'active' : ''); ?>"
                                data-filter="declined">Declined</a>
                            <a href="#" class="a-nav <?php echo e(request('status') == 'cancelled' ? 'active' : ''); ?>"
                                data-filter="cancelled">Cancelled</a>
                            <a href="#" class="a-nav <?php echo e(request('status') == 'completed' ? 'active' : ''); ?>"
                                data-filter="completed">Complete</a>
                        </li>
                    </div>
                    <a href="#" class="add-btn" onclick="openModal(); return false;">
                        <i class="fi fi-br-plus"></i>Create appointment
                    </a>
                </div>
                <div class="search-bar">
                    <div class="table-search">
                        <form method="GET" action="<?php echo e(route('Head.appointments.index')); ?>">
                            <i class="fi fi-br-search"></i>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                placeholder="Search appointments..." id="appointment-search-input">
                            <?php if(request('category')): ?>
                                <input type="hidden" name="category" value="<?php echo e(request('category')); ?>">
                            <?php endif; ?>
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
                    <div class="table-col counselor">Counselor</div>
                    <div class="table-col status">Status</div>
                    <div class="table-col actions">Actions</div>
                </div>
                <div class="table">
                    <?php echo $__env->make('Head.partials.appointment-list', ['appointments' => $appointments], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
    <?php echo $__env->make('Head.modal.requestApppointmentModal', ['counselors' => $counselors, 
             'types' => $types, 'children' => $children], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('Head.Modal.reviewModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

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
                let currentStatus = '<?php echo e(strtolower(request('status') ?? 'all')); ?>';
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
                    let url = `<?php echo e(route('Head.appointments.index')); ?>?` + params.toString();

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

                    // Use the paginator items (array of appointment objects) instead of the
                    // paginator object so we can call array methods like filter/map in JS.
                    let rawAppointments = <?php echo json_encode($appointments->items(), 15, 512) ?>;

                    // Only show Approved and Rescheduled appointments on the calendar
                    rawAppointments = rawAppointments.filter(function(item) {
                        const st = (item.status || '').toLowerCase();
                        return st === 'approved' || st === 'rescheduled';
                    });

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
                        // avatars provided by controller transform
                        counselorAvatar: item.counselor_avatar ?? null,
                        requesterAvatar: item.requester_avatar ?? null,
                        status: item.status ?? "N/A",
                        // disable moving/resizing for completed/cancelled/declined/ongoing
                        startEditable: !(item.status && ['completed','cancelled','declined','ongoing'].includes(item.status.toLowerCase())),
                        durationEditable: !(item.status && ['completed','cancelled','declined','ongoing'].includes(item.status.toLowerCase())),
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
                            // Build a custom DOM node with avatar + title
                            const container = document.createElement('div');
                            container.className = 'fc-event-custom';

                            const bg = arg.event.backgroundColor || '#6b7280';
                            container.style.background = bg;
                            container.style.color = '#fff';
                            container.style.padding = '6px 8px';
                            container.style.borderRadius = '6px';

                            // Avatar (counselor first)
                            const avatarUrl = arg.event.extendedProps.counselorAvatar || arg.event.extendedProps.requesterAvatar || null;
                            if (avatarUrl) {
                                const img = document.createElement('img');
                                img.src = avatarUrl;
                                img.alt = arg.event.extendedProps.counselor || 'avatar';
                                img.className = 'fc-event-avatar';
                                container.appendChild(img);
                            }

                            // Title and optional subtext
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
                        dateClick: function(info) {
                            openModal();
                            var dtInput = document.getElementById('appointment_datetime');
                            if (dtInput) {
                                // default time 08:00 like Counselor calendar
                                dtInput.value = info.dateStr + 'T08:00';
                            }
                        },

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
                        eventDragStop: function() {
                            if (calendar && calendar._lastDenied) calendar._lastDenied = null;
                        },
                        eventDrop: function(info) {
                            // safety: prevent moving completed/cancelled/declined/ongoing even if client tried
                            let st = (info.event.extendedProps.status || '').toLowerCase();
                            if (['completed','cancelled','declined','ongoing'].includes(st)) {
                                    showError('This appointment cannot be moved.');
                                info.revert();
                                return;
                            }

                            fetch('/Head/appointments/' + info.event.id + '/move', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                },
                                body: JSON.stringify({
                                    appointment_datetime: info.event.start.toISOString()
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    // try to parse error json and throw it to catch block
                                    return response.json().then(err => { throw err; });
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (!data.success) {
                                        showError('This Appointment is already taken. Please choose another available date or time.');
                                    info.revert();
                                }
                            })
                            .catch(err => {
                                // If server returned a message, show it. Otherwise show generic message.
                                const msg = (err && err.error) ? err.error : 'This Appointment is already taken. Please choose another available date or time.';
                                    showError(msg);
                                info.revert();
                            });
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
<!-- Error modal (centered) -->
<div id="sgrms-error-modal" style="display:none;">
    <div class="sgrms-error-overlay"></div>
    <div class="sgrms-error-card">
        <button class="sgrms-error-close" aria-label="Close">&times;</button>
        <div class="sgrms-error-icon">!</div>
        <div id="sgrms-error-message" class="sgrms-error-message">An error occurred</div>
        <div class="sgrms-error-actions">
            <button id="sgrms-error-ok" class="sgrms-btn">OK</button>
        </div>
    </div>
</div>

<script>
function showError(msg) {
    const modal = document.getElementById('sgrms-error-modal');
    const msgEl = document.getElementById('sgrms-error-message');
    if (!modal || !msgEl) {
        alert(msg);
        return;
    }
    msgEl.textContent = msg;
    // use flex so the modal centers via align-items/justify-content
    modal.style.display = 'flex';
}
function hideError() { const modal = document.getElementById('sgrms-error-modal'); if (modal) modal.style.display = 'none'; }
document.addEventListener('click', function(e) {
    const modal = document.getElementById('sgrms-error-modal');
    // modal may be shown using 'flex' display; only proceed when it's visible
    if (!modal || modal.style.display === 'none') return;
    if (e.target === modal.querySelector('.sgrms-error-overlay') || e.target.classList.contains('sgrms-error-close') || e.target.id === 'sgrms-error-ok') {
        hideError();
    }
});

// Also attach direct listeners to the modal buttons/overlay to ensure they respond
(function attachModalListeners() {
    const modal = document.getElementById('sgrms-error-modal');
    if (!modal) return;
    const ok = modal.querySelector('#sgrms-error-ok');
    const close = modal.querySelector('.sgrms-error-close');
    const overlay = modal.querySelector('.sgrms-error-overlay');
    if (ok) ok.addEventListener('click', hideError);
    if (close) close.addEventListener('click', hideError);
    if (overlay) overlay.addEventListener('click', hideError);
})();
</script>
<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<?php $__env->stopPush(); ?>

<style>
/* Calendar event avatar styles */
.fc-event-custom { display:flex; align-items:center; gap:8px; }
.fc-event-avatar { width:28px; height:28px; border-radius:50%; object-fit:cover; border:1px solid rgba(255,255,255,0.12); flex:0 0 28px; }
.fc-event-title-wrap { display:flex; flex-direction:column; line-height:1; }
.fc-event-title-text { font-weight:600; font-size:0.9rem; color:inherit; }
.fc-event-sub { font-size:0.75rem; color:rgba(255,255,255,0.9); }
/* Error modal styles */
#sgrms-error-modal { position:fixed; inset:0; display:none; align-items:center; justify-content:center; z-index:1200; }
.sgrms-error-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.45); }
.sgrms-error-card { position:relative; width:420px; max-width:92%; margin:auto; background:#fff; color:#111; border-radius:10px; padding:22px; box-shadow:0 10px 30px rgba(0,0,0,0.25); display:flex; flex-direction:column; align-items:center; gap:12px; z-index:1201; }
.sgrms-error-icon { background:#f97373; color:#fff; width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.2rem; }
.sgrms-error-message { text-align:center; font-size:1rem; font-weight:600; color:#111; }
.sgrms-error-actions { margin-top:6px; }
.sgrms-btn { background:#2563eb; color:#fff; padding:8px 14px; border-radius:8px; border:none; cursor:pointer; }
.sgrms-error-close { position:absolute; top:8px; right:10px; border:none; background:transparent; font-size:1.3rem; cursor:pointer; color:#666; }
</style>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/appointments.blade.php ENDPATH**/ ?>