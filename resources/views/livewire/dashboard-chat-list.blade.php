<div id="chatList" class="chat-list" wire:poll.5s="loadChatList">
    @foreach ($users as $user)
        <a href="{{ route('Head.messages', ['user' => $user->id]) }}"
            class="chat-item">
            <img src="{{ asset('images/user/' . $user->profile_image) }}" class="user-img" alt="User">
            <div class="chat-item-info">
                <h3 class="chat-item-username">
                    {{ $user->first_name }} {{ $user->last_name }}
                    @if (in_array($user->role, ['admin', 'counselor']))
                        <i class="fi fi-sr-badge-check official-badge" title="Verified"></i>
                    @endif
                </h3>
                <div class="chat-item-preview">
                    <span class="chat-item-lastmessage
                        @if ($user->lastMessage && !$user->lastMessage->is_read && $user->lastMessage->sender_id !== Auth::id()) unread @endif">
                        @if ($user->lastMessage)
                            @if ($user->lastMessage->sender_id === Auth::id())
                                You: {{ Str::limit($user->lastMessage->message, 18) }}
                            @else
                                {{ Str::limit($user->lastMessage->message, 18) }}
                            @endif
                        @else
                            No messages yet.
                        @endif
                    </span>
                    <span class="chat-item-time" data-time="{{ $user->lastMessage?->created_at }}">
                        {{ $user->lastMessage?->created_at?->diffForHumans() }}
                    </span>
                </div>
            </div>
        </a>
    @endforeach
</div>