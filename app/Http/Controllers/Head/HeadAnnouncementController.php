<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Announcements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class HeadAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        Log::info('All announcements:', Announcements::all()->toArray());

        $query = Announcements::where('status', 'active');

        if ($request->has('category') && $request->category != 'recent' && $request->category != '') {
            $query->where('category', $request->category);
            if (strtolower($request->category) === 'event') {
                $query->where('end_datetime', '>=', now());
            }
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $announcements = $query->orderBy('created_at', 'desc')->get();

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
        $data['status'] = $data['status'] ?? 'active';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/announcements'), $filename);
            $data['image'] = $filename;
        } else {
            $data['image'] = 'default.png';
        }

        $announcement = Announcements::create($data);

        // 🔔 SMART MESSAGE BUILDER
        $title = trim($announcement->title);
        $lowerTitle = strtolower($title);
        $category = strtolower($announcement->category);

        if (preg_match('/\b(buwan ng wika|linggo ng wika|filipino month)\b/', $lowerTitle)) {
            $emoji = '📜'; $label = 'Buwan ng Wika celebration';
        } elseif (preg_match('/\b(nutrition month|feeding|health|wellness)\b/', $lowerTitle)) {
            $emoji = '🥦'; $label = 'Nutrition & Health program';
        } elseif (preg_match('/\b(valentine|love|heart)\b/', $lowerTitle)) {
            $emoji = '❤️'; $label = 'Valentine celebration';
        } elseif (preg_match('/\b(christmas|pasko|holiday|vacation|break|new year)\b/', $lowerTitle)) {
            $emoji = '🎄'; $label = 'Holiday celebration';
        } elseif (preg_match('/\b(science|math|stem|research|fair|exhibit|innovation)\b/', $lowerTitle)) {
            $emoji = '🔬'; $label = 'Science and Math event';
        } elseif (preg_match('/\b(career|guidance|orientation|recognition|graduation|assembly)\b/', $lowerTitle)) {
            $emoji = '🎓'; $label = 'Academic event';
        } elseif (preg_match('/\b(sports|intramurals|game|tournament|competition|contest|olympics)\b/', $lowerTitle)) {
            $emoji = '🏆'; $label = 'Sports or competition event';
        } elseif (preg_match('/\b(book fair|reading month|library|literature)\b/', $lowerTitle)) {
            $emoji = '📚'; $label = 'Reading and Literacy event';
        } elseif (preg_match('/\b(teachers|teacher\'s day|educator|staff)\b/', $lowerTitle)) {
            $emoji = '🍎'; $label = 'Teacher appreciation event';
        } elseif (preg_match('/\b(cleanup|clean-up|environment|tree|planting|recycle|green)\b/', $lowerTitle)) {
            $emoji = '🌿'; $label = 'Environmental activity';
        } elseif (preg_match('/\b(blood|donation|medical|clinic|healthcare)\b/', $lowerTitle)) {
            $emoji = '💉'; $label = 'Health campaign';
        } elseif (preg_match('/\b(boy scout|girl scout|camp|leadership|training|seminar|workshop)\b/', $lowerTitle)) {
            $emoji = '🏕️'; $label = 'Training or Scouting activity';
        } elseif (preg_match('/\b(outreach|charity|community|foundation|donate)\b/', $lowerTitle)) {
            $emoji = '💞'; $label = 'Community outreach';
        } elseif (preg_match('/\b(countdown|day [0-9]+|upcoming event|coming soon|to go)\b/', $lowerTitle)) {
            $emoji = '⏳'; $label = 'Upcoming event countdown';
        } elseif (preg_match('/\b(no class|suspension|weather|storm|typhoon|cancelled)\b/', $lowerTitle)) {
            $emoji = '🌧️'; $label = 'Class advisory';
        } else {
            $emoji = match ($category) {
                'event' => '📣',
                'announcement' => '🎓',
                'news' => '📰',
                default => '📢',
            };
            $label = ucfirst($category);
        }

        $message = "{$emoji} {$label}: {$title}";

        // 🔔 Notify all users except admin
        $recipients = User::whereIn('role', ['counselor','parent','student'])->get();
        foreach ($recipients as $user) {
            $notif = Notification::create([
                'user_id'    => $user->id,
                'message'    => $message,
                'timestamp'  => now(),
                'is_read'    => 0,
                'related_id' => $announcement->id,
            ]);
            broadcast(new \App\Events\NewNotification($notif, true));
        }

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

        $data['status'] = $data['status'] ?? 'active';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/announcements'), $filename);
            $data['image'] =  $filename;
        }

        $announcement->update($data);

        // 🔔 Reuse the same smart notification message builder
        $title = trim($announcement->title);
        $lowerTitle = strtolower($title);
        $category = strtolower($announcement->category);

        if (preg_match('/\b(buwan ng wika|linggo ng wika|filipino month)\b/', $lowerTitle)) {
            $emoji = '📜'; $label = 'Buwan ng Wika celebration';
        } elseif (preg_match('/\b(nutrition month|feeding|health|wellness)\b/', $lowerTitle)) {
            $emoji = '🥦'; $label = 'Nutrition & Health program';
        } elseif (preg_match('/\b(valentine|love|heart)\b/', $lowerTitle)) {
            $emoji = '❤️'; $label = 'Valentine celebration';
        } elseif (preg_match('/\b(christmas|pasko|holiday|vacation|break|new year)\b/', $lowerTitle)) {
            $emoji = '🎄'; $label = 'Holiday celebration';
        } elseif (preg_match('/\b(science|math|stem|research|fair|exhibit|innovation)\b/', $lowerTitle)) {
            $emoji = '🔬'; $label = 'Science and Math event';
        } elseif (preg_match('/\b(career|guidance|orientation|recognition|graduation|assembly)\b/', $lowerTitle)) {
            $emoji = '🎓'; $label = 'Academic event';
        } elseif (preg_match('/\b(sports|intramurals|game|tournament|competition|contest|olympics)\b/', $lowerTitle)) {
            $emoji = '🏆'; $label = 'Sports or competition event';
        } elseif (preg_match('/\b(book fair|reading month|library|literature)\b/', $lowerTitle)) {
            $emoji = '📚'; $label = 'Reading and Literacy event';
        } elseif (preg_match('/\b(teachers|teacher\'s day|educator|staff)\b/', $lowerTitle)) {
            $emoji = '🍎'; $label = 'Teacher appreciation event';
        } elseif (preg_match('/\b(cleanup|clean-up|environment|tree|planting|recycle|green)\b/', $lowerTitle)) {
            $emoji = '🌿'; $label = 'Environmental activity';
        } elseif (preg_match('/\b(blood|donation|medical|clinic|healthcare)\b/', $lowerTitle)) {
            $emoji = '💉'; $label = 'Health campaign';
        } elseif (preg_match('/\b(boy scout|girl scout|camp|leadership|training|seminar|workshop)\b/', $lowerTitle)) {
            $emoji = '🏕️'; $label = 'Training or Scouting activity';
        } elseif (preg_match('/\b(outreach|charity|community|foundation|donate)\b/', $lowerTitle)) {
            $emoji = '💞'; $label = 'Community outreach';
        } elseif (preg_match('/\b(countdown|day [0-9]+|upcoming event|coming soon|to go)\b/', $lowerTitle)) {
            $emoji = '⏳'; $label = 'Upcoming event countdown';
        } elseif (preg_match('/\b(no class|suspension|weather|storm|typhoon|cancelled)\b/', $lowerTitle)) {
            $emoji = '🌧️'; $label = 'Class advisory';
        } else {
            $emoji = match ($category) {
                'event' => '📣',
                'announcement' => '🎓',
                'news' => '📰',
                default => '📢',
            };
            $label = ucfirst($category);
        }

        $message = "{$emoji} {$label}: {$title}";

        $recipients = User::whereIn('role', ['counselor','parent','student'])->get();
        foreach ($recipients as $user) {
            $notif = Notification::create([
                'user_id'    => $user->id,
                'message'    => $message,
                'timestamp'  => now(),
                'is_read'    => 0,
                'related_id' => $announcement->id,
            ]);
            broadcast(new \App\Events\NewNotification($notif, true));
        }

        return redirect()->route('Head.announcements.index')->with('success', 'Announcement updated!');
    }

    public function showFromNotification($id, $notifId = null)
    {
        $announcement = Announcements::findOrFail($id);

        if ($notifId) {
            Notification::where('id', $notifId)->update(['is_read' => 1]);
        }

        return view('Head.Notify.notification', compact('announcement'));
    }
}
