<div class="chat-app">
    <div class="message-sidebar">
        <div class="message-sidebar-header">
            <div class="message-sidebar-header-title">
                <h2>Chats</h2>
                <a href="#" wire:click.prevent="startNewChat" id="newChatBtn" class="new-chat-btn">+</a>
            </div>
            <div class="search-wrapper">
                <input type="text" id="searchChat" wire:model.live="conversationSearch"
                    placeholder="Search conversations..." class="search-input">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <ul class="convo-results">
                    @forelse($conversationSearchResults as $u)
                        <li wire:click="selectNewChatUser({{ $u->id }})"
                            style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <img src="{{ asset('images/user/' . $u->profile_image) }}" class="search-user-img">
                            <span>{{ $u->first_name }} {{ $u->last_name }}</span>
                        </li>
                    @empty
                        @if (strlen($conversationSearch) > 1)
                            <div class="no-result-convo">
                                <p class="no-results">Nothing found.</p>
                                <p>Try searching for different keywords.</p>
                            </div>
                        @endif
                    @endforelse
                </ul>
            </div>

            @if (empty($conversationSearch))
                <div class="chat-filters" id="chat-filters">
                    <ul>
                        <li>
                            <a href="#" wire:click.prevent="setFilter('recent')"
                                class="chat-nav {{ $filter == 'recent' ? 'active' : '' }}" data-filter="recent">All</a>
                        </li>
                        <li>
                            <a href="#" wire:click.prevent="setFilter('unread')"
                                class="chat-nav {{ $filter == 'unread' ? 'active' : '' }}"
                                data-filter="unread">Unread</a>
                        </li>
                        <li>
                            <a href="#" wire:click.prevent="setFilter('counselor')"
                                class="chat-nav {{ $filter == 'counselor' ? 'active' : '' }}"
                                data-filter="counselor">Counselors</a>
                        </li>
                    </ul>
                </div>
            @endif

        </div>
        @if (empty($conversationSearch))
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
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="main-chat" id="mainChat">
        <div class="chat-container">
            @if ($newChatMode)
                <div class="new-chat-header">
                    <span>To:</span>
                    <input type="text" wire:model.live="searchQuery" class="user-search-input"
                        placeholder="Type a name...">

                    <ul class="user-results">
                        @forelse($newChatSearchResults as $u)
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
                        <i class="fi fi-sr-info" wire:click="toggleUserProfile"></i>
                    </div>
                </div>

                <div class="messages-container" id="messagesContainer">
                    @if ($messages->isEmpty())
                        <div class="no-messages-user">
                            <p class="no-conversation-placeholder">No messages yet.
                                <br>Start the conversation 👋
                            </p>
                        </div>
                    @else
                        @php
                            // Find the latest message sent by me that is read
                            $latestSeenMessageId = null;
                            foreach ($messages as $msg) {
                                if ($msg->sender_id === Auth::id() && $msg->is_read) {
                                    $latestSeenMessageId = $msg->id;
                                    break; // messages are in reverse order (latest first)
                                }
                            }
                        @endphp

                        @foreach ($messages as $index => $message)
                            @php
                                $prevMessage = $messages[$index - 1] ?? null;
                                $prevSenderId = $prevMessage ? $prevMessage->sender_id : null;

                                $isToday = $message->created_at->isToday();
                                $tooltip = $isToday
                                    ? $message->created_at->format('h:i A')
                                    : $message->created_at->format('M d, Y');

                                $isSentByMe = $message->sender_id === Auth::id();
                            @endphp
                            {{-- Show seen status only for the latest seen message --}}
                            @if ($isSentByMe && $message->id === $latestSeenMessageId)
                                <div class="seen-status">
                                    <img src="{{ asset('images/user/' . $selectedUser->profile_image) }}"
                                        class="seen-img" title="Seen {{ $tooltip }}" alt="Seen">
                                </div>
                            @endif

                            <div class="message-row {{ $isSentByMe ? 'sent' : 'received' }}">
                                {{-- Sender Profile Image for RECEIVED messages --}}
                                @if (!$isSentByMe)
                                    @if ($prevSenderId !== $message->sender_id)
                                        <div class="sender-info">
                                            <img src="{{ asset('images/user/' . $selectedUser->profile_image) }}"
                                                class="sender-img" alt="User">
                                        </div>
                                    @else
                                        <div class="sender-info" style="width: 40px;"></div>
                                    @endif
                                @endif

                                <div class="message-data {{ $isSentByMe ? 'sent' : 'received' }}"
                                    title="{{ $tooltip }}">
                                    <p>{{ $message->message }}</p>
                                </div>
                            </div>
                        @endforeach

                    @endif
                </div>


                <div class="chat-footer">
                    <form wire:submit="submit" id="messageForm">
                        <input wire:model.live="newMessage" type="text" id="messageInput"
                            placeholder="Type a message..." required>
                        <button type="submit"><i class="fi fi-sr-paper-plane-top"></i></button>
                    </form>
                </div>

            @endif
            @if ($users->isEmpty() && !$newChatMode && !$selectedUser)
                <div class="no-conversation-holder">
                    <img src="{{ asset('images/img/no-convo.png') }}" alt="No convo" class="no-conversation-image">
                    <p class="no-conversation-placeholder">
                        Looks like you don't have any conversations yet.<br>Start a new conversation!
                    </p>
                </div>
            @endif

        </div>
    </div>
    @if ($selectedUser)
        <div class="user-chat-profile-info {{ $showUserProfile ? 'active' : '' }}" id="userChatProfileInfo"
            data-user-id="{{ $selectedUser->id }}">
            <div class="user-chat-profile-container">
                <div class="user-profile-img-wrapper">
                    <img src="{{ asset('images/user/' . $selectedUser->profile_image) }}" class="user-profile-img"
                        alt="User">
                </div>
                <div class="user-chat-header-info">
                    <h2 class="user-profile-name">{{ $selectedUser->first_name }} {{ $selectedUser->last_name }}</h2>
                    <p class="user-profile-role">{{ $selectedUser->role }}</p>
                </div>
                <div class="user-chat-section">
                    <ul>
                        <li class="user-chat-icon">
                            <i class="fi fi-sr-envelope"></i>
                            <p>Email</p>
                        </li>
                        <li class="user-chat-icon">
                            <i class="fi fi-sr-phone-call"></i>
                            <p>Contact</p>
                        </li>
                    </ul>
                </div>
                <div class="user-chat-privacy">
                    <div class="privacy-header" wire:click="$toggle('showPrivacyDropdown')">
                        <div>
                            <h3>Privacy & support</h3>
                        </div>
                        <div>
                            <i class="fi fi-br-angle-small-{{ $showPrivacyDropdown ? 'up' : 'down' }}"></i>
                        </div>
                    </div>
                    <div class="privacy-dropdown{{ $showPrivacyDropdown ? ' active' : '' }}">
                        <ul>
                            <li>
                                <i class="fi fi-sr-minus-circle"></i>
                                <a href="#">Block</a>
                            </li>
                            <li>
                                <i class="fi fi-sr-trash"></i>
                                <a href="#">Delete chat</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('requestProfilePaneState', ({
            localStorageKey
        }) => {
            const state = localStorage.getItem(localStorageKey);
            Livewire.dispatchSelf('setUserProfileState', state === 'true');
        });

        Livewire.on('saveProfilePaneState', ({
            isVisible,
            localStorageKey
        }) => {
            localStorage.setItem(localStorageKey, isVisible ? 'true' : 'false');
        });
    });
</script>
