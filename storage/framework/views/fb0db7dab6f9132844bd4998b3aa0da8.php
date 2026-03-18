<div>
    <!--[if BLOCK]><![endif]--><?php if($showDeleteModal): ?>
        <div class="delete-chat-modal-overlay" x-data="{ animating: true }" @keydown.escape="window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('closeModal')">
            <div class="delete-chat-modal-wrapper" x-show="animating" x-transition>
                <div class="delete-chat-modal-content">
                    <!-- Modal Header -->
                    <div class="delete-chat-modal-header">
                        <h3>Delete Chat</h3>
                        <button type="button" wire:click="closeModal" class="delete-chat-modal-close" aria-label="Close modal">
                            <i class="fi fi-sr-cross"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="delete-chat-modal-body">
                        <div class="delete-chat-warning-icon">
                            <i class="fi fi-sr-triangle-warning"></i>
                        </div>

                        <p class="delete-chat-modal-title">
                            Are you sure you want to delete this chat?
                        </p>

                        <p class="delete-chat-modal-subtitle">
                            This will permanently delete all messages with <strong><?php echo e($chatUserName); ?></strong>. This action cannot be undone.
                        </p>

                        <!-- Checkbox for confirmation -->
                        <div class="delete-chat-confirmation-checkbox">
                            <input type="checkbox" id="deleteConfirm" wire:model.live="deleteConfirmed">
                            <label for="deleteConfirm">
                                I understand that this action is permanent and cannot be reversed.
                            </label>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="delete-chat-modal-footer">
                        <button type="button" wire:click="closeModal" class="delete-chat-btn-cancel">
                            Cancel
                        </button>
                        <button type="button" wire:click="confirmDelete"
                            class="delete-chat-btn-delete <?php echo e($deleteConfirmed ? '' : 'disabled'); ?>"
                            <?php echo e($deleteConfirmed ? '' : 'disabled'); ?> wire:loading.attr="disabled">
                            <span wire:loading.remove>Delete Chat</span>
                            <span wire:loading>
                                <i class="fi fi-sr-spinner" style="animation: spin 1s linear infinite;"></i> Deleting...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <style>
        .delete-chat-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
        }

        .delete-chat-modal-wrapper {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delete-chat-modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            max-width: 420px;
            width: 90%;
            overflow: hidden;
            animation: slideUp 0.3s ease-out;
        }

        .delete-chat-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 24px 16px;
            border-bottom: 1px solid #f0f0f0;
        }

        .delete-chat-modal-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .delete-chat-modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .delete-chat-modal-close:hover {
            background-color: #f5f5f5;
            color: #333;
        }

        .delete-chat-modal-body {
            padding: 24px;
            text-align: center;
        }

        .delete-chat-warning-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background-color: #fee2e2;
            border-radius: 50%;
            margin: 0 auto 16px;
            font-size: 32px;
            color: #dc2626;
        }

        .delete-chat-modal-title {
            margin: 0 0 12px;
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .delete-chat-modal-subtitle {
            margin: 0 0 24px;
            font-size: 14px;
            color: #666;
            line-height: 1.6;
        }

        .delete-chat-confirmation-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            background-color: #f9f9f9;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .delete-chat-confirmation-checkbox input[type="checkbox"] {
            margin-top: 3px;
            cursor: pointer;
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .delete-chat-confirmation-checkbox label {
            font-size: 13px;
            color: #666;
            cursor: pointer;
            text-align: left;
            margin: 0;
        }

        .delete-chat-modal-footer {
            display: flex;
            gap: 12px;
            padding: 16px 24px 24px;
            border-top: 1px solid #f0f0f0;
        }

        .delete-chat-btn-cancel,
        .delete-chat-btn-delete {
            flex: 1;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .delete-chat-btn-cancel {
            background-color: #f5f5f5;
            color: #333;
        }

        .delete-chat-btn-cancel:hover {
            background-color: #e8e8e8;
        }

        .delete-chat-btn-delete {
            background-color: #dc2626;
            color: white;
        }

        .delete-chat-btn-delete:hover:not(.disabled) {
            background-color: #b91c1c;
        }

        .delete-chat-btn-delete.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .delete-chat-modal-content {
                width: 95%;
            }

            .delete-chat-modal-header,
            .delete-chat-modal-body,
            .delete-chat-modal-footer {
                padding: 16px;
            }

            .delete-chat-modal-header h3 {
                font-size: 16px;
            }
        }
    </style>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/modals/delete-chat-modal.blade.php ENDPATH**/ ?>