<html>
<head>
    <style>
        table { border-collapse: collapse; width: 100%; font-size: 12px; }
        th, td { border: 1px solid #888; padding: 4px; }
        th { background: #2563eb; color: #fff; }
    </style>
</head>
<body>
    <h2>Student Export</h2>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Year Level</th>
                    <th>Section</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Contact Number</th>
                    <th>Email Address</th>
                    <th>Address</th>
                    <th>Father's Name</th>
                    <th>Mother's Name</th>
                    <th>Guardian's Name</th>
                    <th>Relationship</th>
                    <th>Guardian Contact</th>
                    <th>Guardian Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->s_id ?? '' }}</td>
                        <td>{{ trim(($student->lname ?? '') . ', ' . ($student->fname ?? '') . ' ' . ($student->mname ?? '') . ' ' . ($student->suffix ?? '')) }}</td>
                        <td>{{ $student->year_level ?? '' }}</td>
                        <td>{{ $student->section ?? '' }}</td>
                        <td>{{ $student->sex ?? '' }}</td>
                        <td>{{ $student->bod ? date('F d, Y', strtotime($student->bod)) : '' }}</td>
                        <td>{{ $student->contact_num ?? '' }}</td>
                        <td>{{ $student->email ?? '' }}</td>
                        <td>{{ $student->address ?? '' }}</td>
                        <td>{{ $student->father_name ?? '' }}</td>
                        <td>{{ $student->mother_name ?? '' }}</td>
                        <td>{{ $student->guardian_name ?? '' }}</td>
                        <td>{{ $student->relationship ?? '' }}</td>
                        <td>{{ $student->guardian_contact ?? '' }}</td>
                        <td>{{ $student->guardian_email ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</body>
</html>
