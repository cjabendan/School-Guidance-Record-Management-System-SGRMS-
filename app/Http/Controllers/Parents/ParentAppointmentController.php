<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ParentAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointments::with(['students', 'counselor', 'requester', 'type']);
        $query->where('requester_id', auth()->id());

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

        $appointments = $query->orderBy('appointment_datetime', 'desc')->get();

        // Fetch counselors (including Head), types, and children for modal
        $counselors = \App\Models\User::whereIn('role', ['Counselor', 'Head'])->get();
        $types = \App\Models\AppointmentType::all();
        $p_id = DB::table('parents')->where('user_id', auth()->id())->value('p_id');
        $children = Student::whereIn('s_id', function($query) use ($p_id) {
            $query->select('s_id')
                  ->from('parent_student')
                  ->where('p_id', $p_id);
        })->get();

        return view('Parent.appointments', compact('appointments', 'counselors', 'types', 'children'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'counselor_id' => 'required|exists:users,id',
            'type_id' => 'required',
            'student_id' => 'required|array|min:1',
            'student_id.*' => 'exists:students,s_id',
            'reason' => 'required|string',
            'appointment_datetime' => 'required|date',
        ]);
 
        $type_id = $request->type_id === 'general' ? null : $request->type_id;

        // Conflict check: any appointment at the same time
        $globalConflict = \App\Models\Appointments::where('appointment_datetime', $request->appointment_datetime)
            ->whereIn('status', ['Pending', 'Approved'])
            ->exists();

        // Conflict check: any selected student has appointment at same time
        $studentConflict = \App\Models\Appointments::where('appointment_datetime', $request->appointment_datetime)
            ->whereIn('status', ['Pending', 'Approved'])
            ->whereHas('students', function($q) use ($request) {
                $q->whereIn('student_user_id', $request->student_id);
            })->exists();

        if ($globalConflict || $studentConflict) {
            return back()->withErrors(['appointment_datetime' => 'Appointment cannot be booked. A schedule already exists'])->withInput();
        }

        $appointment = Appointments::create([
            'counselor_id' => $request->counselor_id,
            'appointment_type_id' => $type_id, // use correct column name
            'reason' => $request->reason,      // use correct column name
            'appointment_datetime' => $request->appointment_datetime,
            'requester_id' => auth()->id(),
            'status' => 'Pending',
        ]);

        // Attach student to appointment (many-to-many)
        $appointment->students()->attach($request->student_id);

        return redirect()->route('Parent.appointments.index')->with('success', 'Appointment requested successfully!');
    }
}


