@php use App\Helpers\NotificationHelper; @endphp

<div class="notify">
    <h3 class="notify-title">Notifications</h3>
    <div class="notify-list">
        @forelse($notifications as $notify)
            <a href="{{ $notify['link'] ? $notify['link'] . (str_contains($notify['link'], '?') ? '&' : '?') . 'notify=1' : '#' }}" class="notify-item" style="text-decoration:none; color:inherit;">
                <span class="notify-icon">
                    {{ $notify['icon'] ?? NotificationHelper::getIcon($notify['text'] ?? '') }}
                </span>
                <div>
                    <p class="notify-text">{{ $notify['text'] ?? 'Notification' }}</p>
                    <small class="notify-time">{{ $notify['time'] ?? '' }}</small>
                </div>
            </a>
        @empty
            <div class="notify-item notify-empty">
                <span class="notify-icon">🔔</span>
                <p class="notify-text">You're all caught up!</p>
                <small class="notify-time">No new notifications at this time.</small>
            </div>
        @endforelse
    </div>
</div>
