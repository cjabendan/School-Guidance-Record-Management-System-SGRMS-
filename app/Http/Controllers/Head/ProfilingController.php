<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilingController extends Controller
{

    // Display the list of counselors //

    public function counselors()
    {
        $counselors = DB::table('counselors')
            ->leftJoin('users', 'counselors.user_id', '=', 'users.id')
            ->select(
                'counselors.c_id',
                DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name) AS full_name"),
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.contact_num',
                'users.email',
                // 'counselors.c_level', // Removed because it does not exist in the DB
                'users.profile_image',
                'users.username'
                // do NOT select users.password
            )
            ->get();

        return view('Head.profiling.counselors', compact('counselors'));
    }


    // Display the list of students with filters //

    public function students(Request $request)
    {
        $filter = $request->query('status', 'active');

        $educLevelMap = [
            'college' => 'College',
            'highschool' => 'High School',
            'elementary' => 'Elementary'
        ];

        $query = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.s_id',
                'students.id_num',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.email',
                'users.contact_num',
                'users.sex',
                'users.bod',
                'users.address',
                'students.year_level',
                'students.section',
                'students.program',
                'students.previous_school_address',
                'students.status',
                'users.profile_image'
            )
            ->selectRaw('(SELECT COUNT(*) FROM case_records WHERE student_id = students.s_id) AS case_count');


        // Apply filters
        if (array_key_exists($filter, $educLevelMap)) {
            $query->where('students.educ_level', $educLevelMap[$filter])
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


    // Display the list of parents //
    public function parents()
    {
        $parents = DB::table('parents')
            ->leftJoin('users', 'parents.user_id', '=', 'users.id')
            ->select(
                'parents.*',
                DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name) AS full_name"),
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.contact_num',
                'users.email',
                'parents.realationship',
                'users.profile_image',
                'users.username'
            )
            ->get(); // or use ->paginate(10) for pagination

        return view('Head.profiling.parents', compact('parents'));
    }
}
