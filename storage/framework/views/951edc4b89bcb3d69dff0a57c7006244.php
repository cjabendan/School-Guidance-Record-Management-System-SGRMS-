<div class="chat-app">
    
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
                            class="chat-nav <?php echo e(request('category') == 'recent' || !request()->has('category') ? 'active' : ''); ?>"
                            data-filter="recent">All</a>
                    </li>
                    <li>
                        <a href="#" class="chat-nav <?php echo e(request('category') == 'unread' ? 'active' : ''); ?>"
                            data-filter="unread">Unread</a>
                    </li>
                    <li>
                        <a href="#" class="chat-nav <?php echo e(request('category') == 'counselor' ? 'active' : ''); ?>"
                            data-filter="counselor">Counselors</a>
                    </li>
                </ul>
            </div>

        </div>
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
                                        You: <?php echo e(Str::limit($user->lastMessage->message, 20)); ?>

                                    <?php else: ?>
                                        <?php echo e(Str::limit($user->lastMessage->message, 20)); ?>

                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php else: ?>
                                    No messages yet.
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </span>
                            <span class="chat-item-time"
                                data-time="<?php echo e($user->lastMessage?->created_at?->diffForHumans()); ?>">
                                <?php echo e($user->lastMessage?->created_at?->diffForHumans()); ?>

                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

    </div>

    
    <div class="main-chat" id="mainChat">
        
        <!--[if BLOCK]><![endif]--><?php if($newChatMode): ?>
            <div class="new-chat-header">
                <span>To:</span>
                <input type="text" wire:model.live="searchQuery" class="user-search-input"
                    placeholder="Type a name...">

                <ul class="user-results">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $searchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                    <i class="fi fi-sr-info"></i>
                </div>
            </div>

            <div class="messages-container" id="messagesContainer">
                <!--[if BLOCK]><![endif]--><?php if($messages->isEmpty()): ?>
                    <div class="no-messages-user">
                        <p>No messages yet. Start the conversation 👋</p>
                    </div>
                <?php else: ?>
                    <?php $nextSenderId = null; ?>

                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $nextMessage = $messages[$index + 1] ?? null;
                            $nextSenderId = $nextMessage ? $nextMessage->sender_id : null;
                        ?>

                        <div class="message-row <?php echo e($message->sender_id === Auth::id() ? 'sent' : 'received'); ?>">
                            <!--[if BLOCK]><![endif]--><?php if($message->sender_id !== Auth::id()): ?>
                                
                                <!--[if BLOCK]><![endif]--><?php if($nextSenderId !== $message->sender_id): ?>
                                    <div class="sender-info">
                                        <img src="<?php echo e(asset('images/user/' . $selectedUser->profile_image)); ?>"
                                            class="sender-img" alt="User">
                                    </div>
                                <?php else: ?>
                                    <div class="sender-info" style="width: 40px;"></div> 
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <div class="message-data <?php echo e($message->sender_id === Auth::id() ? 'sent' : 'received'); ?>">
                                <p><?php echo e($message->message); ?></p>
                            </div>

                            <span class="message-time"><?php echo e($message->created_at->diffForHumans()); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->


                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>


            <div class="chat-footer">
                <form wire:submit="submit" id="messageForm">
                    <input wire:model.live="newMessage" type="text" id="messageInput" placeholder="Type a message..."
                        required>
                    <button type="submit"><i class="fi fi-sr-paper-plane-top"></i></button>
                </form>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/chat.blade.php ENDPATH**/ ?>