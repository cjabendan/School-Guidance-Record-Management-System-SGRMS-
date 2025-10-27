<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use App\Models\ParentLinkRequest;
use App\Models\ParentStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ParentRequestController extends Controller
{
    // Show all link requests of this parent
    public function index(Request $request)
    {
        $parent = Auth::user()->parentProfile ?? null;
        if (!$parent) {
            return redirect()->back()->withErrors(['error' => 'Parent profile not found.']);
        }

        $requests = ParentLinkRequest::with(['students.student.user'])
            ->where('parent_id', $parent->p_id)
            ->orderByDesc('updated_at')
            ->get();

        $allRequests = $requests->map(function ($r) {
            $students = $r->students->map(function ($ps) {
                $user = $ps->student->user ?? null;
                return $user
                    ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))
                    : ($ps->student->s_id ?? 'N/A');
            })->toArray();

            return [
                'type' => 'Child Link',
                'students' => $students,
                'updated_at' => $r->updated_at ? $r->updated_at->format('M d, Y g:i A') : 'N/A',
                'status' => ucfirst(strtolower($r->status ?? 'Pending')),
            ];
        });

        return view('Parent.requests', ['allRequests' => $allRequests]);
    }

    // Handle form submission to create a new child-link request
    public function store(Request $request)
    {
        $parent = Auth::user()->parentProfile ?? null;
        if (!$parent) {
            return redirect()->back()->withErrors(['error' => 'Parent profile not found.']);
        }

        $validator = Validator::make($request->all(), [
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $studentIds = $request->input('student_ids', []);
        $validIds = [];
        $skipped = [];

        foreach ($studentIds as $sId) {
            $alreadyLinked = ParentStudent::where('p_id', $parent->p_id)
                ->where('s_id', $sId)
                ->exists();

            $pending = ParentLinkRequest::where('parent_id', $parent->p_id)
                ->whereHas('students', fn($q) => $q->where('student_id', $sId))
                ->where('status', 'Pending')
                ->exists();

            if ($alreadyLinked || $pending) {
                $skipped[] = $sId;
            } else {
                $validIds[] = $sId;
            }
        }

        if (empty($validIds)) {
            return redirect()->back()->withErrors([
                'error' => 'All selected students are already linked or have pending requests.'
            ])->withInput();
        }

        $linkRequest = ParentLinkRequest::create([
            'parent_id' => $parent->p_id,
            'status' => 'Pending',
        ]);

        foreach ($validIds as $sId) {
            $linkRequest->students()->create(['student_id' => $sId]);
        }

        $message = 'Request submitted successfully.';
        if (!empty($skipped)) {
            $message .= ' Some students were skipped: ' . implode(', ', $skipped);
        }

        return redirect()->route('Parent.requests.index')->with('success', $message);
    }
}
