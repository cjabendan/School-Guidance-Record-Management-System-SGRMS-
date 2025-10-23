<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ParentMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $user = null)
    {
        return view('Parent.messages', ['selectedUserId' => $user]);
    }
}