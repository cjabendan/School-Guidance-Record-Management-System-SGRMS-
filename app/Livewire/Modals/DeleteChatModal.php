<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatMessage;

class DeleteChatModal extends Component
{
    // Public properties
    public $showDeleteModal = false;
    public $chatUserToDelete = null;
    public $chatUserName = '';
    public $deleteConfirmed = false;

    protected $listeners = [
        'openDeleteChatModal' => 'openModal',
    ];

    /**
     * Open the delete chat modal
     * Called from parent component (Chat.php)
     */
    public function openModal($userId)
    {
        $user = \App\Models\User::find($userId);

        if ($user) {
            $this->chatUserToDelete = $userId;
            $this->chatUserName = $user->first_name . ' ' . $user->last_name;
            $this->showDeleteModal = true;
            $this->deleteConfirmed = false;
        }
    }

    /**
     * Close the modal without deleting
     */
    public function closeModal()
    {
        $this->showDeleteModal = false;
        $this->chatUserToDelete = null;
        $this->chatUserName = '';
        $this->deleteConfirmed = false;
    }

    /**
     * Confirm the deletion - this will actually delete the chat
     */
    public function confirmDelete()
    {
        if (!$this->chatUserToDelete) {
            return;
        }

        $authId = Auth::id();

        // Delete all messages between the authenticated user and the selected user
        ChatMessage::where(function ($query) use ($authId) {
            $query->where('sender_id', $authId)
                ->orWhere('receiver_id', $authId);
        })
            ->where(function ($query) use ($authId) {
                $query->where([
                    ['sender_id', $authId],
                    ['receiver_id', $this->chatUserToDelete],
                ])
                    ->orWhere([
                        ['sender_id', $this->chatUserToDelete],
                        ['receiver_id', $authId],
                    ]);
            })
            ->delete();

        // Dispatch event to refresh the chat list in parent component
        $this->dispatch('chatDeleted', userId: $this->chatUserToDelete);

        // Close modal
        $this->closeModal();

        // Show success message
        session()->flash('success', 'Chat with ' . $this->chatUserName . ' has been deleted.');
    }

    public function render()
    {
        return view('livewire.modals.delete-chat-modal');
    }
}
