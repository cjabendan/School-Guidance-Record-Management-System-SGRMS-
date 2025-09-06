<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SuccessEmail;
use App\Models\User;
use App\Models\ParentModel;

class HeadParentController extends Controller

{

    // Display the list of parents //
    public function index()
    {
        $parents = DB::table('parents')
            ->leftJoin('users', 'parents.user_id', '=', 'users.id')
            ->select(
                'parents.*',
                DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name) AS full_name"),
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.contact_num',
                'users.email',
                'users.profile_image',
                'users.status',
                'users.sex'
            )
            ->get(); // or use ->paginate(10) for pagination

        return view('Head.profiling.parents', compact('parents'));
    }

    // Store a newly created parent
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'nullable|string|max:20',
                'sex' => 'required|in:Male,Female',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
                
        ]);

        DB::beginTransaction();
        try {
            // Create user
                $user = User::create([
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'contact_num' => $request->contact_num,
                    'sex' => $request->sex,
                    'email' => $request->email,         
                    'password' => bcrypt($request->password),
                    'status' => 'active', // Set status to active
                    'role' => 'parent', // Set role to parent
                ]);

            // Create parent
            $parent = ParentModel::create([
                'user_id' => $user->id,
            ]);

            DB::commit();

            // Send success email
            Mail::to($user->email)->send(new SuccessEmail($user));

            return response()->json(['success' => true, 'message' => 'Parent account created and email sent.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    // Get parent data for edit modal (AJAX)
    public function get($id)
    {
        $parent = DB::table('parents')
            ->leftJoin('users', 'parents.user_id', '=', 'users.id')
            ->select(
                'parents.p_id',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.sex',
                'users.contact_num',
                'users.email'
            )
            ->where('parents.p_id', $id)
            ->first();
        if ($parent) {
            return response()->json(['success' => true, 'parent' => $parent]);
        } else {
            return response()->json(['success' => false, 'message' => 'Parent not found.']);
        }
    }

    // Update parent data (AJAX)
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'nullable|string|max:20',
            'sex' => 'required|in:Male,Female',
            'email' => 'required|email',
        ]);

        $parent = DB::table('parents')->where('p_id', $id)->first();
        if (!$parent) {
            return response()->json(['success' => false, 'message' => 'Parent not found.']);
        }
        $user = User::find($parent->user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->sex = $request->sex;
        $user->contact_num = $request->contact_num;
        $user->email = $request->email;
        $user->save();
        return response()->json(['success' => true, 'message' => 'Parent updated successfully.']);
    }

    // Archive (set parent account to inactive)
    public function archive(Request $request, $id)
    {
        $parent = ParentModel::find($id);
        if (!$parent) {
            return response()->json(['success' => false, 'message' => 'Parent not found.']);
        }
        $user = $parent->user;
        if ($user) {
            $user->status = 'inactive';
            $user->save();
            return response()->json(['success' => true, 'message' => 'Parent account archived (set to inactive).']);
        }
        return response()->json(['success' => false, 'message' => 'User not found for parent.']);
    }
}
