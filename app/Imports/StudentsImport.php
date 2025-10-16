<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use App\Models\EducLevel;
use App\Models\YearLevel;
use App\Models\SchoolYear;
use App\Models\StudentSchoolYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class StudentsImport implements OnEachRow, WithHeadingRow
{
    public $errors = [];

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        try {
            // 🧹 Clean the data
            $data = array_map(fn($v) => is_string($v) ? trim(preg_replace('/\x{FEFF}/u', '', $v)) : $v, $data);

            if (empty($data['s_id'])) {
                throw new \Exception('Missing student ID');
            }

            $activeSY = SchoolYear::where('is_active', 1)->firstOrFail();

            $educ = EducLevel::firstOrCreate(['educ_level' => $data['educ_level'] ?? null]);
            $year = YearLevel::firstOrCreate([
                'year_level' => $data['year_level'] ?? null,
                'e_id' => $educ->e_id,
            ]);

            $student = Student::where('s_id', $data['s_id'])->first();

            if ($student) {
                Log::info("🔁 Updating existing student {$data['s_id']}");

                $user = $student->user;

                // 🧠 If user missing, reconnect by contact number
                if (!$user && !empty($data['contact_num'])) {
                    $user = User::where('contact_num', $data['contact_num'])->first();
                    if ($user) {
                        $student->update(['user_id' => $user->id]);
                    }
                }

                // 🆕 Recreate user if still missing
                if (!$user) {
                    $password = ucfirst(strtolower($data['last_name'])) . preg_replace('/[^0-9]/', '', substr($data['s_id'], -4));
                    $user = User::create([
                        'first_name' => $data['first_name'],
                        'middle_name' => $data['middle_name'] ?? null,
                        'last_name' => $data['last_name'],
                        'suffix' => $data['suffix'] ?? null,
                        'email' => $data['email'] ?? null,
                        'contact_num' => $data['contact_num'] ?? null,
                        'sex' => $data['sex'] ?? null,
                        'bod' => $data['bod'] ?? null,
                        'address' => $data['address'] ?? null,
                        'profile_image' => 'default.jpg',
                        'password' => bcrypt($password),
                        'role' => 'student',
                        'status' => 'active',
                    ]);
                    $student->update(['user_id' => $user->id]);
                }

                // ✅ Always update user with latest info from CSV
                DB::table('users')->where('id', $user->id)->update([
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'] ?? null,
                    'last_name' => $data['last_name'],
                    'suffix' => $data['suffix'] ?? null,
                    'email' => $data['email'] ?? null,
                    'contact_num' => $data['contact_num'] ?? null, // ✅ Force overwrite
                    'sex' => $data['sex'] ?? null,
                    'bod' => $data['bod'] ?? null,
                    'address' => $data['address'] ?? null,
                    'status' => 'active',
                ]);

                // ✅ Always update student record
                DB::table('students')->where('s_id', $data['s_id'])->update([
                    'religion' => $data['religion'] ?? null,
                    'civil_status' => $data['civil_status'] ?? null,
                    'father_name' => $data['father_name'] ?? null,
                    'mother_name' => $data['mother_name'] ?? null,
                    'guardian_name' => $data['guardian_name'] ?? null,
                    'relationship' => $data['relationship'] ?? null,
                    'guardian_contact' => $data['guardian_contact'] ?? null,
                    'guardian_email' => $data['guardian_email'] ?? null,
                ]);

                Log::info("✅ Student {$data['s_id']} successfully updated");

            } else {
                // 🆕 Create new student + user
                $password = ucfirst(strtolower($data['last_name'])) . preg_replace('/[^0-9]/', '', substr($data['s_id'], -4));
                $user = User::create([
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'] ?? null,
                    'last_name' => $data['last_name'],
                    'suffix' => $data['suffix'] ?? null,
                    'email' => $data['email'] ?? null,
                    'contact_num' => $data['contact_num'] ?? null,
                    'sex' => $data['sex'] ?? null,
                    'bod' => $data['bod'] ?? null,
                    'address' => $data['address'] ?? null,
                    'profile_image' => 'default.jpg',
                    'password' => bcrypt($password),
                    'role' => 'student',
                    'status' => 'active',
                ]);

                Student::create([
                    's_id' => $data['s_id'],
                    'user_id' => $user->id,
                    'religion' => $data['religion'] ?? null,
                    'civil_status' => $data['civil_status'] ?? null,
                    'father_name' => $data['father_name'] ?? null,
                    'mother_name' => $data['mother_name'] ?? null,
                    'guardian_name' => $data['guardian_name'] ?? null,
                    'relationship' => $data['relationship'] ?? null,
                    'guardian_contact' => $data['guardian_contact'] ?? null,
                    'guardian_email' => $data['guardian_email'] ?? null,
                ]);

                Log::info("🆕 Created new student {$data['s_id']}");
            }

            // 🎓 Link to active school year
            StudentSchoolYear::updateOrCreate(
                ['student_id' => $data['s_id'], 'school_year_id' => $activeSY->id],
                ['year_level' => $year->year_level, 'status' => $data['status'] ?? 'Enrolled']
            );

            Log::info("📘 Linked {$data['s_id']} to SY {$activeSY->year_label}");

        } catch (\Throwable $e) {
            Log::error("❌ Import error: " . $e->getMessage(), ['row' => $data]);
            $this->errors[] = ['row' => $data, 'error' => $e->getMessage()];
        }
    }
}
