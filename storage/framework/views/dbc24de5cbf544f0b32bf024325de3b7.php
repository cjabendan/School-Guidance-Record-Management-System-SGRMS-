<div class="user-chat-profile-info <?php echo e($showUserProfile ? 'active' : ''); ?>" id="userChatProfileInfo"
    data-user-id="<?php echo e($selectedUser->id); ?>" x-data="{
        showPrivacy: false,
        searchMode: false
    }">
    <div class="user-chat-profile-container">

        <div x-show="!searchMode" x-transition>
            <div class="user-profile-img-wrapper">
                <img src="<?php echo e(asset('images/user/' . $selectedUser->profile_image)); ?>" class="user-profile-img"
                    alt="User">
            </div>
            <div class="user-chat-header-info">
                <h2 class="user-profile-name"><?php echo e($selectedUser->first_name); ?> <?php echo e($selectedUser->last_name); ?><!--[if BLOCK]><![endif]--><?php if(in_array($selectedUser->role, ['admin', 'counselor'])): ?>
                        <i class="fi fi-sr-badge-check official-badge" title="Verified"></i>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </h2>
                <p class="user-profile-role"><?php echo e($selectedUser->role); ?></p>
            </div>
            <div class="user-chat-section">
                <ul>
                    <li class="user-chat-icon">
                        <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&tf=1&to=<?php echo e($selectedUser->email); ?>"
                            target="_blank">
                            <i class="fi fi-sr-envelope"></i>
                        </a>
                        <p>Email</p>
                    </li>
                    <li class="user-chat-icon">
                        <a href="tel:<?php echo e($selectedUser->phone); ?>" target="_blank">
                            <i class="fi fi-sr-phone-call"></i>
                        </a>
                        <p>Contact</p>
                    </li>

                    <li class="user-chat-icon" @click="searchMode = true" style="cursor: pointer;">
                        <a href="#"><i class="fi fi-br-search"></i></a>
                        <p>Search</p>
                    </li>
                </ul>
            </div>
            <div class="user-chat-privacy">
                <!--[if BLOCK]><![endif]--><?php if(in_array($selectedUser->role, ['parent', 'student'])): ?>
                    <div class="privacy-header" @click="showPrivacy = !showPrivacy" style="cursor: pointer;">
                        <div>
                            <h3>Privacy & support</h3>
                        </div>
                        <div>
                            
                            <i :class="showPrivacy ? 'fi fi-br-angle-small-up' : 'fi fi-br-angle-small-down'"></i>
                        </div>
                    </div>

                    <div class="privacy-dropdown" x-show="showPrivacy" x-transition :class="{ 'active': showPrivacy }">
                        <ul>
                            <li wire:click.prevent="toggleBlockUser" style="cursor: pointer;">
                                <i class="fi fi-sr-minus-circle"></i>
                                <a href="#"><?php echo e($hasBlocked ? 'Unblock' : 'Block'); ?></a>
                            </li>
                            <li style="cursor: pointer;">
                                <i class="fi fi-sr-trash"></i>
                                <a href="#">Delete chat</a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        
        <div x-show="searchMode" x-transition style="display: none;">
            <div class="search-mode-header" style="display: flex; align-items: center; padding: 10px;">
                
                <a href="#"
                    @click.prevent="searchMode = false; $wire.set('profileSearchQuery', ''); $wire.set('profileSearchResults', [])"
                    style="margin-right: 10px;">
                    <i class="fi fi-br-angle-left"></i>
                </a>
                <form wire:submit.prevent="searchInConversation" style="flex-grow: 1;">
                    <input type="text" wire:model="profileSearchQuery"
                        wire:keydown.enter.prevent="searchInConversation" placeholder="Search in conversation..."
                        class="search-input">
                </form>
            </div>

            
            <div class="search-results-list" style="padding: 10px; overflow-y: auto;">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $profileSearchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php

                        $isSentByMe = $message->sender_id === Auth::id();
                        $sender = $isSentByMe ? Auth::user() : $selectedUser;

                        $authenticatedUser = Auth::user();

                        $senderName = $isSentByMe
                            ? $authenticatedUser->first_name . ' ' . $authenticatedUser->last_name
                            : $sender->first_name . ' ' . $sender->last_name;

                        $senderImage = asset('images/user/' . $sender->profile_image);
                    ?>

                    <div wire:click="goToMessage(<?php echo e($message->id); ?>)"
                        style="padding: 10px; border-bottom: 1px dashed #eee; cursor: pointer; border-radius: 4px; transition: background-color 0.2s;"
                        onmouseover="this.style.backgroundColor='#f9f9f9'"
                        onmouseout="this.style.backgroundColor='white'">

                        <div class="search-convo-result-item">
                            <div class="scri-img">
                                <img src="<?php echo e($senderImage); ?>">
                            </div>
                            <div class="scri-content">
                                <p class="scri-name"><?php echo e($senderName); ?>

                                <p>
                                <p class="scri-message">
                                    <?php echo preg_replace(
                                        '/\b(' . preg_quote($profileSearchQuery, '/') . ')\b/i',
                                        '<b class="highlight-word">$1</b>',
                                        e($message->message),
                                    ); ?>

                                    • <span class="scri-time"><?php echo e($message->created_at->diffForHumans()); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <!--[if BLOCK]><![endif]--><?php if(!empty($profileSearchQuery)): ?>
                        <div
                            style="display: flex; flex-direction: column; padding: 10px 20px; text-align: center; gap: 10px;">
                            <p style="color: #999;">
                                No messages found matching "<span
                                    style="font-weight: bold;"><?php echo e($profileSearchQuery); ?></span>" in this chat.
                            </p>
                            <small style="color: #bbb;">Try a different word or phrase.</small>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center; color: #999;">Enter a word or phrase to search
                            messages in this conversation.</p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/user-profile-pane.blade.php ENDPATH**/ ?>