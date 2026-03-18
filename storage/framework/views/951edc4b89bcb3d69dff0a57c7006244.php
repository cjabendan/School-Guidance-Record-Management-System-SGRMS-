<div class="chat-app">
    
    <?php echo $__env->make('livewire.chat-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('livewire.chat-main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <!--[if BLOCK]><![endif]--><?php if($selectedUser): ?>
        <?php echo $__env->make('livewire.user-profile-pane', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('modals.delete-chat-modal');

$__html = app('livewire')->mount($__name, $__params, 'lw-319146344-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        // ... (Existing listeners for requestProfilePaneState and saveProfilePaneState) ...
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

    });
</script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/chat.blade.php ENDPATH**/ ?>