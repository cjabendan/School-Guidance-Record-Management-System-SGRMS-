<div class="user-chat-profile-info {{ $showUserProfile ? 'active' : '' }}" id="userChatProfileInfo"
    data-user-id="{{ $selectedUser->id }}">
    <div class="user-chat-profile-container">

        @if (!$searchModeInProfile)
            {{-- DEFAULT USER PROFILE VIEW --}}
            <div class="user-profile-header" style="position: absolute; top: 10px; right: 10px;">
                <a href="#" class="close-profile-btn" wire:click.prevent="toggleUserProfile">&times;</a>
            </div>
            <div class="user-profile-img-wrapper">
                <img src="{{ asset('images/user/' . $selectedUser->profile_image) }}" class="user-profile-img"
                    alt="User">
            </div>
            <div class="user-chat-header-info">
                <h2 class="user-profile-name">{{ $selectedUser->first_name }} {{ $selectedUser->last_name }}@if (in_array($selectedUser->role, ['admin', 'counselor']))
                        <i class="fi fi-sr-badge-check official-badge" title="Verified"></i>
                    @endif
                </h2>
                <p class="user-profile-role">{{ $selectedUser->role }}</p>
            </div>
            <div class="user-chat-section">
                <ul>
                    <li class="user-chat-icon">
                        <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&tf=1&to={{ $selectedUser->email }}"
                            target="_blank">
                            <i class="fi fi-sr-envelope"></i>
                        </a>
                        <p>Email</p>
                    </li>
                    <li class="user-chat-icon">
                        <a href="tel:{{ $selectedUser->phone }}" target="_blank">
                            <i class="fi fi-sr-phone-call"></i>
                        </a>
                        <p>Contact</p>
                    </li>
                    <li class="user-chat-icon" wire:click="toggleSearchMode" style="cursor: pointer;">
                        <a href="#">
                            <i class="fi fi-br-search"></i>
                        </a>

                        <p>Search</p>
                    </li>
                </ul>
            </div>
            <div class="user-chat-privacy">
                <div class="privacy-header" wire:click="togglePrivacyDropdown">
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
                            <a href="#" wire:click.prevent="toggleBlockUser">
                                {{ $hasBlocked ? 'Unblock User' : 'Block User' }}
                            </a>
                        </li>
                        <li>
                            <i class="fi fi-sr-trash"></i>
                            <a href="#">Delete chat</a>
                        </li>
                        @if ($isBlocked)
                            <li style="color: #dc3545; font-size: 12px; padding: 5px 15px;">
                                <i class="fi fi-sr-lock"></i>
                                You are blocked by this user.
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @else
            {{-- CONVERSATION SEARCH MODE --}}
            <div class="search-mode-header" style="display: flex; align-items: center; padding: 10px;">
                <a href="#" wire:click.prevent="toggleSearchMode" style="margin-right: 10px;">
                    <i class="fi fi-br-angle-left"></i>
                </a>
                <form wire:submit.prevent="searchInConversation" style="flex-grow: 1; display: flex;">

                    <input type="text" wire:model="profileSearchQuery"
                        wire:keydown.enter.prevent="searchInConversation" placeholder="Search in conversation..."
                        class="search-input">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>

                    <button type="submit" style="background: transparent; border: none; ">

                    </button>
                </form>
            </div>

            <div class="search-results-list" style="padding: 10px; overflow-y: auto;">
                @forelse($profileSearchResults as $message)
                    @php

                        $isSentByMe = $message->sender_id === Auth::id();
                        $sender = $isSentByMe ? Auth::user() : $selectedUser;

                        $authenticatedUser = Auth::user();

                        $senderName = $isSentByMe
                            ? $authenticatedUser->first_name . ' ' . $authenticatedUser->last_name
                            : $sender->first_name . ' ' . $sender->last_name;

                        $senderImage = asset('images/user/' . $sender->profile_image);
                    @endphp


                    <div wire:click="goToMessage({{ $message->id }})"
                        style="padding: 10px; border-bottom: 1px dashed #eee; cursor: pointer; border-radius: 4px; transition: background-color 0.2s;"
                        onmouseover="this.style.backgroundColor='#f9f9f9'"
                        onmouseout="this.style.backgroundColor='white'">

                        <div class="search-convo-result-item">
                            <div class="scri-img">
                                <img src="{{ $senderImage }}">
                            </div>
                            <div class="scri-content">
                                <p class="scri-name">{{ $senderName }}
                                <p>
                                <p class="scri-message">
                                    {!! preg_replace(
                                        '/\b(' . preg_quote($profileSearchQuery, '/') . ')\b/i',
                                        '<b class="highlight-word">$1</b>',
                                        e($message->message),
                                    ) !!}
                                    • <span class="scri-time">{{ $message->created_at->diffForHumans() }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    @if (!empty($profileSearchQuery))
                        <div
                            style="display: flex; flex-direction: column; padding: 10px 20px; text-align: center; gap: 10px;">
                            <p style="color: #999;">
                                No messages found matching "<span
                                    style="font-weight: bold;">{{ $profileSearchQuery }}</span>" in this chat.
                            </p>
                            <small style="color: #bbb;">Try a different word or phrase.</small>
                        </div>
                    @else
                        <p style="text-align: center; color: #999;">Enter a word or phrase to search
                            messages in this conversation.</p>
                    @endif
                @endforelse
            </div>
        @endif
    </div>
</div>
