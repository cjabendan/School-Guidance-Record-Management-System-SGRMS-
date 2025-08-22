<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CounselorDashboardController extends Controller
{
    public function dashboard()
    {
        return view('Counselor.dashboard');
    }
}
