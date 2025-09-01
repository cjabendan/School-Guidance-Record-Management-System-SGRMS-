<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Announcements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HeadAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcements::where('status', 'active');

        if ($request->has('category') && $request->category != 'recent') {
            $query->where('category', $request->category);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Automatically exclude past events
        $query->when($request->category === 'event', function ($q) {
            $q->where('end_datetime', '>=', now());
        });

        $announcements = $query->orderBy('created_at', 'desc')->get();

        return view('Head.announcements', compact('announcements'));
    }

    public function getEvents()
    {
        $events = Announcements::where('status', 'active')
            ->whereIn('category', ['announcement', 'event', 'news'])
            ->get()
            ->map(function ($announcement) {
                // For events, use start/end datetime; for announcement/news, use date_posted as all-day
                if ($announcement->category === 'event' && $announcement->start_datetime && $announcement->end_datetime) {
                    return [
                        'title' => $announcement->title,
                        'start' => $announcement->start_datetime,
                        'end'   => $announcement->end_datetime,
                        'allDay' => false,
                        'extendedProps' => [
                            'category' => $announcement->category,
                            'status'   => $announcement->status,
                        ]
                    ];
                } else {
                    // Announcement or news: show as all-day event on date_posted
                    return [
                        'title' => $announcement->title,
                        'start' => $announcement->date_posted,
                        'allDay' => true,
                        'extendedProps' => [
                            'category' => $announcement->category,
                            'status'   => $announcement->status,
                        ]
                    ];
                }
            });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|url',
            'category' => 'required|in:announcement,news,event',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,archived',
            'start_datetime' => 'nullable|date|required_if:category,event',
            'end_datetime' => 'nullable|date|required_if:category,event|after_or_equal:start_datetime',
        ]);

        $data = $request->only([
            'title',
            'description',
            'link',
            'category',
            'status',
            'start_datetime',
            'end_datetime'
        ]);

        $data['user_id'] = Auth::id();
        $data['date_posted'] = now();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('announcements', 'public');
        }

        Announcements::create($data);

        return redirect()->route('Head.announcements.index')->with('success', 'Announcement posted!');
    }
}
