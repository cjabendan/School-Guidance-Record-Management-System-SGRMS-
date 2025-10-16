@php use App\Helpers\NotificationHelper; @endphp

<div class="notify">
    <h3 class="notify-title">Notifications</h3>
    <div class="notify-list">
        @forelse($notifications as $index => $notify)
            <a href="{{ $notify['link'] ? $notify['link'] . (str_contains($notify['link'], '?') ? '&' : '?') . 'notify_id=' . $notify['id'] : '#' }}"
               class="notify-item"
               style="text-decoration:none; color:inherit;"
               data-notify-index="{{ $index }}"
               data-notify-id="{{ $notify['id'] ?? '' }}">
                <span class="notify-icon">
                    {{ $notify['icon'] ?? NotificationHelper::getIcon($notify['text'] ?? '') }}
                </span>
                <div>
                    <p class="notify-text{{ isset($notify['is_read']) && $notify['is_read'] ? ' notify-text-read' : '' }}">{{ $notify['text'] ?? 'Notification' }}</p>
                    <small class="notify-time">{{ $notify['time'] ?? '' }}</small>
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.notification-dropdown .notify-item[data-notify-id]').forEach(function(item) {
        item.addEventListener('click', function(e) {
            const notifyId = this.getAttribute('data-notify-id');
            if (notifyId) {
                fetch("{{ route('notify.markAsRead') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: notifyId })
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        window.location.href = this.href;
                    }
                });
                e.preventDefault();
            }
        });
    });
});
</script>