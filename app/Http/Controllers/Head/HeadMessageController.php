<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class HeadMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index($user = null)
    {
        return view('Head.messages', ['selectedUserId' => $user]);
    }
}
