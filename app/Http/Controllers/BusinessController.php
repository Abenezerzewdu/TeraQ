<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BusinessController extends Controller
{
    //
public function show(Business $business)
{
    $business->load('queues');

    return Inertia::render('Business/Show', [
        'business' => $business
    ]);
}
    public function create()
{
    return Inertia::render('Business/Create');
}


      public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
        ]);

        $business = Business::create($validated);

        return redirect()->route('business.show', $business);
    }
}
