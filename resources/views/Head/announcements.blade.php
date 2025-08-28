@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')
    <!-- MAIN CONTENT -->
    <section id="content">
        @include('partials.navbar')
        <div class="wrapper">
            <div class="announcement-container">
                <div class="announcement-management">
                    <button class="add-btn">Add Announcement</button>
                    <form method="GET" id="appointmentFilterForm">
                        <select class="dropdown" name="filter"
                            onchange="document.getElementById('appointmentFilterForm').submit()">
                            <option value="Recent" {{ request('filter') == 'Recent' ? 'selected' : '' }}>Recent
                            </option>
                            <option value="Announcements" {{ request('filter') == 'Announcements' ? 'selected' : '' }}>
                                Announcements</option>
                            <option value="Events" {{ request('filter') == 'Events' ? 'selected' : '' }}>This
                                Events
                            </option>
                            <option value="News" {{ request('filter') == 'News' ? 'selected' : '' }}>This
                                News
                            </option>
                        </select>
                    </form>
                </div>
                <div class="announcement-list">
                    <div class="announcement-list-header">
                        <div class="announcement-col title">Title</div>
                        <div class="announcement-col category">Category</div>
                        <div class="announcement-col date">Date Posted</div>
                        <div class="announcement-col status">Status</div>
                        <div class="announcement-col actions">Actions</div>
                    </div>
                    @forelse($announcements as $announcement)
                        <div class="announcement-card">
                            <div class="announcement-col title">{{ $announcement->title }}</div>
                            <div class="announcement-col category">{{ $announcement->category }}</div>
                            <div class="announcement-col date">{{ \Carbon\Carbon::parse($announcement->date_posted)->format('M d, Y') }}</div>
                            <div class="announcement-col status">{{ ucfirst($announcement->status) }}</div>
                            <div class="announcement-col actions">
                                <a href="#" title="View"><span style="font-size:18px;">👁️</span></a>
                                <a href="#" title="Edit" style="margin-left:10px;"><span style="font-size:18px;">✏️</span></a>
                                <a href="#" title="Delete" style="margin-left:10px;"><span style="font-size:18px;">🗑️</span></a>
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
    </section>

@endsection
