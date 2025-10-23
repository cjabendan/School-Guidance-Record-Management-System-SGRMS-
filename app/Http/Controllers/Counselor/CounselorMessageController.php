<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class CounselorMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $user = null)
    {
        return view('Counselor.messages', ['selectedUserId' => $user]);
    }

   
}
