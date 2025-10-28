<div id="chatList" class="chat-list" wire:poll.3s="loadChatList">
    <?php
    
        $roleToPrefix = [
            'admin' => 'Head',
            'counselor' => 'Counselor',
            'parent' => 'Parent',
            'student' => 'Student',
        ];

        $rolePrefix = 'Head';
        if (auth()->check()) {
            $roleValue = strtolower(auth()->user()->role ?? '');
            if (isset($roleToPrefix[$roleValue])) {
                $candidate = $roleToPrefix[$roleValue];
                $candidateRoute = $candidate . '.messages';
                if (\Illuminate\Support\Facades\Route::has($candidateRoute)) {
                    $rolePrefix = $candidate;
                } elseif (\Illuminate\Support\Facades\Route::has('Head.messages')) {
                    // fallback to Head if candidate route is not defined
                    $rolePrefix = 'Head';
                }
            } else {
                // unknown role: fallback to Head if available
                if (\Illuminate\Support\Facades\Route::has('Head.messages')) {
                    $rolePrefix = 'Head';
                }
            }
        }
    ?>
    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route($rolePrefix . '.messages', ['user' => $user->id])); ?>" class="chat-item">
            <img src="<?php echo e(asset('images/user/' . $user->profile_image)); ?>" class="user-img" alt="User">
            <div class="chat-item-info">
                <h3 class="chat-item-username">
                    <?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>

                    <!--[if BLOCK]><![endif]--><?php if(in_array($user->role, ['admin', 'counselor'])): ?>
                        <i class="fi fi-sr-badge-check official-badge" title="Verified"></i>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </h3>
                <div class="chat-item-preview">
                    <span class="chat-item-lastmessage
                        <?php if($user->lastMessage && !$user->lastMessage->is_read && $user->lastMessage->sender_id !== Auth::id()): ?> unread <?php endif; ?>">
                        <!--[if BLOCK]><![endif]--><?php if($user->lastMessage): ?>
                            <!--[if BLOCK]><![endif]--><?php if($user->lastMessage->sender_id === Auth::id()): ?>
                                You: <?php echo e($user->lastMessage->message); ?>

                            <?php else: ?>
                                <?php echo e($user->lastMessage->message); ?>

                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php else: ?>
                            No messages yet.
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </span>
                    <span class="chat-item-time" data-time="<?php echo e($user->lastMessage?->created_at); ?>">
                        <?php echo e($this->formatTimeShort($user->lastMessage?->created_at)); ?> 
                    </span>
                </div>
            </div>
        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/dashboard-chat-list.blade.php ENDPATH**/ ?>