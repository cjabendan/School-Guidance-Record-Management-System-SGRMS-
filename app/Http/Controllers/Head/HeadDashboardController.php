<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointments;
use App\Models\ParentModel;
use App\Models\CaseModel;
use App\Models\ParentLinkRequest;
use App\Models\DocumentRequest;

class HeadDashboardController extends Controller
{

    public function dashboard(Request $request)
    {
        // Counts
        $totalStudents = User::where('role', 'student')->count();
        $totalParents = User::where('role', 'parent')->count();
        $totalCounselors = User::where('role', 'counselor')->count();
        $totalCases = CaseModel::count();

        // Merge pending requests
        $linkRequests = ParentLinkRequest::with(['parent.user', 'students.student.user'])
            ->where('status', 'pending')
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->request_id,
                    'type' => 'child-link',
                    'parent' => $req->parent,
                    'students' => $req->students,
                    'requested_at' => $req->requested_at,
                ];
            });

        $documentRequests = DocumentRequest::with(['parent.user', 'drs.student.user'])
            ->where('status', 'pending')
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->request_id,
                    'type' => 'document',
                    'parent' => $req->parent,
                    'students' => $req->drs,
                    'requested_at' => $req->requested_at,
                ];
            });

        $pendingRequests = $linkRequests->merge($documentRequests);

        // Appointments (unchanged)
        $userId = Auth::id();
        $filter = $request->input('filter', 'today');
        $query = Appointments::with(['student', 'counselor', 'requester', 'type'])
            ->where('counselor_id', $userId)
            ->where('status', 'approved')
            ->where('appointment_datetime', '>', now());

        if ($filter === 'today') {
            $query->whereDate('appointment_datetime', now()->toDateString());
        } elseif ($filter === 'tomorrow') {
            $query->whereDate('appointment_datetime', now()->addDay()->toDateString());
        } elseif ($filter === 'week') {
            $query->whereBetween('appointment_datetime', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        $upcomingAppointments = $query->orderBy('appointment_datetime', 'asc')->limit(5)->get();

        return view('Head.dashboard', compact(
            'totalStudents',
            'totalParents',
            'totalCounselors',
            'totalCases',
            'upcomingAppointments',
            'pendingRequests'
        ));
    }
}
