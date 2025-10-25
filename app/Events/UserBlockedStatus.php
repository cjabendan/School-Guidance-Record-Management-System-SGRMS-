<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserBlockedStatus implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $blockedId;
    public $blockerId;
    public $isBlocked; 

    public function __construct($blockedId, $blockerId, $isBlocked)
    {
        $this->blockedId = $blockedId;
        $this->blockerId = $blockerId;
        $this->isBlocked = $isBlocked;
    }

    public function broadcastOn(): array
    {
       
        return [
            new PrivateChannel("chat.{$this->blockedId}"),
        ];
    }

 
    public function broadcastWith() 
    {
        return [
            'blockedId' => $this->blockedId,
            'blockerId' => $this->blockerId,
            'isBlocked' => $this->isBlocked,
        ];
    }
}