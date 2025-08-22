<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeadDashboardController extends Controller
{
    public function dashboard()
    {
        return view('Head.dashboard');
    }
}
