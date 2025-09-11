<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentLinkRequest;
use App\Models\DocumentRequest;
use App\Models\ParentStudent;
use Illuminate\Support\Facades\DB;

class HeadRequestController extends Controller
{
    public function index()
    {
        // Load child link requests
        $linkRequests = ParentLinkRequest::with(['parent.user', 'students.student.user'])
            ->get()
            ->map(function ($req) {
                $sex = strtolower($req->parent->user->sex ?? '');
                $prefix = $sex === 'male' ? 'Mr.' : ($sex === 'female' ? 'Ms.' : '');
                $lastName = $req->parent->user->last_name ?? 'Unknown';

                return [
                    'id' => $req->request_id,
                    'type' => 'Child Link',
                    'students' => $req->students->map(
                        fn($s) =>
                        $s->student->user
                            ? $s->student->user->first_name . ' ' . $s->student->user->last_name
                            : $s->student_id
                    )->toArray(),
                    'parent_name' => trim($prefix . ' ' . $lastName),
                    'requested_at' => $req->requested_at
                        ? \Carbon\Carbon::parse($req->requested_at)->format('M d, Y')
                        : 'N/A',
                    'status' => ucfirst($req->status),
                ];
            });

        // Load document requests
        $documentRequests = DocumentRequest::with(['parent.user', 'drs.student.user'])
            ->get()
            ->map(function ($req) {
                $sex = strtolower($req->parent->user->sex ?? '');
                $prefix = $sex === 'male' ? 'Mr.' : ($sex === 'female' ? 'Ms.' : '');
                $lastName = $req->parent->user->last_name ?? 'Unknown';

                return [
                    'id' => $req->request_id,
                    'type' => 'Document',
                    'students' => $req->drs->map(
                        fn($d) =>
                        $d->student->user
                            ? $d->student->user->first_name . ' ' . $d->student->user->last_name
                            : $d->s_id
                    )->toArray(),
                    'parent_name' => trim($prefix . ' ' . $lastName),
                    'requested_at' => $req->requested_at
                        ? \Carbon\Carbon::parse($req->requested_at)->format('M d, Y')
                        : 'N/A',
                    'status' => ucfirst($req->status),
                ];
            });

        // Merge all requests
        $allRequests = $linkRequests->merge($documentRequests);

        return view('Head.requests', compact('allRequests'));
    }

    // Approve link request and link students to parent
    public function approve($id)
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
                    'p_id'     => $linkRequest->parent_id,
                    's_id'     => $studentRequest->student_id,
                    'relation' => 'Parent', // dynamic if needed
                ]);
            }
        }

        return redirect()->back()->with('success', 'Request approved and students linked successfully.');
    }

    // Reject link request with reason
    public function reject(Request $request, $id)
    {
        $linkRequest = ParentLinkRequest::findOrFail($id);
        $linkRequest->status = 'rejected';
        $linkRequest->rejection_reason = $request->input('reason');
        $linkRequest->save();

        return redirect()->back()->with('success', 'Request rejected successfully.');
    }

    // Approve document request
    public function approveDocument($id)
    {
        $docRequest = DocumentRequest::findOrFail($id);
        $docRequest->status = 'approved';
        $docRequest->save();

        return redirect()->back()->with('success', 'Document request approved successfully.');
    }

    // Reject document request
    public function rejectDocument(Request $request, $id)
    {
        $docRequest = DocumentRequest::findOrFail($id);
        $docRequest->status = 'rejected';
        $docRequest->rejection_reason = $request->input('reason');
        $docRequest->save();

        return redirect()->back()->with('success', 'Document request rejected successfully.');
    }
}
