<?php

namespace App\Http\Controllers\Head;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HeadStudentController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'students_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new \App\Imports\StudentsImport, $request->file('students_file'));
            return redirect()->back()->with('success', 'Students imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $filter = $request->query('status', 'active');

        $educLevelMap = [
            'college' => 'College',
            'highschool' => 'High School',
            'elementary' => 'Elementary'
        ];

        $query = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('year_levels', 'students.y_id', '=', 'year_levels.y_id')
            ->leftJoin('educ_levels', 'year_levels.e_id', '=', 'educ_levels.e_id')
            ->select(
                'students.s_id',
                'users.first_name as fname',
                'users.middle_name as mname',
                'users.last_name as lname',
                'users.suffix',
                'users.email',
                'users.contact_num',
                'users.sex',
                'users.bod',
                'users.address',
                'educ_levels.educ_level',
                'year_levels.year_level',
                'students.section',
                'students.program',
                'students.status',
                'users.profile_image'
            )
            ->selectRaw('(SELECT COUNT(*) FROM cases ) AS case_count');

        // Apply filters
        if (array_key_exists($filter, $educLevelMap)) {
            $query->where('educ_levels.educ_level', $educLevelMap[$filter])
                ->where('students.status', 'active');
        } elseif (in_array($filter, ['inactive', 'archived'])) {
            $query->where('students.status', $filter);
        } else {
            $query->where('students.status', 'active');
        }

        // Order by last name from users table
        $students = $query->orderBy('users.last_name')->get();

        return view('Head.profiling.students', compact('students', 'filter'));
    }



    public function getNextStudentId()
    {
        $currentYear = date('y');
        // Use direct SQL to get the max number for current year
        $maxId = Student::where('s_id', 'LIKE', "MA{$currentYear}-%")
            ->selectRaw("MAX(CAST(SUBSTRING(s_id, 7, 4) AS UNSIGNED)) as max_num")
            ->value('max_num');
        $nextNumber = ($maxId ? intval($maxId) : 0) + 1;
        $fullId = "MA{$currentYear}-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        return response()->json(['next_id' => $fullId]);
    }

    public function addStudent(Request $request)
    {
        $validated = $request->validate([
            's_id' => 'required|unique:students,s_id',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'contact_num' => 'nullable|string|max:20',
            'sex' => 'required|in:Male,Female',
            'bod' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'educ_level' => 'required|string|max:50',
            'year_level' => 'required|string|max:50',
            'section' => 'nullable|string|max:20',
            'program' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:100',
            'civil_status' => 'nullable|string|max:50',
        ]);

        // Insert or get educ_level
        $educLevel = DB::table('educ_levels')->where('educ_level', $validated['educ_level'])->first();
        if (!$educLevel) {
            $e_id = DB::table('educ_levels')->insertGetId([
                'educ_level' => $validated['educ_level'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $e_id = $educLevel->e_id;
        }

        // Insert or get year_level
        $yearLevel = DB::table('year_levels')->where('year_level', $validated['year_level'])->where('e_id', $e_id)->first();
        if (!$yearLevel) {
            $y_id = DB::table('year_levels')->insertGetId([
                'year_level' => $validated['year_level'],
                'e_id' => $e_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $y_id = $yearLevel->y_id;
        }

        // Handle image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = uniqid('stud_') . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/stud.img'), $imageName);
            $imagePath = 'images/stud.img/' . $imageName;
        } else {
            $imagePath = 'images/stud.img/default.png';
        }

        // Set username and password
        $username = $validated['s_id'];
        $password = ucfirst(strtolower($validated['last_name']));

        $user = \App\Models\User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'contact_num' => $validated['contact_num'] ?? null,
            'bod' => $validated['bod'] ?? null,
            'address' => $validated['address'] ?? null,
            'profile_image' => $imagePath,
            'password' => bcrypt($password),
            'role' => 'student',
            'status' => $validated['status'] ?? 'active',
        ]);

        $student = Student::create([
            's_id' => $validated['s_id'],
            'user_id' => $user->id,
            'y_id' => $y_id,
            'section' => $validated['section'] ?? null,
            'program' => $validated['program'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'religion' => $validated['religion'] ?? null,
            'civil_status' => $validated['civil_status'] ?? null,
        ]);

        // return redirect with success message
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
           
        ]);

        $student->update([
            'lname' => $validated['lname'],
            'fname' => $validated['fname'],
            'mname' => $validated['mname'] ?? null,
            'suffix' => $validated['suffix'] ?? null,
            'bod' => $validated['bod'],
            'sex' => $validated['gender'],
            
        ]);

        return redirect()->back()->with('success', 'Student updated successfully!');
    }
}


