<?php

namespace App\Http\Controllers\Parents;

use App\Models\ParentModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcements;
use App\Models\Appointments;
use App\Models\ParentLinkRequestStudent;
use App\Models\ParentStudent;
use App\Models\CaseModel;

class ParentDashboardController extends Controller
{
    /**
     * Display the dashboard view.
     */
    public function dashboard(Request $request)
    {
        // Fetch latest announcements
        $announcements = Announcements::orderByDesc('date_posted')->take(5)->get();

        $userId = Auth::id();
        $parent = ParentModel::where('user_id', $userId)->first();

        // Child count
        $childCount = 0;
        $casesCount = 0;
        $pendingRequestCount = 0;

        if ($parent) {
            // Count linked children via pivot
            $childCount = ParentStudent::where('p_id', $parent->p_id)->count();

            // Get linked child IDs
            $childIds = ParentStudent::where('p_id', $parent->p_id)->pluck('s_id')->toArray();

            // Count cases linked to these students via pivot
            $casesCount = CaseModel::whereHas('students', function ($q) use ($childIds) {
                $q->whereIn('students.s_id', $childIds);
            })->count();

            // Count pending link requests
            $pendingRequestCount = ParentLinkRequestStudent::whereHas('linkRequest', function ($q) use ($parent) {
                $q->where('parent_id', $parent->p_id)
                    ->where('status', 'pending');
            })->count();
        }
        // Upcoming appointments
        $filter = $request->input('filter', 'today');
        $query = Appointments::with(['student', 'counselor', 'requester', 'type'])
            ->where('requester_id', $userId)
            ->where('status', 'approved')
            ->where('appointment_datetime', '>', now());

        if ($filter === 'today') {
            $query->whereDate('appointment_datetime', now()->toDateString());
        } elseif ($filter === 'tomorrow') {
            $query->whereDate('appointment_datetime', now()->addDay()->toDateString());
        } elseif ($filter === 'week') {
            $query->whereBetween('appointment_datetime', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        } else {
            $query->where('appointment_datetime', '>', now());
        }

        $upcomingAppointments = $query->orderBy('appointment_datetime', 'asc')->limit(5)->get();

        if ($request->ajax()) {
            $html = view('Parents.dashboard.appointments-table', compact('upcomingAppointments'))->render();
            return response()->json(['html' => $html]);
        }

        return view('Parent.dashboard', compact(
            'announcements',
            'upcomingAppointments',
            'childCount',
            'casesCount',
            'pendingRequestCount'
        ));
    }
}
