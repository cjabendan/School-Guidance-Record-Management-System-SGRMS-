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

        $announcements = $query
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Head.announcements', compact('announcements'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'body' => 'required|string',
            'link' => 'nullable|url',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,archived',
        ]);

        $data = $request->only([
            'title',
            'description',
            'body',
            'link',
            'category',
            'status'
        ]);
        $data['user_id'] = Auth::id();
        $data['date_posted'] = now();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('announcements', 'public');
        }

        Announcements::create($data);

        return redirect()->route('Head.announcements.index')->with('success', 'Announcement posted!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcements $announcements)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcements $announcements)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcements $announcements)
    {
        //
    }
}
