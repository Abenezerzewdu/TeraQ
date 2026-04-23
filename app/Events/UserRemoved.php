<?php

namespace App\Events;

use App\Models\QueueEntry;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRemoved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $entry;

    public function __construct(QueueEntry $entry)
    {
        $this->entry = $entry;
    }

    public function broadcastOn()
    {
        return new Channel('queue.' . $this->entry->queue_id);
    }

    public function broadcastAs()
    {
        return 'user.removed';
    }
}
