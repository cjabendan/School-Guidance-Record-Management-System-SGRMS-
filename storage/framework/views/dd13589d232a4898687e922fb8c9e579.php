<section class="w-full p-6">
    <h2 class="text-xl font-semibold mb-2">Profile</h2>
    <p class="text-gray-500 mb-6">Update your name and email address</p>

    <form wire:submit.prevent="updateProfileInformation" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
            <input wire:model="name" type="text"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                required>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input wire:model="email" type="email"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                required>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if(auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail()): ?>
                <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                    Your email address is unverified.
                    <button type="button" wire:click="resendVerificationNotification"
                        class="text-blue-500 hover:underline">Click here to resend verification email.</button>

                    <!--[if BLOCK]><![endif]--><?php if(session('status') === 'verification-link-sent'): ?>
                        <p class="text-green-600 mt-2">A new verification link has been sent to your email address.</p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
            <span wire:loading class="text-gray-500 text-sm">Saving...</span>
        </div>
    </form>
</section>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/head/settings/profile.blade.php ENDPATH**/ ?>