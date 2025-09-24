<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use Illuminate\Http\Request;

class CounselorAppointmentController extends Controller
{
    public function index(Request $request)
    {

        $query = Appointments::with(['student', 'counselor', 'requester', 'type']);

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

    return view('Counselor.appointment', compact('appointments'));
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


}
