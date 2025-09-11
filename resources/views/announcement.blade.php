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
                    <a href="#"
                        class="a-nav {{ request('category') == 'recent' || !request()->has('category') ? 'active' : '' }}"
                        data-category="recent">Recent</a>
                    <a href="#" class="a-nav {{ request('category') == 'announcement' ? 'active' : '' }}"
                        data-category="announcement">Announcements</a>
                    <a href="#" class="a-nav {{ request('category') == 'event' ? 'active' : '' }}"
                        data-category="event">Events</a>
                    <a href="#" class="a-nav {{ request('category') == 'news' ? 'active' : '' }}"
                        data-category="news">News</a>
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
            <!-- Announcement View Modal -->
            <div class="view-modal-announcement" id="announcement-view-modal">
                <div class="view-modal-content-announcement" id="announcement-view-modal-content">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('announcement-search-input');
            const announcementsList = document.getElementById('announcements-list');
            const filters = document.getElementById('announcement-filters');
            let timeout = null;
            let currentCategory = '{{ request('category') ?? 'recent' }}';

            // Modal logic
            window.openAnnouncementModal = function(id) {
                console.log("Opening announcement with id:", id);
                const modal = document.getElementById('announcement-view-modal');
                const modalContent = document.getElementById('announcement-view-modal-content');
                modal.style.display = 'flex';

                fetch(`{{ url('/announcements/view') }}/${id}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to load announcement');
                        return response.text();
                    })
                    .then(html => {
                        console.log("HTML:", html);
                        modalContent.innerHTML = html;
                        const closeBtn = modalContent.querySelector('.close-modal-btn');
                        if (closeBtn) {
                            closeBtn.onclick = function() {
                                modal.style.display = 'none';
                                modalContent.innerHTML = '';
                            };
                        }
                    })
                    .catch(err => {
                        modalContent.innerHTML = `<p style="color:red;">Error: ${err.message}</p>`;
                    });
            };


            // Close modal when clicking outside content
            document.addEventListener('click', function(e) {
                const modal = document.getElementById('announcement-view-modal');
                const modalContent = document.getElementById('announcement-view-modal-content');
                if (modal.style.display === 'flex' && !modalContent.contains(e.target) && !e.target.closest(
                        '.announcement-box')) {
                    modal.style.display = 'none';
                    modalContent.innerHTML = '';
                }
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

            // AJAX for filters
            filters.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    filters.querySelectorAll('a').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    currentCategory = this.dataset.category;
                    fetchAnnouncements(currentCategory, searchInput.value);
                });
            });

            function fetchAnnouncements(category = 'recent', search = '') {
                let params = new URLSearchParams();
                if (category && category !== 'recent') params.append('category', category);
                if (search && search.trim() !== '') params.append('search', search.trim());
                let url = `{{ route('announcements.index') }}?` + params.toString();

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
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
