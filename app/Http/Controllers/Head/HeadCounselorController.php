<?php

namespace App\Http\Controllers\Head;

use Illuminate\Http\Request;
use App\Models\Counselor;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HeadCounselorController extends Controller
{
    // Get next available counselor ID 
    public function getNextCounselorId()
    {
        $latest = DB::table('counselors')
            ->where('c_id', 'like', 'MA25-C%')
            ->orderByDesc('c_id')
            ->value('c_id');

        if ($latest) {
            $num = (int)substr($latest, 7); // MA25-C001 
            $nextNum = $num + 1;
        } else {
            $nextNum = 1;
        }
        $nextId = 'MA25-C' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        return response()->json(['next_c_id' => $nextId]);
    }

    public function index()
    {
        $counselors = DB::table('counselors')
            ->leftJoin('users', 'counselors.user_id', '=', 'users.id')
            ->select(
                'counselors.c_id',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.contact_num',
                'users.email',
                DB::raw('COALESCE(users.profile_image, "") as profile_image')
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
            'password' => 'required|string|min:6',
            'c_id' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile image upload (save with original filename)
        $profileImageName = 'default.jpg';
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $originalName = $file->getClientOriginalName();
            $file->move(public_path('images/user'), $originalName);
            $profileImageName = $originalName;
        }

        // Use a transaction to ensure both user and counselor are created
        DB::beginTransaction();
        try {
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'counselor',
                'first_name' => $request->fname,
                'middle_name' => $request->mname,
                'last_name' => $request->lname,
                'contact_num' => $request->contact_num,
                'status' => 'Pending',
                'profile_image' => $profileImageName,
            ]);

            Counselor::create([
                'c_id' => $request->c_id,
                'user_id' => $user->id,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Counselor and user account created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add counselor: ' . $e->getMessage());
        }
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
        $user = $counselor->user;

        $user->first_name = $request->input('fname');
        $user->middle_name = $request->input('mname');
        $user->last_name = $request->input('lname');
        $user->contact_num = $request->input('contact_num');
        $user->email = $request->input('email');

        // Handle profile image upload (save with original filename)
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $originalName = $file->getClientOriginalName();
            $file->move(public_path('images/user'), $originalName);
            $user->profile_image = $originalName;
        }

        $user->save();

        return redirect()->back()->with('success', 'Counselor updated successfully!');
    }

    // Add dashboard method to fix missing method error
    public function dashboard()
    {
        return view('Counselor.dashboard.counselor');
    }

    //_____________________________________________________________________________

    // Return counselor data as JSON for view modal
    public function showAjax($c_id)
    {
        $counselor = \App\Models\Counselor::where('c_id', $c_id)->first();
        if (!$counselor) {
            return response()->json(['error' => 'Counselor not found'], 404);
        }
        $user = $counselor->user;
        $profile_image_url = asset('images/user/' . ($user && $user->profile_image ? $user->profile_image : 'default.jpg'));
        return response()->json([
            'c_id' => $counselor->c_id,
            'fname' => $user ? $user->first_name : '',
            'mname' => $user ? $user->middle_name : '',
            'lname' => $user ? $user->last_name : '',
            'email' => $user ? $user->email : '',
            'contact_num' => $user ? $user->contact_num : '',
            'profile_image_url' => $profile_image_url,
        ]);
    }
}
