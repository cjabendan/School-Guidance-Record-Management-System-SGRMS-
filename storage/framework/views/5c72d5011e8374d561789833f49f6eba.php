<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>

    <!-- MAIN CONTENT -->
    <section id="content">
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="wrapper">
            <div class="table-container">
                <div class="table-management">
                    <div class="ann-nav">
                        <div class="table-filter" id="announcement-filters">
                            <!-- Table view filters -->
                            <div class="filters" id="table-filters">
                                <li>
                                    <a href="#"
                                        class="a-nav <?php echo e(request('category') == 'recent' || !request()->has('category') ? 'active' : ''); ?>"
                                        data-filter="recent">Recent</a>
                                    <a href="#"
                                        class="a-nav <?php echo e(request('category') == 'announcement' ? 'active' : ''); ?>"
                                        data-filter="announcement">Announcements</a>
                                    <a href="#" class="a-nav <?php echo e(request('category') == 'event' ? 'active' : ''); ?>"
                                        data-filter="event">Events</a>
                                    <a href="#" class="a-nav <?php echo e(request('category') == 'news' ? 'active' : ''); ?>"
                                        data-filter="news">News</a>
                                </li>
                            </div>
                            <button class="add-btn" onclick="openModal()"><i class="fi fi-br-plus"></i>Add
                                Announcement</button>
                        </div>
                    </div>
                    <div class="search-bar">
                        <div class="table-search">
                            <form method="GET" action="<?php echo e(route('Head.announcements.index')); ?>">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                    placeholder="Search announcements..." id="announcement-search-input">
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

                <!-- Table view -->
                <div class="table-list" id="announcements-list" style="margin-bottom:0;">
                    <div class="table-header">
                        <div class="table-col title">Title</div>
                        <div class="table-col category">Category</div>
                        <div class="table-col date">Date Posted</div>
                        <div class="table-col status">Status</div>
                        <div class="table-col actions">Actions</div>
                    </div>
                    <div class="table">
                        <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="table-card" id="announcement-card">
                                <div class="table-col title"><?php echo e($announcement->title); ?></div>
                                <div class="table-col category"><?php echo e($announcement->category); ?></div>
                                <div class="table-col date">
                                    <?php echo e(\Carbon\Carbon::parse($announcement->date_posted)->format('M d, Y')); ?>

                                </div>
                                <div class="table-col status">
                                    <?php
                                        $status = strtolower($announcement->status);
                                        $dotClass = match ($status) {
                                            'active' => 'status-dot status-approved',
                                            'archived' => 'status-dot status-declined',
                                            'pending' => 'status-dot status-pending',
                                            default => 'status-dot',
                                        };
                                        $labelClass = match ($status) {
                                            'active' => 'status-label status-approved',
                                            'archived' => 'status-label status-declined',
                                            'pending' => 'status-label status-pending',
                                            default => 'status-label',
                                        };
                                    ?>
                                    <span class="<?php echo e($labelClass); ?>">
                                        <span class="<?php echo e($dotClass); ?>"></span>
                                        <?php echo e(ucfirst($announcement->status)); ?>

                                    </span>
                                </div>
                                <div class="table-col actions">
                                    <a href="#" title="View" class="view-btn"
                                        onclick="openAnnouncementModal(<?php echo e($announcement->id); ?>, 'view')"
                                        data-id="<?php echo e($announcement->id); ?>" data-title="<?php echo e($announcement->title); ?>"
                                        data-description="<?php echo e($announcement->description); ?>"
                                        data-link="<?php echo e($announcement->link); ?>"
                                        data-category="<?php echo e($announcement->category); ?>"
                                        data-status="<?php echo e($announcement->status); ?>" data-image="<?php echo e($announcement->image); ?>"
                                        data-date_posted="<?php echo e($announcement->date_posted); ?>"
                                        data-start_datetime="<?php echo e($announcement->start_datetime); ?>"
                                        data-end_datetime="<?php echo e($announcement->end_datetime); ?>"><i
                                            class='bx bx-show'></i></a>
                                    <a href="#" title="Edit" class="edit-btn"
                                        onclick="openAnnouncementModal(<?php echo e($announcement->id); ?>, 'edit')"
                                        data-id="<?php echo e($announcement->id); ?>" data-title="<?php echo e($announcement->title); ?>"
                                        data-description="<?php echo e($announcement->description); ?>"
                                        data-link="<?php echo e($announcement->link); ?>"
                                        data-category="<?php echo e($announcement->category); ?>"
                                        data-status="<?php echo e($announcement->status); ?>" data-image="<?php echo e($announcement->image); ?>"
                                        data-date_posted="<?php echo e($announcement->date_posted); ?>"
                                        data-start_datetime="<?php echo e($announcement->start_datetime); ?>"
                                        data-end_datetime="<?php echo e($announcement->end_datetime); ?>"><i
                                            class='bx bx-edit'></i></a>
                                    <a href="#" title="Archive" class="archive-btn"><i class='bx bx-archive'></i></a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="no-table-cell">No announcements found.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Calendar view -->
                <div id="calendar-view" style="display:none; width:100%; margin-bottom:0;">
                    <div id="calendar"></div>
                    <div id="calendar-empty-message"
                        style="display:none; text-align:center; color:#8888884b; font-size:1.1rem; padding:32px;">
                        No events to display.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php echo $__env->make('Head.Modal.announcementModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<script>
    function openModal() {
        const modal = document.getElementById('announcementModal');
        const form = document.getElementById('announcementForm');
        const modalTitle = document.getElementById('modalTitle');
        const saveBtn = document.getElementById('saveBtn');
        const removeImageBtn = document.getElementById('remove-image-btn');

        // Reset form fields
        form.reset();
        document.getElementById('announcement_id').value = '';
        document.getElementById('form-method').value = 'POST';
        form.action = '<?php echo e(route('Head.announcements.store')); ?>';

        // Enable all fields
        Array.from(form.elements).forEach(el => el.disabled = false);

        // Hide event fields
        document.getElementById('event-fields').style.display = 'none';

        // Reset image preview
        document.getElementById('image-preview').src = '';
        document.getElementById('image-preview-wrapper').style.display = 'none';
        document.querySelector('.custom-upload-btn').style.display = 'inline-block';
        document.getElementById('file-chosen').style.display = 'inline-block';

        // Set modal title/button
        modalTitle.textContent = 'Add Announcement';
        saveBtn.style.display = 'inline-block';
        removeImageBtn.style.display = 'inline-block';

        modal.style.display = 'block';
    }

    function closeModal() {
        document.getElementById('announcementModal').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('announcement-search-input');
        const announcementsList = document.getElementById('announcements-list');
        const calendarView = document.getElementById('calendar-view');
        const toggleBtn = document.getElementById('toggle-view-btn');
        const toggleIcon = document.getElementById('toggle-icon');
        const toggleLabel = document.getElementById('toggle-label');
        const tableFilters = document.getElementById('table-filters');
        let timeout = null;
        let currentCategory = '<?php echo e(strtolower(request('category') ?? 'recent')); ?>';
        let calendar = null;
        let isTableView = true;

        function setView(table) {
            isTableView = table;
            if (table) {
                announcementsList.style.display = 'block';
                calendarView.style.display = 'none';
                tableFilters.style.display = 'flex';
                toggleIcon.className = 'fi fi-rr-table-layout';

            } else {
                announcementsList.style.display = 'none';
                calendarView.style.display = 'block';
                tableFilters.style.display = 'none';
                toggleIcon.className = 'fi fi-rr-calendar-day';

                setTimeout(renderCalendar, 0);
            }
        }
        setView(true);

        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            setView(!isTableView);
        });

        // AJAX for search
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    fetchAnnouncements(currentCategory, searchInput.value);
                }, 400);
            });
        }

        // AJAX for table filters
        tableFilters.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!isTableView) return;
                e.preventDefault();
                tableFilters.querySelectorAll('a').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                currentCategory = this.dataset.filter;
                fetchAnnouncements(currentCategory, searchInput.value);
            });
        });

        function fetchAnnouncements(category = 'recent', search = '') {
            let params = new URLSearchParams();
            if (category && category !== 'recent') params.append('category', category);
            if (search && search.trim() !== '') params.append('search', search.trim());
            let url = `<?php echo e(route('Head.announcements.index')); ?>?` + params.toString();

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newList = doc.getElementById('announcements-list');
                    if (newList) {
                        announcementsList.innerHTML = newList.innerHTML;
                    }
                });
        }

        function renderCalendar() {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            if (calendar) calendar.destroy();

            // Normalize backend events
            let rawEvents = <?php echo json_encode($events, 15, 512) ?>;

            let formattedEvents = rawEvents.map(item => {
                if (item.category === "Event") {
                    // Properly parse event start/end
                    return {
                        title: item.title,
                        start: item.start_datetime ? new Date(item.start_datetime).toISOString() : null,
                        end: item.end_datetime ? new Date(item.end_datetime).toISOString() : null,
                        allDay: false,
                        category: item.category,
                        description: item.description,
                        color: "#10b981"
                    };
                } else {
                    // Announcements: treat as partial-day (6:00–20:00)
                    const start = new Date(item.date_posted);
                    start.setHours(6, 0, 0);
                    const end = new Date(item.date_posted);
                    end.setHours(20, 0, 0);

                    return {
                        title: `[${item.category}] ${item.title}`,
                        start: start.toISOString(),
                        end: end.toISOString(),
                        allDay: false, // false so it shows in week/day view
                        category: item.category,
                        description: item.description,
                        color: "#1ea7ff"
                    };
                }
            });

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                events: formattedEvents,

                eventContent: function(arg) {
                    let category = arg.event.extendedProps.category.toLowerCase();
                    let color = '#1ea7ff'; // default: Announcement
                    let iconClass = 'bx bxs-megaphone'; // default icon

                    if (category === 'announcement') {
                        color = '#1ea7ff';
                        iconClass = 'bx bxs-megaphone';
                    } else if (category === 'event') {
                        color = '#10b981';
                        iconClass = 'bx bxs-calendar-event';
                    }

                    return {
                        html: `
        <div style="
            background: ${color};
            color: #fff;
            padding: 8px 12px;
            border-radius: 7px;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;     
            gap: 6px;    
        ">
            <i class='${iconClass}' style='margin-right:6px;'></i>
            ${arg.event.title}
        </div>
        `
                    };
                },
                selectable: true,
                editable: false,
                slotMinTime: "06:00:00",
                slotMaxTime: "20:00:00",
                allDaySlot: false,
                nowIndicator: true,

                eventClick: function(info) {
                    let props = info.event.extendedProps;
                    alert(
                        "Title: " + info.event.title + "\n" +
                        "Category: " + props.category + "\n" +
                        "Description: " + (props.description ?? 'N/A')
                    );
                }
            });

            calendar.render();
        }

    });
    let events = <?php echo json_encode($events, 15, 512) ?>;
    console.log("Events from backend:", events);
</script>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/Head/announcements.blade.php ENDPATH**/ ?>