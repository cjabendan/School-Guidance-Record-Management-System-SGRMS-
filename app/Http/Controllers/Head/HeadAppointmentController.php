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
        // Convert UTC to Asia/Manila timezone
        $utcDate = $request->input('appointment_datetime');
        $manilaDate = \Carbon\Carbon::parse($utcDate)->setTimezone('Asia/Manila');
        $appointment->appointment_datetime = $manilaDate;
        $appointment->save();
        return response()->json(['success' => true]);
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

        $query = Appointments::where('status', 'Approved')
            ->whereNotNull('appointment_datetime')
            ->whereDate('appointment_datetime', '>=', $start)
            ->whereDate('appointment_datetime', '<=', $end)
            ->with(['type', 'requester', 'counselor', 'students.user']);

        // Optional: filter by specific counselor if requested
        if ($request->has('counselor_id') && $request->counselor_id != 'all') {
            $query->where('counselor_id', $request->counselor_id);
        }

        $appointments = $query->get()->map(function ($a) {
            return [
                'appointment_id' => $a->appointment_id,
                'title' => $a->type->type_name ?? 'Appointment',
                'start' => $a->appointment_datetime ? $a->appointment_datetime->toIso8601String() : null,
                'end' => $a->appointment_datetime ? $a->appointment_datetime->copy()->addHour()->toIso8601String() : null,
                'allDay' => false,
                'extendedProps' => [
                    'status' => $a->status,
                    'requester' => $a->requester->name ?? 'N/A',
                    'counselor' => $a->counselor ? $a->counselor->first_name . ' ' . $a->counselor->last_name : 'N/A',
                    'student' => $a->students->map(fn($s) => $s->user->first_name . ' ' . $s->user->last_name)->join(', '),
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
        // Basic validation; we'll handle 'other' specially below
        $request->validate([
            'counselor_id' => 'required|exists:users,id',
            'type_id' => 'required',
            'student_id' => 'required|array',
            'reason' => 'required|string',
            'appointment_datetime' => 'required|date',
        ]);

        // If the user selected 'other', require other_type
        if ($request->type_id === 'other') {
            $request->validate([
                'other_type' => 'required|string|max:255',
            ]);
            $typeName = trim($request->other_type);
            // Find existing type (case-insensitive) or create
            $type = \App\Models\AppointmentType::whereRaw('LOWER(type_name) = ?', [strtolower($typeName)])->first();
            if (!$type) {
                $type = \App\Models\AppointmentType::create([
                    'type_name' => $typeName,
                ]);
            }
            $typeId = $type->id;
        } else {
            // ensure provided type exists
            $request->validate([
                'type_id' => 'exists:appointment_types,id',
            ]);
            $typeId = $request->type_id;
        }

        // Treat submitted time as Asia/Manila local time
        $manilaDate = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->appointment_datetime)->toIso8601String();

        // Conflict check: any appointment at the same time
        $globalConflict = Appointments::where('appointment_datetime', $manilaDate)
            ->whereIn('status', ['Pending', 'Approved'])
            ->exists();

        // Conflict check: any selected student has appointment at same time
        $studentConflict = Appointments::where('appointment_datetime', $manilaDate)
            ->whereIn('status', ['Pending', 'Approved'])
            ->whereHas('students', function ($q) use ($request) {
                $q->whereIn('student_user_id', $request->student_id);
            })->exists();

        if ($globalConflict || $studentConflict) {
            return back()->withErrors(['appointment_datetime' => 'Appointment cannot be booked. A schedule already exists'])->withInput();
        }

        $appointment = Appointments::create([
            'counselor_id' => $request->counselor_id,
            'appointment_type_id' => $typeId,
            'reason' => $request->reason,
            'appointment_datetime' => $manilaDate,
            'status' => 'Pending',
            'requester_id' => auth()->id(),
        ]);

        $appointment->students()->sync($request->student_id);

        return redirect()->back()->with('success', 'Appointment requested successfully.');
    }

    /**
     * Update an existing appointment (from the reschedule/edit modal)
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointments::findOrFail($id);

        // Basic validation
        $request->validate([
            'counselor_id' => 'required|exists:users,id',
            'type_id' => 'required',
            'student_id' => 'required|array',
            'reason' => 'required|string',
            'appointment_datetime' => 'required|date',
        ]);

        if ($request->type_id === 'other') {
            $request->validate([
                'other_type' => 'required|string|max:255',
            ]);
            $typeName = trim($request->other_type);
            $type = \App\Models\AppointmentType::whereRaw('LOWER(type_name) = ?', [strtolower($typeName)])->first();
            if (!$type) {
                $type = \App\Models\AppointmentType::create([
                    'type_name' => $typeName,
                ]);
            }
            $typeId = $type->id;
        } else {
            $request->validate([
                'type_id' => 'exists:appointment_types,id',
            ]);
            $typeId = $request->type_id;
        }

        // Convert submitted datetime (local Manila) to ISO string
        $manilaDate = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->appointment_datetime)->toIso8601String();

        // Conflict checks excluding the current appointment
        $globalConflict = Appointments::where('appointment_datetime', $manilaDate)
            ->whereIn('status', ['Pending', 'Approved'])
            ->where('appointment_id', '!=', $appointment->appointment_id)
            ->exists();

        $studentConflict = Appointments::where('appointment_datetime', $manilaDate)
            ->whereIn('status', ['Pending', 'Approved'])
            ->where('appointment_id', '!=', $appointment->appointment_id)
            ->whereHas('students', function ($q) use ($request) {
                $q->whereIn('student_user_id', $request->student_id);
            })->exists();

        if ($globalConflict || $studentConflict) {
            return back()->withErrors(['appointment_datetime' => 'Appointment cannot be booked. A schedule already exists'])->withInput();
        }

        // Update fields
        $appointment->counselor_id = $request->counselor_id;
        $appointment->appointment_type_id = $typeId;
        $appointment->reason = $request->reason;
        $appointment->appointment_datetime = $manilaDate;
        $appointment->save();

        // Sync students
        $appointment->students()->sync($request->student_id);

        return redirect()->back()->with('success', 'Appointment updated successfully.');
    }
}
