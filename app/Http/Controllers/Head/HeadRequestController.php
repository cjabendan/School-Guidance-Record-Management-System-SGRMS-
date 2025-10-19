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
        // Default filter is 'all' to show everything initially
        $status = $request->query('status', 'all');

        $linkRequests = ParentLinkRequest::with(['parent.user', 'students.student.user'])
            ->when($status !== 'all', function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->get()
            ->map(function ($req) {
                $sex = strtolower($req->parent->user->sex ?? '');
                $prefix = $sex === 'male' ? 'Mr.' : ($sex === 'female' ? 'Ms.' : '');
                $lastName = $req->parent->user->last_name ?? 'Unknown';

                // Keep machine-friendly fields (status lowercase, type) and human display fields
                $rawStatus = strtolower($req->status ?? 'pending');
                $requestedAt = $req->requested_at ? Carbon::parse($req->requested_at) : null;

                return [
                    'id'                => $req->request_id,
                    'type'              => 'child-link',
                    'display_type'      => 'Child Link',
                    'status'            => $rawStatus, // e.g. 'approved', 'pending', 'rejected'
                    'display_status'    => ucfirst($rawStatus), // human readable
                    'requested_at'      => $requestedAt ? $requestedAt->toDateTimeString() : null, // sortable
                    'requested_at_display' => $requestedAt ? $requestedAt->format('M d, Y g:i A') : 'N/A', // for UI
                    'parent_name'       => trim($prefix . ' ' . $lastName),
                ];
            });

        // Sort by date (descending) using proper datetime (nulls last)
        $allRequests = $linkRequests->sortByDesc(function ($item) {
            return $item['requested_at'] ? strtotime($item['requested_at']) : 0;
        })->values();

        // Manual pagination
        $perPage = 10;
        $page = (int) $request->query('page', 1);
        $total = $allRequests->count();
        $results = $allRequests->slice(($page - 1) * $perPage, $perPage)->values();

        // For AJAX calls (front-end fetch)
        if ($request->ajax()) {
            return response()->json([
                'data' => $results,
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => (int) ceil($total / $perPage),
            ]);
        }

        // For direct page render - Blade expects $type variable (used in filters)
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
    public function approve(Request $request, $type, $id)
    {
        if ($type === 'child-link') {
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
                        'p_id'     => $linkRequest->parent_id,
                        's_id'     => $studentRequest->student_id,
                        'relation' => 'Parent',
                    ]);
                }
            }

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Request approved and students linked successfully.']);
            }

            return redirect()->back()->with('success', 'Request approved and students linked successfully.');
        }

        abort(404, 'Unknown request type');
    }

    // Reject request
    public function reject(Request $request, $type, $id)
    {
        if ($type === 'child-link') {
            $linkRequest = ParentLinkRequest::findOrFail($id);
            $linkRequest->status = 'rejected';
            $linkRequest->rejection_reason = $request->input('reason');
            $linkRequest->save();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'Request rejected successfully.');
        }

        abort(404, 'Unknown request type');
    }

    // Show request details
    public function show($type, $id)
    {
        if ($type === 'child-link') {
            $request = ParentLinkRequest::with(['parent.user', 'students.student.user'])
                ->findOrFail($id);

            $data = [
                'type' => 'Child Link',
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

        abort(404, 'Unknown request type');
    }
}
