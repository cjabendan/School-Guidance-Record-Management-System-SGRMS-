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
                            <a href="{{ route('Head.announcements.index', ['category' => 'recent']) }}"
                                class="a-nav {{ request('category') == 'recent' || !request()->has('category') ? 'active' : '' }}">Recent</a>
                            <a href="{{ route('Head.announcements.index', ['category' => 'Announcements']) }}"
                                class="a-nav {{ request('category') == 'Announcements' ? 'active' : '' }}">Announcements</a>
                            <a href="{{ route('Head.announcements.index', ['category' => 'Events']) }}"
                                class="a-nav {{ request('category') == 'Events' ? 'active' : '' }}">Events</a>
                            <a href="{{ route('Head.announcements.index', ['category' => 'News']) }}"
                                class="a-nav {{ request('category') == 'News' ? 'active' : '' }}">News</a>
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
                    </div>

                </div>
                <div class="announcement-list" id="announcements-list">
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
                                    <a href="#" title="View"><span style="font-size:18px;">👁️</span></a>
                                    <a href="#" title="Edit" style="margin-left:10px;"><span
                                            style="font-size:18px;">✏️</span></a>
                                    <a href="#" title="Delete" style="margin-left:10px;"><span
                                            style="font-size:18px;">🗑️</span></a>
                                </div>
                            </div>
                        @empty
                            <div class="no-announcements-cell">
                                No announcements found.
                            </div>
                        @endforelse
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
        const filters = document.getElementById('announcement-filters');
        let timeout = null;

        // AJAX for search
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(fetchAnnouncements, 400);
            });
        }

        // AJAX for filters
        filters.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                // manually set active class
                filters.querySelectorAll('a').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                fetchAnnouncements(this.getAttribute('href'));
            });
        });

        // function to fetch data
        function fetchAnnouncements(url = `{{ route('Head.announcements.index') }}`) {
            // if no custom url passed, build from search input
            if (!url) {
                let search = searchInput.value;
                let params = new URLSearchParams();
                if (search.trim() !== '') params.append('search', search.trim());
                url = `{{ route('Head.announcements.index') }}?` + params.toString();
            }

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // announcements
                    const newList = doc.getElementById('announcements-list');
                    if (newList) {
                        announcementsList.innerHTML = newList.innerHTML;
                    }

                });

        }

        // Optional: Close modal when clicking outside modal content
        window.onclick = function(event) {
            if (event.target == announcementModal) {
                announcementModal.style.display = "none";
            }
        }
    });
</script>
