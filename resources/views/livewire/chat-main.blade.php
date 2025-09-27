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
                    {{-- ⭐ Toggling the Profile Pane --}}
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
                        $latestSeenMessageId = null;
                        foreach ($messages as $msg) {
                            if ($msg->sender_id === Auth::id() && $msg->is_read) {
                                $latestSeenMessageId = $msg->id;
                                break;
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

                        <div class="message-row {{ $isSentByMe ? 'sent' : 'received' }}" id="message-{{ $message->id }}">
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
                @if ($isBlocked)
                    <div class="blocked-status-message">
                        <p>Communication has been temporarily suspended due to potential violations of the system's Terms of Service. Please contact the Guidance Office immediately for a resolution.</p>
                    </div>
                @elseif ($hasBlocked)
                    <div class="blocked-status-message">
                        <p>You have blocked <span class="blocked-user-name">{{ $selectedUser->first_name }}</span>. Unblock them to chat.</p>
                    </div>
                @else
                    <form wire:submit="submit" id="messageForm">
                        <input wire:model.live="newMessage" type="text" id="messageInput"
                            placeholder="Type a message..." required>
                        <button type="submit"><i class="fi fi-sr-paper-plane-top"></i></button>
                    </form>
                @endif
            </div>

        @endif

        @if ($showInactiveUserMessage)
            <div class="no-conversation-holder">
                <img src="{{ asset('images/img/no-convo.png') }}" alt="No convo" class="no-conversation-image">
                <p class="no-conversation-placeholder">
                    This user is currently Inactive and unavailable for messaging.
                </p>
            </div>
        @endif

        @if ($users->isEmpty() && !$newChatMode && !$selectedUser && !$showInactiveUserMessage)
            <div class="no-conversation-holder">
                <img src="{{ asset('images/img/no-convo.png') }}" alt="No convo" class="no-conversation-image">
                <p class="no-conversation-placeholder">
                    Looks like you don't have any conversations yet.<br>Start a new conversation!
                </p>
            </div>
        @endif

    </div>
</div>