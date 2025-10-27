<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentLinkRequest;
use App\Models\ParentModel;
use App\Models\ParentStudent;
use Carbon\Carbon;

class HeadRequestController extends Controller
{
    // Load requests
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $parents = ParentModel::with(['user', 'latestLinkRequest'])
            ->withCount([
                'linkedStudents as linked_students_count',
                'linkRequests as pending_requests_count' => fn($q) => $q->where('status', 'pending'),
                'linkRequests as total_requests_count',
            ])
            ->get()
            // show only parents who have at least one request
            ->filter(fn($p) => $p->total_requests_count > 0)
            ->map(function ($p) {
                $user = $p->user;
                $prefix = strtolower($user->sex ?? '') === 'male' ? 'Mr. ' : 'Ms. ';
                $lastUpdatedTimestamp = $p->latestLinkRequest->updated_at ?? $p->updated_at;
                return [
                    'id' => $p->p_id,
                    'parent_name' => $prefix . ($user->last_name ?? 'Unknown'),
                    'profile_image' => $user->profile_image ?? null,
                    'linked_students' => $p->linked_students_count,
                    'pending_requests' => $p->pending_requests_count,
                    'last_updated' => optional($lastUpdatedTimestamp)->format('M d, Y g:i A') ?? 'N/A',
                ];
            });

        if ($status === 'approved') {
            $parents = $parents->filter(fn($x) => $x['linked_students'] > 0);
        } elseif ($status === 'pending') {
            $parents = $parents->filter(fn($x) => $x['pending_requests'] > 0);
        } elseif ($status === 'rejected') {
            $parents = $parents->filter(fn($x) => $x['linked_students'] == 0 && $x['pending_requests'] == 0);
        }

        $perPage = 10;
        $page = (int) $request->query('page', 1);
        $total = $parents->count();
        $results = $parents->slice(($page - 1) * $perPage, $perPage)->values();

        if ($request->ajax()) {
            return response()->json([
                'data' => $results,
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => (int) ceil($total / $perPage),
            ]);
        }

        return view('Head.requests', [
            'allRequests' => $results,
            'type' => $status,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => (int) ceil($total / $perPage),
        ]);
    }


    // Approve request
    public function approveStudent(Request $request, $id)
    {
        $linkRequest = ParentLinkRequest::with('students')->findOrFail($id);

        $linkRequest->status = 'approved';
        $linkRequest->rejection_reason = null;
        $linkRequest->save();

        foreach ($linkRequest->students as $studentRequest) {
            $exists = ParentStudent::where('p_id', $linkRequest->parent_id)
                ->where('s_id', $studentRequest->student_id)
                ->exists();

            if (!$exists) {
                ParentStudent::create([
                    'p_id' => $linkRequest->parent_id,
                    's_id' => $studentRequest->student_id,
                    'relation' => 'Parent',
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Request approved and students linked successfully.']);
        }

        return redirect()->back()->with('success', 'Request approved and students linked successfully.');
    }

    // Reject request
    public function rejectStudent(Request $request, $id)
    {
        $linkRequest = ParentLinkRequest::findOrFail($id);
        $linkRequest->status = 'rejected';
        $linkRequest->rejection_reason = $request->input('reason');
        $linkRequest->save();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Request rejected successfully.');
    }

    // Show request details
  public function show($type, $id)
{
    $requests = ParentLinkRequest::with([
        'parent.user',
        'students.student.user',
        'students.student.schoolYear',
    ])
    ->where('parent_id', $id)
    ->orderByDesc('updated_at')
    ->get();

    if ($requests->isEmpty()) {
        return response()->json(['error' => 'No requests found.'], 404);
    }

    $parent = $requests->first()->parent->user;

    // top-level requested_at for modal header (first request)
    $topRequestedAt = optional($requests->first()->requested_at)->format('M d, Y g:i A') ?? null;

    $data = $requests->flatMap(function ($req) {
        return $req->students->map(function ($s) use ($req) {
            $student = $s->student;
            $studentUser = optional($student->user);
            $studentName = trim(($studentUser->first_name ?? '') . ' ' . ($studentUser->last_name ?? ''));

            // Determine grade
            $grade = 'N/A';
            if ($student) {
                if (!empty($student->schoolYear) && isset($student->schoolYear->year_level)) {
                    $grade = $student->schoolYear->year_level;
                } elseif (!empty($student->year_level)) {
                    $grade = $student->year_level;
                }
            }

    
            $timestamp = $s->updated_at ?? $req->requested_at ?? $req->updated_at;
            $formattedDate = optional($timestamp)->format('M d, Y g:i A') ?? 'N/A';

            return [
                'id' => $s->getKey(),
                'student_name' => $studentName ?: 'Unknown',
                'grade' => $grade,
                'status' => ucfirst($s->status ?? $req->status),
                // return requested_at key expected by the frontend
                'requested_at' => $formattedDate,
            ];
        });
    })->values();

    return response()->json([
        'parent_name' => ($parent->first_name ?? '') . ' ' . ($parent->last_name ?? ''),
        'email' => $parent->email,
        'contact' => $parent->contact_num,
        'requested_at' => $topRequestedAt,
        'requests' => $data,
    ]);
}
}
