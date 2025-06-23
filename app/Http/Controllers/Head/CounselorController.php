<?php

namespace App\Http\Controllers\Head;

use Illuminate\Http\Request;
use App\Models\Counselor;
use App\Http\Controllers\Controller;
use App\Models\User;

class CounselorController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'lname' => 'required|string',
            'fname' => 'required|string',
            'mname' => 'nullable|string',
            'email' => 'required|email|unique:counselors,email|unique:users,email',
            'contact_num' => 'required|string',
            'c_level' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
        ]);


        $counselor = new Counselor();
        $counselor->lname = $request->lname;
        $counselor->fname = $request->fname;
        $counselor->mname = $request->mname;
        $counselor->email = $request->email;
        $counselor->contact_num = $request->contact_num;
        $counselor->c_level = $request->c_level;
        $counselor->c_image = 'default.png';
        $counselor->save();


        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = 'counselor';
        $user->counselor_id = $counselor->c_id;
        $user->status = 'Pending';
        $user->account_status = 'active';
        $user->save();

        return redirect()->back()->with('success', 'Counselor and user account created successfully.');
    }

    public function show($id)
    {
        $counselor = Counselor::find($id);
        if (!$counselor) {
            return response()->json(['error' => 'Counselor not found'], 404);
        }
        return response()->json($counselor);
    }

    public function update(Request $request)
    {
        $counselor = Counselor::findOrFail($request->input('c_id'));
        $counselor->lname = $request->input('lname');
        $counselor->fname = $request->input('fname');
        $counselor->mname = $request->input('mname');
        $counselor->email = $request->input('email');
        $counselor->contact_num = $request->input('contact_num');
        $counselor->c_level = $request->input('c_level');
        $counselor->save();

        return redirect()->back()->with('success', 'Counselor updated successfully!');
    }

}
