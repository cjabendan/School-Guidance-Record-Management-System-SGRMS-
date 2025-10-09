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
        $appointment->appointment_datetime = $manilaDate;
        $appointment->save();
        return response()->json(['success' => true]);
    }
    public function dashboard()
    {
        $user = auth()->user();
        $counselor = $user->counselor;
        $name = $user->first_name . ' ' . $user->last_name;
        return view('Counselor.dashboard', compact('name'));
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

        // Filter by status if provided
        if (request()->has('status') && in_array(request('status'), ['pending', 'approved', 'declined'])) {
            $query->where('status', request('status'));
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
