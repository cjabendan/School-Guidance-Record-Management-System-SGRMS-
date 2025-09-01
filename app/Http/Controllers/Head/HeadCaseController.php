<?php

namespace App\Http\Controllers\Head;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\CaseModel;
use Illuminate\Support\Facades\DB;

class HeadCaseController extends Controller
{
    private function getStatusEnumValues()
    {
        $type = DB::select("SHOW COLUMNS FROM cases WHERE Field = 'status'")[0]->Type;
        preg_match('/enum\((.*)\)/', $type, $matches);
        $enum = [];
        foreach(explode(',', $matches[1]) as $value){
            $enum[] = trim($value, "'");
        }
        return $enum;   
    }
    private function getSeverityEnumValues()
    {
        $type = DB::select("SHOW COLUMNS FROM cases WHERE Field = 'severity'")[0]->Type;
        preg_match('/enum\((.*)\)/', $type, $matches);
        $enum = [];
        foreach(explode(',', $matches[1]) as $value){
            $enum[] = trim($value, "'");
        }
        return $enum;
    }

    public function index(Request $request)
    {
        $query = CaseModel::orderBy('case_id', 'desc');

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

        return redirect()->route('Head.case')->with('success', 'Case added successfully!');
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

        return redirect()->route('Head.cases')->with('success', 'Case updated successfully!');
    }

    public function archive($case_id)
    {
        $case = CaseModel::findOrFail($case_id);
        $case->archived = true;
        $case->save();

        return redirect()->route('Head.cases')->with('success', 'Case archived successfully!');
    }
}
    


