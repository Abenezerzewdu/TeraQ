<?php
namespace App\Services;

use App\Events\UserJoinedQueue;
use App\Models\Queue;
use App\Models\QueueEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueService
{
    public function joinQueue(Queue $queue, Request $request): QueueEntry
    {
        return DB::transaction(function () use ($queue, $request) {


        //prevent duplicate joins
        $existing = $queue->entries()
    ->where(function ($query) use ($request) {
        $query->where('device_id', $request->device_id);

        if ($request->phone) {
            $query->orWhere('phone', $request->phone);
        }
    })
    ->where('status', 'waiting')
    ->first();

if ($existing) {
    return $existing;
}

        //get lastposition
            $lastPosition = $queue->entries()
                ->lockForUpdate()
                ->max('position') ?? 0;
                //create an entry to the queue
     $entry=$queue->entries()->create([
                'user_name' => $request->name ?? 'Guest',
                 'device_id' => $request->device_id,
                 'phone' => $request->phone,
                 'position' => $lastPosition + 1,
                 'status' => 'waiting',
            ]);
            //trigger event for websocket
                  event(new UserJoinedQueue($entry));
            return $entry;
        });
    }
}