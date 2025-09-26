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
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $conversationSearchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li wire:click="selectNewChatUser(<?php echo e($u->id); ?>)"
                            style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <img src="<?php echo e(asset('images/user/' . $u->profile_image)); ?>" class="search-user-img">
                            <span><?php echo e($u->first_name); ?> <?php echo e($u->last_name); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <!--[if BLOCK]><![endif]--><?php if(strlen($conversationSearch) > 1): ?>
                            <div class="no-result-convo">
                                <p class="no-results">Nothing found.</p>
                                <p>Try searching for different keywords.</p>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </ul>
            </div>

            <!--[if BLOCK]><![endif]--><?php if(empty($conversationSearch)): ?>
                <div class="chat-filters" id="chat-filters">
                    <ul>
                        <li>
                            <a href="#" wire:click.prevent="setFilter('recent')"
                                class="chat-nav <?php echo e($filter == 'recent' ? 'active' : ''); ?>" data-filter="recent">All</a>
                        </li>
                        <li>
                            <a href="#" wire:click.prevent="setFilter('unread')"
                                class="chat-nav <?php echo e($filter == 'unread' ? 'active' : ''); ?>"
                                data-filter="unread">Unread</a>
                        </li>
                        <li>
                            <a href="#" wire:click.prevent="setFilter('counselor')"
                                class="chat-nav <?php echo e($filter == 'counselor' ? 'active' : ''); ?>"
                                data-filter="counselor">Counselors</a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>
        <!--[if BLOCK]><![endif]--><?php if(empty($conversationSearch)): ?>
            <div id="chatList" class="chat-list">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div wire:click="selectUser(<?php echo e($user->id); ?>)"
                        class="chat-item <?php echo e($selectedUser && $selectedUser->id === $user->id ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('images/user/' . $user->profile_image)); ?>" class="user-img" alt="User">
                        <div class="chat-item-info">
                            <h3 class="chat-item-username"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></h3>
                            <div class="chat-item-preview">
                                <span
                                    class="chat-item-lastmessage
                                <?php if($user->lastMessage && !$user->lastMessage->is_read && $user->lastMessage->sender_id !== Auth::id()): ?> unread <?php endif; ?>">
                                    <!--[if BLOCK]><![endif]--><?php if($user->lastMessage): ?>
                                        <!--[if BLOCK]><![endif]--><?php if($user->lastMessage->sender_id === Auth::id()): ?>
                                            You: <?php echo e(Str::limit($user->lastMessage->message, 18)); ?>

                                        <?php else: ?>
                                            <?php echo e(Str::limit($user->lastMessage->message, 18)); ?>

                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <?php else: ?>
                                        No messages yet.
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </span>
                                <span class="chat-item-time" data-time="<?php echo e($user->lastMessage?->created_at); ?>">
                                    <?php echo e($user->lastMessage?->created_at?->diffForHumans()); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <div class="main-chat" id="mainChat">
    <div class="chat-container">
        <!--[if BLOCK]><![endif]--><?php if($newChatMode): ?>
            <div class="new-chat-header">
                <span>To:</span>
                <input type="text" wire:model.live="searchQuery" class="user-search-input"
                    placeholder="Type a name...">

                <ul class="user-results">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $newChatSearchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li wire:click="selectNewChatUser(<?php echo e($u->id); ?>)"
                            style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <img src="<?php echo e(asset('images/user/' . $u->profile_image)); ?>" class="search-user-img">
                            <span><?php echo e($u->first_name); ?> <?php echo e($u->last_name); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <!--[if BLOCK]><![endif]--><?php if(strlen($searchQuery) > 1): ?>
                            <li>No users found.</li>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </ul>
            </div>

            <div class="messages-container" id="messagesContainer"></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><?php if($selectedUser && !$newChatMode): ?>
            <div class="chat-header">
                <div class="chat-header-left">
                    <div>
                        <img src="<?php echo e(asset('images/user/' . $selectedUser->profile_image)); ?>" alt="User">
                    </div>
                    <div class="chat-header-info">
                        <h2><?php echo e($selectedUser->first_name); ?> <?php echo e($selectedUser->last_name); ?></h2>
                        <p>Online</p>
                    </div>
                </div>
                <div class="chat-header-right">
                    
                    <i class="fi fi-sr-info" wire:click="toggleUserProfile"></i>
                </div>
            </div>

            <div class="messages-container" id="messagesContainer">
                <!--[if BLOCK]><![endif]--><?php if($messages->isEmpty()): ?>
                    <div class="no-messages-user">
                        <p class="no-conversation-placeholder">No messages yet.
                            <br>Start the conversation 👋
                        </p>
                    </div>
                <?php else: ?>
                    <?php
                        // Find the latest message sent by me that is read
                        $latestSeenMessageId = null;
                        foreach ($messages as $msg) {
                            if ($msg->sender_id === Auth::id() && $msg->is_read) {
                                $latestSeenMessageId = $msg->id;
                                break; // messages are in reverse order (latest first)
                            }
                        }
                    ?>

                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $prevMessage = $messages[$index - 1] ?? null;
                            $prevSenderId = $prevMessage ? $prevMessage->sender_id : null;

                            $isToday = $message->created_at->isToday();
                            $tooltip = $isToday
                                ? $message->created_at->format('h:i A')
                                : $message->created_at->format('M d, Y');

                            $isSentByMe = $message->sender_id === Auth::id();
                        ?>
                        
                        <!--[if BLOCK]><![endif]--><?php if($isSentByMe && $message->id === $latestSeenMessageId): ?>
                            <div class="seen-status">
                                <img src="<?php echo e(asset('images/user/' . $selectedUser->profile_image)); ?>"
                                    class="seen-img" title="Seen <?php echo e($tooltip); ?>" alt="Seen">
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <div class="message-row <?php echo e($isSentByMe ? 'sent' : 'received'); ?>">
                            
                            <!--[if BLOCK]><![endif]--><?php if(!$isSentByMe): ?>
                                <!--[if BLOCK]><![endif]--><?php if($prevSenderId !== $message->sender_id): ?>
                                    <div class="sender-info">
                                        <img src="<?php echo e(asset('images/user/' . $selectedUser->profile_image)); ?>"
                                            class="sender-img" alt="User">
                                    </div>
                                <?php else: ?>
                                    <div class="sender-info" style="width: 40px;"></div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <div class="message-data <?php echo e($isSentByMe ? 'sent' : 'received'); ?>"
                                title="<?php echo e($tooltip); ?>">
                                <p><?php echo e($message->message); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>


            <div class="chat-footer">
                <!--[if BLOCK]><![endif]--><?php if($isBlocked): ?>
                    <div class="blocked-status-message">
                        <p>Communication has been temporarily suspended due to potential violations of the system's Terms of Service. Please contact the Guidance Office immediately for a resolution.</p>
                    </div>
                <?php elseif($hasBlocked): ?>
                    <div class="blocked-status-message">
                        <p>You have blocked <span class="blocked-user-name"><?php echo e($selectedUser->first_name); ?></span>. Unblock them to chat.</p>
                    </div>
                <?php else: ?>
                    <form wire:submit="submit" id="messageForm">
                        <input wire:model.live="newMessage" type="text" id="messageInput"
                            placeholder="Type a message..." required>
                        <button type="submit"><i class="fi fi-sr-paper-plane-top"></i></button>
                    </form>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
            </div>

        <?php endif; ?><!--[if ENDBLOCK]><![endif]--> 
        
        <!--[if BLOCK]><![endif]--><?php if($showInactiveUserMessage): ?>
            <div class="no-conversation-holder">
                <img src="<?php echo e(asset('images/img/no-convo.png')); ?>" alt="No convo" class="no-conversation-image">
                <p class="no-conversation-placeholder">
                    This user is currently Inactive and unavailable for messaging.
                </p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        
        <!--[if BLOCK]><![endif]--><?php if($users->isEmpty() && !$newChatMode && !$selectedUser && !$showInactiveUserMessage): ?>
            <div class="no-conversation-holder">
                <img src="<?php echo e(asset('images/img/no-convo.png')); ?>" alt="No convo" class="no-conversation-image">
                <p class="no-conversation-placeholder">
                    Looks like you don't have any conversations yet.<br>Start a new conversation!
                </p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    </div>
</div>
    <!--[if BLOCK]><![endif]--><?php if($selectedUser): ?>
        <div class="user-chat-profile-info <?php echo e($showUserProfile ? 'active' : ''); ?>" id="userChatProfileInfo"
            data-user-id="<?php echo e($selectedUser->id); ?>">
            <div class="user-chat-profile-container">
                
                <div class="user-profile-header" style="position: absolute; top: 10px; right: 10px;">
                    <a href="#" class="close-profile-btn" wire:click.prevent="toggleUserProfile">&times;</a>
                </div>
                <div class="user-profile-img-wrapper">
                    <img src="<?php echo e(asset('images/user/' . $selectedUser->profile_image)); ?>" class="user-profile-img"
                        alt="User">
                </div>
                <div class="user-chat-header-info">
                    <h2 class="user-profile-name"><?php echo e($selectedUser->first_name); ?> <?php echo e($selectedUser->last_name); ?></h2>
                    <p class="user-profile-role"><?php echo e($selectedUser->role); ?></p>
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
                    <div class="privacy-header" wire:click="togglePrivacyDropdown">
                        <div>
                            <h3>Privacy & support</h3>
                        </div>
                        <div>
                            <i class="fi fi-br-angle-small-<?php echo e($showPrivacyDropdown ? 'up' : 'down'); ?>"></i>
                        </div>
                    </div>
                    <div class="privacy-dropdown<?php echo e($showPrivacyDropdown ? ' active' : ''); ?>">
                        <ul>
                            <li>
                                <i class="fi fi-sr-minus-circle"></i>
                                <a href="#" wire:click.prevent="toggleBlockUser">
                                    <?php echo e($hasBlocked ? 'Unblock User' : 'Block User'); ?>

                                </a>
                            </li>
                            <li>
                                <i class="fi fi-sr-trash"></i>
                                <a href="#">Delete chat</a>
                            </li>
                            <!--[if BLOCK]><![endif]--><?php if($isBlocked): ?>
                                <li style="color: #dc3545; font-size: 12px; padding: 5px 15px;">
                                    <i class="fi fi-sr-lock"></i>
                                    You are blocked by this user.
                                </li>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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

        Livewire.on('chat:selected', () => {
            setTimeout(() => {
                const container = document.getElementById('messagesContainer');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }, 50);
        });

        Livewire.on('chat:messageSent', () => {
            setTimeout(() => {
                const container = document.getElementById('messagesContainer');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }, 50);
        });
    });
</script><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/chat.blade.php ENDPATH**/ ?>