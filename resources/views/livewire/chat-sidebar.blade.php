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
                    <li wire:key="search-result-{{ $u->id }}" wire:click="selectNewChatUser({{ $u->id }})"
                        style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <img src="{{ asset('images/user/' . $u->profile_image) }}" class="search-user-img">
                        <span>{{ $u->first_name }} {{ $u->last_name }}@if (in_array($u->role, ['admin', 'counselor']))
                                    <i class="fi fi-sr-badge-check official-badge" title="Verified"></i>
                            @endif
                        </span>
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
                                class="chat-nav {{ $filter == 'unread' ? 'active' : '' }}" data-filter="unread">Unread</a>
                        </li>
                        <li>
                          <a href="#" wire:click.prevent="setFilter('staff')"
   class="chat-nav {{ in_array($filter, ['counselor', 'admin']) ? 'active' : '' }}"
   data-filter="staff">Counselors</a>
                        </li>
                    </ul>
                </div>
            @endif

        </div>
        @if (empty($conversationSearch))
            <div id="chatList" class="chat-list">
                @foreach ($users as $user)
                    <div wire:key="chat-item-{{ $user->id }}" wire:click="selectUser({{ $user->id }})"
                        class="chat-item {{ $selectedUser && $selectedUser->id === $user->id ? 'active' : '' }}">
                        <img src="{{ asset('images/user/' . $user->profile_image) }}" class="user-img" alt="User">
                        <div class="chat-item-info">
                            <h3 class="chat-item-username">{{ $user->first_name }} {{ $user->last_name }}@if (in_array($user->role, ['admin', 'counselor']))
                                    <i class="fi fi-sr-badge-check official-badge" title="Verified"></i>
                                @endif
                            </h3>
                            <div class="chat-item-preview">
                                <span
                                    class="chat-item-lastmessage
                                @if ($user->lastMessage && !$user->lastMessage->is_read && $user->lastMessage->sender_id !== Auth::id()) unread @endif">
                                    @if ($user->lastMessage)
                                        @if ($user->lastMessage->sender_id === Auth::id())
                                            You: {{ $user->lastMessage->message }}
                                        @else
                                            {{ $user->lastMessage->message, }}
                                        @endif
                                    @else
                                        No messages yet.
                                    @endif
                                </span>
                                <span class="chat-item-time" data-time="{{ $user->lastMessage?->created_at }}">
                                    {{ $this->formatTimeShort($user->lastMessage?->created_at) }} 
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
