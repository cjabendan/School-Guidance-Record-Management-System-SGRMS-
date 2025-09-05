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
                'users.status'
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
}
