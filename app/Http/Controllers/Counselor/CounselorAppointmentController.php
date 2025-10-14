<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AppointmentStudent;
use Carbon\Carbon;

class CounselorAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $counselorId = $request->input('counselor_id', Auth::id());

        $query = Appointments::with(['students', 'counselor', 'requester', 'type'])
            ->where('counselor_id', $counselorId);

        // Apply filters if any
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('reason', 'like', "%{$s}%")
                  ->orWhereHas('students', function($q2) use ($s) {
                      $q2->where('name', 'like', "%{$s}%");
                  });
            });
        }

        $appointments = $query->orderBy('appointment_datetime', 'desc')->get();

        return view('Counselor.appointments', compact('appointments'));
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
        $counselorId = $request->query('counselor_id', Auth::id());

        $appointments = Appointments::where('status', 'Approved')
            ->where('counselor_id', $counselorId)
            ->whereBetween('appointment_datetime', [$start, $end])
            ->get()
            ->map(function($a){
                return [
                    'id' => $a->appointment_id,
                    'title' => $a->requester_name ?? ($a->type->type_name ?? 'Appointment'),
                    'start' => optional($a->appointment_datetime)->toIso8601String(),
                    'status' => $a->status,
                ];
            });

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'counselor_id' => 'required|integer|exists:users,id',
            'type_id' => 'required|integer|exists:appointment_types,id',
            'student_id' => 'required|array|min:1',
            'student_id.*' => 'required|integer',
            'reason' => 'nullable|string|max:2000',
            'appointment_datetime' => 'required|string', // datetime-local from the form
        ]);

        try {
            // The form uses <input type="datetime-local"> and JS sets Asia/Manila value.
            // Parse the incoming value as Asia/Manila to preserve user selection.
            $dt = Carbon::createFromFormat('Y-m-d\TH:i', $data['appointment_datetime'], 'Asia/Manila');
        } catch (\Throwable $e) {
            // Fallback to generic parse
            $dt = Carbon::parse($data['appointment_datetime']);
        }

        $appointment = Appointments::create([
            'requester_id' => Auth::id(),
            'requester_type' => 'Counselor',
            'counselor_id' => $data['counselor_id'],
            'appointment_type_id' => $data['type_id'],
            'reason' => $data['reason'] ?? null,
            'appointment_datetime' => $dt,
            'status' => 'Pending',
            'rescheduled_count' => 0,
        ]);

        foreach ($data['student_id'] as $sid) {
            AppointmentStudent::create([
                'appointment_id' => $appointment->appointment_id,
                'student_user_id' => $sid,
            ]);
        }

        // Redirect to the counselor appointments index and keep the selected counselor so the
        // table/calendar shows the newly created request for that counselor.
        return redirect()->route('Counselor.appointments.index', ['counselor_id' => $data['counselor_id']])
            ->with('success', 'Appointment request submitted.');
    }


}
