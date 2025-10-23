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
    // Show list of this parent's requests
    public function index(Request $request)
    {
        $parentId = Auth::user()->parentProfile->p_id ?? null;

        if (!$parentId) {
            return redirect()->back()->withErrors(['error' => 'Parent profile not found.']);
        }

        $requests = ParentLinkRequest::with(['students.student.user'])
            ->where('parent_id', $parentId)
            ->orderByDesc('requested_at')
            ->get();

        // Convert to structure expected by the blade
        $allRequests = $requests->map(function ($r) {
            $students = $r->students->map(function ($ps) {
                $u = $ps->student->user ?? null;
                return $u ? trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) : ($ps->student->s_id ?? 'N/A');
            })->toArray();

            return [
                'type' => 'Child Link',
                'students' => $students,
                'requested_at' => $r->requested_at ? $r->requested_at : '',
                'status' => $r->status ?? 'pending',
            ];
        });

        return view('Parent.requests', ['allRequests' => $allRequests]);
    }

    // Handle form POST to create a new child-link request
    public function store(Request $request)
    {
        $parentId = Auth::user()->parentProfile->p_id ?? null;

        if (!$parentId) {
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
            // Check already linked
            $alreadyLinked = ParentStudent::where('p_id', $parentId)
                ->where('s_id', $sId)
                ->exists();

            // Check pending
            $pending = ParentLinkRequest::where('parent_id', $parentId)
                ->whereHas('students', function ($q) use ($sId) {
                    $q->where('student_id', $sId);
                })
                ->where('status', 'pending')
                ->exists();

            if ($alreadyLinked || $pending) {
                $skipped[] = $sId;
            } else {
                $validIds[] = $sId;
            }
        }

        if (count($validIds) === 0) {
            return redirect()->back()->withErrors([
                'error' => 'All selected students are either already linked or have pending requests.'
            ])->withInput();
        }

        // Create request only for valid students
        $linkRequest = ParentLinkRequest::create([
            'parent_id' => $parentId,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        foreach ($validIds as $sId) {
            $linkRequest->students()->create([
                'student_id' => $sId,
            ]);
        }

        $message = 'Request submitted successfully.';
        if (count($skipped) > 0) {
            $message .= ' Some students were skipped because they are already linked or pending: ' . implode(', ', $skipped);
        }

        return redirect()->route('Parent.requests.index')->with('success', $message);
    }
}
