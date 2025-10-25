<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ParentAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $relations = ['students', 'counselor', 'requester', 'type'];
        if (Schema::hasTable('appointment_reschedules')) {
            $relations[] = 'reschedules';
        }
        $query = Appointments::with($relations);
        $query->where('requester_id', auth()->id());

        if ($request->has('status') && $request->status != 'all') {
            $status = $request->status;
            if (strtolower($status) === 'pending') {
                $query->whereIn('status', ['Pending', 'Rescheduled']);
            } else {
                $query->whereRaw('LOWER(status) = ?', [strtolower($status)]);
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

        // Fetch counselors (including Head), types, and children for modal
    // Include 'admin' role as well so admins who function as counselors are listed
    $counselors = \App\Models\User::whereIn('role', ['Counselor', 'Head', 'admin'])->get();
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
        // Handle 'other' appointment type
        if ($request->type_id === 'other') {
            $request->validate(['other_type' => 'required|string|max:255']);
            $typeName = trim($request->other_type);
            $type = \App\Models\AppointmentType::whereRaw('LOWER(type_name) = ?', [strtolower($typeName)])->first();
            if (!$type) {
                $type = \App\Models\AppointmentType::create(['type_name' => $typeName]);
            }
            $type_id = $type->id;
        } else {
            $type_id = $request->type_id === 'general' ? null : $request->type_id;
        }

        // Conflict check: prevent booking if there is an already 'approved' appointment within a 1-hour window
        $requested = \Carbon\Carbon::parse($request->appointment_datetime);
        $startWindow = $requested->copy()->subHour();
        $endWindow = $requested->copy()->addHour();

        // For the given counselor, check approved appointments in the 1-hour window (same day)
        $globalConflict = \App\Models\Appointments::where('counselor_id', $request->counselor_id)
            ->whereDate('appointment_datetime', $requested->toDateString())
            ->whereBetween('appointment_datetime', [$startWindow, $endWindow])
            ->whereRaw('LOWER(status) = ?', ['approved'])
            ->exists();

        // If any of the selected students already have an approved appointment in that window (any counselor)
        $studentConflict = \App\Models\Appointments::whereDate('appointment_datetime', $requested->toDateString())
            ->whereBetween('appointment_datetime', [$startWindow, $endWindow])
            ->whereRaw('LOWER(status) = ?', ['approved'])
            ->whereHas('students', function($q) use ($request) {
                $q->whereIn('student_user_id', $request->student_id);
            })->exists();

        if ($globalConflict || $studentConflict) {
            return back()->withErrors(['appointment_datetime' => 'this time is already taken by someone'])->withInput();
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
}


