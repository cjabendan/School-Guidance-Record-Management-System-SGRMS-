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
    public function partialTable()
    {
        $parents = ParentModel::with('user')->paginate(10);
        return view('Head.partials.parent_table', compact('parents'));
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = ParentModel::with('user');
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        $parents = $query->paginate(10);
        if ($request->ajax()) {
            return view('Head.partials.parent_table', compact('parents'))->render();
        }
        return view('Head.profiling.parents', compact('parents'));
    }

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
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'contact_num' => $request->contact_num,
                'sex' => $request->sex,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'status' => 'active',
                'role' => 'parent',
            ]);

            ParentModel::create([
                'user_id' => $user->id,
            ]);

            DB::commit();

            Mail::to($user->email)->send(new SuccessEmail($user));

            return response()->json(['success' => true, 'message' => 'Parent added successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

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
        }
        return response()->json(['success' => false, 'message' => 'Parent not found.']);
    }

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

        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'sex' => $request->sex,
            'contact_num' => $request->contact_num,
            'email' => $request->email,
        ]);

        return response()->json(['success' => true, 'message' => 'Parent updated successfully.']);
    }

    public function archive(Request $request, $id)
    {
        $parent = ParentModel::find($id);
        if (!$parent) {
            return response()->json(['success' => false, 'message' => 'Parent not found.']);
        }

        $user = $parent->user;
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $user->status = 'inactive';
        $user->save();

        return response()->json(['success' => true, 'message' => 'Parent archived successfully.']);
    }
}
