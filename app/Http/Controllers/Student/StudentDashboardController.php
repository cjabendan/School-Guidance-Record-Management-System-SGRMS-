<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    // ...existing methods...

    public function dashboard()
    {
        return view('Student.dashboard');
    }
}