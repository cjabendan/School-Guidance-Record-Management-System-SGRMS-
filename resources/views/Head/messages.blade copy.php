@extends('layouts.main')
@section('title', 'SGRMS - Chat')
@section('content')

    <script>
        window.routes = {
            sendMessage: "/Head/messages/send/:id", // existing conversation
            fetchConversation: "/Head/messages/fetch/:id", // fetch messages dynamically
            startConversation: "/Head/messages/conversations/:id" // new conversation
        };
    </script>

    <section id="content">
        @include('partials.navbar')


        <div class="chat-app">
            {{-- Sidebar --}}
            <div class="message-sidebar">
                <div class="message-sidebar-header">
                    <div class="message-sidebar-header-title">
                        <h2>Chats</h2>
                        <a href="#" id="newChatBtn" class="new-chat-btn">+</a>
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
                                <a href="#"
                                    class="chat-nav {{ request('category') == 'announcement' ? 'active' : '' }}"
                                    data-filter="announcement">Unread</a>
                            </li>
                            <li>
                                <a href="#" class="chat-nav {{ request('category') == 'event' ? 'active' : '' }}"
                                    data-filter="event">Counselors</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <div id="chatList" class="chat-list">
                    @php $firstConversation = $conversations->first(); @endphp
                    @foreach ($conversations as $conv)
                        @php
                            $other = $conv->getOtherParticipant($user->id);
                            $lastMsg = $conv->messages->last();
                        @endphp
                        <div class="chat-item {{ $loop->first ? 'active' : '' }} @if ($lastMsg && $lastMsg->status === 'sent' && $lastMsg->receiver_id === $user->id) unread @endif"
                            data-conversation="{{ $conv->id }}" data-other-user-id="{{ $other->id }}">
                            <img src="{{ asset('images/user/' . $other->profile_image) }}" class="user-img" alt="User">
                            <div class="chat-item-info">
                                <h3 class="chat-item-username">{{ $other->first_name }} {{ $other->last_name }}</h3>
                                <div class="chat-item-preview">
                                    <span class="chat-item-lastmessage">
                                        @if ($lastMsg)
                                            {{ $lastMsg->sender_id === $user->id ? 'You: ' : '' }}
                                            {{ \Illuminate\Support\Str::limit($lastMsg->msg, 30) }}
                                        @else
                                            No messages yet
                                        @endif
                                    </span>
                                    <span class="chat-item-time" data-time="{{ $lastMsg?->created_at }}"></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Main chat area --}}
            <div class="main-chat" id="mainChat">
                <div class="chat-container">
                    @if ($firstConversation)
                        @php
                            $conv = $firstConversation;
                            $other = $conv->getOtherParticipant($user->id);
                        @endphp
                        <div class="chat-header">
                            <div class="chat-header-left">
                                <div>
                                    <img src="{{ asset('images/user/' . $other->profile_image) }}" alt="User">
                                </div>
                                <div class="chat-header-info">
                                    <h2>{{ $other->first_name }} {{ $other->last_name }}</h2>
                                    <p>{{ $other->status ?? 'Online' }}</p>
                                </div>
                            </div>
                            <div class="chat-header-right">
                                <i class="fi fi-sr-info"></i>
                            </div>
                        </div>
                        <div class="messages-container" id="messagesContainer">
                            @foreach ($conv->messages as $msg)
                                @if ($msg->sender_id === $user->id)
                                    {{-- Sent message (right) --}}
                                    <div class="message-row sent">
                                        <div class="message-data sent">
                                            <p>{{ $msg->msg }}</p>
                                        </div>
                                        <span class="message-time">{{ $msg->created_at->format('H:i') }}</span>
                                    </div>
                                @else
                                    {{-- Received message (left) --}}
                                    <div class="message-row received">
                                        <div class="sender-info">
                                            <img src="{{ asset('images/user/' . $msg->sender->profile_image) }}"
                                                class="sender-img" alt="User">
                                        </div>
                                        <div class="sender-data">
                                            <div class="message-data received">
                                                <p>{{ $msg->msg }}</p>
                                            </div>
                                            <span class="message-time">{{ $msg->created_at->format('H:i') }}</span>
                                        </div>
                                        
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="chat-footer">
                            <form id="messageForm" data-conversation="{{ $conv->id }}">
                                <input type="text" id="messageInput" placeholder="Type a message..." required>
                                <button type="submit"><i class="fi fi-sr-paper-plane-top"></i></button>
                            </form>
                        </div>
                    @else
                        {{-- No conversations placeholder --}}
                        <div class="no-conversation-holder">
                            <img src="{{ asset('images/img/no-convo.png') }}" alt="No convo" class="no-conversation-image">
                            <p class="no-conversation-placeholder">Looks like you don't have any conversations
                                yet.<br>Start a new conversation!</p>
                        </div>
                    @endif
                </div>

            </div>
            <div class="user-chat-profile-info" id="userChatProfileInfo"></div>
        </div>


    </section>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chatList = document.getElementById('chatList');
            const mainChat = document.getElementById('mainChat');
            const profilePanel = document.getElementById('userChatProfileInfo');
            let isNewChatMode = false;
            let lastMessageIds = {};


            // --- Helper: Format time like Laravel's diffForHumans ---
            function timeAgo(dateString) {
                const seconds = Math.floor((new Date() - new Date(dateString)) / 1000);
                if (seconds < 60) return "1m";
                const intervals = [{
                        label: 'y',
                        seconds: 31536000
                    },
                    {
                        label: 'mo',
                        seconds: 2592000
                    },
                    {
                        label: 'd',
                        seconds: 86400
                    },
                    {
                        label: 'h',
                        seconds: 3600
                    },
                    {
                        label: 'm',
                        seconds: 60
                    }
                ];
                for (const interval of intervals) {
                    const count = Math.floor(seconds / interval.seconds);
                    if (count >= 1) return `${count}${interval.label}`;
                }
                return 'just now';
            }

            // --- Refresh sidebar chat times ---
            function refreshChatTimes() {
                document.querySelectorAll('.chat-item-time').forEach(el => {
                    const time = el.getAttribute('data-time');
                    if (time) el.textContent = timeAgo(time);
                });
            }

            // --- Mark conversation as read ---
            function markConversationAsRead(conversationId) {
                fetch(`/Head/messages/mark-as-read/${conversationId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    const chatItem = document.querySelector(
                        `.chat-item[data-conversation="${conversationId}"] .chat-item-lastmessage`
                    );
                    if (chatItem) chatItem.classList.remove('unread');
                });
            }

            // --- Update sidebar conversation ---
            function updateSidebarConversation(data) {
                const convId = data.conversation.id;
                const messages = data.messages;
                if (!messages.length) return;

                const lastMsg = messages[messages.length - 1];
                let chatItem = chatList.querySelector(`.chat-item[data-conversation="${convId}"]`);

                if (chatItem) {
                    const preview = chatItem.querySelector('.chat-item-lastmessage');
                    const time = chatItem.querySelector('.chat-item-time');
                    if (preview) preview.textContent =
                        `${lastMsg.sender_id === data.currentUserId ? 'You: ' : ''}${lastMsg.msg}`;
                    if (time) {
                        time.textContent = timeAgo(lastMsg.created_at);
                        time.setAttribute('data-time', lastMsg.created_at);
                    }
                    chatList.prepend(chatItem);
                } else {
                    const newChatItem = document.createElement('div');
                    newChatItem.classList.add('chat-item');
                    newChatItem.dataset.conversation = convId;
                    newChatItem.dataset.otherUserId = data.otherUser.id;
                    newChatItem.innerHTML = `
                <img src="{{ asset('images/user') }}/${data.otherUser.profile_image}" class="user-img" alt="User">
                <div class="chat-item-info">
                    <h3 class="chat-item-username">${data.otherUser.first_name} ${data.otherUser.last_name}</h3>
                    <div class="chat-item-preview">
                        <p class="chat-item-lastmessage">You: ${lastMsg.msg}</p>
                        <span class="chat-item-time" data-time="${lastMsg.created_at}">${timeAgo(lastMsg.created_at)}</span>
                    </div>
                </div>
            `;
                    newChatItem.addEventListener('click', () => loadConversation(convId));
                    chatList.prepend(newChatItem);
                }
            }


            function renderMessages(messages, otherUser, currentUserId) {
                const container = document.getElementById('messagesContainer');
                if (!container) return;
                container.innerHTML = '';
                messages.forEach(msg => {
                    const msgDiv = document.createElement('div');
                    if (msg.sender_id === currentUserId) {
                        msgDiv.className = 'message-row sent';
                        msgDiv.innerHTML =
                            `<div class="message-data sent"><p>${msg.msg}</p></div><span class="message-time">${timeAgo(msg.created_at)}</span>`;
                    } else {
                        msgDiv.className = 'message-row received';
                        msgDiv.innerHTML =
                            `<div class="sender-info"><img src="/images/user/${otherUser.profile_image}" class="sender-img" alt="User"></div>
                                <div class="sender-data">
                                    <div class="message-data received">
                                        <p>${msg.msg}</p>
                                    </div>
                                    <span class="message-time">${timeAgo(msg.created_at)}</span>
                                </div>`;
                    }
                    container.appendChild(msgDiv);
                });
                container.scrollTop = container.scrollHeight;
            }
            // --- Render user profile info ---
            function renderUserProfileInfo(user) {
                if (!user) {
                    profilePanel.innerHTML = '';
                    profilePanel.classList.remove('active'); // hide panel if no user
                    profilePanel.removeAttribute('data-user-id');
                    return;
                }

                profilePanel.innerHTML = `
            <div class="user-chat-profile-container">
                <div>
                    <img src="{{ asset('images/user') }}/${user.profile_image}" class="user-profile-img" alt="User">
                </div>
                <div class="chat-header-info">
                    <h2 class="user-profile-name">${user.first_name} ${user.last_name}</h2>
                    <p class="user-profile-role">${user.role ?? 'Online'}</p>
                </div>
            </div>
        `;
                profilePanel.dataset.userId = user.id;

                // Only activate panel if toggle is active AND user exists
                const toggleActive = localStorage.getItem('userInfoActive') === 'true';
                if (toggleActive && user) {
                    profilePanel.classList.add('active');
                } else {
                    profilePanel.classList.remove('active');
                }
            }

            // --- Bind message form ---
            function bindMessageForm(form) {
                if (!form) return;
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    const input = form.querySelector('#messageInput');
                    const conversationId = form.dataset.conversation;
                    const receiverId = form.dataset.receiver;
                    if (!input.value.trim()) return;

                    const url = receiverId ?
                        window.routes.startConversation.replace(':id', receiverId) :
                        window.routes.sendMessage.replace(':id', conversationId);

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                msg: input.value
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            input.value = '';
                            const container = document.getElementById('messagesContainer');
                            container.innerHTML = '';
                            data.messages.forEach(msg => {
                                container.innerHTML += msg.sender_id === data.currentUserId ?
                                    `<div class="message-row sent"><div class="message-data sent"><p>${msg.msg}</p></div><span class="message-time">${timeAgo(msg.created_at)}</span></div>` :
                                    `<div class="message-row received"><div class="sender-info"><img src="{{ asset('images/user') }}/${data.otherUser.profile_image}" class="user-img" alt="User"></div><div class="sender-data"><div class="message-data received"><p>${msg.msg}</p></div></div><span class="message-time">${timeAgo(msg.created_at)}</span></div>`;
                            });
                            container.scrollTop = container.scrollHeight;
                            updateSidebarConversation(data);

                            // If a new conversation was created, set activeConversationId and disable new chat mode
                            if (data.conversation && data.conversation.id) {
                                localStorage.setItem('activeConversationId', data.conversation.id);
                                isNewChatMode = false;
                            }
                            if (form.dataset.conversation) markConversationAsRead(form.dataset
                                .conversation);
                        })
                        .catch(console.error);
                });
            }

            // --- Load a conversation ---
            function loadConversation(convId) {
                fetch(window.routes.fetchConversation.replace(':id', convId), {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Save active conversation
                        localStorage.setItem('activeConversationId', convId);

                        renderUserProfileInfo(data.otherUser);
                        mainChat.innerHTML = `
                    <div class="chat-header">
                        <div class="chat-header-left">
                            <div><img src="{{ asset('images/user') }}/${data.otherUser.profile_image}" class="user-img" alt="User"></div>
                            <div class="chat-header-info">
                                <h2>${data.otherUser.first_name} ${data.otherUser.last_name}</h2>
                                <p>${data.otherUser.status ?? 'Online'}</p>
                            </div>
                        </div>
                        <div class="chat-header-right">
                            <i class="fi fi-sr-info"></i>
                        </div>
                    </div>
                    <div class="messages-container" id="messagesContainer"></div>
                    <div class="chat-footer">
                        <form id="messageForm" data-conversation="${data.conversation.id}">
                            <input type="text" id="messageInput" placeholder="Type a message..." required>
                            <button type="submit"><i class="fi fi-sr-paper-plane-top"></i></button>
                        </form>
                    </div>
                `;
                        const container = document.getElementById('messagesContainer');
                        data.messages.forEach(msg => {
                            container.innerHTML += msg.sender_id === data.currentUserId ?
                                `<div class="message-row sent"><div class="message-data sent"><p>${msg.msg}</p></div><span class="message-time">${timeAgo(msg.created_at)}</span></div>` :
                                `<div class="message-row received">
                                    <div class="sender-info">
                                        <img src="{{ asset('images/user') }}/${data.otherUser.profile_image}" class="sender-img" alt="User"></div>
                                        <div class="sender-data">
                                            <div class="message-data received">
                                                <p>${msg.msg}</p>
                                            </div>
                                            <span class="message-time">${timeAgo(msg.created_at)}</span>
                                        </div>`;
                                      
                                        
                        });
                        container.scrollTop = container.scrollHeight;
                        markConversationAsRead(convId);
                        bindMessageForm(document.getElementById('messageForm'));
                    })
                    .catch(console.error);
            }

            // --- Sidebar click ---
            chatList.addEventListener('click', e => {
                const item = e.target.closest('.chat-item');
                if (!item) return;
                chatList.querySelectorAll('.chat-item').forEach(c => c.classList.remove('active'));
                item.classList.add('active');
                localStorage.setItem('activeConversationId', item.dataset.conversation);
                loadConversation(item.dataset.conversation);
            });

            // --- New chat ---
            document.getElementById('newChatBtn').addEventListener('click', e => {
                e.preventDefault();
                showNewChat();
            });

            function showNewChat() {
                isNewChatMode = true; // enable new chat mode
                localStorage.removeItem('activeConversationId');



                mainChat.innerHTML = `
        <div class="new-chat-header">
            <span>To:</span>
            <input type="text" id="userSearch" class="user-search-input" placeholder="Type a name...">
            <ul id="userResults" class="user-results"></ul>
        </div>
        <div class="messages-container" id="messagesContainer"></div>
    `;
                profilePanel.classList.remove('active');
                profilePanel.innerHTML = '';
                profilePanel.removeAttribute('data-user-id');

                const searchBox = document.getElementById('userSearch');
                const resultsBox = document.getElementById('userResults');

                searchBox.addEventListener('input', async e => {
                    const q = e.target.value.trim();
                    resultsBox.innerHTML = '';
                    if (!q) return;

                    const res = await fetch(
                        `{{ route('Head.messages.search-users') }}?query=${encodeURIComponent(q)}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );
                    const users = await res.json();
                    users.forEach(u => {
                        const li = document.createElement('li');
                        li.innerHTML =
                            `<img src="{{ asset('images/user') }}/${u.profile_image}" class="search-user-img"><span>${u.first_name} ${u.last_name}</span>`;
                        li.style.display = 'flex';
                        li.style.alignItems = 'center';
                        li.style.gap = '8px';

                        li.addEventListener('click', () => selectNewChatUser(u));
                        resultsBox.appendChild(li);
                    });
                });
            }


            function selectNewChatUser(u) {
                isNewChatMode = false; // disable new chat mode
                const existingChat = [...chatList.querySelectorAll('.chat-item')].find(item => item.dataset
                    .otherUserId == u.id);



                if (existingChat) {
                    chatList.querySelectorAll('.chat-item').forEach(c => c.classList.remove('active'));
                    existingChat.classList.add('active');
                    localStorage.setItem('activeConversationId', existingChat.dataset.conversation);
                    isNewChatMode = false;
                    loadConversation(existingChat.dataset.conversation);
                } else {
                    localStorage.removeItem('activeConversationId'); // No active conversation yet
                    // Only render profile info if toggle is active AND user is selected
                    const toggleActive = localStorage.getItem('userInfoActive') === 'true';
                    if (toggleActive) renderUserProfileInfo(u);

                    mainChat.innerHTML = `
                <div class="chat-header">
                    <div class="chat-header-left">
                        <div><img src="{{ asset('images/user') }}/${u.profile_image}" class="user-img" alt="User"></div>
                        <div class="chat-header-info">
                            <h2>${u.first_name} ${u.last_name}</h2>
                            <p>${u.status ?? 'Online'}</p>
                        </div>
                    </div>
                    <div class="chat-header-right">
                        <i class="fi fi-sr-info"></i>
                    </div>
                </div>
                <div class="messages-container" id="messagesContainer"></div>
                <div class="chat-footer">
                    <form id="messageForm" data-receiver="${u.id}">
                        <input type="text" id="messageInput" placeholder="Type a message..." required>
                        <button type="submit"><i class="fi fi-sr-paper-plane-top"></i></button>
                    </form>
                </div>
            `;
                    bindMessageForm(document.getElementById('messageForm'));
                }
            }

            // --- Polling ---
            let activeConvLastMsgId = null;

            async function pollSidebar() {
                const res = await fetch('/Head/messages/sidebar-list', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                const conversations = data.conversations;
                const currentUserId = data.currentUserId;
                const activeConvId = localStorage.getItem('activeConversationId');

                conversations.forEach(conv => {
                    const other = conv.otherUser;
                    const lastMsg = conv.lastMessage;
                    const isUnread = lastMsg && lastMsg.status === 'sent' && lastMsg.receiver_id ===
                        currentUserId;

                    let chatItem = chatList.querySelector(`.chat-item[data-conversation="${conv.id}"]`);
                    const html = `
            <img src="/images/user/${other.profile_image}" class="user-img" alt="User">
            <div class="chat-item-info">
                <h3 class="chat-item-username">${other.first_name} ${other.last_name}</h3>
                <div class="chat-item-preview">
                    <span class="chat-item-lastmessage">${lastMsg ? (lastMsg.sender_id === currentUserId ? 'You: ' : '') + lastMsg.msg : 'No messages yet'}</span>
                    <span class="chat-item-time" data-time="${lastMsg ? lastMsg.created_at : ''}">${lastMsg ? timeAgo(lastMsg.created_at) : ''}</span>
                </div>
            </div>
        `;

                    if (chatItem) {
                        // Update content and classes
                        chatItem.innerHTML = html;
                        chatItem.classList.toggle('unread', isUnread);
                        chatItem.classList.toggle('active', conv.id == activeConvId);
                        // Move to top
                        chatList.prepend(chatItem);
                    } else {
                        // Create new chat item
                        chatItem = document.createElement('div');
                        chatItem.className =
                            `chat-item${isUnread ? ' unread' : ''}${conv.id == activeConvId ? ' active' : ''}`;
                        chatItem.dataset.conversation = conv.id;
                        chatItem.dataset.otherUserId = other.id;
                        chatItem.innerHTML = html;
                        chatItem.addEventListener('click', () => {
                            chatList.querySelectorAll('.chat-item').forEach(c => c.classList
                                .remove('active'));
                            chatItem.classList.add('active');
                            localStorage.setItem('activeConversationId', conv.id);
                            loadConversation(conv.id);
                        });
                        chatList.prepend(chatItem);
                    }
                });
            }

            function pollActiveConversation() {
                const activeConvId = localStorage.getItem('activeConversationId');
                if (!activeConvId || isNewChatMode) return;

                fetch(`/Head/messages/fetch/${activeConvId}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        const messages = data.messages;
                        if (!messages.length) return;

                        // Find the last received message
                        const lastReceivedMsg = [...messages].reverse().find(msg => msg.receiver_id === data
                            .currentUserId);

                        // Only update UI if there's a new last received message
                        if (
                            lastReceivedMsg &&
                            lastMessageIds[activeConvId] !== lastReceivedMsg.id
                        ) {
                            renderMessages(messages, data.otherUser, data.currentUserId);
                            lastMessageIds[activeConvId] = lastReceivedMsg.id;
                            markConversationAsRead(activeConvId);
                        }
                    });
            }

            setInterval(() => {
                pollSidebar();
                pollActiveConversation();
            }, 2000);

            // --- Toggle user info panel ---
            document.addEventListener('click', e => {
                const infoBtn = e.target.closest('.fi-sr-info');
                if (infoBtn && profilePanel) {
                    profilePanel.classList.toggle('active');
                    localStorage.setItem('userInfoActive', profilePanel.classList.contains('active'));
                    localStorage.setItem('userInfoUserId', profilePanel.dataset.userId || null);
                }
            });

            // --- Initialize ---
            bindMessageForm(document.getElementById('messageForm'));
            refreshChatTimes();
            setInterval(refreshChatTimes, 20000);

            // --- Load saved active conversation if any ---
            const savedConversationId = localStorage.getItem('activeConversationId');
            if (savedConversationId) {
                const savedChatItem = chatList.querySelector(
                    `.chat-item[data-conversation="${savedConversationId}"]`);
                if (savedChatItem) {
                    chatList.querySelectorAll('.chat-item').forEach(c => c.classList.remove('active'));
                    savedChatItem.classList.add('active');
                    loadConversation(savedConversationId);
                }
            } else {
                @if ($firstConversation)
                    renderUserProfileInfo(@json($firstConversation->getOtherParticipant($user->id)));
                @endif
            }
        });
    </script>

@endsection
