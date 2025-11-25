<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\CounselingNotes;
use Illuminate\Http\Request;

class CounselorCounselingController extends Controller
{
    public function index()
    {
        $counselings = CounselingNotes::with(['user'])->get();
        return view('Counselor.counseling', compact('counselings'));
    }


}