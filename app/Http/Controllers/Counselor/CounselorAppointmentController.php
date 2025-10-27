<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CounselorAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $relations = ['students', 'counselor', 'requester', 'type'];
        if (Schema::hasTable('appointment_reschedules')) {
            $relations[] = 'reschedules';
        }
        $query = Appointments::with($relations);

        // Apply filters if any (case-insensitive)
        $statusFilter = $request->has('status') ? strtolower($request->status) : null;
        if ($statusFilter && $statusFilter !== 'all') {
            // When filtering for pending, include 'Rescheduled' since they're awaiting approval
            if ($statusFilter === 'pending') {
                $query->whereIn('status', ['Pending', 'Rescheduled']);
            } else {
                $query->whereRaw('LOWER(`status`) = ?', [$statusFilter]);
            }

            // If filtering completed, optionally restrict by end_datetime if that column exists
            if ($statusFilter === 'completed' && Schema::hasColumn((new Appointments)->getTable(), 'end_datetime')) {
                $query->where('end_datetime', '>=', now());
            }
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

        $appointments = $query->orderBy('appointment_datetime', 'desc')->get();

        // Attach avatar URLs for counselor and requester to simplify client rendering
        $appointments->transform(function ($a) {
            // counselor avatar
            $a->counselor_avatar = null;
            if ($a->counselor) {
                if (!empty($a->counselor->profile_image)) {
                    $a->counselor_avatar = asset('images/user/' . $a->counselor->profile_image);
                } else {
                    $a->counselor_avatar = asset('images/default-avatar.png');
                }
            } else {
                $a->counselor_avatar = asset('images/default-avatar.png');
            }

            // requester avatar
            $a->requester_avatar = null;
            if ($a->requester) {
                if (!empty($a->requester->profile_image)) {
                    $a->requester_avatar = asset('images/user/' . $a->requester->profile_image);
                } else {
                    $a->requester_avatar = asset('images/default-avatar.png');
                }
            } else {
                $a->requester_avatar = asset('images/default-avatar.png');
            }

            return $a;
        });

        $counselors = \App\Models\User::whereIn('role', ['Counselor', 'Head', 'admin'])->get();
        $types = \App\Models\AppointmentType::all();
        $children = \App\Models\Student::with('user')->get();

        return view('Counselor.appointments', compact('appointments', 'counselors', 'types', 'children'));
    }

    public function reschedule(Request $request, $id)
    {
        $appointment = Appointments::findOrFail($id);
        
        // Store the current time as original before updating to new time
        if (!$appointment->original_appointment_datetime) {
            $appointment->original_appointment_datetime = $appointment->appointment_datetime;
        }

        $appointment->update([
            'appointment_datetime' => $request->new_datetime,
            'rescheduled_count' => $appointment->rescheduled_count + 1,
            'last_rescheduled_at' => now(),
            'status' => 'Approved' // Mark as approved since counselor is doing the reschedule
        ]);

        return redirect()->back()->with('success', 'Appointment rescheduled successfully.');
    }

    /**
     * Update an existing appointment (full edit from modal)
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
    $manilaDate = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->appointment_datetime)->toDateTimeString();

        // Conflict checks excluding the current appointment: check for APPROVED appointments within 1 hour (same day)
        $requested = \Carbon\Carbon::parse($manilaDate);
        $startWindow = $requested->copy()->subHour();
        $endWindow = $requested->copy()->addHour();

        $globalConflict = Appointments::where('counselor_id', $request->counselor_id)
            ->whereDate('appointment_datetime', $requested->toDateString())
            ->whereBetween('appointment_datetime', [$startWindow, $endWindow])
            ->whereRaw('LOWER(status) = ?', ['approved'])
            ->where('appointment_id', '!=', $appointment->appointment_id)
            ->exists();

        $studentConflict = Appointments::whereDate('appointment_datetime', $requested->toDateString())
            ->whereBetween('appointment_datetime', [$startWindow, $endWindow])
            ->whereRaw('LOWER(status) = ?', ['approved'])
            ->where('appointment_id', '!=', $appointment->appointment_id)
            ->whereHas('students', function($q) use ($request) {
                $q->whereIn('student_user_id', $request->student_id);
            })->exists();

        if ($globalConflict || $studentConflict) {
            return back()->withErrors(['appointment_datetime' => 'this time is already taken by someone'])->withInput();
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

    public function decline(Request $request, $id)
    {
        $request->validate([
            'decline_reason' => 'required|string',
        ]);

        $appointment = Appointments::findOrFail($id);
        $appointment->status = 'Declined';
        $appointment->decline_reason = $request->decline_reason;
        $appointment->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'status' => 'Declined']);
        }

        return redirect()->back()->with('success', 'Appointment declined successfully.');
    }

    public function startSession(Request $request, $id)
    {
        $appointment = Appointments::findOrFail($id);
        $appointment->status = 'Ongoing';
        if (Schema::hasColumn($appointment->getTable(), 'started_at')) {
            $appointment->started_at = now();
        }
        $appointment->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'status' => 'Ongoing']);
        }

        return redirect()->back()->with('success', 'Session started.');
    }

    public function endSession(Request $request, $id)
    {
        $appointment = Appointments::findOrFail($id);
        $appointment->status = 'Completed';
        if (Schema::hasColumn($appointment->getTable(), 'ended_at')) {
            $appointment->ended_at = now();
        }
        $appointment->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'status' => 'Completed']);
        }

        return redirect()->back()->with('success', 'Session ended.');
    }

    /**
     * Cancel an appointment (Counselor)
     */
    public function cancel(Request $request, $id)
    {
        $appointment = Appointments::findOrFail($id);
        if (strtolower($appointment->status) !== 'pending') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Only pending appointments can be cancelled.'], 400);
            }
            return redirect()->back()->withErrors(['status' => 'Only pending appointments can be cancelled.']);
        }

        $appointment->status = 'Cancelled';
        if (Schema::hasColumn($appointment->getTable(), 'cancelled_at')) {
            $appointment->cancelled_at = now();
        }
        $appointment->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'status' => 'Cancelled']);
        }

        return redirect()->back()->with('success', 'Appointment cancelled successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'counselor_id' => 'required|exists:users,id',
            'type_id' => 'required',
            'student_id' => 'required|array',
            'reason' => 'required|string',
            'appointment_datetime' => 'required|date',
        ]);

        // Handle 'other' appointment type
        if ($request->type_id === 'other') {
            $request->validate(['other_type' => 'required|string|max:255']);
            $typeName = trim($request->other_type);
            $type = \App\Models\AppointmentType::whereRaw('LOWER(type_name) = ?', [strtolower($typeName)])->first();
            if (!$type) {
                $type = \App\Models\AppointmentType::create(['type_name' => $typeName]);
            }
            $typeId = $type->id;
        } else {
            $request->validate(['type_id' => 'exists:appointment_types,id']);
            $typeId = $request->type_id;
        }

        // Treat submitted time as Asia/Manila local time
    $manilaDate = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->appointment_datetime)->toDateTimeString();

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
            'appointment_type_id' => $typeId,
            'reason' => $request->reason,
            'appointment_datetime' => $manilaDate,
            'status' => 'Pending',
            'requester_id' => auth()->id(),
        ]);

        $appointment->students()->sync($request->student_id);

        return redirect()->back()->with('success', 'Appointment requested successfully.');
    }
}
