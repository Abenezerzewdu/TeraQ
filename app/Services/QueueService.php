<?php
namespace App\Services;

use App\Events\UserJoinedQueue;
use App\Models\Queue;
use App\Models\QueueEntry;
use Illuminate\Support\Facades\DB;

class QueueService
{
    public function joinQueue(Queue $queue, string $name): QueueEntry
    {
        return DB::transaction(function () use ($queue, $name) {

            $lastPosition = $queue->entries()
                ->lockForUpdate()
                ->max('position') ?? 0;
                //create an entry to the queue
     $entry=$queue->entries()->create([
                'user_name' => $name,
                'position' => $lastPosition + 1,
                'status' =>'waiting',
            ]);
            //trigger event for websocket
                  event(new UserJoinedQueue($entry));
            return $entry;
        });
    }
}