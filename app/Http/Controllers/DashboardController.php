<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function redirect()
    {
        $role = session('role');
        switch ($role) {
            case 'Head Guidance':
                return redirect()->route('Head.dashboard');
            case 'Guidance Counselor':
                return redirect()->route('Counselor.dashboard');
            case 'Parent':
                return redirect()->route('Parent.dashboard');
            case 'Student':
                return redirect()->route('Student.dashboard');
            default:
                return redirect('/');
        }
    }
}
