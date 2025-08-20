<?php

namespace App\Http\Controllers\Head;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function getNextStudentId()
    {
        $lastStudent = Student::orderByDesc('s_id')->first();

        if ($lastStudent && preg_match('/MA(\d{2})-(\d{4})$/', $lastStudent->id_num, $matches)) {
            $lastNumber = intval($matches[2]);
        } else {
            $lastNumber = 0;
        }

        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $currentYear = date('y');
        $fullId = "MA{$currentYear}-{$nextNumber}";

        return response()->json(['next_id' => $fullId]);
    }

    public function addStudent(Request $request)
    {
        $validated = $request->validate([
            'id_num' => 'required|unique:students,id_num',
            'lname' => 'required|string|max:50',
            'fname' => 'required|string|max:50',
            'mname' => 'nullable|string|max:50',
            'suffix' => 'nullable|string|max:10',
            'bod' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string|max:100',
            'mobile_num' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'educ_level' => 'required|string|max:50',
            'year_level' => 'required|string|max:20',
            'section' => 'nullable|string|max:20',
            'program' => 'nullable|string|max:100',
            'previous_school' => 'nullable|string|max:255',
            'previous_school_address' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:100',
            'civil_status' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid('stud_') . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/stud.img'), $imageName);
            $imagePath = 'images/stud.img/' . $imageName;
        } else {
            // Use default image if none uploaded
            $imagePath = 'images/stud.img/circle-user.png';
        }

        Student::create([
            'id_num' => $validated['id_num'],
            'lname' => $validated['lname'],
            'fname' => $validated['fname'],
            'mname' => $validated['mname'] ?? null,
            'suffix' => $validated['suffix'] ?? null,
            'bod' => $validated['bod'],
            'sex' => $validated['gender'],
            'address' => $validated['address'],
            'mobile_num' => $validated['mobile_num'] ?? null,
            'email' => $validated['email'] ?? null,
            'educ_level' => $validated['educ_level'],
            'year_level' => $validated['year_level'],
            'section' => $validated['section'] ?? null,
            'program' => $validated['program'] ?? null,
            'previous_school' => $validated['previous_school'] ?? null,
            'previous_school_address' => $validated['previous_school_address'] ?? null,
            'religion' => $validated['religion'] ?? null,
            'civil_status' => $validated['civil_status'] ?? null,
            's_image' => $imagePath,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Student added successfully!');
    }

    public function showAjax($id_num)
    {
        $student = Student::where('id_num', $id_num)->firstOrFail();
        $birthdate = new \DateTime($student->bod);
        $today = new \DateTime();
        $student->age = $today->diff($birthdate)->y;
        return response()->json($student);
    }

    public function editStudent(Request $request, $id_num)
    {
        $student = Student::where('id_num', $id_num)->firstOrFail();

        $validated = $request->validate([
            'lname' => 'required|string|max:50',
            'fname' => 'required|string|max:50',
            'mname' => 'nullable|string|max:50',
            'suffix' => 'nullable|string|max:10',
            'bod' => 'required|date',
            'gender' => 'required|in:Male,Female',
            // Add other fields as needed
        ]);

        $student->update([
            'lname' => $validated['lname'],
            'fname' => $validated['fname'],
            'mname' => $validated['mname'] ?? null,
            'suffix' => $validated['suffix'] ?? null,
            'bod' => $validated['bod'],
            'sex' => $validated['gender'],
            // Add other fields as needed
        ]);

        return redirect()->back()->with('success', 'Student updated successfully!');
    }
}


