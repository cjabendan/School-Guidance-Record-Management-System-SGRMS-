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

        // Appointments (unchanged)
        $filter = $request->input('filter', 'today');

        // Head should see upcoming appointments across counselors, not only those assigned to the head user.
        // Eager-load the correct relation name 'students' and other relations used in views.
        // Build two queries:
        // 1) Pending/Approved appointments filtered by date (used for normal upcoming list)
        // 2) Ongoing appointments (always included regardless of date)

        $baseQuery = Appointments::with(['students', 'counselor', 'requester', 'type'])
            ->whereIn('status', ['Pending', 'Approved'])
            ->where('appointment_datetime', '>', now());

        if ($filter === 'today') {
            $baseQuery->whereDate('appointment_datetime', now()->toDateString());
        } elseif ($filter === 'tomorrow') {
            $baseQuery->whereDate('appointment_datetime', now()->addDay()->toDateString());
        } elseif ($filter === 'week') {
            $baseQuery->whereBetween('appointment_datetime', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        $pendingApproved = $baseQuery->orderBy('appointment_datetime', 'asc')->get();

        $ongoing = Appointments::with(['students', 'counselor', 'requester', 'type'])
            ->where('status', 'Ongoing')
            ->get();

        // Merge, de-duplicate by appointment_id, and sort by appointment_datetime ascending
        $merged = $pendingApproved->merge($ongoing)->keyBy('appointment_id')->values();
        $upcomingAppointments = $merged->sortBy('appointment_datetime')->values()->take(5);

        return view('Head.dashboard', compact(
            'totalStudents',
            'totalParents',
            'totalCounselors',
            'totalCases',
            'upcomingAppointments'
        ));
    }
}
