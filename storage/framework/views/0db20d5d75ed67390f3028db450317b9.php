<?php $__env->startSection('title', 'SGRMS - Chat'); ?>
<?php $__env->startSection('content'); ?>

    <script>
        window.routes = {
            sendMessage: "/Head/messages/send/:id", // existing conversation
            fetchConversation: "/Head/messages/fetch/:id", // fetch messages dynamically
            startConversation: "/Head/messages/conversations/:id" // new conversation
        };
    </script>

    <section id="content">
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        <div class="chat-app">
            
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
                                    class="chat-nav <?php echo e(request('category') == 'recent' || !request()->has('category') ? 'active' : ''); ?>"
                                    data-filter="recent">All</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="chat-nav <?php echo e(request('category') == 'announcement' ? 'active' : ''); ?>"
                                    data-filter="announcement">Unread</a>
                            </li>
                            <li>
                                <a href="#" class="chat-nav <?php echo e(request('category') == 'event' ? 'active' : ''); ?>"
                                    data-filter="event">Counselors</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <div id="chatList" class="chat-list">
                    <?php $firstConversation = $conversations->first(); ?>
                    <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $other = $conv->getOtherParticipant($user->id);
                            $lastMsg = $conv->messages->last();
                        ?>
                        <div class="chat-item <?php echo e($loop->first ? 'active' : ''); ?>" data-conversation="<?php echo e($conv->id); ?>"
                            data-other-user-id="<?php echo e($other->id); ?>">

                            <img src="<?php echo e(asset('images/user/' . $other->profile_image)); ?>" class="user-img" alt="User">
                            <div class="chat-item-info">
                                <h3 class="chat-item-username"><?php echo e($other->first_name); ?> <?php echo e($other->last_name); ?></h3>
                                <div class="chat-item-preview">
                                    <p class="chat-item-lastmessage
    <?php if($lastMsg && $lastMsg->status === 'sent' && $lastMsg->receiver_id === $user->id): ?> unread <?php endif; ?>
">
                                        <?php if($lastMsg): ?>
                                            <?php echo e($lastMsg->sender_id === $user->id ? 'You: ' : ''); ?>

                                            <?php echo e(\Illuminate\Support\Str::limit($lastMsg->msg, 30)); ?>

                                        <?php else: ?>
                                            No messages yet
                                        <?php endif; ?>
                                    </p>
                                    <span class="chat-item-time" data-time="<?php echo e($lastMsg->created_at); ?>"></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="main-chat" id="mainChat">
                <div class="chat-container">
                    <?php if($firstConversation): ?>
                        <?php
                            $conv = $firstConversation;
                            $other = $conv->getOtherParticipant($user->id);
                        ?>
                        <div class="chat-header">
                            <div class="chat-header-left">
                                <div>
                                    <img src="<?php echo e(asset('images/user/' . $other->profile_image)); ?>" alt="User">
                                </div>
                                <div class="chat-header-info">
                                    <h2><?php echo e($other->first_name); ?> <?php echo e($other->last_name); ?></h2>
                                    <p><?php echo e($other->status ?? 'Online'); ?></p>
                                </div>
                            </div>
                            <div class="chat-header-right">
                                <i class="fi fi-sr-info"></i>
                            </div>
                        </div>
                        <div class="messages-container" id="messagesContainer">
                            <?php $__currentLoopData = $conv->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($msg->sender_id === $user->id): ?>
                                    
                                    <div class="message-row sent">
                                        <div class="message-data sent">
                                            <p><?php echo e($msg->msg); ?></p>
                                        </div>
                                        <span class="message-time"><?php echo e($msg->created_at->format('H:i')); ?></span>
                                    </div>
                                <?php else: ?>
                                    
                                    <div class="message-row received">
                                        <div class="sender-info">
                                            <img src="<?php echo e(asset('images/user/' . $msg->sender->profile_image)); ?>"
                                                class="sender-img" alt="User">
                                        </div>
                                        <div class="sender-data">
                                            <div class="message-data received">
                                                <p><?php echo e($msg->msg); ?></p>
                                            </div>
                                            <span class="message-time"><?php echo e($msg->created_at->format('H:i')); ?></span>
                                        </div>

                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="chat-footer">
                            <form id="messageForm" data-conversation="<?php echo e($conv->id); ?>">
                                <input type="text" id="messageInput" placeholder="Type a message..." required>
                                <button type="submit"><i class="fi fi-sr-paper-plane-top"></i></button>
                            </form>
                        </div>
                    <?php else: ?>
                        
                        <div class="no-conversation-holder">
                            <img src="<?php echo e(asset('images/img/no-convo.png')); ?>" alt="No convo" class="no-conversation-image">
                            <p class="no-conversation-placeholder">Looks like you don't have any conversations
                                yet.<br>Start a new conversation!</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
            <div class="user-chat-profile-info" id="userChatProfileInfo"></div>
        </div>


    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chatList = document.getElementById('chatList');
            const mainChat = document.getElementById('mainChat');

            //-- Mark As Read function
            function markConversationAsRead(conversationId) {
                fetch(`/Head/messages/mark-as-read/${conversationId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    // Optionally, update the sidebar UI to remove bold
                    const chatItem = document.querySelector(
                        `.chat-item[data-conversation="${conversationId}"] .chat-item-lastmessage`);
                    if (chatItem) chatItem.classList.remove('unread');
                });
            }

            // --- Format time like Laravel's diffForHumans ---
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

            // --- Update sidebar with a conversation (only if there’s at least one message) ---
            function updateSidebarConversation(data) {
                const convId = data.conversation.id;
                const messages = data.messages;
                if (!messages.length) return; // Don't add empty conversation

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
                    newChatItem.innerHTML = `
                <img src="<?php echo e(asset('images/user')); ?>/${data.otherUser.profile_image}" class="user-img" alt="User">
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

            // --- Render user profile info in the side panel ---
            function renderUserProfileInfo(user) {
                const profile = document.getElementById('userChatProfileInfo');
                if (!user) {
                    profile.innerHTML = '';
                    profile.classList.remove('active');
                    return;
                }
                profile.innerHTML = `
        <div class="user-chat-profile-container">
            <div>
                <img src="<?php echo e(asset('images/user')); ?>/${user.profile_image}" alt="User">
            </div>
            <div class="chat-header-info">
                <h2>${user.first_name} ${user.last_name}</h2>
                <p>${user.role ?? 'Online'}</p>
            </div>
        </div>
    `;
                // Optionally keep it hidden until info icon is clicked
                profile.classList.remove('active');
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

                    const url = receiverId ? window.routes.startConversation.replace(':id', receiverId) :
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

                            // Render messages
                            container.innerHTML = '';
                            data.messages.forEach(msg => {
                                container.innerHTML += `
    ${msg.sender_id === data.currentUserId
        ? `<div class="message-row sent">
                                                                                                        <div class="message-data sent"><p>${msg.msg}</p></div>
                                                                                                        <span class="message-time">${timeAgo(msg.created_at)}</span>
                                                                                                   </div>`
        : `<div class="message-row received">
                                                                                                    <div class="sender-info">
                                                                                                        <img src="<?php echo e(asset('images/user')); ?>/${data.otherUser.profile_image}" class="user-img" alt="User">
                                                                                                    </div>
                                                                                                    <div class="sender-data">
                                                                                                        <div class="message-data received"><p>${msg.msg}</p></div>
                                                                                                    </div>
                                                                                                  
                                                                                                    <span class="message-time">${timeAgo(msg.created_at)}</span>
                                                                                               </div>`
    }
`;
                            });
                            container.scrollTop = container.scrollHeight;

                            // Update sidebar
                            updateSidebarConversation(data);

                            // Mark as read after sending a message (Messenger-like)
                            if (form.dataset.conversation) {
                                markConversationAsRead(form.dataset.conversation);
                            }
                            
                        })
                        .catch(console.error);
                });
            }

            // --- Load an existing conversation ---
            function loadConversation(convId) {
                fetch(window.routes.fetchConversation.replace(':id', convId), {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {

                        renderUserProfileInfo(data.otherUser);

                        // Render main chat area
                        mainChat.innerHTML = `
            
                    <div class="chat-header">
                            <div class="chat-header-left">
                                <div>
                                  <img src="<?php echo e(asset('images/user')); ?>/${data.otherUser.profile_image}" class="user-img" alt="User">
                                </div>
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
                            container.innerHTML += `
    ${msg.sender_id === data.currentUserId
        ? `<div class="message-row sent">
                    <div class="message-data sent"><p>${msg.msg}</p></div>
                                <span class="message-time">${timeAgo(msg.created_at)}</span>
                                                                                               </div>`
        : `<div class="message-row received">
                                                                                                    <div class="sender-info">
                                                                                                        <img src="<?php echo e(asset('images/user')); ?>/${data.otherUser.profile_image}" class="user-img" alt="User">
                                                                                                    </div>
                                                                                                    <div class="sender-data">
                                                                                                       
                                                                                                        <div class="message-data received"><p>${msg.msg}</p></div>
                                                                                                    </div>
                                                                                                  
                                                                                                    <span class="message-time">${timeAgo(msg.created_at)}</span>
                                                                                               </div>`
    }
`;
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
                loadConversation(item.dataset.conversation);
            });



            // --- New chat ---
            document.getElementById('newChatBtn').addEventListener('click', e => {
                e.preventDefault();
                showNewChat();
            });

            function showNewChat() {
                mainChat.innerHTML = `
            <div class="new-chat-header">
                <span>To:</span>
                <input type="text" id="userSearch" class="user-search-input" placeholder="Type a name...">
                <ul id="userResults" class="user-results"></ul>
            </div>
            <div class="messages-container" id="messagesContainer"></div>
        `;

                const searchBox = document.getElementById('userSearch');
                const resultsBox = document.getElementById('userResults');

                searchBox.addEventListener('input', async e => {
                    const q = e.target.value.trim();
                    resultsBox.innerHTML = '';
                    if (!q) return;

                    const res = await fetch(
                        `<?php echo e(route('Head.messages.search-users')); ?>?query=${encodeURIComponent(q)}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                    const users = await res.json();

                    users.forEach(u => {
                        const li = document.createElement('li');
                        li.innerHTML =
                            `<img src="<?php echo e(asset('images/user')); ?>/${u.profile_image}" class="search-user-img"><span>${u.first_name} ${u.last_name}</span>`;
                        li.style.display = 'flex';
                        li.style.alignItems = 'center';
                        li.style.gap = '8px';
                        li.addEventListener('click', () => selectNewChatUser(u));
                        resultsBox.appendChild(li);
                    });
                });
            }

            // --- Select a user for new conversation ---
            function selectNewChatUser(u) {
                // Check if a conversation with this user already exists in the sidebar
                const existingChat = [...chatList.querySelectorAll('.chat-item')].find(item => {
                    return item.dataset.otherUserId == u.id; // <-- we need to store otherUserId in sidebar
                });

                if (existingChat) {
                    // If exists, load existing conversation
                    chatList.querySelectorAll('.chat-item').forEach(c => c.classList.remove('active'));
                    existingChat.classList.add('active');
                    loadConversation(existingChat.dataset.conversation);

                } else {
                    renderUserProfileInfo(u);
                    // Otherwise, show new chat
                    mainChat.innerHTML = `
            <div class="chat-header">
                <div class="chat-header-left">
                    <div>
                        <img src="<?php echo e(asset('images/user')); ?>/${u.profile_image}" class="user-img" alt="User">
                    </div>
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

            // --- Init ---
            bindMessageForm(document.getElementById('messageForm'));
            refreshChatTimes();
            setInterval(refreshChatTimes, 20000);

            // Render profile info for the first conversation if it exists
            <?php if($firstConversation): ?>
                renderUserProfileInfo(<?php echo json_encode($firstConversation->getOtherParticipant($user->id), 15, 512) ?>);
            <?php endif; ?>
        });


        document.addEventListener('DOMContentLoaded', () => {


            // Toggle user profile info
            document.addEventListener('click', function(e) {
                if (e.target.closest('.fi-sr-info')) {
                    const profile = document.querySelector('.user-chat-profile-info');
                    if (profile) {
                        profile.classList.toggle('active');
                    }
                }
            });


        });
    </script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/Head/messages.blade.php ENDPATH**/ ?>