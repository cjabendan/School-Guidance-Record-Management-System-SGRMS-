<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public $errors = [];
    public function model(array $row)
    {
        try {
            // Create user first
            $user = User::create([
                'first_name' => $row['first_name'] ?? '',
                'middle_name' => $row['middle_name'] ?? '',
                'last_name' => $row['last_name'] ?? '',
                'suffix' => $row['suffix'] ?? '',
                'email' => $row['email'] ?? '',
                'contact_num' => $row['contact_num'] ?? '',
                'sex' => $row['sex'] ?? '',
                'bod' => $row['bod'] ?? null,
                'address' => $row['address'] ?? '',
                'profile_image' => 'default.png',
                'password' => Hash::make(ucfirst(strtolower($row['last_name'] ?? 'password'))),
                'role' => 'student',
                'status' => $row['status'] ?? 'active',
            ]);

            // Find or create educ_level
            $educLevelModel = \App\Models\EducLevel::firstOrCreate([
                'educ_level' => $row['educ_level'] ?? null
            ]);

            // Find or create year_level (must link to educ_level)
            $yearLevelModel = \App\Models\YearLevel::firstOrCreate([
                'year_level' => $row['year_level'] ?? null,
                'e_id' => $educLevelModel->e_id
            ]);

            // Create student with y_id
            return new Student([
                's_id' => $row['s_id'],
                'user_id' => $user->id,
                'y_id' => $yearLevelModel->y_id,
                'section' => $row['section'] ?? null,
                'program' => $row['program'] ?? null,
                'status' => $row['status'] ?? 'active',
                'religion' => $row['religion'] ?? null,
                'civil_status' => $row['civil_status'] ?? null,
            ]);
        } catch (\Throwable $e) {
            $this->errors[] = [
                'row' => $row,
                'error' => $e->getMessage(),
            ];
            return null;
        }
    }
    }
