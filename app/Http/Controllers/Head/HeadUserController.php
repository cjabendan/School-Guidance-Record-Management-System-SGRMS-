<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SuccessEmail;
use App\Models\Counselor;
use App\Models\User;
use App\Models\ParentModel; // assuming this is your parent model

class HeadUserController extends Controller
{
    public function partialTable()
    {
        $users = User::with(['student', 'counselor', 'parentProfile'])
            ->whereIn('role', ['parent', 'student', 'counselor'])
            ->paginate(10);

        return view('Head.partials.user_table', compact('users'));
    }
    public function index(Request $request)
    {
        $search = $request->query('search');
        $roleFilter = $request->query('role'); // new

        $query = User::with(['student', 'counselor', 'parentProfile'])
            ->whereIn('role', ['parent', 'student', 'counselor'])
            ->orderBy('created_at', 'desc'); // exclude admin by default

        if ($roleFilter && in_array($roleFilter, ['parent', 'student', 'counselor'])) {
            $query->where('role', $roleFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->paginate(10);

        if ($request->ajax()) {
            return view('Head.partials.users_table', compact('users'))->render();
        }

        return view('Head.profiling.users', compact('users'));
    }

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
        return $nextId; // return string directly for internal use
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'nullable|regex:/^\d{10}$/|max:11|unique:users,contact_num',
            'sex' => 'required|in:Male,Female',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:parent,counselor,student',
            'password' => 'required|string|min:6',
        ]);

        DB::beginTransaction();

        try {
            // Create the user first
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'contact_num' => $request->contact_num,
                'sex' => $request->sex,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'status' => 'active',
                'role' => $request->role,
            ]);

            // Default profile image based on sex
            if (empty($user->profile_image)) {
                $user->profile_image = $user->sex === 'Male' ? 'male.png' : 'female.png';
                $user->save();
            }

            // Create related profile based on role
            if ($request->role === 'parent') {
                ParentModel::create(['user_id' => $user->id]);
            }

            if ($request->role === 'counselor') {
                Counselor::create([
                    'user_id' => $user->id,
                    'c_id' => $this->getNextCounselorId(), // Auto-generate c_id
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'User added successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function get($id)
    {
        $user = User::with(['parentProfile', 'student', 'counselor'])->find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'nullable|regex:/^\d{10}$/|max:11|unique:users,contact_num,' . $id,
            'sex' => 'required|in:Male,Female',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'sometimes|required_if:role,' . $request->role,
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        $user = User::findOrFail($id);

        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->sex = $request->sex;
        $user->contact_num = $request->contact_num;
        $user->email = $request->email;

        // Only update role if current user is not a student
        if ($user->role !== 'student' && $request->has('role')) {
            $user->role = $request->role;
        }

        if ($request->has('status')) {
            $user->status = $request->status;
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'User updated successfully.']);
    }

    public function archive($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $user->status = 'inactive';
        $user->save();

        return response()->json(['success' => true, 'message' => 'User archived successfully.']);
    }
}
