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
                                <a href="{{ route('Head.announcements.index', ['category' => 'recent']) }}"
                                    class="a-nav {{ request('category') == 'recent' || !request()->has('category') ? 'active' : '' }}"
                                    data-filter="recent">Recent</a>
                                <a href="{{ route('Head.announcements.index', ['category' => 'Announcements']) }}"
                                    class="a-nav {{ request('category') == 'Announcements' ? 'active' : '' }}"
                                    data-filter="Announcements">Announcements</a>
                                <a href="{{ route('Head.announcements.index', ['category' => 'Events']) }}"
                                    class="a-nav {{ request('category') == 'Events' ? 'active' : '' }}"
                                    data-filter="Events">Events</a>
                                <a href="{{ route('Head.announcements.index', ['category' => 'News']) }}"
                                    class="a-nav {{ request('category') == 'News' ? 'active' : '' }}"
                                    data-filter="News">News</a>
                            </div>
                            <!-- Calendar view filters 
                            <div class="nav-filters" id="calendar-filters" style="display:none;">
                                <a href="#" class="a-nav active" data-filter="day">Day</a>
                                <a href="#" class="a-nav" data-filter="week">Week</a>
                                <a href="#" class="a-nav" data-filter="month">Month</a>
                            </div>
                            -->
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
                                    {{ \Carbon\Carbon::parse($announcement->date_posted)->format('M d, Y') }}</div>
                                <div class="announcement-col status">{{ ucfirst($announcement->status) }}</div>
                                <div class="announcement-col actions">
                                    <a href="#" title="View" class="view-btn">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    <a href="#" title="Edit" class="edit-btn">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <a href="#" title="Archive" class="archive-btn">
                                        <i class='bx bx-archive'></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="no-announcements-cell">
                                No announcements found.
                            </div>
                        @endforelse
                    </div>
                </div>
                <div id="calendar-view" style="display:none; width:100%; margin-bottom:0;">
                    <div id="calendar" style="margin:0;"></div>
                    <div id="calendar-empty-message"
                        style="display:none; text-align:center; color:#888; font-size:1.1rem; padding:32px;">No events to
                        display.</div>
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
        const filters = document.getElementById('announcement-filters');
        let timeout = null;

        // Toggle view logic
        const tableFilters = document.getElementById('table-filters');
        // State: true = table, false = calendar
        let isTableView = true;

        function setView(table) {
            isTableView = table;
            if (table) {
                announcementsList.style.display = 'block';
                calendarView.style.display = 'none';
                tableFilters.style.display = 'flex';
                toggleBtn.classList.add('active');
                toggleIcon.className = 'fi fi-rr-table-layout';
                toggleLabel.textContent = 'Table view';
            } else {
                announcementsList.style.display = 'none';
                calendarView.style.display = 'block';
                tableFilters.style.display = 'none';
                toggleBtn.classList.add('active');
                toggleIcon.className = 'bx bxs-calendar';
                toggleLabel.textContent = 'Calendar view';
                setTimeout(function() {
                    renderCalendar('month');
                }, 0);
            }
        }
        // Initial view
        setView(true);
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            setView(!isTableView);
        });

        // AJAX for search
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(fetchAnnouncements, 400);
            });
        }

        // AJAX for table filters (recent/announcements/events/news)
        tableFilters.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                // Only handle filter if table view is active
                if (!isTableView) return;
                e.preventDefault();
                tableFilters.querySelectorAll('a').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                // AJAX fetch announcements for selected filter
                let category = this.dataset.filter;
                let url =
                    `{{ route('Head.announcements.index') }}?category=${encodeURIComponent(category)}`;
                fetchAnnouncements(url);
            });
        });
    // Calendar nav filters removed; no JS needed for them

        // function to fetch data
        function fetchAnnouncements(url = `{{ route('Head.announcements.index') }}`) {
            let search = searchInput ? searchInput.value : '';
            let params = new URLSearchParams();
            if (search.trim() !== '') params.append('search', search.trim());
            url = `{{ route('Head.announcements.index') }}?` + params.toString();

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

        // FullCalendar integration
        function renderCalendar(viewType = 'month') {
            let calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            let initialView = 'dayGridMonth';
            if (viewType === 'day') initialView = 'timeGridDay';
            if (viewType === 'week') initialView = 'timeGridWeek';
            if (viewType === 'month') initialView = 'dayGridMonth';

            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: initialView,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                events: '/api/announcements', 
                selectable: true,
                editable: false,
                slotMinTime: "06:00:00", 
                slotMaxTime: "18:00:00", 
                allDaySlot: false, 
                nowIndicator: true, 
                eventDidMount: function(info) {
                    // Color code by category
                    const category = info.event.extendedProps.category;
                    if (category === 'announcement') {
                        info.el.style.backgroundColor = '#1ea7ff';
                        info.el.style.color = '#000';
                    } else if (category === 'event') {
                        info.el.style.backgroundColor = '#10b981';
                        info.el.style.color = '#fff';
                    } else if (category === 'news') {
                        info.el.style.backgroundColor = '#fd7238';
                        info.el.style.color = '#fff';
                    }
                }
            });

            calendar.render();
        }
    });
</script>
