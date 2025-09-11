<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 15px;
            color: #2563eb;
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
            font-size: 10.5px; 
            table-layout: fixed;
        }
        th, td {
            word-break: break-word;
            max-width: 120px;
        }
        @page {
            size: A4 landscape;
            margin: 20px 25px 20px 25px;
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 6px 8px; 
            text-align: left;
        }
        th { 
            background: #2563eb; 
            color: #fff; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) { 
            background: #f9f9f9; 
        }
        tr:hover {
            background: #eef4ff;
        }
    </style>
</head>
<body>
    <h2>
        @php
            $filterValue = isset($filter) ? strtolower($filter) : '';
            $listTitle = '';
            if ($filterValue === 'elementary') {
                $listTitle = 'Elementary List';
            } elseif ($filterValue === 'juniorhigh') {
                $listTitle = 'Junior High School List';
            } elseif ($filterValue === 'seniorhigh') {
                $listTitle = 'Senior High School List';
            } elseif ($filterValue === 'kindergarten') {
                $listTitle = 'Kindergarten List';
            } elseif ($filterValue === 'inactive') {
                $listTitle = 'Inactive Students List';
            }
        @endphp
        {{ $listTitle ?: 'Student List' }}
    </h2>
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
