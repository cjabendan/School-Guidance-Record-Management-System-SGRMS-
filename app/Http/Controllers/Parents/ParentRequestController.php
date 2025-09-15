<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use App\Models\ParentLinkRequest;
use App\Models\DocumentRequest;
use App\Models\ParentStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentRequestController extends Controller
{
    public function index(Request $request)
    {
        $parentId = Auth::user()->parentProfile->p_id ?? null;

        if (!$parentId) {
            return redirect()->back()->withErrors(['error' => 'Parent profile not found.']);
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
            ]);
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
