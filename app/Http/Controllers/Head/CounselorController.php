<?php

namespace App\Http\Controllers\Head;

use Illuminate\Http\Request;
use App\Models\Counselor;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CounselorController extends Controller
{

    public function index()
    {
        $counselors = DB::table('counselors')
            ->leftJoin('users', 'counselors.user_id', '=', 'users.id')
            ->select(
                'counselors.c_id',
                DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name) AS full_name"),
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.contact_num',
                'users.email',
                'users.profile_image',
                'users.username'
            )
            ->get();

        return view('Head.profiling.counselors', compact('counselors'));
    }

        public function store(Request $request)
    {

        $request->validate([
            'lname' => 'required|string',
            'fname' => 'required|string',
            'mname' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'contact_num' => 'required|string',
            'c_level' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
        ]);



    $user = new User();
    $user->username = $request->username;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->role = 'counselor';
    $user->first_name = $request->fname;
    $user->middle_name = $request->mname;
    $user->last_name = $request->lname;
    $user->contact_num = $request->contact_num;
    $user->status = 'Pending';
    $user->save();

    $counselor = new Counselor();
    $counselor->user_id = $user->id;
    $counselor->save();

        return redirect()->back()->with('success', 'Counselor and user account created successfully.');
    }

    public function show($id)
    {
        $counselor = Counselor::with('user')->find($id);
        if (!$counselor || !$counselor->user) {
            return response()->json(['error' => 'Counselor not found'], 404);
        }
        // Merge counselor and user info for the modal
        $data = [
            'c_id' => $counselor->c_id,
            'lname' => $counselor->user->last_name,
            'fname' => $counselor->user->first_name,
            'mname' => $counselor->user->middle_name,
            'email' => $counselor->user->email,
            'contact_num' => $counselor->user->contact_num,
            'c_level' => $counselor->user->c_level ?? '',
            // add more fields if needed
        ];
        return response()->json($data);
    }

    public function update(Request $request)
    {


    $counselor = Counselor::findOrFail($request->input('c_id'));
    // Only update user info, not counselor table
    $user = $counselor->user;
    $user->first_name = $request->input('fname');
    $user->middle_name = $request->input('mname');
    $user->last_name = $request->input('lname');
    $user->contact_num = $request->input('contact_num');
    $user->save();

        return redirect()->back()->with('success', 'Counselor updated successfully!');
    }

    
}
