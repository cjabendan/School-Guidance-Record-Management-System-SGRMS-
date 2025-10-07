<div class="settings-form">
    <div class="settings-form-header">
        <p class="settings-form-heading">Profile</p>
        <p class="settings-form-subheading">Update your name and email address.</p>
    </div>

    <div class="settings-flex-row">
        <!--[if BLOCK]><![endif]--><?php if(Auth::user()->role === 'admin'): ?>
            <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
                <label class="settings-form-label">First name</label>
                <input type="text" value="<?php echo e(Auth::user()->first_name); ?>" wire:model="name"
                    class="settings-form-input">
            </div>
            <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
                <label class="settings-form-label">Middle name</label>
                <input type="text" value="<?php echo e(Auth::user()->middle_name); ?>" wire:model="middle_name"
                    class="settings-form-input">
            </div>
            <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
                <label class="settings-form-label">Last name</label>
                <input type="text" value="<?php echo e(Auth::user()->last_name); ?>" wire:model="last_name"
                    class="settings-form-input">
            </div>
        <?php else: ?>
            <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
                <label class="settings-form-label">Name</label>
                <input type="text"
                    value="<?php echo e(Auth::user()->first_name . ' ' . Auth::user()->middle_name . ' ' . Auth::user()->last_name); ?>"
                    class="settings-form-input" disabled>
            </div>
            <div class="flex-1"></div>
            <div class="flex-1"></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <div class="settings-flex-row">
        <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
            <label class="settings-form-label">Email</label>
            <input type="email" value="<?php echo e(Auth::user()->email); ?>" wire:model="email" class="settings-form-input">
        </div>
        <div class="flex-1"></div>
        <div class="flex-1"></div>
    </div>

    <div>
        <button wire:click="save" class="settings-form-button">Save</button>
    </div>

    <!--[if BLOCK]><![endif]--><?php if(Auth::user()->role === 'admin'): ?>
        <div class="settings-form-footer">
            <p class="settings-form-heading">Transfer privilege</p>
            <p class="settings-form-subheading">Transfer your admin role or delete this account.</p>
        </div>

        <div>
            <button wire:click="transferPrivilege" class="settings-form-button delete">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="icons">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>

                Transfer role</button>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
        <p class="text-green-400 mt-2"><?php echo e(session('success')); ?></p>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/settings/profile.blade.php ENDPATH**/ ?>