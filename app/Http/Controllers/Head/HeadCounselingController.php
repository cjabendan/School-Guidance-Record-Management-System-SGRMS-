<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\CounselingNotes;
use Illuminate\Http\Request;

class HeadCounselingController extends Controller
{
    public function index()
    {
        $counselings = CounselingNotes::with(['user'])->get();
        return view('Head.counseling', compact('counselings'));
    }


}