<div class="settings-form">
    <div class="settings-form-header">
        <p class="settings-form-heading">Database Backup</p>
        <p class="settings-form-subheading">
            Set your preferred backup frequency and download backups when needed.
        </p>
    </div>

    
    <div style="display: flex; flex-direction: column; gap: 1.5rem; width: 58%;">
        <div>
            <p class="settings-form-subheading" style="font-size: 16px;">
                Choose how often your system automatically creates database backups.
                You can also manually trigger a backup anytime.
            </p>
        </div>

        
        <div>
            <label class="settings-form-subheading">Backup Frequency</label>
            <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                <label>
                    <input type="radio" wire:model.live="frequency" value="weekly"> Weekly
                </label>
                <label>
                    <input type="radio" wire:model.live="frequency" value="monthly"> Monthly
                </label>
            </div>

            <!--[if BLOCK]><![endif]--><?php if($message && !in_array($backupStatus, ['running', 'completed', 'failed'])): ?>
                <p class="settings-form-subheading" style="font-size: 14px; color: blue; margin-top: 0.5rem;">
                    <?php echo e($message); ?>

                </p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <!--[if BLOCK]><![endif]--><?php if($downloadPath && $backupStatus === 'completed'): ?>
                <button type="button" class="settings-form-button" style="background-color:#4caf50;"
                    wire:click="afterDownload('<?php echo e($downloadPath); ?>')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="icons">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download Backup
                </button>
            <?php else: ?>
                <button wire:click="backup" wire:loading.attr="disabled" class="settings-form-button"
                    style="background-color: <?php echo e($backupStatus === 'running' ? '#f0ad4e' : ''); ?>;">
                    <span wire:loading.remove>
                        <?php echo e($backupStatus === 'running' ? 'Backing up...' : 'Backup Now'); ?>

                    </span>
                    <span wire:loading>Processing...</span>
                </button>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if($message && in_array($backupStatus, ['failed', 'completed'])): ?>
                <p class="settings-form-subheading"
                    style="font-size:14px; color:<?php echo e($backupStatus === 'failed' ? 'red' : 'green'); ?>;">
                    <?php echo $message; ?>

                </p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

    </div>
</div>
<script>
document.addEventListener('download-file', e => {
    window.open(e.detail.url, '_blank');
});
</script>

<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/settings/backup-database.blade.php ENDPATH**/ ?>