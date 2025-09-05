<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ParentModel;

class ParentChildController extends Controller
{
    
    public function index()
    {

          // Get the logged-in parent's model
        $parent = ParentModel::where('user_id', Auth::id())->first();

        // Get students linked to this parent via the pivot table
        $students = [];
        if ($parent) {
            $students = $parent->students()->with('user')->get();
        }

        return view('Parent.child', compact('students'));
    }
}
