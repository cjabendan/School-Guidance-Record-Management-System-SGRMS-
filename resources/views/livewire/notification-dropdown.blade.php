<div class="notification-wrapper" wire:poll.3s="pollNotifications">
    <a href="#" class="notification-bell" wire:click="toggleDropdown">
        <i class='bx bxs-bell'></i>
        @if ($unreadCount > 0)
            <span class="badge">{{ $unreadCount }}</span>
        @endif
    </a>

    @if ($showDropdown)
        <div class="notification-dropdown" style="max-height:400px; overflow-y:auto;">
            <div class="notificationDropdownContent">
                <div class="notify-list" style="overflow:visible; max-height:none;">
                    @forelse($notifications as $notification)
                        <a href="{{ route(
                            Auth::user()->role === 'head' || Auth::user()->role === 'admin'
                                ? 'Head.notify.notification'
                                : (Auth::user()->role === 'counselor'
                                    ? 'Counselor.notify.notification'
                                    : (Auth::user()->role === 'parent'
                                        ? 'Parent.notify.notification'
                                        : 'Student.notify.notification'))
                        ) }}?notify_id={{ $notification['id'] ?? '' }}"
                        class="notify-item {{ isset($notification['is_read']) && $notification['is_read'] ? 'read' : 'unread' }}"
                        style="text-decoration:none; color:inherit;"
                        data-notify-id="{{ $notification['id'] ?? '' }}"
                        wire:key="notification-dropdown-{{ $notification['id'] }}">
                            <span class="notify-icon">📢</span>
                            <div>
                                <p class="notify-text {{ isset($notification['is_read']) && $notification['is_read'] ? 'notify-text-read' : '' }}">
                                    {{ $notification['message'] }}
                                </p>
                                <small class="notify-time">
                                    {{ isset($notification['timestamp'])
                                        ? \Carbon\Carbon::parse($notification['timestamp'])->diffForHumans()
                                        : 'just now' }}
                                </small>
                            </div>
                        </a>
                    @empty
                        <div class="notify-empty">
                            <span class="notify-icon">🔔</span>
                            <p class="notify-text">You're all caught up!</p>
                            <small class="notify-time">No new notifications at this time.</small>
                        </div>
                    @endforelse
                </div>
                {{-- See previous notifications button --}}
                @if(!$showAll && count($notifications) >= 10)
                    <div style="text-align:center; margin-top:16px;">
                        <button wire:click="showAllNotifications" class="see-more-btn" style="
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                            background: #f3f4f6;
                            color: #2563eb;
                            border: none;
                            border-radius: 24px;
                            padding: 10px 24px;
                            font-size: 16px;
                            font-weight: 500;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                            cursor: pointer;
                            transition: background 0.2s, color 0.2s;
                        "
                        onmouseover="this.style.background='#e0e7ff';this.style.color='#1d4ed8'"
                        onmouseout="this.style.background='#f3f4f6';this.style.color='#2563eb'"
                        >
                            <span style="font-size:20px;">🔎</span>
                            See previous notifications
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.notification-dropdown .notify-item[data-notify-id]').forEach(function (item) {
        item.addEventListener('click', function (e) {
            const notifyId = this.getAttribute('data-notify-id');
            const notifyText = this.querySelector('.notify-text');
            if (!notifyId) return;

            e.preventDefault();

            fetch("{{ route('notify.markAsRead') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: notifyId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // ✅ Mark visually as read
                    this.classList.remove('unread');
                    this.classList.add('read');
                    if (notifyText) {
                        notifyText.classList.add('notify-text-read');
                    }

                    // ✅ Tell Livewire to reorder and update
                    Livewire.emit('notificationRead', notifyId);

                    // ✅ Redirect after small delay
                    setTimeout(() => {
                        window.location.href = this.href;
                    }, 150);
                }
            })
            .catch(err => console.error('Error marking as read:', err));
        });
    });
});
</script>
