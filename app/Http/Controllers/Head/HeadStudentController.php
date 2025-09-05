<?php

namespace App\Http\Controllers\Head;

use Illuminate\Support\Facades\Log;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HeadStudentController extends Controller
{

    public function index(Request $request)
    {
        $filter = $request->query('status', 'active');

        $educLevelMap = [
            'seniorhigh' => 'Senior High School',
            'juniorhigh' => 'Junior High School',
            'elementary' => 'Elementary',
            'kindergarten' => 'Kindergarten',
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
                'users.profile_image',
                'students.father_name',
                'students.mother_name',
                'students.guardian_name',
                'students.relationship',
                'students.guardian_contact',
                'students.guardian_email'
            )
            ->selectRaw('(SELECT COUNT(*) FROM cases ) AS case_count');

        // Apply filters
        if (array_key_exists($filter, $educLevelMap)) {
            $query->where('educ_levels.educ_level', $educLevelMap[$filter])
                ->where('students.status', 'active');
        } elseif ($filter === 'inactive') {
            $query->where('students.status', 'inactive');
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
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'relationship' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:255',
            'guardian_email' => 'nullable|string|max:255',
        ]);

        // Insert or get educ_level
        $educLevel = DB::table('educ_levels')->where('educ_level', $validated['educ_level'])->first();
        $e_id = $educLevel ? $educLevel->e_id : DB::table('educ_levels')->insertGetId([
            'educ_level' => $validated['educ_level'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert or get year_level
        $yearLevel = DB::table('year_levels')->where('year_level', $validated['year_level'])->where('e_id', $e_id)->first();
        $y_id = $yearLevel ? $yearLevel->y_id : DB::table('year_levels')->insertGetId([
            'year_level' => $validated['year_level'],
            'e_id' => $e_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Handle image upload 
        $imagePath = 'default.jpg';
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $originalName = $image->getClientOriginalName();
            $image->move(public_path('images/user'), $originalName);
            $imagePath = $originalName;
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
            'sex' => $validated['sex'],
            'bod' => $validated['bod'] ?? null,
            'address' => $validated['address'] ?? null,
            'profile_image' => $imagePath,
            'password' => bcrypt($password),
            'role' => 'student',
            'status' => $validated['status'] ?? 'active',
        ]);

        Student::create([
            's_id' => $validated['s_id'],
            'user_id' => $user->id,
            'y_id' => $y_id,
            'section' => $validated['section'] ?? null,
            'father_name' => $validated['father_name'] ?? null,
            'mother_name' => $validated['mother_name'] ?? null,
            'guardian_name' => $validated['guardian_name'] ?? null,
            'relationship' => $validated['relationship'] ?? null,
            'guardian_contact' => $validated['guardian_contact'] ?? null,
            'guardian_email' => $validated['guardian_email'] ?? null,
            'program' => $validated['program'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'religion' => $validated['religion'] ?? null,
            'civil_status' => $validated['civil_status'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Student added successfully!');
    }

    //_________________________________________________________________________________


    public function showAjax($s_id)
    {
        $student = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('year_levels', 'students.y_id', '=', 'year_levels.y_id')
            ->leftJoin('educ_levels', 'year_levels.e_id', '=', 'educ_levels.e_id')
            ->select(
                'students.s_id as id_num',
                'users.first_name as fname',
                'users.middle_name as mname',
                'users.last_name as lname',
                'users.suffix',
                'users.email',
                'users.contact_num as mobile_num',
                'users.sex as gender',
                'users.bod',
                'users.address',
                'educ_levels.educ_level',
                'year_levels.year_level',
                'students.section',
                'students.program',
                'students.status',
                'users.profile_image',
                'students.father_name',
                'students.mother_name',
                'students.guardian_name',
                'students.relationship',
                'students.guardian_contact',
                'students.guardian_email',
                'students.religion',
                'students.civil_status'
            )
            ->where('students.s_id', $s_id)
            ->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // Add image URL
        $student->image_url = asset('images/user/' . ($student->profile_image ?? 'default.jpg'));

        return response()->json($student);
    }

        // Update student info (edit modal)
    public function editStudent(Request $request, $s_id)
    {
        $validated = $request->validate([
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
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'relationship' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:255',
            'guardian_email' => 'nullable|string|max:255',
        ]);

        // Find student and user
        $student = \App\Models\Student::where('s_id', $s_id)->firstOrFail();
        $user = $student->user;

        // Update or get educ_level
        $educLevel = DB::table('educ_levels')->where('educ_level', $validated['educ_level'])->first();
        $e_id = $educLevel ? $educLevel->e_id : DB::table('educ_levels')->insertGetId([
            'educ_level' => $validated['educ_level'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Update or get year_level
        $yearLevel = DB::table('year_levels')->where('year_level', $validated['year_level'])->where('e_id', $e_id)->first();
        $y_id = $yearLevel ? $yearLevel->y_id : DB::table('year_levels')->insertGetId([
            'year_level' => $validated['year_level'],
            'e_id' => $e_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Handle image upload (save with original filename)
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $originalName = $image->getClientOriginalName();
            $image->move(public_path('images/user'), $originalName);
            $user->profile_image = $originalName;
        }

        // Update user
        $user->first_name = $validated['first_name'];
        $user->middle_name = $validated['middle_name'] ?? null;
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->contact_num = $validated['contact_num'] ?? null;
        $user->sex = $validated['sex'];
        $user->bod = $validated['bod'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->status = $validated['status'] ?? 'active';
        $user->save();

        // Update student
        $student->y_id = $y_id;
        $student->section = $validated['section'] ?? null;
        $student->father_name = $validated['father_name'] ?? null;
        $student->mother_name = $validated['mother_name'] ?? null;
        $student->guardian_name = $validated['guardian_name'] ?? null;
        $student->relationship = $validated['relationship'] ?? null;
        $student->guardian_contact = $validated['guardian_contact'] ?? null;
        $student->guardian_email = $validated['guardian_email'] ?? null;
        $student->program = $validated['program'] ?? null;
        $student->status = $validated['status'] ?? 'active';
        $student->religion = $validated['religion'] ?? null;
        $student->civil_status = $validated['civil_status'] ?? null;
        $student->save();

        return redirect()->back()->with('success', 'Student updated successfully!');
    }

//_____________________________________________________________________________

    // Archive student 
    public function archiveStudent(Request $request)
    {
        $s_id = $request->input('s_id');
        $student = Student::where('s_id', $s_id)->first();
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        $student->status = 'inactive';
        $student->save();
        return response()->json(['success' => true, 'message' => 'Student archived (inactive)']);
    }

    // Archive and disable student 
    public function archiveAndDisableStudent(Request $request)
    {
        $s_id = $request->input('s_id');
        $student = Student::where('s_id', $s_id)->first();
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        $student->status = 'inactive';
        $student->save();
        if ($student->user_id) {
            $user = \App\Models\User::find($student->user_id);
            if ($user) {
                $user->status = 'inactive';
                $user->save();
            }
        }
        return response()->json(['success' => true, 'message' => 'Student and user account archived (inactive)']);
    }


//_____________________________________________________________________________

    // Import Students
    public function import(Request $request)
    {
        $validated = $request->validate([
            'students_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('students_file');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'No valid file uploaded.');
        }

        try {
            Excel::import(new \App\Imports\StudentsImport, $file);
            return redirect()->back()->with('success', 'Students imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = collect($failures)->map(function($failure) {
                return 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            })->implode(' | ');
            return redirect()->back()->with('error', 'Import failed: ' . $messages);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

//_____________________________________________________________________________

    // Export students as PDF, CSV, or Excel
    public function export(Request $request)
    {
        $filter = $request->query('status', 'active');
        $educLevelMap = [
            'seniorhigh' => 'Senior High School',
            'juniorhigh' => 'Junior High School',
            'elementary' => 'Elementary',
            'kindergarten' => 'Kindergarten',
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
                'users.profile_image',
                'students.father_name',
                'students.mother_name',
                'students.guardian_name',
                'students.relationship',
                'students.guardian_contact',
                'students.guardian_email'
            );
        if (array_key_exists($filter, $educLevelMap)) {
            $query->where('educ_levels.educ_level', $educLevelMap[$filter])
                ->where('students.status', 'active');
        } elseif ($filter === 'inactive') {
            $query->where('students.status', 'inactive');
        } else {
            $query->where('students.status', 'active');
        }
        $students = $query->orderBy('users.last_name')->get();

        $format = $request->query('format', 'csv');
        $columns = [
            's_id', 'fname', 'mname', 'lname', 'suffix', 'email', 'contact_num', 'sex', 'bod', 'address',
            'educ_level', 'year_level', 'section', 'program', 'status', 'profile_image',
            'father_name', 'mother_name', 'guardian_name', 'relationship', 'guardian_contact', 'guardian_email'
        ];

        if ($format === 'pdf') {
            $html = view('Head.profiling.export_pdf', compact('students', 'columns'))->render();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'landscape');
            $filename = 'students_export_' . $filter . '_' . date('Ymd_His') . '.pdf';
            return $pdf->download($filename);
        } elseif ($format === 'excel') {
            // Excel export using Laravel Excel
            $exportData = [];
            foreach ($students as $student) {
                $row = [];
                foreach ($columns as $col) {
                    $row[$col] = $student->$col ?? '';
                }
                $exportData[] = $row;
            }
            $filename = 'students_export_' . $filter . '_' . date('Ymd_His') . '.xlsx';
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentsExport($exportData, $columns), $filename);
        } else {
            // Default: CSV
            $filename = 'students_export_' . $filter . '_' . date('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\""
            ];
            $callback = function() use ($students, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($students as $student) {
                    $row = [];
                    foreach ($columns as $col) {
                        $row[] = $student->$col ?? '';
                    }
                    fputcsv($file, $row);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
    }
}
   


