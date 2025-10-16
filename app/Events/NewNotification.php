<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $notification;
    public $isAnnouncement;

    public function __construct($notification, $isAnnouncement = false)
    {
        $this->notification = $notification;
        $this->isAnnouncement = $isAnnouncement;
    }

    public function broadcastOn()
    {
        if ($this->isAnnouncement) {
            // Shared channel for announcements
            return new Channel('announcements');
        }

        // User-specific notifications (PRIVATE channel)
        return new PrivateChannel('user.' . $this->notification->user_id);
    }

    public function broadcastAs()
    {
        return 'notification.created';
    }
}
