<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointments;

class StudentDashboardController extends Controller
{
    // ...existing methods...

    public function dashboard(Request $request)
    {
        $userId = Auth::id();
        $filter = $request->input('filter', 'today');

        $query = Appointments::with(['students', 'counselor', 'requester', 'type'])
            ->whereHas('students', function ($q) use ($userId) {
                // Filter by the students.user_id column (links Student -> User)
                $q->where('user_id', $userId);
            })
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

        return view('Student.dashboard', compact('upcomingAppointments'));
    }
}