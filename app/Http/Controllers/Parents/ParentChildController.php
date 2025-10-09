<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\ParentLinkRequest;
use App\Models\ParentLinkRequestStudent;

class ParentChildController extends Controller
{

    public function index()
    {
        // Get the logged-in parent's model
        $parent = ParentModel::where('user_id', Auth::id())->first();

        if (!$parent) {
            return back()->withErrors(['error' => 'Parent not found.']);
        }

        // Get students linked to this parent via pivot table
        $students = Student::whereHas('parents', function ($q) use ($parent) {
            $q->where('parent_student.p_id', $parent->p_id);
        })->with('user')->get();

        $children = $students->map(function ($student) {
            return [
                'id' => $student->s_id,
                'name' => $student->user ? $student->user->first_name . ' ' . $student->user->last_name : 'N/A',
                'email' => $student->user ? $student->user->email : 'N/A',
                
            ];
        });

        return view('Parent.child', compact('children'));
    } 

    public function searchStudents(Request $request)
    {
        $q = $request->get('q', '');
        $students = Student::with('user')
            ->where('s_id', 'like', "%{$q}%")
            ->orWhereHas('user', function ($query) use ($q) {
                $query->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get();

        $results = $students->map(function ($student) {
            return [
                's_id' => $student->s_id,
                'name' => $student->user->first_name . ' ' . $student->user->last_name,
            ];
        });

        return response()->json($results);
    }


    public function sendLinkRequest(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,s_id',
        ]);

        $parent = ParentModel::where('user_id', Auth::id())->first();
        if (!$parent) {
            return back()->withErrors(['error' => 'Parent not found.']);
        }

        $studentIds = $request->student_ids;

        // Check existing pending requests for these students
        $alreadyPending = ParentLinkRequestStudent::whereHas('linkRequest', function ($q) use ($parent) {
            $q->where('parent_id', $parent->p_id) // or ->id depending on your model
                ->where('status', 'pending');
        })
            ->whereIn('student_id', $studentIds)
            ->pluck('student_id')
            ->toArray();

        if (!empty($alreadyPending)) {
            return back()->withErrors([
                'error' => 'You already have a pending request for: ' . implode(', ', $alreadyPending)
            ]);
        }

        // Create ParentLinkRequest
        $linkRequest = ParentLinkRequest::create([
            'parent_id'   => $parent->p_id,
            'status'      => 'pending',
            'requested_at' => now(),
            'email'       => $request->parent_email,
            'number'      => $request->parent_contact,
        ]);

        foreach ($studentIds as $sid) {
            $student = Student::with('user')->find($sid);
            if ($student) {
                ParentLinkRequestStudent::create([
                    'request_id'   => $linkRequest->request_id,
                    'student_id'   => $sid
                ]);
            }
        }

        return redirect()->route('Parent.child.index')
            ->with('success', 'Link request sent successfully!');
    }
}
