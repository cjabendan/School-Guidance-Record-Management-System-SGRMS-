<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // <-- Add this line

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointments;
use App\Models\ParentModel;

class HeadDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        
        // Count stats
        $totalStudents = User::where('role', 'student')->count();
        $totalParents = ParentModel::count(); 
        $totalCounselors = User::where('role', 'counselor')->count();
        $totalCases = class_exists('\App\Models\CaseModel') ? \App\Models\CaseModel::count() : 0;

        $userId = Auth::id();
        $filter = $request->input('filter', 'today');
        $query = Appointments::with(['student', 'admin', 'counselor', 'requester'])
            ->where('counselor_id', $userId)
            ->where('status', 'approved')
            ->where('appointment_datetime', '>', now());

        if ($filter === 'today') {
            $query->whereDate('appointment_datetime', now()->toDateString());
        } elseif ($filter === 'tomorrow') {
            $query->whereDate('appointment_datetime', now()->addDay()->toDateString());
        } elseif ($filter === 'week') {
            $query->whereBetween('appointment_datetime', [
                now()->startOfWeek(), now()->endOfWeek()
            ]);
        } else {
            $query->where('appointment_datetime', '>', now());
        }

        $upcomingAppointments = $query->orderBy('appointment_datetime', 'asc')->limit(5)->get();

        if ($request->ajax()) {
            $html = view('Head.partials.appointments-table', compact('upcomingAppointments'))->render();
            return response()->json(['html' => $html]);
        }

        return view('Head.dashboard', compact(
            'totalStudents',
            'totalParents',
            'totalCounselors',
            'totalCases',
            'upcomingAppointments'
        ));
    }
}
