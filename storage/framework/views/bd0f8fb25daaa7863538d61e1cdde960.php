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
                        <span><?php echo e($u->first_name); ?> <?php echo e($u->last_name); ?><!--[if BLOCK]><![endif]--><?php if(in_array($u->role, ['admin', 'counselor'])): ?>
                                    <i class="fi fi-sr-badge-check official-badge" title="Verified"></i>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </span>
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
                                class="chat-nav <?php echo e($filter == 'unread' ? 'active' : ''); ?>" data-filter="unread">Unread</a>
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
                            <h3 class="chat-item-username"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?><!--[if BLOCK]><![endif]--><?php if(in_array($user->role, ['admin', 'counselor'])): ?>
                                    <i class="fi fi-sr-badge-check official-badge" title="Verified"></i>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </h3>
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
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/chat-sidebar.blade.php ENDPATH**/ ?>