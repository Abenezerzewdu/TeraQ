<?php
namespace App\Services;

use App\Events\UserJoinedQueue;
use App\Events\UserLeftQueue;
use App\Models\Queue;
use App\Models\QueueEntry;
use Illuminate\Http\Request;
use Illuminate\Queue\Events\QueueBusy;
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

    public function leaveQueue(Queue $queue, Request $request)
    {
        $entry = $queue->entries()->where('device_id', $request->device_id)->where('status', 'waiting')
            ->first();

        if (!$entry) {
            return null;
        }

        $entry->update([
            'status' => 'left',
        ]);

        $queue->entries()
            ->where('status', 'waiting')
            ->where('position', '>', $entry->position)
            ->decrement('position');

        event(new UserLeftQueue($entry));
        return $entry;
    }

    public function startServingNext(Queue $queue)
    {
        return DB::transaction(function () use ($queue) {
            // Finish any currently serving
            $queue->entries()->where('status', 'serving')->update([
                'status' => 'done',
                'served_at' => now(),
            ]);

            // Get the next in line
            $next = $queue->entries()
                ->where('status', 'waiting')
                ->orderBy('position')
                ->first();

            if ($next) {
                $next->update([
                    'status' => 'serving',
                    'position' => 0,
                ]);

                // Shift everyone else up
                $queue->entries()
                    ->where('status', 'waiting')
                    ->decrement('position');

                event(new \App\Events\UserServingStarted($next));
            }

            return $next;
        });
    }
}