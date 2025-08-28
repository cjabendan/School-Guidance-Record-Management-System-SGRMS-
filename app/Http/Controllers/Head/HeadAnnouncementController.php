<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeadAnnouncementController extends Controller
{
    public function index()
    {
        $announcements = \App\Models\Announcements::orderBy('date_posted', 'desc')->get();
        return view('Head.announcements', compact('announcements'));
    }
}
