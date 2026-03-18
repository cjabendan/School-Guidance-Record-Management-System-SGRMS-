<?php
namespace App\Http\Controllers\Head;
use Illuminate\Support\Facades\Log;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

use App\Models\Student;
use App\Models\StudentSchoolYear;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HeadStudentController extends Controller
{
    public function partialTable()
    {
        $students = Student::paginate(10);
    return view('Head.partials.student_table', compact('students'));
    }

    public function index(Request $request)
    {
    $filter = $request->query('status', 'active');
    $search = $request->query('search');

        $educLevelMap = [
            'seniorhigh' => 'Senior High School',
            'juniorhigh' => 'Junior High School',
            'elementary' => 'Elementary',
            'kindergarten' => 'Kindergarten',
        ];


        $activeSchoolYearId = SchoolYear::where('is_active', 1)->value('id');
        $query = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('student_schoolyear', function($join) use ($activeSchoolYearId) {
                $join->on('students.s_id', '=', 'student_schoolyear.student_id')
                    ->where('student_schoolyear.school_year_id', '=', $activeSchoolYearId);
            })
            ->leftJoin('year_levels', function($join) {
                $join->on('student_schoolyear.year_level', '=', 'year_levels.year_level');
            })
            ->leftJoin('educ_levels', 'year_levels.e_id', '=', 'educ_levels.e_id')
            // Join case_students and cases to get severity
            ->leftJoin('case_students', 'students.s_id', '=', 'case_students.student_id')
            ->leftJoin('cases', function($join) {
                $join->on('case_students.case_id', '=', 'cases.case_id')
                    ->where('cases.archived', '=', 0);
            })
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
                'student_schoolyear.year_level',
                'student_schoolyear.status as enrollment_status',
                'users.profile_image',
                'students.father_name',
                'students.mother_name',
                'students.guardian_name',
                'students.relationship',
                'students.guardian_email',
                DB::raw('MAX(CASE WHEN cases.severity = "Severe" THEN 3 WHEN cases.severity = "Intermediate" THEN 2 WHEN cases.severity = "Low" THEN 1 ELSE 0 END) as severity_rank'),
                DB::raw('COUNT(cases.case_id) as case_count')
            );
    $query->groupBy(
            'students.s_id',
            'users.first_name',
            'users.middle_name',
            'users.last_name',
            'users.suffix',
            'users.email',
            'users.contact_num',
            'users.sex',
            'users.bod',
            'users.address',
            'educ_levels.educ_level',
            'student_schoolyear.year_level',
            'student_schoolyear.status',
            'users.profile_image',
            'students.father_name',
            'students.mother_name',
            'students.guardian_name',
            'students.relationship',
            'students.guardian_email'
        );

        // Apply filters using student_schoolyear.status
        if (array_key_exists($filter, $educLevelMap)) {
            $query->where('educ_levels.educ_level', $educLevelMap[$filter])
                ->where('student_schoolyear.status', 'Enrolled');
        }
    // End of index()

        // Add search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('students.s_id', 'like', "%$search%")
                  ->orWhere('users.first_name', 'like', "%$search%")
                  ->orWhere('users.last_name', 'like', "%$search%")
                  ->orWhere('student_schoolyear.status', 'like', "%$search%")
                  ;
            });
        }

    $students = $query->orderBy('users.last_name')->paginate(10);

        if ($request->ajax()) {
            return view('Head.profiling.students', compact('students', 'filter'))->render();
        }
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


    public function getStudentCases($s_id)
    {
        $cases = DB::table('case_students')
            ->join('cases', 'case_students.case_id', '=', 'cases.case_id')
            ->where('case_students.student_id', $s_id)
            ->where('cases.archived', 0)
            ->select(
                'cases.presenting_problem as case_title',
                'cases.severity',
                'cases.filed_date as date_reported',     
                'cases.status',
                'cases.description'
            )
            ->orderBy('cases.filed_date', 'desc')
            ->get();

        return response()->json($cases);
    }

    /**
     * Simple JSON search for student names for autocomplete.
     * Returns up to 5 results with id and full name.
     */
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $q = trim($q);
        if ($q === '') {
            return response()->json([]);
        }

        $students = Student::select('s_id', 'user_id')
            ->with('user')
            ->whereHas('user', function ($query) use ($q) {
                $query->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'like', "%{$q}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($s) {
                return [
                    's_id' => $s->s_id,
                    'name' => ($s->user->first_name ?? '') . ' ' . ($s->user->last_name ?? ''),
                ];
            });

        return response()->json($students);
    }

//_________________________________________________________________________________


    
    //Add Student
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
        if ($educLevel && isset($educLevel->e_id)) {
            $e_id = $educLevel->e_id;
        } else {
            $e_id = DB::table('educ_levels')->insertGetId([
                'educ_level' => $validated['educ_level'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert or get year_level
        $yearLevel = DB::table('year_levels')->where('year_level', $validated['year_level'])->where('e_id', $e_id)->first();
        $y_id = $yearLevel ? $yearLevel->y_id : DB::table('year_levels')->insertGetId([
            'year_level' => $validated['year_level'],
            'e_id' => $e_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Handle cropped image (if available) or uploaded file; preserve original filename when possible
        $imagePath = 'default.jpg';
        if ($request->filled('cropped_image_data')) {
            $imageData = $request->input('cropped_image_data');
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            // Use provided original name if available
            $origName = $request->input('cropped_image_name');
            if ($origName) {
                // sanitize and ensure .png extension
                $safeBase = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($origName, PATHINFO_FILENAME));
                $imageName = $safeBase . '.png';
                $target = public_path('images/user/' . $imageName);
                if (file_exists($target)) {
                    $imageName = $safeBase . '_' . time() . '.png';
                }
            } else {
                $imageName = 'student_' . time() . '.png';
            }
            Storage::disk('public')->put('images/user/' . $imageName, base64_decode($imageData));
            $imagePath = $imageName;
        } elseif ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            // Prefer original filename
            $orig = $image->getClientOriginalName();
            $safeBase = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($orig, PATHINFO_FILENAME));
            $ext = $image->getClientOriginalExtension() ?: 'jpg';
            $safeName = $safeBase . '.' . $ext;
            $target = public_path('images/user/' . $safeName);
            if (file_exists($target)) {
                // avoid overwriting: append timestamp
                $safeName = $safeBase . '_' . time() . '.' . $ext;
            }
            $image->move(public_path('images/user'), $safeName);
            $imagePath = $safeName;
        }


        // Set username and password
        $username = $validated['s_id'];

        // Extract last 4 digits from ID (after the dash)
        $idSuffix = preg_replace('/[^0-9]/', '', substr($validated['s_id'], -4));
        $lastName = ucfirst(strtolower($validated['last_name']));

        // Combine last name + last 4 digits
        $password = $lastName . $idSuffix;


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
            'father_name' => $validated['father_name'] ?? null,
            'mother_name' => $validated['mother_name'] ?? null,
            'guardian_name' => $validated['guardian_name'] ?? null,
            'relationship' => $validated['relationship'] ?? null,
            'guardian_contact' => $validated['guardian_contact'] ?? null,
            'guardian_email' => $validated['guardian_email'] ?? null,
            'religion' => $validated['religion'] ?? null,
            'civil_status' => $validated['civil_status'] ?? null,
        ]);

            // Ensure there is an active school year, or create one for the current year
            $currentYear = date('Y');
            $nextYear = $currentYear + 1;
            $yearLabel = $currentYear . '-' . $nextYear;
            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
            if (!$activeSchoolYear) {
                $activeSchoolYear = SchoolYear::create([
                    'year_label' => $yearLabel,
                    'is_active' => 1,
                    'start_date' => $currentYear . '-06-01',
                    'end_date' => $nextYear . '-03-31',
                ]);
            }
            StudentSchoolYear::create([
                'student_id' => $validated['s_id'],
                'school_year_id' => $activeSchoolYear->id,
                'year_level' => $validated['year_level'],
                'status' => 'Enrolled',
            ]);
    
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Student added successfully!']);
            }
            return redirect()->back()->with('success', 'Student added successfully!');
        }


    //_________________________________________________________________________________

    public function showAjax($s_id)
    {
        $student = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('student_schoolyear', 'students.s_id', '=', 'student_schoolyear.student_id')
            ->leftJoin('school_year', 'student_schoolyear.school_year_id', '=', 'school_year.id')
            ->leftJoin('year_levels', 'student_schoolyear.year_level', '=', 'year_levels.year_level')
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
                'student_schoolyear.year_level',
                'student_schoolyear.status as enrollment_status',
                'users.profile_image',
                'students.father_name',
                'students.mother_name',
                'students.guardian_name',
                'students.relationship',
                'students.guardian_contact',
                'students.guardian_email',
                'students.religion',
                'students.civil_status',
                'school_year.year_label as school_year_label',
                'educ_levels.educ_level as educ_level',
            )
            ->where('students.s_id', $s_id)
            ->where('student_schoolyear.school_year_id', SchoolYear::where('is_active', 1)->value('id'))
            ->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // Add image URL
        $student->image_url = asset('images/user/' . ($student->profile_image ?? 'default.jpg'));

        return response()->json($student);
    }


//_________________________________________________________________________________

    // Update student
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

        // Handle photo deletion
        if ($request->has('delete_photo') && $request->input('delete_photo') == '1') {
            $user->profile_image = 'default.jpg';
            $user->save();
        }

        // Update or get educ_level
        $educLevel = DB::table('educ_levels')->where('educ_level', $validated['educ_level'])->first();
        if ($educLevel && isset($educLevel->e_id)) {
            $e_id = $educLevel->e_id;
        } else {
            $e_id = DB::table('educ_levels')->insertGetId([
                'educ_level' => $validated['educ_level'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Update or get year_level
        $yearLevel = DB::table('year_levels')->where('year_level', $validated['year_level'])->where('e_id', $e_id)->first();
        $y_id = $yearLevel ? $yearLevel->y_id : DB::table('year_levels')->insertGetId([
            'year_level' => $validated['year_level'],
            'e_id' => $e_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Handle cropped image first (preserve original name if provided) or uploaded file
        if ($request->filled('cropped_image_data')) {
            $imageData = $request->input('cropped_image_data');
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $origName = $request->input('cropped_image_name');
            if ($origName) {
                $safeBase = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($origName, PATHINFO_FILENAME));
                $imageName = $safeBase . '.png';
                $target = public_path('images/user/' . $imageName);
                if (file_exists($target)) {
                    $imageName = $safeBase . '_' . time() . '.png';
                }
            } else {
                $imageName = 'student_' . time() . '.png';
            }
            Storage::disk('public')->put('images/user/' . $imageName, base64_decode($imageData));
            $user->profile_image = $imageName;
        } elseif ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $orig = $image->getClientOriginalName();
            $safeBase = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($orig, PATHINFO_FILENAME));
            $ext = $image->getClientOriginalExtension() ?: 'jpg';
            $safeName = $safeBase . '.' . $ext;
            $target = public_path('images/user/' . $safeName);
            if (file_exists($target)) {
                $safeName = $safeBase . '_' . time() . '.' . $ext;
            }
            $image->move(public_path('images/user'), $safeName);
            $user->profile_image = $safeName;
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
        $student->father_name = $validated['father_name'] ?? null;
        $student->mother_name = $validated['mother_name'] ?? null;
        $student->guardian_name = $validated['guardian_name'] ?? null;
        $student->relationship = $validated['relationship'] ?? null;
        $student->guardian_contact = $validated['guardian_contact'] ?? null;
        $student->guardian_email = $validated['guardian_email'] ?? null;
        $student->religion = $validated['religion'] ?? null;
        $student->civil_status = $validated['civil_status'] ?? null;
        $student->save();

        // Update student_schoolyear for current school year
        $activeSchoolYearId = \App\Models\SchoolYear::where('is_active', 1)->value('id');
        if ($activeSchoolYearId) {
            $updateData = [
                'year_level' => $validated['year_level'],
                // 'section' => $validated['section'] ?? null,
                'status' => $validated['status'] ?? 'Enrolled',
            ];

            \App\Models\StudentSchoolYear::where('student_id', $student->s_id)
                ->where('school_year_id', $activeSchoolYearId)
                ->update($updateData);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Student updated successfully!']);
        }
        return redirect()->back()->with('success', 'Student updated successfully!');

    }

//_____________________________________________________________________________

//Archive Student
    public function archive(Request $request)
    {
    Log::info('Archive request received', ['body' => $request->all()]);
    try {
        $validated = $request->validate([
            's_id' => 'required|string',
            'status' => 'required|string',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } else {
            throw $e;
        }
    }
    $s_id = $validated['s_id'];
    $status = $validated['status'];
    Log::info('Archive validated', ['s_id' => $s_id, 'status' => $status]);
    // Archive student only (do not disable user)
    $activeSchoolYearId = SchoolYear::where('is_active', 1)->value('id');
    Log::info('Active school year ID', ['activeSchoolYearId' => $activeSchoolYearId]);
    if (!$activeSchoolYearId) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['error' => 'No active school year.'], 400);
        } else {
            return redirect()->back()->with('error', 'No active school year.');
        }
    }
    $ssy = StudentSchoolYear::where('student_id', $s_id)
        ->where('school_year_id', $activeSchoolYearId)
        ->first();
    Log::info('StudentSchoolYear found', ['ssy' => $ssy]);
    if ($ssy) {
        $ssy->status = $status;
        $ssy->save();
    }
    if ($request->ajax() || $request->wantsJson()) {
        // Return the new status in the response for frontend update
        return response()->json([
            'success' => true,
            'message' => 'Student status updated to ' . $status . '.',
            'status' => $status,
            's_id' => $s_id
        ]);
    } else {
        return redirect()->back()->with('success', 'Student status updated to ' . $status . '.');
    }
    }

    public function archiveAndDisableStudent(Request $request)
    {
    Log::info('Archive & Disable request received', ['body' => $request->all()]);
    try {
        $validated = $request->validate([
            's_id' => 'required|string',
            'status' => 'required|string',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } else {
            throw $e;
        }
    }
    $s_id = $validated['s_id'];
    $status = $validated['status'];
    Log::info('Archive & Disable validated', ['s_id' => $s_id, 'status' => $status]);
    $activeSchoolYearId = \App\Models\SchoolYear::where('is_active', 1)->value('id');
    Log::info('Active school year ID', ['activeSchoolYearId' => $activeSchoolYearId]);
    if (!$activeSchoolYearId) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['error' => 'No active school year.'], 400);
        } else {
            return redirect()->back()->with('error', 'No active school year.');
        }
    }
    $ssy = \App\Models\StudentSchoolYear::where('student_id', $s_id)
        ->where('school_year_id', $activeSchoolYearId)
        ->first();
    Log::info('StudentSchoolYear found', ['ssy' => $ssy]);
    if ($ssy) {
        $ssy->status = $status;
        $ssy->save();
    }
    // Also disable the user account
    $student = \App\Models\Student::where('s_id', $s_id)->first();
    if ($student && $student->user) {
        $student->user->status = 'inactive';
        $student->user->save();
    }
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json(['success' => true, 'message' => 'Student status updated to ' . $status . ' and account disabled.']);
    } else {
        return redirect()->back()->with('success', 'Student status updated to ' . $status . ' and account disabled.');
    }
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
            $importer = new \App\Imports\StudentsImport;
            Excel::import($importer, $file);
            if (!empty($importer->errors)) {
                $errorMessages = collect($importer->errors)->map(function($err) {
                    $rowInfo = isset($err['row']['s_id']) ? $err['row']['s_id'] : json_encode($err['row']);
                    return 'Student ID: ' . $rowInfo . ' - Error: ' . $err['error'];
                })->toArray();
                return redirect()->back()->with(['import_errors' => $errorMessages]);
            }
            return redirect()->back()->with('success', 'Students imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = collect($failures)->map(function($failure) {
                return 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            })->toArray();
            return redirect()->back()->with(['import_errors' => $messages]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['import_errors' => ['Import failed: ' . $e->getMessage()]]);
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
        $activeSchoolYearId = SchoolYear::where('is_active', 1)->value('id');
        $query = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('student_schoolyear', function($join) use ($activeSchoolYearId) {
                $join->on('students.s_id', '=', 'student_schoolyear.student_id')
                    ->where('student_schoolyear.school_year_id', '=', $activeSchoolYearId);
            })
            ->leftJoin('year_levels', function($join) {
                $join->on('student_schoolyear.year_level', '=', 'year_levels.year_level');
            })
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
                'student_schoolyear.year_level',
                'student_schoolyear.status as enrollment_status',
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
                ->where('student_schoolyear.status', 'Enrolled');
        } elseif ($filter === 'inactive') {
            $query->where('student_schoolyear.status', '!=', 'Enrolled');
        } else {
            $query->where('student_schoolyear.status', 'Enrolled');
        }
        $students = $query->orderBy('users.last_name')->get();

        $format = $request->query('format', 'csv');
        // Import-ready column names
        $importColumns = [
            's_id', 'first_name', 'middle_name', 'last_name', 'suffix',
            'email', 'contact_num', 'sex', 'bod', 'address',
            'educ_level', 'year_level', 'status', 'profile_image',
            'father_name', 'mother_name', 'guardian_name', 'relationship',
            'guardian_contact', 'guardian_email'
        ];

        // Filename
        $filterValue = strtolower($filter);
        $filenameBase = match ($filterValue) {
            'elementary'   => 'Elementary_List',
            'juniorhigh'   => 'Junior High List',
            'seniorhigh'   => 'Senior High List',
            'kindergarten' => 'Kindergarten List',
            'inactive'     => 'Inactive List',
            default        => 'Student_List'
        };

        // Prepare data
        $exportData = [];
        foreach ($students as $student) {
            $row = [];
            foreach ($importColumns as $col) {
                if ($col === 'first_name') {
                    $row[$col] = $student->fname ?? '';
                } elseif ($col === 'middle_name') {
                    $row[$col] = $student->mname ?? '';
                } elseif ($col === 'last_name') {
                    $row[$col] = $student->lname ?? '';
                } elseif ($col === 'status') {
                    $row[$col] = $student->enrollment_status ?? '';
                } else {
                    $row[$col] = $student->$col ?? '';
                }
            }
            $exportData[] = $row;
        }

        // Export logic
        if ($format === 'pdf') {
            $columns = [
                'Student ID', 'Full Name', 'Year Level', 'Gender', 'Date of Birth',
                'Contact No.', 'Email', 'Address', 'Father', 'Mother', 'Guardian Info',
            ];
            $html = view('Head.profiling.export_pdf', compact('students', 'columns', 'filter'))->render();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'landscape');
            $filename = $filenameBase . '.pdf';
            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } elseif ($format === 'xlsx' || $format === 'xls') {
            // Title + Subtitle + Date + Blank + Header + Data
            $schoolTitle = ['Montessori Academy of Southern Cebu, Inc.'];
            $subtitle = ['Student List'];
            $dateRow = ['Date Generated: ' . now()->format('F d, Y h:i A')];
            $exportDataWithTitle = [];
            $colCount = count($importColumns);

            $exportDataWithTitle[] = array_merge([$schoolTitle[0]], array_fill(1, $colCount - 1, ''));
            $exportDataWithTitle[] = array_merge([$subtitle[0]], array_fill(1, $colCount - 1, ''));
            $exportDataWithTitle[] = array_merge([$dateRow[0]], array_fill(1, $colCount - 1, ''));
            $exportDataWithTitle[] = array_fill(0, $colCount, ''); // Blank row
            $exportDataWithTitle[] = $importColumns; // header row
            foreach ($exportData as $row) {
                $exportDataWithTitle[] = array_values($row);
            }

            $filename = $filenameBase . '.' . $format;
            $excelFormat = $format === 'xlsx'
                ? \Maatwebsite\Excel\Excel::XLSX
                : \Maatwebsite\Excel\Excel::XLS;

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\StudentsExport($exportDataWithTitle, $importColumns),
                $filename,
                $excelFormat
            );
        } elseif ($format === 'csv') {
            $schoolTitle = ['Montessori Academy of Southern Cebu, Inc.'];
            $subtitle = ['Student List'];
            $dateRow = ['Date Generated: ' . now()->format('F d, Y h:i A')];
            $filename = $filenameBase . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\""
            ];
            $callback = function() use ($exportData, $importColumns, $schoolTitle, $subtitle, $dateRow) {
                $file = fopen('php://output', 'w');
                $colCount = count($importColumns);
                fputcsv($file, array_merge([$schoolTitle[0]], array_fill(1, $colCount-1, '')));
                fputcsv($file, array_merge([$subtitle[0]], array_fill(1, $colCount-1, '')));
                fputcsv($file, array_merge([$dateRow[0]], array_fill(1, $colCount-1, '')));
                fputcsv($file, array_fill(0, $colCount, ''));
                fputcsv($file, $importColumns); 
                foreach ($exportData as $row) {
                    fputcsv($file, array_values($row));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        } else {
            return response()->json(['error' => 'Invalid export format'], 400);
        }
    }
}
