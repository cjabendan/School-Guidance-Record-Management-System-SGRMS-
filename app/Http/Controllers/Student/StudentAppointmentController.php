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
use Illuminate\Support\Facades\Schema;

class StudentAppointmentController extends Controller
{
	public function index(Request $request)
	{
		$userId = auth()->id();

		$relations = ['students.user', 'counselor', 'requester', 'type'];
		if (Schema::hasTable('appointment_reschedules')) {
			$relations[] = 'reschedules';
		}

		$query = Appointments::with($relations)
			->whereHas('students', function ($q) use ($userId) {
				$q->where('user_id', $userId);
			});

		if ($request->has('status') && $request->status != 'all') {
			$status = $request->status;
			// When filtering for pending, include Rescheduled since those are awaiting approval
			if (strtolower($status) === 'pending') {
				$query->whereIn('status', ['Pending', 'Rescheduled']);
			} else {
				// Case-insensitive match for other statuses
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

		// Conflict checks: prevent booking if there is an already 'approved' appointment
		$requested = Carbon::parse($request->appointment_datetime);
		$startWindow = $requested->copy()->subHour();
		$endWindow = $requested->copy()->addHour();

		// Check for approved appointments for the same counselor in the 1-hour window on the same day
		$globalConflict = Appointments::where('counselor_id', $request->counselor_id)
			->whereDate('appointment_datetime', $requested->toDateString())
			->whereBetween('appointment_datetime', [$startWindow, $endWindow])
			->whereRaw('LOWER(status) = ?', ['approved'])
			->exists();

		// Check if the student already has an approved appointment in that window (any counselor)
		$studentConflict = Appointments::whereDate('appointment_datetime', $requested->toDateString())
			->whereBetween('appointment_datetime', [$startWindow, $endWindow])
			->whereRaw('LOWER(status) = ?', ['approved'])
			->whereHas('students', function ($q) {
				$q->where('user_id', auth()->id());
			})->exists();

		if ($globalConflict || $studentConflict) {
			return back()->withErrors(['appointment_datetime' => 'this time is already taken by someone'])->withInput();
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

