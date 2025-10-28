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
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </h2>
                        <!--[if BLOCK]><![endif]--><?php if($selectedUser->is_online): ?>
                            <p>Active now  <span class="status-online"></span></p>
                        <?php else: ?>
                            <p class="status-offline">Offline</p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <div class="chat-header-right">
                    <i class="fi fi-sr-info" wire:click="toggleUserProfile"></i>
                </div>
            </div>

            <div class="messages-container" id="messagesContainer" x-data="{
                // 1. Bind the Livewire property to an Alpine variable
                messageToScroll: <?php if ((object) ('scrollToMessageId') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('scrollToMessageId'->value()); ?>')<?php echo e('scrollToMessageId'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('scrollToMessageId'); ?>')<?php endif; ?>.live
            }" x-init="// 2. Use $watch to monitor the property for changes
            $watch('messageToScroll', (messageId) => {
                if (messageId !== null) {
                    // $nextTick() ensures we wait for Livewire's DOM update to complete
                    $nextTick(() => {
                        const messageRow = document.getElementById(`message-${messageId}`);
            
                        if (messageRow) {
                            const messageBubble = messageRow.querySelector('.message-data');
            
                            messageRow.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
            
                            // Apply highlight
                            if (messageBubble) {
                                messageBubble.classList.add('highlight-message');
            
                                setTimeout(() => {
                                    messageBubble.classList.remove('highlight-message');
                                }, 100000);
                            }
            
                            // Use Livewire.dispatchSelf to avoid full page reloads if possible.
                            Livewire.dispatchSelf('resetScrollId');
                        }
                    });
                }
            });">
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

                            $highlightedMessage = $message->message;
                            $searchQuery = $this->profileSearchQuery ?? '';

                            if ($message->id === $this->messageIdToHighlight && !empty($searchQuery)) {
                                $escapedQuery = preg_quote($searchQuery, '/');

                                $pattern = '/(' . $escapedQuery . ')/i';

                                $replacement = '<span class="highlight-result-word">$1</span>';

                                $highlightedMessage = preg_replace($pattern, $replacement, $message->message);
                            }
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
                                <p><?php echo $highlightedMessage; ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>


            <div class="chat-footer">
                <!--[if BLOCK]><![endif]--><?php if($isBlocked): ?>
                    <div class="blocked-status-message">
                        <p class="block-message">You’ve been blocked by the counselor and you can’t send or receive
                            messages
                            in this chat.</p>
                        <p class="block-reason">This action was taken due to a policy violation. Please report to the
                            Guidance Office to
                            resolve the issue.</p>
                    </div>
                <?php elseif($hasBlocked): ?>
                    <div iv class="blocked-status-message">
                        <p>You blocked messages from <span class="blocked-user-name"><?php echo e($selectedUser->first_name); ?>

                                <?php echo e($selectedUser->last_name); ?></span>'s account.</p>
                        <p class="block-reason">You can't message in this chat, and you won't receive their messages.
                        </p>

                        <a href="#" wire:click.prevent="toggleBlockUser" class="block-btn">
                            <?php echo e($hasBlocked ? 'Unblock' : 'Block'); ?>

                        </a>


                    </div>
                <?php else: ?>
                    <form wire:submit="submit" id="messageForm">
                        <input wire:model="newMessage" type="text" id="messageInput" placeholder="Type a message..."
                            required x-data
                            @input.debounce.300ms="
        Livewire.dispatch('userTyping', {
            userID: <?php echo e(auth()->id()); ?>,
            profileImage: '<?php echo e(auth()->user()->profile_image); ?>',
            selectedUserID: <?php echo e($selectedUser?->id); ?>

        })
    ">
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