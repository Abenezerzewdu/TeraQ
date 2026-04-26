<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        // Check for raw PHP file upload errors BEFORE Laravel touches them
        $uploadErrors = [];
        foreach (['hero_image', 'logo'] as $field) {
            if (isset($_FILES[$field]) && $_FILES[$field]['error'] !== UPLOAD_ERR_OK && $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE   => 'File exceeds PHP upload_max_filesize (' . ini_get('upload_max_filesize') . ')',
                    UPLOAD_ERR_FORM_SIZE  => 'File exceeds form MAX_FILE_SIZE',
                    UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temp folder. upload_tmp_dir=' . ini_get('upload_tmp_dir') . ' sys_temp_dir=' . sys_get_temp_dir(),
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the upload',
                ];
                $code = $_FILES[$field]['error'];
                $uploadErrors[$field] = 'Upload failed (code ' . $code . '): ' . ($errorMessages[$code] ?? 'Unknown error');
            }
        }

        if (!empty($uploadErrors)) {
            return back()->withErrors($uploadErrors);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
            'hero_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,heic,heif,avif,bmp|max:30240',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,heic,heif,avif,bmp|max:30240',
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
