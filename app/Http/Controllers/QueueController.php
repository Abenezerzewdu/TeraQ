<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Services\QueueService;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    //join queue
    public function join(Queue $queue,Request $request,QueueService $service){
   $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

    $service->joinQueue($queue,$validated['name']);

    return back()->with('success', 'You joined the queue successfully.');


    }
}
