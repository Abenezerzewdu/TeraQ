<?php

namespace App\Services;

use App\Events\UserJoinedQueue;
use App\Events\UserLeftQueue;
use App\Events\UserRemoved;
use App\Events\UserServingStarted;
use App\Models\Queue;
use App\Models\QueueEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueService
{
    /**
     * Add a user to the queue, preventing duplicate entries by device ID or phone.
     */
    public function joinQueue(Queue $queue, Request $request): QueueEntry
    {
        return DB::transaction(function () use ($queue, $request) {
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

            $lastPosition = $queue->entries()->lockForUpdate()->max('position') ?? 0;

            $entry = $queue->entries()->create([
                'user_id'   => auth()->id(),
                'user_name' => $request->name ?? 'Guest',
                'device_id' => $request->device_id,
                'phone'     => $request->phone,
                'position'  => $lastPosition + 1,
                'status'    => 'waiting',
            ]);

            event(new UserJoinedQueue($entry));

            return $entry;
        });
    }

    /**
     * Remove a user from the queue and compact positions.
     */
    public function leaveQueue(Queue $queue, Request $request): ?QueueEntry
    {
        $entry = $queue->entries()
            ->where('device_id', $request->device_id)
            ->where('status', 'waiting')
            ->first();

        if (! $entry) {
            return null;
        }

        $entry->update(['status' => 'left']);

        $queue->entries()
            ->where('status', 'waiting')
            ->where('position', '>', $entry->position)
            ->decrement('position');

        event(new UserLeftQueue($entry));

        return $entry;
    }

    /**
     * Mark the current serving entry as done and advance the next in line.
     */
    public function startServingNext(Queue $queue): ?QueueEntry
    {
        return DB::transaction(function () use ($queue) {
            $queue->entries()->where('status', 'serving')->update([
                'status'    => 'done',
                'served_at' => now(),
            ]);

            $next = $queue->entries()
                ->where('status', 'waiting')
                ->orderBy('position')
                ->first();

            if (! $next) {
                return null;
            }

            $next->update(['status' => 'serving', 'position' => 0]);

            $queue->entries()->where('status', 'waiting')->decrement('position');

            event(new UserServingStarted($next));

            return $next;
        });
    }

    /**
     * Update the phone number for an active queue entry.
     */
    public function updatePhone(Queue $queue, Request $request): ?QueueEntry
    {
        $entry = $queue->entries()
            ->where('device_id', $request->device_id)
            ->whereIn('status', ['waiting', 'serving'])
            ->first();

        $entry?->update(['phone' => $request->phone]);

        return $entry;
    }

    /**
     * Remove a specific entry by an owner and compact positions.
     */
    public function removeEntry(Queue $queue, QueueEntry $entry): bool
    {
        return DB::transaction(function () use ($queue, $entry) {
            if ($entry->queue_id !== $queue->id) {
                return false;
            }

            $oldPosition = $entry->position;
            $wasWaiting  = $entry->status === 'waiting';

            $entry->update(['status' => 'removed', 'position' => 0]);

            if ($wasWaiting) {
                $queue->entries()
                    ->where('status', 'waiting')
                    ->where('position', '>', $oldPosition)
                    ->decrement('position');
            }

            event(new UserRemoved($entry));

            return true;
        });
    }
}
