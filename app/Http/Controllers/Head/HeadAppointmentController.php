<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use Illuminate\Http\Request;

class HeadAppointmentController extends Controller
{
    public function index()
    {
        $Appointments = Appointments::with(['student', 'counselor', 'requester', 'type'])->get();
        return view('Head.appointments', compact('Appointments'));
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
    
}
