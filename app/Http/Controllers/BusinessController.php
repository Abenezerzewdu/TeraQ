<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BusinessController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');

        $businesses = Business::with(['queues' => function($query) {
                $query->withCount(['entries' => function($q) {
                    $q->where('status', 'waiting');
                }]);
            }])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return Inertia::render('Business/Index', [
            'businesses' => $businesses,
            'filters' => $request->only(['search']),
        ]);
    }
    //
public function show(Business $business)
{
    $business->load('queues');

    return Inertia::render('Business/Show', [
        'business' => $business,
        'slug'=>$business->slug
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

        $business = Business::create([
            ...$validated,
            'owner_id' => auth()->id(),
        ]);

        return redirect()->route('business.show', $business);
    }

    public function edit(Business $business)
    {
        abort_unless($business->owner_id === auth()->id(), 403);
        return Inertia::render('Business/Edit', [
            'business' => $business
        ]);
    }

    public function update(Request $request, Business $business)
    {
        abort_unless($business->owner_id === auth()->id(), 403);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
        ]);

        $business->update($validated);

        return redirect()->route('dashboard')->with('success', 'Business updated successfully');
    }

    public function destroy(Business $business)
    {
        abort_unless($business->owner_id === auth()->id(), 403);
        
        $business->delete();

        return redirect()->route('dashboard')->with('success', 'Business deleted successfully');
    }
}
