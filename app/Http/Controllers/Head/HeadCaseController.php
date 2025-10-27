<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\CaseModel;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\CaseType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

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
    $query = CaseModel::with(['caseType', 'students'])->orderBy('case_id', 'desc');

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

    // Paginate results: show 10 cases per page and preserve query string for filters/search
    $cases = $query->paginate(10)->appends($request->except('page'));
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
            $newType = CaseType::create([
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
            'involved_students' => 'required|string', 
        ]);

        // Handle "Other" case type
        if ($request->case_type_id === 'other') {
            $newType = CaseType::create([
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

        // Update involved students
        $studentIds = array_map('trim', explode(',', $request->involved_students));
        // Remove all current links
        DB::table('case_students')->where('case_id', $case->case_id)->delete();
        // Add new links
        foreach ($studentIds as $studentId) {
            if ($studentId) {
                DB::table('case_students')->insert([
                    'case_id' => $case->case_id,
                    'student_id' => $studentId,
                ]);
            }
        }

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

        $students = Student::with('user')
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

    public function export()
    {
    $request = request();

        // Base query: include case type and students
        $query = CaseModel::with(['caseType', 'students'])->orderBy('case_id', 'desc');

        // Keep compatibility with existing filters if any
        if ($request->filled('archived') && $request->archived == '1') {
            $query->where('archived', true);
        } else {
            $query->where('archived', false);
        }

        if ($request->filled('filter_type')) {
            $query->where('case_type_id', $request->filter_type);
        }

        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        if ($request->filled('filter_severity')) {
            $query->where('severity', $request->filter_severity);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('case_id', 'LIKE', "%$search%")
                  ->orWhereHas('caseType', function($t) use ($search) {
                      $t->where('type_name', 'LIKE', "%$search%");
                  });
            });
        }

        $cases = $query->get();

        $format = $request->query('format', 'csv');

        // Columns for export
        $exportColumns = [
            'case_id', 'case_type', 'presenting_problem', 'description', 'severity', 'filed_date', 'filed_time', 'status'
        ];

        // Prepare export rows
        $exportData = [];
        foreach ($cases as $case) {
            $row = [];
            $row['case_id'] = $case->case_id ?? '';
            $row['case_type'] = $case->caseType->type_name ?? '';
            $row['presenting_problem'] = $case->presenting_problem ?? '';
            $row['description'] = $case->description ?? '';
            $row['severity'] = $case->severity ?? '';
            $row['filed_date'] = $case->filed_date ?? '';
            $row['filed_time'] = $case->filed_time ?? '';
            $row['status'] = $case->status ?? '';
            $exportData[] = $row;
        }

        // Filename base
        $filenameBase = 'Case_List';

        if ($format === 'pdf') {
            $columns = [
                'Case ID', 'Case Type', 'Presenting Problem', 'Description', 'Severity', 'Filed Date', 'Filed Time', 'Status'
            ];
            $html = view('Head.case_export_pdf', compact('cases', 'columns'))->render();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'landscape');
            // Use a fixed filename 'case record.pdf' per user request
            $filename = 'case record.pdf';
            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } elseif ($format === 'xlsx' || $format === 'xls') {
            // Title + Subtitle + Date + Blank + Header + Data
            $schoolTitle = ['Montessori Academy of Southern Cebu, Inc.'];
            $subtitle = ['Case List'];
            $dateRow = ['Date Generated: ' . now()->format('F d, Y h:i A')];
            $colCount = count($exportColumns);

            $exportDataWithTitle = [];
            $exportDataWithTitle[] = array_merge([$schoolTitle[0]], array_fill(1, $colCount - 1, ''));
            $exportDataWithTitle[] = array_merge([$subtitle[0]], array_fill(1, $colCount - 1, ''));
            $exportDataWithTitle[] = array_merge([$dateRow[0]], array_fill(1, $colCount - 1, ''));
            $exportDataWithTitle[] = array_fill(0, $colCount, ''); // Blank row
            $exportDataWithTitle[] = $exportColumns; // header row
            foreach ($exportData as $row) {
                $exportDataWithTitle[] = array_values($row);
            }

            $filename = $filenameBase . '.' . $format;
            $excelFormat = $format === 'xlsx' ? \Maatwebsite\Excel\Excel::XLSX : \Maatwebsite\Excel\Excel::XLS;

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\CasesExport($exportDataWithTitle, $exportColumns),
                $filename,
                $excelFormat
            );
        } elseif ($format === 'csv') {
            $schoolTitle = ['Montessori Academy of Southern Cebu, Inc.'];
            $subtitle = ['Case List'];
            $dateRow = ['Date Generated: ' . now()->format('F d, Y h:i A')];
            $filename = $filenameBase . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\""
            ];
            $callback = function() use ($exportData, $exportColumns, $schoolTitle, $subtitle, $dateRow) {
                $file = fopen('php://output', 'w');
                $colCount = count($exportColumns);
                fputcsv($file, array_merge([$schoolTitle[0]], array_fill(1, $colCount-1, '')));
                fputcsv($file, array_merge([$subtitle[0]], array_fill(1, $colCount-1, '')));
                fputcsv($file, array_merge([$dateRow[0]], array_fill(1, $colCount-1, '')));
                fputcsv($file, array_fill(0, $colCount, ''));
                fputcsv($file, $exportColumns);
                foreach ($exportData as $row) {
                    fputcsv($file, array_values($row));
                }
                fclose($file);
            };
            return Response::stream($callback, 200, $headers);
        } else {
            return response()->json(['error' => 'Invalid export format'], 400);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('import_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            // Find or create case type
            $caseType = CaseType::firstOrCreate([
                'type_name' => $data['case_type'] ?? 'N/A'
            ], [
                'description' => ''
            ]);

            // Create case
            CaseModel::updateOrCreate(
                ['case_id' => $data['case_id']],
                [
                    'case_type_id' => $caseType->type_id,
                    'presenting_problem' => $data['presenting_problem'] ?? '',
                    'description' => $data['description'] ?? '',
                    'severity' => $data['severity'] ?? '',
                    'filed_date' => $data['filed_date'] ?? null,
                    'filed_time' => $data['filed_time'] ?? null,
                    'status' => $data['status'] ?? '',
                    'archived' => false,
                ]
            );
        }
        fclose($handle);

        return redirect()->route('Head.cases.index')->with('success', 'Cases imported successfully!');
    }
}
