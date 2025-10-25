<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentLinkRequest;
use App\Models\ParentStudent;
use Carbon\Carbon;

class HeadRequestController extends Controller
{
    // Load requests
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $linkRequests = ParentLinkRequest::with(['parent.user', 'students.student.user'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->orderByDesc('requested_at')
            ->get()
            ->map(function ($req) {
                $user = $req->parent->user ?? null;
                $sex = strtolower($req->parent->user->sex ?? '');
                $prefix = $sex === 'male' ? 'Mr.' : ($sex === 'female' ? 'Ms.' : '');
                $lastName = $req->parent->user->last_name ?? 'Unknown';

                $rawStatus = strtolower($req->status ?? 'pending');
                $requestedAt = $req->requested_at ? Carbon::parse($req->requested_at) : null;

                return [
                    'id' => $req->request_id,
                    'status' => $rawStatus,
                    'display_status' => ucfirst($rawStatus),
                    'requested_at' => $requestedAt ? $requestedAt->toDateTimeString() : null,
                    'requested_at_display' => $requestedAt ? $requestedAt->format('M d, Y g:i A') : 'N/A',
                    'parent_name' => trim($prefix . ' ' . $lastName),
                    'profile_image' => $user->profile_image
                    
                ];
            });

        $allRequests = $linkRequests->sortByDesc(fn($item) => $item['requested_at'] ? strtotime($item['requested_at']) : 0)->values();

        $perPage = 10;
        $page = (int) $request->query('page', 1);
        $total = $allRequests->count();
        $results = $allRequests->slice(($page - 1) * $perPage, $perPage)->values();

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
            'type'        => $status,
            'status' => $status,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => (int) ceil($total / $perPage),
        ]);
    }

    // Approve request
    public function approve(Request $request, $id)
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
    public function reject(Request $request, $id)
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
    public function show($id)
    {
        $request = ParentLinkRequest::with(['parent.user', 'students.student.user'])->findOrFail($id);

        $data = [
            'status' => ucfirst($request->status),
            'requested_at' => Carbon::parse($request->requested_at)->format('F d, Y g:i A'),
            'parent_name' => $request->parent->user->first_name . ' ' . $request->parent->user->last_name,
            'email' => $request->parent->user->email,
            'contact' => $request->parent->user->contact_num,
            'students' => $request->students->map(
                fn($s) => $s->student->user->first_name . ' ' . $s->student->user->last_name
            )->toArray(),
            'rejection_reason' => $request->rejection_reason,
        ];

        return response()->json($data);
    }
}
