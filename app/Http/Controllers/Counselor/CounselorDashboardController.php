<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CounselorDashboardController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'counselor_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:appointment_types,id',
            'student_id' => 'required|array',
            'reason' => 'required|string',
            'appointment_datetime' => 'required|date',
        ]);

        $manilaDate = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->appointment_datetime)->toIso8601String();

        // Conflict check: any appointment at the same time
        $globalConflict = \App\Models\Appointments::where('appointment_datetime', $manilaDate)
            ->whereIn('status', ['Pending', 'Approved'])
            ->exists();

        // Conflict check: any selected student has appointment at same time
        $studentConflict = \App\Models\Appointments::where('appointment_datetime', $manilaDate)
            ->whereIn('status', ['Pending', 'Approved'])
            ->whereHas('students', function($q) use ($request) {
                $q->whereIn('student_user_id', $request->student_id);
            })->exists();

        if ($globalConflict || $studentConflict) {
            return back()->withErrors(['appointment_datetime' => 'Appointment cannot be booked. A schedule already exists'])->withInput();
        }

        $appointment = \App\Models\Appointments::create([
            'counselor_id' => $request->counselor_id,
            'appointment_type_id' => $request->type_id,
            'reason' => $request->reason,
            'appointment_datetime' => $manilaDate,
            'status' => 'Pending',
            'requester_id' => auth()->id(),
        ]);

        $appointment->students()->sync($request->student_id);

        return redirect()->route('Counselor.appointments.index')->with('success', 'Appointment created successfully.');
    }
    public function move(Request $request, $id)
    {
        $user = auth()->user();
        $appointment = \App\Models\Appointments::where('appointment_id', $id)
            ->where('counselor_id', $user->id)
            ->firstOrFail();
        // Convert UTC to Asia/Manila timezone
        $utcDate = $request->input('appointment_datetime');
        $manilaDate = \Carbon\Carbon::parse($utcDate)->setTimezone('Asia/Manila');

        // Conflict check: prevent moving into an occupied approved slot (±1 hour)
        $requested = $manilaDate->copy();
        $startWindow = $requested->copy()->subHour();
        $endWindow = $requested->copy()->addHour();

        // Treat both Approved and Rescheduled as blocking statuses so the
        // counselor cannot move into an occupied slot (matches Head behavior).
        $conflict = \App\Models\Appointments::where('counselor_id', $appointment->counselor_id)
            ->whereDate('appointment_datetime', $requested->toDateString())
            ->whereBetween('appointment_datetime', [$startWindow, $endWindow])
            ->where('appointment_id', '!=', $appointment->appointment_id)
            ->whereIn('status', ['Approved', 'Rescheduled'])
            ->exists();

        // Also check student conflicts
        $studentIds = $appointment->students()->pluck('student_user_id')->toArray();
        $studentConflict = false;
        if (!empty($studentIds)) {
            $studentConflict = \App\Models\Appointments::whereDate('appointment_datetime', $requested->toDateString())
                ->whereBetween('appointment_datetime', [$startWindow, $endWindow])
                ->whereHas('students', function($q) use ($studentIds) {
                    $q->whereIn('student_user_id', $studentIds);
                })
                ->where('appointment_id', '!=', $appointment->appointment_id)
                ->whereIn('status', ['Approved', 'Rescheduled'])
                ->exists();
        }

        if ($conflict || $studentConflict) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'this time is already taken by someone'], 422);
            }
            return redirect()->back()->with('error', 'this time is already taken by someone');
        }

        $appointment->appointment_datetime = $manilaDate;
        $appointment->status = 'Rescheduled';
        $appointment->save();

        return response()->json(['success' => true, 'status' => 'Rescheduled']);
    }
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $counselor = $user->counselor;
        $name = $user->first_name . ' ' . $user->last_name;

        $filter = $request->input('filter', 'today');

        $query = \App\Models\Appointments::with(['students', 'counselor', 'requester', 'type'])
            ->where('counselor_id', $user->id)
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

        return view('Counselor.dashboard', compact('name', 'upcomingAppointments'));
    }

    public function appointments()
    {
        $user = auth()->user();
        $counselor = $user->counselor;
        if (!$counselor) {
            abort(403, 'Unauthorized');
        }
        $query = \App\Models\Appointments::with(['type', 'requester', 'students.user', 'counselor'])
            ->where('counselor_id', $user->id);

        // Filter by status if provided (accepts: pending, approved, declined, cancelled, completed)
        if (request()->has('status')) {
            $status = strtolower(request('status'));
            $allowed = ['pending', 'approved', 'declined', 'cancelled', 'completed'];
            if (in_array($status, $allowed)) {
                // For pending, include rescheduled entries
                if ($status === 'pending') {
                    $query->whereIn('status', ['Pending', 'Rescheduled']);
                } else {
                    // case-insensitive equality for other statuses
                    $query->whereRaw('LOWER(`status`) = ?', [$status]);
                }
            }
        }

        // Filter by search if provided
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->whereHas('type', function($q2) use ($search) {
                    $q2->where('type_name', 'like', "%$search%");
                })
                ->orWhereHas('requester', function($q2) use ($search) {
                    $q2->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%");
                })
                ->orWhereHas('students.user', function($q2) use ($search) {
                    $q2->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%");
                });
            });
        }

        $appointments = $query->orderByDesc('appointment_datetime')->get();

        // Include all counselors and Head Counselor (admin)
        $counselors = \App\Models\User::whereIn('role', ['Counselor', 'Head', 'admin'])
            ->get(['id', 'first_name', 'last_name']);

        // Appointment types
        $types = \App\Models\AppointmentType::all();

        // Show all students
        $children = \App\Models\Student::with('user')->get();

        return view('Counselor.appointments', compact('appointments', 'counselors', 'types', 'children'));
    }

    public function approve($id)
    {
        $user = auth()->user();
        $appointment = \App\Models\Appointments::where('appointment_id', $id)
            ->where('counselor_id', $user->id)
            ->firstOrFail();
        $appointment->status = 'approved';
        $appointment->save();
        return redirect()->route('Counselor.appointments.index')->with('success', 'Appointment approved.');
    }

    public function decline(Request $request, $id)
    {
        $user = auth()->user();
        $appointment = \App\Models\Appointments::where('appointment_id', $id)
            ->where('counselor_id', $user->id)
            ->firstOrFail();
        $appointment->status = 'declined';
        $appointment->decline_reason = $request->input('decline_reason');
        $appointment->save();
        return redirect()->route('Counselor.appointments.index')->with('success', 'Appointment declined.');
    }
}
