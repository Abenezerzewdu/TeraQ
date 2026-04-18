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
        $queue->load('entries');

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

    //join queue using QueueService
    public function join(Queue $queue,Request $request,QueueService $service){
   $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

    $service->joinQueue($queue,$validated['name']);

    return back()->with('success', 'You joined the queue successfully.');


    }
}
