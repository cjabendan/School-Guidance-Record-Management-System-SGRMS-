@extends('layouts.main')
@section('title', 'SGRMS - School Guidance Records Management System')

@section('content')
<section id="content">
    @include('partials.navbar')

    <div class="notification-container">
        <div class="notification-header">
            <p>Your Notifications — Stay Updated</p>
        </div>

        <div class="notification-board">

            {{-- 🔵 New --}}
            @if($new->count())
                <h3 class="notif-section-title">New</h3>
                <div class="notifications-list">
                    @foreach ($new as $notification)
                        <div class="notification-item unread">
                            <span class="notification-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="notification-svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 0 0 
                                            5.454-1.31A8.967 8.967 0 0 1 
                                            18 9.75V9A6 6 0 0 0 6 9v.75a8.967 
                                            8.967 0 0 1-2.312 6.022c1.733.64 
                                            3.56 1.085 5.455 1.31m5.714 0a24.255 
                                            24.255 0 0 1-5.714 0m5.714 0a3 3 
                                            0 1 1-5.714 0" />
                                </svg>
                            </span>
                            <p class="notification-message">{{ $notification->message }}</p>
                            <small class="notification-time">
                                {{ \Carbon\Carbon::parse($notification->timestamp)->diffForHumans() }}
                            </small>
                        </div>

                    @endforeach
                </div>
            @endif

            {{-- 📅 Today --}}
            @if($today->count())
                <h3 class="notif-section-title">Today</h3>
                <div class="notifications-list">
                    @foreach ($today as $notification)
                        <div class="notification-item read">
                            <span class="notification-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="notification-svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 0 0 
                                            5.454-1.31A8.967 8.967 0 0 1 
                                            18 9.75V9A6 6 0 0 0 6 9v.75a8.967 
                                            8.967 0 0 1-2.312 6.022c1.733.64 
                                            3.56 1.085 5.455 1.31m5.714 0a24.255 
                                            24.255 0 0 1-5.714 0m5.714 0a3 3 
                                            0 1 1-5.714 0" />
                                </svg>
                            </span>
                            <p class="notification-message">{{ $notification->message }}</p>
                            <small class="notification-time">
                                Today • {{ \Carbon\Carbon::parse($notification->timestamp)->diffForHumans() }}
                            </small>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- ⏳ Earlier --}}
            @if($earlier->count())
                <h3 class="notif-section-title">Earlier</h3>
                <div class="notifications-list">
                    @foreach ($earlier as $notification)
                        <div class="notification-item read">
                            <span class="notification-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="notification-svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 0 0 
                                            5.454-1.31A8.967 8.967 0 0 1 
                                            18 9.75V9A6 6 0 0 0 6 9v.75a8.967 
                                            8.967 0 0 1-2.312 6.022c1.733.64 
                                            3.56 1.085 5.455 1.31m5.714 0a24.255 
                                            24.255 0 0 1-5.714 0m5.714 0a3 3 
                                            0 1 1-5.714 0" />
                                </svg>
                            </span>
                            <p class="notification-message">{{ $notification->message }}</p>
                            <small class="notification-time">
                                {{ \Carbon\Carbon::parse($notification->timestamp)->format('M d, Y') }}
                                • {{ \Carbon\Carbon::parse($notification->timestamp)->diffForHumans() }}
                            </small>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</section>
@endsection
