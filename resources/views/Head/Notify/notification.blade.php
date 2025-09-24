@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

<section id="content">
    @include('partials.navbar')

    <div class="notification-container">
        <div class="notification-header">
            <p>Here are all your latest notifications.</p>
        </div>

        <div class="notification-board">
            {{-- Notifications List --}}
            <div class="notifications-list">
                @forelse ($notifications as $notification)
                    <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}">
                        <p class="notification-message">{{ $notification->message }}</p>
                        <small class="notification-time">
                            {{ \Carbon\Carbon::parse($notification->timestamp)->diffForHumans() }}
                        </small>
                    </div>
                @empty
                    <p>No notifications available.</p>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if(isset($notifications) && $notifications->hasPages())
                <x-pagination :paginator="$notifications" />
            @endif
        </div>
    </div>
</section>

@endsection
