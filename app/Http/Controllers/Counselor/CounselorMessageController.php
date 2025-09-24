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
    public function index()
    {
        return view('Counselor.messages');
    }


    /**
     * Search users for new conversation
     */
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();

        $users = User::whereIn('role', ['counselor', 'parent'])
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
