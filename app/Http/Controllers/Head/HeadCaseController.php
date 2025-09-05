<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\CaseModel;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

class HeadCaseController extends Controller
{
    private function getStatusEnumValues()
    {
        $type = DB::select("SHOW COLUMNS FROM cases WHERE Field = 'status'")[0]->Type;
        preg_match('/enum\((.*)\)/', $type, $matches);
        $enum = [];
        foreach (explode(',', $matches[1]) as $value) {
            $enum[] = trim($value, "'");
        }
        return $enum;
    }
    private function getSeverityEnumValues()
    {
        $type = DB::select("SHOW COLUMNS FROM cases WHERE Field = 'severity'")[0]->Type;
        preg_match('/enum\((.*)\)/', $type, $matches);
        $enum = [];
        foreach (explode(',', $matches[1]) as $value) {
            $enum[] = trim($value, "'");
        }
        return $enum;
    }

    public function index(Request $request)
    {
        $query = CaseModel::with('caseType')->orderBy('case_id', 'desc');

        // Filter by archived status
        if ($request->filled('archived') && $request->archived == '1') {
            $query->where('archived', true);
        } else {
            $query->where('archived', false);
        }

        // Filter by case type
        if ($request->filled('filter_type')) {
            $query->where('case_type_id', $request->filter_type);
        }

        // Filter by status
        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        // Filter by severity
        if ($request->filled('filter_severity')) {
            $query->where('severity', $request->filter_severity);
        }

        // Search by case ID or case type
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('case_id', 'LIKE', "%$search%")
                ->orWhereHas('caseType', function($q2) use ($search) {
                    $q2->where('type_name', 'LIKE', "%$search%");
                });
            });
        }

        $cases = $query->get();
        $statusOptions = $this->getStatusEnumValues();
        $severityOptions = $this->getSeverityEnumValues();
        return view('Head.case', compact('cases', 'statusOptions', 'severityOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'case_type_id' => 'required',
            'presenting_problem' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|string',
            'filed_date' => 'required|date',
            'filed_time' => 'required',
            'status' => 'required|string',
            'involved_students' => 'required|string', // comma-separated student IDs
        ]);

        // Handle "Other" case type
        if ($request->case_type_id === 'other') {
            $newType = \App\Models\CaseType::create([
                'type_name' => $request->other_case_type,
                'description' => '',
            ]);
            $case_type_id = $newType->type_id;
        } else {
            $case_type_id = $request->case_type_id;
        }

        // Create the case
        $case = CaseModel::create([
            'case_type_id' => $case_type_id,
            'presenting_problem' => $request->presenting_problem,
            'description' => $request->description,
            'severity' => $request->severity,
            'witnesses' => $request->witnesses,
            'investigation_notes' => $request->investigation_notes,
            'evidence' => $request->evidence,
            'filed_date' => $request->filed_date,
            'filed_time' => $request->filed_time,
            'status' => $request->status,
            'action_taken' => $request->action_taken,
            'resolution_notes' => $request->resolution_notes,
            'resolved_date' => $request->resolved_date,
            'follow_up_date' => $request->follow_up_date,
        ]);

        // Link involved students
        $studentIds = array_map('trim', explode(',', $request->involved_students));
        foreach ($studentIds as $studentId) {
            if ($studentId) {
                DB::table('case_students')->insert([
                    'case_id' => $case->case_id,
                    'student_id' => $studentId,
                ]);
            }
        }

        return redirect()->route('Head.cases.index')->with('success', 'Case added successfully!');
    }

    public function update(Request $request, $case_id)
    {
        $request->validate([
            'case_type_id' => 'required',
            'presenting_problem' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|string',
            'filed_date' => 'required|date',
            'filed_time' => 'required',
            'status' => 'required|string',
        ]);

        // Handle "Other" case type
        if ($request->case_type_id === 'other') {
            $newType = \App\Models\CaseType::create([
                'type_name' => $request->other_case_type,
                'description' => '',
            ]);
            $case_type_id = $newType->type_id;
        } else {
            $case_type_id = $request->case_type_id;
        }

        $case = CaseModel::findOrFail($case_id);

        $case->update([
            'case_type_id' => $case_type_id,
            'presenting_problem' => $request->presenting_problem,
            'description' => $request->description,
            'severity' => $request->severity,
            'witnesses' => $request->witnesses,
            'investigation_notes' => $request->investigation_notes,
            'evidence' => $request->evidence,
            'filed_date' => $request->filed_date,
            'filed_time' => $request->filed_time,
            'status' => $request->status,
            'action_taken' => $request->action_taken,
            'resolution_notes' => $request->resolution_notes,
            'resolved_date' => $request->resolved_date,
            'follow_up_date' => $request->follow_up_date,
        ]);

        return redirect()->route('Head.cases.index')->with('success', 'Case updated successfully!');
    }

    public function archive($case_id)
    {
        $case = CaseModel::findOrFail($case_id);
        $case->archived = true;
        $case->save();

        return redirect()->route('Head.cases.index')->with('success', 'Case archived successfully!');
    }

    public function searchStudent(Request $request)
    {
        $query = $request->input('q');

        $students = \App\Models\Student::with('user')
            ->where('s_id', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        $results = $students->map(function ($student) {
            $fullName = trim(($student->user->first_name ?? '') . ' ' . ($student->user->last_name ?? ''));
            return [
                'id' => (string) $student->s_id, // Use s_id as string
                'text' => "{$fullName} | {$student->s_id}", // Show name and s_id
            ];
        });

        return response()->json($results);
    }
}
