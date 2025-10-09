<div class="main-chat" id="mainChat">
    <div class="chat-container">
        <!--[if BLOCK]><![endif]--><?php if($newChatMode): ?>
            <div class="new-chat-header">
                <span>To:</span>
                <input type="text" wire:model.live="searchQuery" class="user-search-input" placeholder="Type a name...">

                <ul class="user-results">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $newChatSearchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li wire:click="selectNewChatUser(<?php echo e($u->id); ?>)"
                            style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <img src="<?php echo e(asset('images/user/' . $u->profile_image)); ?>" class="search-user-img">
                            <span><?php echo e($u->first_name); ?> <?php echo e($u->last_name); ?><!--[if BLOCK]><![endif]--><?php if(in_array($u->role, ['admin', 'counselor'])): ?>
                                    <i class="fi fi-sr-badge-check official-badge" title="Verified"></i>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </span>
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
                        <h2><?php echo e($selectedUser->first_name); ?> <?php echo e($selectedUser->last_name); ?> <!--[if BLOCK]><![endif]--><?php if(in_array($selectedUser->role, ['admin', 'counselor'])): ?>
                                    <i class="fi fi-sr-badge-check official-badge" title="Verified"></i>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]--></h2>
                        <p>Online</p>
                    </div>
                </div>
                <div class="chat-header-right">
                    
                    <i class="fi fi-sr-info" wire:click="toggleUserProfile"></i>
                </div>
            </div>

            <div class="messages-container" id="messagesContainer" >
                <!--[if BLOCK]><![endif]--><?php if($messages->isEmpty()): ?>
                    <div class="no-messages-user">
                        <p class="no-conversation-placeholder">No messages yet.
                            <br>Start the conversation 👋
                        </p>
                    </div>
                <?php else: ?>
                    <?php
                        $latestSeenMessageId = null;
                        foreach ($messages as $msg) {
                            if ($msg->sender_id === Auth::id() && $msg->is_read) {
                                $latestSeenMessageId = $msg->id;
                                break;
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
                                <img src="<?php echo e(asset('images/user/' . $selectedUser->profile_image)); ?>" class="seen-img"
                                    title="Seen <?php echo e($tooltip); ?>" alt="Seen">
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <div class="message-row <?php echo e($isSentByMe ? 'sent' : 'received'); ?>"
                            id="message-<?php echo e($message->id); ?>">
                            
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
                        <p>Communication has been temporarily suspended due to potential violations of the system's
                            Terms of Service. Please contact the Guidance Office immediately for a resolution.</p>
                    </div>
                <?php elseif($hasBlocked): ?>
                    <div class="blocked-status-message">
                        <p>You have blocked <span class="blocked-user-name"><?php echo e($selectedUser->first_name); ?></span>.
                            Unblock them to chat.</p>
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
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/chat-main.blade.php ENDPATH**/ ?>