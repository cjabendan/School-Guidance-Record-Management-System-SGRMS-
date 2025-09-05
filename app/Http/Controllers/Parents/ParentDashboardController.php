<?php

namespace App\Http\Controllers\Parents;

use App\Models\ParentModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcements;
use App\Models\Appointments;
use Illuminate\Support\Facades\DB;

class ParentDashboardController extends Controller
{

    /**
     * Display the dashboard view.
     */
    public function dashboard(Request $request)
    {
        $announcements = Announcements::orderByDesc('date_posted')->take(10)->get();

        $userId = Auth::id();
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
        
        return view('Parent.dashboard', compact('announcements', 'upcomingAppointments'));
    }
}
