<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use Illuminate\Http\Request;

class HeadAppointmentController extends Controller
{
    public function json($id)
    {
        $appointment = Appointments::with(['students.user', 'counselor', 'type', 'requester'])->findOrFail($id);
        return response()->json([
            'appointment_id' => $appointment->appointment_id,
            'counselor_id' => $appointment->counselor_id,
            'type_id' => $appointment->type ? $appointment->type->type_id : null,
            'student_ids' => $appointment->students->pluck('s_id')->toArray(),
            'reason' => $appointment->reason,
            'appointment_datetime' => $appointment->appointment_datetime ? $appointment->appointment_datetime->format('Y-m-d\TH:i') : null,
        ]);
    }
    public function move(Request $request, $id)
    {
        $appointment = Appointments::findOrFail($id);

        $data = $request->validate([
            'appointment_datetime' => 'required|string',
        ]);

        try {
            // FullCalendar sends ISO string in UTC via toISOString()
            $dt = \Carbon\Carbon::parse($data['appointment_datetime']);

            // Convert to the timezone you store in DB (example: Asia/Manila)
            $manila = $dt->setTimezone('Asia/Manila');

            $appointment->appointment_datetime = $manila;
            $appointment->save();

            return response()->json([
                'success' => true,
                'saved_datetime' => $manila->toDateTimeString(),
            ]);
        } catch (\Throwable $e) {
            \Log::error('Appointment move error: ' . $e->getMessage(), [
                'id' => $id,
                'payload' => $request->all()
            ]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    public function index(Request $request)
    {

        $query = Appointments::with(['students', 'counselor', 'requester', 'type']);

        // Apply filters if any
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('appointment_id', 'like', '%' . $search . '%')
                  ->orWhereHas('type', function ($typeQuery) use ($search) {
                      $typeQuery->where('type_name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Exclude past events automatically if filtering by events
        $query->when($request->status === 'completed', function ($q) {
            $q->where('end_datetime', '>=', now());
        });

        $appointments = $query->orderBy('appointment_datetime', 'desc')->get();

    $counselors = \App\Models\User::whereIn('role', ['Counselor', 'Head', 'admin'])->get();
        $types = \App\Models\AppointmentType::all();
        $children = \App\Models\Student::with('user')->get();

        return view('Head.appointments', compact('appointments', 'counselors', 'types', 'children'));
    }

    public function reschedule(Request $request, $id)
    {
        $appointment = Appointments::findOrFail($id);

        $appointment->update([
            'appointment_datetime' => $request->new_datetime,
            'rescheduled_count' => $appointment->rescheduled_count + 1,
            'last_rescheduled_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Appointment rescheduled successfully.');
    }

    public function getAppointments(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $appointments = Appointments::where('status', 'Approved')
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->whereNotNull('appointment_datetime')
                        ->whereDate('appointment_datetime', '>=', $start)
                        ->whereDate('appointment_datetime', '<=', $end);
                });
            })
            ->get()
            ->map(function ($a) {
                return [
                    'Type' => $a->type,
                    'start' => \Carbon\Carbon::parse($a->appointment_datetime)->toIso8601String(),
                    'end'   => \Carbon\Carbon::parse($a->appointment_datetime)->addHours(1)->toIso8601String(),
                    'allDay' => false,
                    'extendedProps' => [
                        'status' => $a->status,
                        'requested' => $a->requester->name,
                    ]
                ];
            });

        return response()->json($appointments);
    }

    public function approve($id)
    {
        $appointment = Appointments::findOrFail($id);
        $appointment->status = 'Approved';
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment approved successfully.');
    }

    public function decline(Request $request, $id)
    {
        $request->validate([
            'decline_reason' => 'required|string',
        ]);
        $appointment = Appointments::findOrFail($id);
        $appointment->status = 'Declined';
        $appointment->decline_reason = $request->decline_reason;
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment declined successfully.');
    }
    public function store(Request $request)
    {
        $request->validate([
            'counselor_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:appointment_types,id',
            'student_id' => 'required|array',
            'reason' => 'required|string',
            'appointment_datetime' => 'required|date',
        ]);

        // Treat submitted time as Asia/Manila local time
        $manilaDate = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->appointment_datetime)->toIso8601String();

        // Conflict check: any appointment at the same time
        $globalConflict = Appointments::where('appointment_datetime', $manilaDate)
            ->whereIn('status', ['Pending', 'Approved'])
            ->exists();

        // Conflict check: any selected student has appointment at same time
        $studentConflict = Appointments::where('appointment_datetime', $manilaDate)
            ->whereIn('status', ['Pending', 'Approved'])
            ->whereHas('students', function($q) use ($request) {
                $q->whereIn('student_user_id', $request->student_id);
            })->exists();

        if ($globalConflict || $studentConflict) {
            return back()->withErrors(['appointment_datetime' => 'Appointment cannot be booked. A schedule already exists'])->withInput();
        }

        $appointment = Appointments::create([
            'counselor_id' => $request->counselor_id,
            'appointment_type_id' => $request->type_id, // <-- FIXED HERE
            'reason' => $request->reason,
            'appointment_datetime' => $manilaDate,
            'status' => 'Pending',
            'requester_id' => auth()->id(),
        ]);

        $appointment->students()->sync($request->student_id);

        return redirect()->back()->with('success', 'Appointment requested successfully.');
    }
}
