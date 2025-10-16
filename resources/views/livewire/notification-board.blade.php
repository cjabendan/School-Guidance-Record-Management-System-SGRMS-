<div wire:poll.3s="refreshNotifications">
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
                    wire:click="markAsReadAndShowModal({{ $notification->id }})"
                    wire:key="notification-{{ $notification->id }}"
                    style="cursor:pointer;"
                >
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
    {{-- See previous notifications button --}}
    @if(!$showAll && ($new->count() + $today->count() + $earlier->count()) >= 10)
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

    @if(!$new->count() && !$today->count() && !$earlier->count())
        <p style="text-align:center; color:#6b7280; padding:30px 0;">
            You're all caught up — no notifications right now 🎉
        </p>
    @endif
</div>
