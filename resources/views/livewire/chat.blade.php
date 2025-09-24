<div class="chat-app">
    {{-- Sidebar --}}
    <div class="message-sidebar">
        <div class="message-sidebar-header">
            <div class="message-sidebar-header-title">
                <h2>Chats</h2>
                <a href="#" wire:click.prevent="startNewChat" id="newChatBtn" class="new-chat-btn">+</a>
            </div>
            <div class="search-wrapper">
                <input type="text" id="searchChat" placeholder="Search conversations..." class="search-input">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div class="chat-filters" id="chat-filters">
                <ul>
                    <li>
                        <a href="#"
                            class="chat-nav {{ request('category') == 'recent' || !request()->has('category') ? 'active' : '' }}"
                            data-filter="recent">All</a>
                    </li>
                    <li>
                        <a href="#" class="chat-nav {{ request('category') == 'unread' ? 'active' : '' }}"
                            data-filter="unread">Unread</a>
                    </li>
                    <li>
                        <a href="#" class="chat-nav {{ request('category') == 'counselor' ? 'active' : '' }}"
                            data-filter="counselor">Counselors</a>
                    </li>
                </ul>
            </div>

        </div>
        <div id="chatList" class="chat-list">
            @foreach ($users as $user)
                <div wire:click="selectUser({{ $user->id }})"
                    class="chat-item {{ $selectedUser && $selectedUser->id === $user->id ? 'active' : '' }}">
                    <img src="{{ asset('images/user/' . $user->profile_image) }}" class="user-img" alt="User">
                    <div class="chat-item-info">
                        <h3 class="chat-item-username">{{ $user->first_name }} {{ $user->last_name }}</h3>
                        <div class="chat-item-preview">
                            <span
                                class="chat-item-lastmessage
                        @if ($user->lastMessage && !$user->lastMessage->is_read && $user->lastMessage->sender_id !== Auth::id()) unread @endif">
                                @if ($user->lastMessage)
                                    @if ($user->lastMessage->sender_id === Auth::id())
                                        You: {{ Str::limit($user->lastMessage->message, 20) }}
                                    @else
                                        {{ Str::limit($user->lastMessage->message, 20) }}
                                    @endif
                                @else
                                    No messages yet.
                                @endif
                            </span>
                            <span class="chat-item-time"
                                data-time="{{ $user->lastMessage?->created_at?->diffForHumans() }}">
                                {{ $user->lastMessage?->created_at?->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    {{-- Main chat area --}}
    <div class="main-chat" id="mainChat">
        {{-- === NEW CHAT MODE === --}}
        @if ($newChatMode)
            <div class="new-chat-header">
                <span>To:</span>
                <input type="text" wire:model.live="searchQuery" class="user-search-input"
                    placeholder="Type a name...">

                <ul class="user-results">
                    @forelse($searchResults as $u)
                        <li wire:click="selectNewChatUser({{ $u->id }})"
                            style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <img src="{{ asset('images/user/' . $u->profile_image) }}" class="search-user-img">
                            <span>{{ $u->first_name }} {{ $u->last_name }}</span>
                        </li>
                    @empty
                        @if (strlen($searchQuery) > 1)
                            <li>No users found.</li>
                        @endif
                    @endforelse
                </ul>
            </div>

            <div class="messages-container" id="messagesContainer"></div>
        @endif

        {{-- === SELECTED CHAT MODE === --}}
        @if ($selectedUser && !$newChatMode)
            <div class="chat-header">
                <div class="chat-header-left">
                    <div>
                        <img src="{{ asset('images/user/' . $selectedUser->profile_image) }}" alt="User">
                    </div>
                    <div class="chat-header-info">
                        <h2>{{ $selectedUser->first_name }} {{ $selectedUser->last_name }}</h2>
                        <p>Online</p>
                    </div>
                </div>
                <div class="chat-header-right">
                    <i class="fi fi-sr-info"></i>
                </div>
            </div>

            <div class="messages-container" id="messagesContainer">
                @if ($messages->isEmpty())
                    <div class="no-messages-user">
                        <p>No messages yet. Start the conversation 👋</p>
                    </div>
                @else
                    @php $nextSenderId = null; @endphp

                    @foreach ($messages as $index => $message)
                        @php
                            $nextMessage = $messages[$index + 1] ?? null;
                            $nextSenderId = $nextMessage ? $nextMessage->sender_id : null;
                        @endphp

                        <div class="message-row {{ $message->sender_id === Auth::id() ? 'sent' : 'received' }}">
                            @if ($message->sender_id !== Auth::id())
                                {{-- Show avatar only if next message is from a different sender or this is last message --}}
                                @if ($nextSenderId !== $message->sender_id)
                                    <div class="sender-info">
                                        <img src="{{ asset('images/user/' . $selectedUser->profile_image) }}"
                                            class="sender-img" alt="User">
                                    </div>
                                @else
                                    <div class="sender-info" style="width: 40px;"></div> {{-- keep alignment --}}
                                @endif
                            @endif

                            <div class="message-data {{ $message->sender_id === Auth::id() ? 'sent' : 'received' }}">
                                <p>{{ $message->message }}</p>
                            </div>

                            <span class="message-time">{{ $message->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach


                @endif
            </div>


            <div class="chat-footer">
                <form wire:submit="submit" id="messageForm">
                    <input wire:model.live="newMessage" type="text" id="messageInput" placeholder="Type a message..."
                        required>
                    <button type="submit"><i class="fi fi-sr-paper-plane-top"></i></button>
                </form>
            </div>
        @endif
    </div>
    <div class="user-chat-profile-info" id="userChatProfileInfo"></div>
</div>

<script>
    function scrollMessagesToBottom(force = false) {
        const container = document.getElementById("messagesContainer");
        if (!container) return;

        const isNearBottom =
            container.scrollHeight - container.scrollTop - container.clientHeight < 100;

        if (force || isNearBottom) {
            container.scrollTo({
                top: container.scrollHeight,
                behavior: "smooth"
            });
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        // Initial scroll when page loads
        scrollMessagesToBottom(true);

        // After Livewire updates the DOM (new message, switched chat, etc.)
        Livewire.hook("message.processed", () => {
            scrollMessagesToBottom(true);
        });

        // If you also dispatch a browser event when switching chats
        window.addEventListener("chat:selected", () => {
            setTimeout(() => scrollMessagesToBottom(true), 50);
        });

        // Listen for custom event after message sent/received
        window.addEventListener("chat:messageSent", () => {
            setTimeout(() => scrollMessagesToBottom(true), 50);
        });
    });

    // Optionally, dispatch this event from Livewire after submit or receiving a message
    // In your Livewire component, after pushing a message:
    // $this->dispatchBrowserEvent('chat:messageSent');
</script>
