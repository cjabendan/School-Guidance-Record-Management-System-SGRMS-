<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use App\Models\ParentLinkRequest;
use App\Models\DocumentRequest;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentRequestController extends Controller
{
    public function index()
    {
        $parentId = Auth::user()->parentProfile->p_id ?? null;

        if (!$parentId) {
            return redirect()->back()->withErrors(['error' => 'Parent profile not found.']);
        }

        // Load child link requests for this parent
        $parentModel = ParentModel::find($parentId);
        $parentName = $parentModel ? $parentModel->name : '';

        $linkRequests = ParentLinkRequest::with(['students.student.user'])
            ->where('parent_id', $parentId)
            ->get()
            ->map(function ($req) use ($parentName) {
                return [
                    'id' => $req->request_id,
                    'type' => 'Child Link',
                    'students' => $req->students->map(
                        fn($s) =>
                        $s->student->user
                            ? $s->student->user->first_name . ' ' . $s->student->user->last_name
                            : $s->student_id
                    )->toArray(),
                    'requested_at' => $req->requested_at
                        ? \Carbon\Carbon::parse($req->requested_at)->format('M d, Y')
                        : 'N/A',
                    'status' => ucfirst($req->status),
                    'parent_name' => $parentName,
                ];
            });

        // Load document requests for this parent
        $documentRequests = DocumentRequest::with(['drs.student.user'])
            ->where('parent_id', $parentId)
            ->get()
            ->map(function ($req) use ($parentName) {
                return [
                    'id' => $req->request_id,
                    'type' => 'Document',
                    'students' => $req->students->map(
                        fn($s) =>
                        $s->student->user
                            ? $s->student->user->first_name . ' ' . $s->student->user->last_name
                            : $s->student_id
                    )->toArray(),

                    'requested_at' => $req->requested_at
                        ? \Carbon\Carbon::parse($req->requested_at)->format('M d, Y')
                        : 'N/A',

                    'status' => ucfirst($req->status),
                    'parent_name' => $parentName,
                ];
            });

        // Merge all requests
        $allRequests = $linkRequests->merge($documentRequests);

        return view('Parent.requests', compact('allRequests'));
    }
}
