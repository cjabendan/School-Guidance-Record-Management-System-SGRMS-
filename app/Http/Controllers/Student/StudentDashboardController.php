<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    // ...existing methods...

    public function dashboard()
    {
        return view('Student.dashboard');
    }
}