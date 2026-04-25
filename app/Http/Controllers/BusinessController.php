<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Laravel\Reverb\Loggers\Log;

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
            'hero_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,heic,heif,avif,bmp|max:10240',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,heic,heif,avif,bmp|max:10240',
        ]);

        $data = [
            'name' => $validated['name'],
            'location' => $validated['location'] ?? null,
            'owner_id' => auth()->id(),
        ];

        if ($request->hasFile('hero_image')) {
            $data['hero_image_path'] = $request->file('hero_image')->store('businesses', 'public');
        }

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('businesses', 'public');
        }

        $business = Business::create($data);

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
            'hero_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,heic,heif,avif,bmp|max:30240',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,heic,heif,avif,bmp|max:30240',
        ]);

        $data = [
            'name' => $validated['name'],
            'location' => $validated['location'] ?? null,
        ];

        if ($request->hasFile('hero_image')) {
            if ($business->hero_image_path) {
                Storage::disk('public')->delete($business->hero_image_path);
            }
            $data['hero_image_path'] = $request->file('hero_image')->store('businesses', 'public');
        }

        if ($request->hasFile('logo')) {
            if ($business->logo_path) {
                Storage::disk('public')->delete($business->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('businesses', 'public');
        }

        $business->update($data);

        return redirect()->route('dashboard')->with('success', 'Business updated successfully');
    }

    public function destroy(Business $business)
    {
        abort_unless($business->owner_id === auth()->id(), 403);
        
        $business->delete();

        return redirect()->route('dashboard')->with('success', 'Business deleted successfully');
    }
}
