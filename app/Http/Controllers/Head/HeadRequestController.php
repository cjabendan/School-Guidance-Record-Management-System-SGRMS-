<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentLinkRequest;
use App\Models\DocumentRequest;
use App\Models\ParentStudent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HeadRequestController extends Controller
{
    // Load requests
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $type   = $request->query('type', 'all');

        // Use pagination for both types, then merge and sort, then paginate manually
        $linkRequests = ParentLinkRequest::with(['parent.user', 'students.student.user'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status));
        $documentRequests = DocumentRequest::with(['parent.user'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status));

        // Fetch paginated results for each type
        $linkRequestsPaginated = $linkRequests->get()->map(function ($req) {
            $sex = strtolower($req->parent->user->sex ?? '');
            $prefix = $sex === 'male' ? 'Mr.' : ($sex === 'female' ? 'Ms.' : '');
            $lastName = $req->parent->user->last_name ?? 'Unknown';
            return [
                'id'           => $req->request_id,
                'type'         => 'child-link',
                'display_type' => 'Child Link',
                'status'       => ucfirst($req->status),
                'requested_at' => $req->requested_at
                    ? Carbon::parse($req->requested_at)->format('M d, Y')
                    : 'N/A',
                'parent_name'  => trim($prefix . ' ' . $lastName),
            ];
        });
        $documentRequestsPaginated = $documentRequests->get()->map(function ($req) {
            return [
                'id'           => $req->id,
                'type'         => 'document',
                'display_type' => 'Document Request',
                'status'       => ucfirst($req->status),
                'requested_at' => $req->requested_at
                    ? Carbon::parse($req->requested_at)->format('M d, Y')
                    : 'N/A',
                'parent_name'  => trim($req->parent->user->first_name . ' ' . $req->parent->user->last_name),
            ];
        });

        $allRequests = $linkRequestsPaginated->concat($documentRequestsPaginated);
        if ($type !== 'all') {
            $allRequests = $allRequests->where('type', $type);
        }
        // Sort by requested_at desc (latest first)
        $allRequests = $allRequests->sortByDesc(function($item) {
            return strtotime($item['requested_at']);
        })->values();

        // Paginate manually (since we merged two collections)
        $perPage = 10;
        $page = $request->query('page', 1);
        $total = $allRequests->count();
        $results = $allRequests->slice(($page - 1) * $perPage, $perPage)->values();

        // AJAX → JSON response with pagination info
        if ($request->ajax()) {
            return response()->json([
                'data' => $results,
                'current_page' => (int)$page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => (int)ceil($total / $perPage),
            ]);
        }

        // For blade, pass paginated data (first page)
        return view('Head.requests', [
            'allRequests' => $results,
            'status' => $status,
            'type' => $type,
            'current_page' => (int)$page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => (int)ceil($total / $perPage),
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
        } elseif ($type === 'document') {
            $docRequest = DocumentRequest::findOrFail($id);
            $docRequest->status = 'approved';
            $docRequest->rejection_reason = null;
            $docRequest->save();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Document request approved successfully.']);
            }

            return redirect()->back()->with('success', 'Document request approved successfully.');
        } else {
            abort(404, 'Unknown request type');
        }
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
        } elseif ($type === 'document') {
            $docRequest = DocumentRequest::findOrFail($id);
            $docRequest->status = 'rejected';
            $docRequest->rejection_reason = $request->input('reason');
            $docRequest->save();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            return redirect()->back()->with('success', 'Document request rejected successfully.');
        } else {
            abort(404, 'Unknown request type');
        }
    }

    public function show($type, $id)
    {
        if ($type === 'child-link') {
            $request = ParentLinkRequest::with(['parent.user', 'students.student.user'])
                ->findOrFail($id);

            $data = [
                'type' => 'Child Link',
                'status' => $request->status,
                'requested_at' => Carbon::parse($request->requested_at)->format('F d, Y g:i A'),
                'parent_name' => $request->parent->user->first_name . ' ' . $request->parent->user->last_name,
                'email' => $request->parent->user->email,
                'contact' => $request->parent->user->contact_num,
                'students' => $request->students->map(
                    fn($s) =>
                    $s->student->user->first_name . ' ' . $s->student->user->last_name
                )->toArray(),
                'rejection_reason' => $request->rejection_reason,
            ];
        } elseif ($type === 'document') {
            $request = DocumentRequest::with(['parent.user'])
                ->findOrFail($id);

            $data = [
                'type' => 'Document Request',
                'status' => $request->status,
                'requested_at' => Carbon::parse($request->requested_at)->format('F d, Y g:i A'),
                'parent_name' => $request->parent->user->first_name . ' ' . $request->parent->user->last_name,
                'email' => $request->parent->user->email,
                'contact' => $request->parent->user->contact_num,
                'students' => [], // no students for document requests
                'rejection_reason' => $request->rejection_reason,
            ];
        } else {
            abort(404, 'Unknown request type');
        }

        return response()->json($data);
    }
}
