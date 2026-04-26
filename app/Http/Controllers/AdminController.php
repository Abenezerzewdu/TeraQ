<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Queue;
use App\Models\QueueEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'users'      => User::count(),
                'businesses' => Business::count(),
                'queues'     => Queue::count(),
                'entries'    => QueueEntry::whereIn('status', ['waiting', 'serving'])->count(),
            ],
            'recent_businesses' => Business::with('queues')
                ->withCount('queues')
                ->latest()
                ->take(5)
                ->get(),
            'recent_users' => User::latest()->take(5)->get(),
        ]);
    }

    public function users(Request $request)
    {
        $users = User::withCount('businesses')
            ->when($request->search, fn ($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Users', [
            'users'   => $users,
            'filters' => $request->only('search'),
        ]);
    }

    public function destroyUser(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'Cannot delete your own account.');

        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    public function businesses(Request $request)
    {
        $businesses = Business::with('queues')
            ->withCount(['queues', 'queues as active_entries_count' => fn ($q) =>
                $q->whereHas('entries', fn ($q) => $q->whereIn('status', ['waiting', 'serving']))
            ])
            ->when($request->search, fn ($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('location', 'like', "%{$s}%")
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Businesses', [
            'businesses' => $businesses,
            'filters'    => $request->only('search'),
        ]);
    }

    public function destroyBusiness(Business $business)
    {
        Storage::disk('public')->delete(array_filter([
            $business->hero_image_path,
            $business->logo_path,
        ]));

        $business->delete();

        return back()->with('success', 'Business deleted.');
    }

    public function queues(Request $request)
    {
        $queues = Queue::with('business')
            ->withCount([
                'entries as waiting_count' => fn ($q) => $q->where('status', 'waiting'),
                'entries as total_count',
            ])
            ->when($request->search, fn ($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Queues', [
            'queues'  => $queues,
            'filters' => $request->only('search'),
        ]);
    }

    public function destroyQueue(Queue $queue)
    {
        $queue->delete();

        return back()->with('success', 'Queue deleted.');
    }
}
