<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointments;
use App\Models\AppointmentType;
use App\Models\Student;
use App\Models\AppointmentStudent;
use Carbon\Carbon;

class StudentAppointmentController extends Controller
{
	public function index(Request $request)
	{
		$userId = auth()->id();

		$query = Appointments::with(['students.user', 'counselor', 'requester', 'type'])
			->whereHas('students', function ($q) use ($userId) {
				$q->where('user_id', $userId);
			});

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

		// Fetch counselors and types for modal
		$counselors = \App\Models\User::whereIn('role', ['Counselor', 'Head', 'admin'])->get();
		$types = AppointmentType::all();

		return view('Student.appointments', compact('appointments', 'counselors', 'types'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'counselor_id' => 'required|exists:users,id',
			'type_id' => 'required',
			'reason' => 'required|string',
			'appointment_datetime' => 'required|date',
		]);

		// Handle 'other' appointment type
		if ($request->type_id === 'other') {
			$request->validate(['other_type' => 'required|string|max:255']);
			$typeName = trim($request->other_type);
			$type = AppointmentType::whereRaw('LOWER(type_name) = ?', [strtolower($typeName)])->first();
			if (!$type) {
				$type = AppointmentType::create(['type_name' => $typeName]);
			}
			$type_id = $type->id;
		} else {
			$type_id = $request->type_id === 'general' ? null : $request->type_id;
		}

		// Conflict checks
		$globalConflict = Appointments::where('appointment_datetime', $request->appointment_datetime)
			->whereIn('status', ['Pending', 'Approved'])
			->exists();

		$studentConflict = Appointments::where('appointment_datetime', $request->appointment_datetime)
			->whereIn('status', ['Pending', 'Approved'])
			->whereHas('students', function ($q) {
				$q->where('user_id', auth()->id());
			})->exists();

		if ($globalConflict || $studentConflict) {
			return back()->withErrors(['appointment_datetime' => 'Appointment cannot be booked. A schedule already exists'])->withInput();
		}

		$appointment = Appointments::create([
			'counselor_id' => $request->counselor_id,
			'appointment_type_id' => $type_id,
			'reason' => $request->reason,
			'appointment_datetime' => Carbon::parse($request->appointment_datetime),
			'requester_id' => auth()->id(),
			'status' => 'Pending',
		]);

		// Attach current student using the student's s_id (students.s_id) so it matches other flows
		$student = Student::where('user_id', auth()->id())->first();
		if ($student) {
			try {
				AppointmentStudent::create([
					'appointment_id' => $appointment->appointment_id,
					'student_user_id' => $student->s_id,
				]);
			} catch (\Exception $e) {
				// fallback to attach via relation if pivot model creation fails for any reason
				if (method_exists($appointment, 'students')) {
					$appointment->students()->attach($student->s_id);
				}
			}
		} else {
			// As a last resort, attach by user id (not ideal) so request still records
			try {
				AppointmentStudent::create([
					'appointment_id' => $appointment->appointment_id,
					'student_user_id' => auth()->id(),
				]);
			} catch (\Exception $e) {
				if (method_exists($appointment, 'students')) {
					$appointment->students()->attach(auth()->id());
				}
			}
		}

		return redirect()->route('Student.appointments.index')->with('success', 'Appointment requested successfully!');
	}
}

