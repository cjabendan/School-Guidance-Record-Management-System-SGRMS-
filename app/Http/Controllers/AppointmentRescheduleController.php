<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppointmentReschedule;
use App\Models\Appointments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AppointmentRescheduleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'reason' => 'required|string|max:2000',
            'proposed_datetime' => 'nullable|date',
        ]);

        // support route param {id} or form field appointment_id
        $appointmentId = $request->route('id') ?? $request->input('appointment_id');
        $appointment = Appointments::where('appointment_id', $appointmentId)->firstOrFail();

        // Only allow requester (student/parent) who created the appointment or related student/parent to request reschedule
        $user = Auth::user();

        // Simple check: requester_id must match current user OR (for students/parents more complex checks could be added)
        if ($appointment->requester_id !== $user->id && $user->role !== 'Head' && $user->role !== 'Counselor') {
            // For Parent/Student, also allow if they are linked via appointment students relation
            $linked = $appointment->students()->where('student_user_id', function($q) use ($user) {
                $q->select('s_id')->from('students')->where('user_id', $user->id);
            })->exists();
            if (!$linked) {
                return redirect()->back()->withErrors(['permission' => 'You are not authorized to request a reschedule for this appointment.']);
            }
        }

        // Only allow reschedule requests when the appointment has been approved (unless user is Head/Counselor)
        if (!in_array($user->role, ['Head', 'Counselor']) && strtolower($appointment->status) !== 'approved') {
            return redirect()->back()->withErrors(['status' => 'You can only request a reschedule for appointments that are Approved.']);
        }

        // If the appointment table has rescheduled counters, update them and apply proposed datetime.
        try {
            if (Schema::hasColumn('appointments', 'rescheduled_count')) {
                $appointment->rescheduled_count = ($appointment->rescheduled_count ?? 0) + 1;
            }
            if (Schema::hasColumn('appointments', 'last_rescheduled_at')) {
                $appointment->last_rescheduled_at = now();
            }

            // If the requester supplied a proposed datetime, update the appointment datetime
            if ($request->filled('proposed_datetime')) {
                try {
                    $appointment->appointment_datetime = Carbon::parse($request->input('proposed_datetime'));
                } catch (\Exception $e) {
                    // ignore parse errors, keep original datetime
                    Log::warning('Failed to parse proposed_datetime for reschedule: '.$e->getMessage());
                }
            }

            // Set appointment status to 'Rescheduled' if enum allows it.
            if (Schema::hasColumn('appointments', 'status')) {
                $appointment->status = 'Rescheduled';
            }

            $appointment->save();
        } catch (\Exception $e) {
            // Log the error so it can be inspected instead of being silently ignored
            Log::error('Failed updating appointment during reschedule request: '.$e->getMessage(), ['appointment_id' => $appointmentId]);
        }

        // Create a record in appointment_reschedules table if it exists; otherwise we've updated the appointments table only.
        $reschedule = null;
        if (Schema::hasTable('appointment_reschedules')) {
            $reschedule = AppointmentReschedule::create([
                'appointment_id' => $appointment->appointment_id,
                'requester_id' => $user->id,
                'reason' => $request->reason,
                'proposed_datetime' => $request->proposed_datetime ?: null,
                'status' => 'Pending',
            ]);
        }

        // Optionally: notify Head/Counselor here (not implemented)

        return redirect()->back()->with('success', 'Reschedule request submitted.');
    }
}
