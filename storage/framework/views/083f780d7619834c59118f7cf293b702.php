<div class="settings-form">
    <div class="settings-form-header">
        <p class="settings-form-heading">Update password</p>
        <p class="settings-form-subheading">Ensure your account is using a long, random password to stay secure.</p>
    </div>

    <div class="settings-flex-row">

       <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
            <label class="settings-form-label">Current Password</label>
            <input type="password" wire:model="current_password" class="settings-form-input">
        </div>
        <div class="flex-1"></div>
        <div class="flex-1"></div>
    </div>

    <div class="settings-flex-row">
        <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
            <label class="settings-form-label">New Password</label>
            <input type="password" wire:model="new_password" class="settings-form-input">
        </div>
        <div class="flex-1"></div>
        <div class="flex-1"></div>
    </div>

    <div class="settings-flex-row">
        <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
            <label class="settings-form-label">Confirm Password</label>
            <input type="password" wire:model="confirm_password" class="settings-form-input">
        </div>
        <div class="flex-1"></div>
        <div class="flex-1"></div>
    </div>

    <div>
        <button wire:click="save" class="settings-form-button">Save</button>
    </div>

    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
        <p class="text-green-400 mt-2"><?php echo e(session('success')); ?></p>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/settings/password.blade.php ENDPATH**/ ?>