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

        // Exclude past events automatically if filtering by events
        $query->when($request->category === 'event', function ($q) {
            $q->where('end_datetime', '>=', now());
        });

        $announcements = $query->orderBy('created_at', 'desc')->get();

        // Calendar events: only Announcements + Events
        $events = Announcements::where('status', 'active')
            ->whereIn('category', ['Announcement', 'Event'])
            ->get();

        return view('Head.announcements', compact('announcements', 'events'));
    }

    public function getEvents(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $events = Announcements::where('status', 'active')
            ->whereIn('category', ['Announcement', 'Event'])
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->whereNotNull('start_datetime')
                        ->whereNotNull('end_datetime')
                        ->whereDate('start_datetime', '<=', $end)
                        ->whereDate('end_datetime', '>=', $start);
                })
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->whereNotNull('date_posted')
                            ->whereDate('date_posted', '>=', $start)
                            ->whereDate('date_posted', '<=', $end);
                    });
            })
            ->get()
            ->map(function ($a) {
                if ($a->category === 'Event' && $a->start_datetime && $a->end_datetime) {
                    return [
                        'title' => $a->title,
                        'start' => \Carbon\Carbon::parse($a->start_datetime)->toIso8601String(),
                        'end'   => \Carbon\Carbon::parse($a->end_datetime)->toIso8601String(),
                        'allDay' => false,
                        'extendedProps' => [
                            'category' => $a->category,
                            'status' => $a->status,
                            'description' => $a->description,
                        ]
                    ];
                } else {
                    // Announcements: partial-day (6:00–20:00)
                    $start = \Carbon\Carbon::parse($a->date_posted)->setTime(6, 0);
                    $end = \Carbon\Carbon::parse($a->date_posted)->setTime(20, 0);
                    return [
                        'title' => $a->title,
                        'start' => $start->toIso8601String(),
                        'end'   => $end->toIso8601String(),
                        'allDay' => false,
                        'extendedProps' => [
                            'category' => $a->category,
                            'status' => $a->status,
                            'description' => $a->description,
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
            'category' => 'required|in:Announcement,News,Event',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,archived',
            'start_datetime' => 'nullable|date|required_if:category,Event',
            'end_datetime' => 'nullable|date|required_if:category,Event|after_or_equal:start_datetime',
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
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/announcements'), $filename);
            $data['image'] = $filename;
        }else {
            $data['image'] = 'default.png'; 
        }

        Announcements::create($data);

        return redirect()->route('Head.announcements.index')->with('success', 'Announcement posted!');
    }
    public function update(Request $request, $id)
    {
        $announcement = Announcements::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|url',
            'category' => 'required|in:Announcement,News,Event',
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

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/announcements'), $filename);
            $data['image'] =  $filename;
        }

        $announcement->update($data);

        return redirect()->route('Head.announcements.index')->with('success', 'Announcement updated!');
    }
}
