<?php

namespace App\Http\Controllers;

use App\Models\Announcements;
use Illuminate\Http\Request;

class AnnouncementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            ->orderBy('date_posted', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(4);

        return view('announcement', compact('announcements'));
    }

    
}
