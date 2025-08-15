<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Student;


class HeadController extends Controller
{

    public function dashboard()
    {
        $currentYear = date('Y');
        $allCaseTypes = ['Behavioral', 'Emotional', 'Peer Conflict', 'Academic'];

        // Line chart data (monthly count per type)
        $caseTypeData = [];
        foreach ($allCaseTypes as $type) {
            $caseTypeData[$type] = array_fill(1, 12, 0); // Fill Jan-Dec with 0
        }
        $caseTypeData['Total'] = array_fill(1, 12, 0);

        $records = DB::table('case_records')
            ->select('case_type', DB::raw('MONTH(filed_date) as month'), DB::raw('COUNT(*) as total'))
            ->whereNotNull('filed_date')
            ->groupBy('case_type', 'month')
            ->get();

        foreach ($records as $row) {
            $type = $row->case_type;
            $month = (int)$row->month;
            if (isset($caseTypeData[$type])) {
                $caseTypeData[$type][$month] = (int)$row->total;
                $caseTypeData['Total'][$month] += (int)$row->total;
            }
        }

        // Pie chart data
        $pieRecords = DB::table('case_records')
            ->select('case_type', DB::raw('COUNT(*) as total'))
            ->whereYear('filed_date', $currentYear)
            ->groupBy('case_type')
            ->get();

        $pieLabels = $pieRecords->pluck('case_type')->toArray();
        $pieData = $pieRecords->pluck('total')->toArray();
        $pieColors = [
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 99, 132, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)'
        ];

        return view('Head.dashboard', compact('caseTypeData', 'pieLabels', 'pieData', 'pieColors'));
    }


    public function appointments()
    {
        return view('Head.appointments');
    }


    public function reports()
    {
        return view('Head.reports');
    }

    public function settings()
    {
        return view('Head.settings');
    }

    public function getNextStudentId()
    {
        $lastStudent = DB::table('students')->orderByDesc('s_id')->first();

        if ($lastStudent && preg_match('/(\d{8})$/', $lastStudent->id_num, $matches)) {
            $lastNumber = intval($matches[1]);
        } else {
            $lastNumber = 0;
        }

        $nextNumber = str_pad($lastNumber + 1, 8, '0', STR_PAD_LEFT);
        $currentYear = date('y');
        $fullId = "SCC-$currentYear-$nextNumber";

        return response()->json(['next_id' => $fullId]);
    }
}
