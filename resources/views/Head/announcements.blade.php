@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')

        <div class="wrapper">
            <div class="announcement-container">
                <div class="announcement-management">
                    <div class="ann-nav">
                        <div class="announcement-filter" id="announcement-filters">
                            <!-- Table view filters -->
                            <div class="nav-filters" id="table-filters">
                                <a href="#"
                                    class="a-nav {{ request('category') == 'recent' || !request()->has('category') ? 'active' : '' }}"
                                    data-filter="recent">Recent</a>
                                <a href="#" class="a-nav {{ request('category') == 'announcement' ? 'active' : '' }}"
                                    data-filter="announcement">Announcements</a>
                                <a href="#" class="a-nav {{ request('category') == 'event' ? 'active' : '' }}"
                                    data-filter="event">Events</a>
                                <a href="#" class="a-nav {{ request('category') == 'news' ? 'active' : '' }}"
                                    data-filter="news">News</a>
                            </div>
                        </div>
                    </div>
                    <div class="search-bar">
                        <div class="ann-search">
                            <form method="GET" action="{{ route('Head.announcements.index') }}">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search announcements..." id="announcement-search-input">
                                @if (request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                <button type="submit" style="display:none"></button>
                            </form>
                        </div>
                        <button class="add-btn" onclick="openModal()">Add Announcement</button>
                        <button class="toggle-btn" id="toggle-view-btn">
                            <i class="fi fi-rr-table-layout" id="toggle-icon"></i>
                            <span id="toggle-label">Table View</span>
                        </button>
                    </div>
                </div>

                <!-- Table view -->
                <div class="announcement-list" id="announcements-list" style="margin-bottom:0;">
                    <div class="announcement-header">
                        <div class="announcement-col title">Title</div>
                        <div class="announcement-col category">Category</div>
                        <div class="announcement-col date">Date Posted</div>
                        <div class="announcement-col status">Status</div>
                        <div class="announcement-col actions">Actions</div>
                    </div>
                    <div class="announcement-table">
                        @forelse($announcements as $announcement)
                            <div class="announcement-card" id="announcement-card">
                                <div class="announcement-col title">{{ $announcement->title }}</div>
                                <div class="announcement-col category">{{ $announcement->category }}</div>
                                <div class="announcement-col date">
                                    {{ \Carbon\Carbon::parse($announcement->date_posted)->format('M d, Y') }}
                                </div>
                                <div class="announcement-col status">{{ ucfirst($announcement->status) }}</div>
                                <div class="announcement-col actions">
                                    <a href="#" title="View" class="view-btn"><i class='bx bx-show'></i></a>
                                    <a href="#" title="Edit" class="edit-btn"><i class='bx bx-edit'></i></a>
                                    <a href="#" title="Archive" class="archive-btn"><i class='bx bx-archive'></i></a>
                                </div>
                            </div>
                        @empty
                            <div class="no-announcements-cell">No announcements found.</div>
                        @endforelse
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

    @include('Head.Modal.announcementModal')

@endsection

<script>
    function openModal() {
        document.getElementById('announcementModal').style.display = 'block';
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
        let currentCategory = '{{ strtolower(request('category') ?? 'recent') }}';
        let calendar = null;
        let isTableView = true;

        function setView(table) {
            isTableView = table;
            if (table) {
                announcementsList.style.display = 'block';
                calendarView.style.display = 'none';
                tableFilters.style.display = 'flex';
                toggleBtn.classList.remove('active');
                toggleIcon.className = 'fi fi-rr-table-layout';
                toggleLabel.textContent = 'Table view';
            } else {
                announcementsList.style.display = 'none';
                calendarView.style.display = 'block';
                tableFilters.style.display = 'none';
                toggleBtn.classList.add('active');
                toggleIcon.className = 'bx bxs-calendar';
                toggleLabel.textContent = 'Calendar view';
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
            let url = `{{ route('Head.announcements.index') }}?` + params.toString();

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
            let calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            if (calendar) calendar.destroy();

            // normalize backend events
            let rawEvents = @json($events);

            let formattedEvents = rawEvents.map(item => {
                if (item.category === "Event") {
                    return {
                        title: item.title,
                        start: item.start_datetime ? new Date(item.start_datetime).toISOString() : item
                            .date_posted,
                        end: item.end_datetime ? new Date(item.end_datetime).toISOString() : null,
                        allDay: false,
                        category: item.category,
                        description: item.description,
                        color: "#10b981"
                    };
                } else {
                    return {
                        title: `[${item.category}] ${item.title}`,
                        start: item.date_posted,
                        end: null,
                        allDay: false,
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
                    let color = '#1ea7ff'; // default Announcement

                    if (category === 'announcement') color = '#1ea7ff';
                    if (category === 'event') color = '#10b981';

                    return {
                        html: `
                <div style="
                    background:${color};
                    color:#fff;
                    padding:4px 6px;
                    border-radius:6px;
                    font-size:0.85rem;
                    font-weight:500;
                    box-shadow:0 1px 3px rgba(0,0,0,0.15);
                    white-space:nowrap;
                    overflow:hidden;
                    text-overflow:ellipsis;
                ">
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
    let events = @json($events);
    console.log("Events from backend:", events);
</script>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
@endpush
