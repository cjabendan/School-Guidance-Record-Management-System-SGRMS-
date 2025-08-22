<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeadAppointmentController extends Controller
{
    public function index()
    {
        return view('Head.appointments');
    }
}
