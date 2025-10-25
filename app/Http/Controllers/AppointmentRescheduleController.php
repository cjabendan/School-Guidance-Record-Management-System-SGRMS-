<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // Persist reschedule request data on the appointments row (fused approach)
        try {
            if (Schema::hasColumn('appointments', 'rescheduled_count')) {
                $appointment->rescheduled_count = ($appointment->rescheduled_count ?? 0) + 1;
            }
            if (Schema::hasColumn('appointments', 'last_rescheduled_at')) {
                $appointment->last_rescheduled_at = now();
            }

            // store proposed datetime and reason on dedicated columns (don't overwrite official appointment_datetime)
            if ($request->filled('proposed_datetime')) {
                try {
                    $proposedDatetime = Carbon::parse($request->input('proposed_datetime'));
                    // Set both the reschedule proposal and update the actual appointment time
                    if (Schema::hasColumn('appointments', 'reschedule_proposed_datetime')) {
                        $appointment->reschedule_proposed_datetime = $proposedDatetime;
                    }
                    $appointment->appointment_datetime = $proposedDatetime;
                } catch (\Exception $e) {
                    Log::warning('Failed to parse proposed_datetime for reschedule: '.$e->getMessage());
                }
            }

            if (Schema::hasColumn('appointments', 'reschedule_reason')) {
                $appointment->reschedule_reason = $request->input('reason');
            }

            if (Schema::hasColumn('appointments', 'reschedule_requester_id')) {
                $appointment->reschedule_requester_id = $user->id;
            }

            // Mark appointment as Rescheduled
            if (Schema::hasColumn('appointments', 'status')) {
                $appointment->status = 'Rescheduled';
            }

            $appointment->save();
        } catch (\Exception $e) {
            Log::error('Failed updating appointment during reschedule request: '.$e->getMessage(), ['appointment_id' => $appointmentId]);
            return redirect()->back()->withErrors(['db' => 'Failed to submit reschedule request.']);
        }

        // Optionally: notify Head/Counselor here (not implemented)

        return redirect()->back()->with('success', 'Reschedule request submitted.');
    }
}
