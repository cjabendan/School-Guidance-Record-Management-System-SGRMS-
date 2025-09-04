<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParentChildController extends Controller
{
    
    public function index()
    {
        return view('Parent.child');
    }
}
