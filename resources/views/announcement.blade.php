@extends('layouts.app')

@section('content')
    <div class="announcement-container">
        <div class="announcement-header">
            <h2>Announcement Board</h2>
            <p>Stay informed with the latest news, updates, and important announcements from your school. </p>
        </div>
        <div class="announcement-board">
            <div class="announcement-nav">
                <div class="announcement-filter" id="announcement-filters">
                    <a href="{{ route('announcements.index', ['category' => 'recent']) }}"
                        class="a-nav {{ request('category') == 'recent' || !request()->has('category') ? 'active' : '' }}">Recent</a>
                    <a href="{{ route('announcements.index', ['category' => 'Announcements']) }}"
                        class="a-nav {{ request('category') == 'Announcements' ? 'active' : '' }}">Announcements</a>
                    <a href="{{ route('announcements.index', ['category' => 'Events']) }}"
                        class="a-nav {{ request('category') == 'Events' ? 'active' : '' }}">Events</a>
                    <a href="{{ route('announcements.index', ['category' => 'News']) }}"
                        class="a-nav {{ request('category') == 'News' ? 'active' : '' }}">News</a>
                </div>
                <div class="announcement-search">
                    <form method="GET" action="{{ route('announcements.index') }}">
                        <i class="fi fi-br-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search announcements..." id="announcement-search-input">
                        @if (request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <button type="submit" style="display:none"></button>
                    </form>
                </div>
            </div>
            <div class="announcements-list" id="announcements-list">
                @foreach ($announcements as $announcement)
                    <x-announcement-card :announcement="$announcement" />
                @endforeach
            </div>
            @if ($announcements->hasPages())
                <x-pagination :paginator="$announcements" />
            @endif
        </div>
    </div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('announcement-search-input');
        const announcementsList = document.getElementById('announcements-list');
        const filters = document.getElementById('announcement-filters');
        let timeout = null;

        // AJAX for search
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(fetchAnnouncements, 400);
        });

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
        function fetchAnnouncements(url = `{{ route('announcements.index') }}`) {
            // if no custom url passed, build from search input
            if (!url) {
                let search = searchInput.value;
                let params = new URLSearchParams();
                if (search.trim() !== '') params.append('search', search.trim());
                url = `{{ route('announcements.index') }}?` + params.toString();
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

                    // pagination
                    const newPagination = doc.querySelector('.pagination');
                    const oldPagination = document.querySelector('.pagination');
                    if (newPagination && oldPagination) {
                        oldPagination.innerHTML = newPagination.innerHTML;
                    } else if (oldPagination) {
                        oldPagination.innerHTML = '';
                    }
                });
        }
    });
</script>

@endsection
