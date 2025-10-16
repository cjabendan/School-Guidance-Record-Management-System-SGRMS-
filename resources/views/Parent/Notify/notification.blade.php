@extends('layouts.parent')
@section('title', 'SGRMS - Notifications')

@section('content')
<section id="content">
    @include('partials.navbar')

    <div class="notification-container">
        <div class="notification-header">
            <p>Stay updated with the latest announcements, events, and advisories.</p>
        </div>

        <div class="notification-board">
            {{-- Unified and user-friendly layout --}}
            @php
                $sections = [
                    'New Notifications' => $new,
                    'Recent Today' => $today,
                    'Earlier Updates' => $earlier,
                ];
            @endphp

            @foreach($sections as $title => $list)
                @if($list->count())
                    <h3 class="notif-section-title">{{ $title }}</h3>
                    <div class="notifications-list">
                        @foreach ($list as $notification)
                            <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}"
                                 data-id="{{ $notification->id }}"
                                 data-title="{{ $notification->relatedAnnouncement->title ?? 'Announcement' }}"
                                 data-description="{{ $notification->relatedAnnouncement->description ?? 'No description available.' }}"
                                 data-time="{{ \Carbon\Carbon::parse($notification->timestamp)->format('F d, Y g:i A') }}"
                                 data-category="{{ $notification->relatedAnnouncement->category ?? 'General' }}">
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
            @endforeach

            @if(!$new->count() && !$today->count() && !$earlier->count())
                <p style="text-align:center; color:#6b7280; padding:30px 0;">
                    You're all caught up — no notifications right now 🎉
                </p>
            @endif
        </div>
    </div>
</section>

{{-- Modal for notification details --}}
<div id="notificationModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" id="closeNotificationModal">&times;</span>
        <h3 id="notifTitle"></h3>
        <p id="notifCategory" style="font-size:0.9rem; color:#2563eb; margin-top:-8px;"></p>
        <p id="notifDescription" style="white-space:pre-wrap; margin-top:10px;"></p>
        <small id="notifTime" style="color:gray;"></small>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('notificationModal');
    const closeBtn = document.getElementById('closeNotificationModal');

    // Handle click on notification
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const desc = this.getAttribute('data-description');
            const time = this.getAttribute('data-time');
            const category = this.getAttribute('data-category');

            // Fill modal
            document.getElementById('notifTitle').textContent = title;
            document.getElementById('notifCategory').textContent = '📂 ' + category;
            document.getElementById('notifDescription').textContent = desc;
            document.getElementById('notifTime').textContent = '📅 ' + time;

            modal.style.display = 'flex';

            // Mark as read visually
            this.classList.remove('unread');
            this.classList.add('read');

            // Mark as read via AJAX
            fetch('{{ route("notify.markAsRead") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            }).then(res => res.json());
        });
    });

    // Close modal
    closeBtn.onclick = () => modal.style.display = 'none';
    window.onclick = e => { if (e.target == modal) modal.style.display = 'none'; };
});
</script>
@endsection

<style>
    /* ============================
   Notification Modal Styles
============================ */
.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(17, 24, 39, 0.6); /* dark overlay */
    backdrop-filter: blur(4px);
    justify-content: center;   /* centers horizontally */
    align-items: center;       /* centers vertically */
    animation: fadeIn 0.3s ease;
}

/* Main content box */
.modal-content {
    background: #ffffff;
    border-radius: 16px;
    width: 90%;
    max-width: 600px; /* larger for better readability */
    padding: 30px 40px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.25);
    position: relative;
    animation: slideUp 0.3s ease;
}

/* Close button */
.modal-content .close {
    position: absolute;
    top: 14px;
    right: 20px;
    color: #6b7280;
    font-size: 28px;
    font-weight: 600;
    cursor: pointer;
    transition: color 0.2s ease;
}
.modal-content .close:hover {
    color: #111827;
}

/* Typography */
.modal-content h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 6px;
}

#notifCategory {
    color: #2563eb;
    font-weight: 500;
    margin-bottom: 10px;
}

#notifDescription {
    font-size: 1rem;
    line-height: 1.6;
    color: #374151;
    margin-top: 10px;
    white-space: pre-wrap;
}

#notifTime {
    display: block;
    margin-top: 15px;
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes slideUp {
    from { transform: translateY(40px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-content {
        width: 92%;
        max-width: 95%;
        padding: 25px;
        border-radius: 12px;
    }
    .modal-content h3 {
        font-size: 1.2rem;
    }
    #notifDescription {
        font-size: 0.95rem;
    }
}

</style>