<html>
<head>
    <style>
        /* Import Poppins (headers) and Roboto (body) */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap');

        body {
            font-family: "Roboto", Arial, sans-serif;
            font-size: 10.5px;
            color: #333;
        }

        /* Header with logo and school name */
        .report-header {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            margin-bottom: 15px;
            text-align: center;
            font-family: "Poppins", Arial, sans-serif;
        }
        .report-header img {
            width: 70px;
            height: 70px;
            margin-bottom: 8px;
        }
        .report-acronym {
            font-size: 18px;
            font-weight: 700;
            color: #111;
            letter-spacing: 1px;
        }
        .report-org {
            font-size: 12px;
            font-weight: 500;
            color: #444;
        }
        .report-title {
            font-size: 15px;
            font-weight: 700;
            color: #1e3a8a;
            text-transform: uppercase;
            margin: 8px 0 4px 0;
            letter-spacing: 0.5px;
        }
        .report-meta {
            font-size: 9px;
            color: #555;
            font-family: "Roboto", Arial, sans-serif;
        }

        /* Table styling */
        table { 
            border-collapse: collapse; 
            width: 100%; 
            font-size: 9px; 
            table-layout: fixed;
            font-family: "Roboto", Arial, sans-serif;
        }
        th, td {
            word-break: break-word;
            border: 1px solid #cbd5e1; 
            padding: 5px 7px; 
            text-align: left;
        }
        th { 
            background: #1e40af; 
            color: #fff; 
            text-transform: uppercase;
            font-size: 8.5px;
            letter-spacing: 0.5px;
            font-weight: 600;
            font-family: "Poppins", Arial, sans-serif;
        }
        tr:nth-child(even) { background: #f8fafc; }
        tr:hover { background: #eef2ff; }
        thead { display: table-row-group; }

        @page {
            size: A4 landscape;
            margin: 15px 20px;
        }

        /* Footer with page numbers */
        @bottom-right {
            content: "Page " counter(page) " of " counter(pages);
            font-size: 9px;
            color: #555;
            font-family: "Roboto", Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <img src="{{ public_path('images/logo/MASCI-LOGO.jpg') }}" alt="MASCI Logo">
        <div class="report-acronym">MASCI</div>
        <div class="report-org">Montessori Academy of Southern Cebu, Inc.</div>
        <div class="report-title">
            @php
                $filterValue = isset($filter) ? strtolower($filter) : '';
                $listTitle = match($filterValue) {
                    'elementary'   => 'Elementary List',
                    'juniorhigh'   => 'Junior High List',
                    'seniorhigh'   => 'Senior High List',
                    'kindergarten' => 'Kindergarten List',
                    'inactive'     => 'Inactive List',
                    default        => 'Student List'
                };
            @endphp
            {{ $listTitle }}
        </div>
        <div class="report-meta">
            Date Generated: {{ now()->format('F d, Y h:i A') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Year Level</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Contact No.</th>
                <th>Email</th>
                <th>Address</th>
                <th>Father</th>
                <th>Mother</th>
                <th>Guardian Info</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $student->s_id ?? '' }}</td>
                    <td>{{ trim(($student->lname ?? '') . ', ' . ($student->fname ?? '') . ' ' . ($student->mname ?? '') . ' ' . ($student->suffix ?? '')) }}</td>
                    <td>{{ $student->year_level ?? '' }}</td>
                    <td>{{ $student->sex ?? '' }}</td>
                    <td>{{ $student->bod ? date('F d, Y', strtotime($student->bod)) : '' }}</td>
                    <td>{{ $student->contact_num ?? '' }}</td>
                    <td>{{ $student->email ?? '' }}</td>
                    <td>{{ $student->address ?? '' }}</td>
                    <td>{{ $student->father_name ?? '' }}</td>
                    <td>{{ $student->mother_name ?? '' }}</td>
                    <td>
                        {{ $student->guardian_name ?? '' }}<br>
                        {{ $student->relationship ?? '' }}<br>
                        {{ $student->guardian_contact ?? '' }}<br>
                        {{ $student->guardian_email ?? '' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
