<div id="chatList" class="chat-list">
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('Head.messages', ['user' => $user->id])); ?>"
            class="chat-item <?php echo e($selectedUser && $selectedUser->id === $user->id ? 'active' : ''); ?>">
            <img src="<?php echo e(asset('images/user/' . $user->profile_image)); ?>" class="user-img" alt="User">
            <div class="chat-item-info">
                <h3 class="chat-item-username">
                    <?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>

                    <?php if(in_array($user->role, ['admin', 'counselor'])): ?>
                        <i class="fi fi-sr-badge-check official-badge" title="Verified"></i>
                    <?php endif; ?>
                </h3>
                <div class="chat-item-preview">
                    <span class="chat-item-lastmessage
                        <?php if($user->lastMessage && !$user->lastMessage->is_read && $user->lastMessage->sender_id !== Auth::id()): ?> unread <?php endif; ?>">
                        <?php if($user->lastMessage): ?>
                            <?php if($user->lastMessage->sender_id === Auth::id()): ?>
                                You: <?php echo e(Str::limit($user->lastMessage->message, 18)); ?>

                            <?php else: ?>
                                <?php echo e(Str::limit($user->lastMessage->message, 18)); ?>

                            <?php endif; ?>
                        <?php else: ?>
                            No messages yet.
                        <?php endif; ?>
                    </span>
                    <span class="chat-item-time" data-time="<?php echo e($user->lastMessage?->created_at); ?>">
                        <?php echo e($user->lastMessage?->created_at?->diffForHumans()); ?>

                    </span>
                </div>
            </div>
        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/chat-list-dashboard.blade.php ENDPATH**/ ?>