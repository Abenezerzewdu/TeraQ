<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Queue;
use App\Services\QueueService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QueueController extends Controller
{

    public function show(Queue $queue)
    {
        $queue->load(['entries', 'business']);

        return Inertia::render('Queue/Show', [
            'queue' => $queue,
            'entries' => $queue->entries()->orderBy('position')->get(),
        ]);
    }
    
// create a queue for business
public function store(Business $business, Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'avg_service_time' => 'required|integer|min:1',
    ]);

    $business->queues()->create($validated);

    return back()->with('success', 'Queue created');
}

    public function join(Queue $queue, Request $request, QueueService $service)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'phone'=>'nullable|string|max:20',
            'device_id' => 'required|string',
        ]);

        $entry = $service->joinQueue($queue, $request);

        if ($entry->wasRecentlyCreated) {
            return back()->with('success', 'You joined the queue successfully.');
        }

        return back()->with('info', "Welcome back! You're already at position #{$entry->position}.");
    }

    public function leave(Request $request, Queue $queue, QueueService $service)
    {
        $service->leaveQueue($queue, $request);
        return back()->with('info', 'You have left the queue.');
    }

    public function startNext(Queue $queue, QueueService $service)
    {
        $entry = $service->startServingNext($queue);
        
        if ($entry) {
            return back()->with('success', "Now serving {$entry->user_name}");
        }

        return back()->with('info', 'No more users in line.');
    }

    }

