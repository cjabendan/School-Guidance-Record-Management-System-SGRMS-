@extends('layouts.counselor')
@section('title', 'SGRMS - Notifications')

@section('content')
<section id="content">
    @include('partials.navbar')

    <div class="notification-container">
        <div class="notification-header">
            <p>Stay updated with the latest announcements, events, and advisories.</p>
        </div>

        <div class="notification-board">
            @livewire('notification-board')
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

    // Listen for Livewire event to open modal
    if (window.Livewire) {
        window.Livewire.on('openNotificationModal', (data) => {
            // Ensure we log what’s received
            console.log('Modal Data:', data);

            const title = data.title ?? 'Notification';
            const description = data.description ?? 'No description available.';
            const category = data.category ?? 'General';
            const time = data.time ?? 'No date available';

            document.getElementById('notifTitle').textContent = title;
            document.getElementById('notifCategory').textContent = '📂 ' + category;
            document.getElementById('notifDescription').textContent = description;
            document.getElementById('notifTime').textContent = '📅 ' + time;
            modal.style.display = 'flex';
        });

    }

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
            </script>
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