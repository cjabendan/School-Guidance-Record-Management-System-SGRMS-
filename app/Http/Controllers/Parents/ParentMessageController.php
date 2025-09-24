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
    public function index()
    {
        return view('Parent.messages');
    }
    /**
     * Search users for new conversation
     */
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();

        $users = User::whereIn('role', ['counselor', 'admin'])
            ->where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%$query%")
                    ->orWhere('last_name', 'like', "%$query%");
            })
            ->limit(5)
            ->get();

        return response()->json($users);
    }

}