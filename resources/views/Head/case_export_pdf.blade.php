<html>
<head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap');
        body { font-family: "Roboto", Arial, sans-serif; font-size: 10.5px; color: #333; }
        .report-header { display:flex; align-items:center; justify-content:center; flex-direction:column; margin-bottom:15px; text-align:center; font-family: "Poppins", Arial, sans-serif; }
        .report-header img { width:70px; height:70px; margin-bottom:8px; }
        .report-acronym { font-size:18px; font-weight:700; color:#111; letter-spacing:1px; }
        .report-org { font-size:12px; font-weight:500; color:#444; }
        .report-title { font-size:15px; font-weight:700; color:#1e3a8a; text-transform:uppercase; margin:8px 0 4px 0; letter-spacing:0.5px; }
        .report-meta { font-size:9px; color:#555; font-family: "Roboto", Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; font-size: 9px; table-layout: fixed; font-family: "Roboto", Arial, sans-serif; }
        th, td { word-break: break-word; border:1px solid #cbd5e1; padding:5px 7px; text-align:left; }
        th { background:#1e40af; color:#fff; text-transform:uppercase; font-size:8.5px; letter-spacing:0.5px; font-weight:600; font-family: "Poppins", Arial, sans-serif; }
        tr:nth-child(even) { background: #f8fafc; }
        tr:hover { background: #eef2ff; }
        thead { display: table-row-group; }
        @page { size: A4 landscape; margin: 15px 20px; }
        @bottom-right { content: "Page " counter(page) " of " counter(pages); font-size:9px; color:#555; font-family: "Roboto", Arial, sans-serif; }
    </style>
</head>
<body>
    <div class="report-header">
        <img src="{{ public_path('images/logo/school-logo.png') }}" alt="MASCI Logo">
        <div class="report-acronym">MASCI</div>
        <div class="report-org">Montessori Academy of Southern Cebu, Inc.</div>
        <div class="report-title">Case List</div>
        <div class="report-meta">Date Generated: {{ now()->format('F d, Y h:i A') }}</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Case ID</th>
                <th>Case Type</th>
                <th>Presenting Problem</th>
                <th>Description</th>
                <th>Severity</th>
                <th>Filed Date</th>
                <th>Filed Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cases as $case)
                <tr>
                    <td>{{ $case->case_id ?? '' }}</td>
                    <td>{{ $case->caseType->type_name ?? '' }}</td>
                    <td>{{ $case->presenting_problem ?? '' }}</td>
                    <td>{{ $case->description ?? '' }}</td>
                    <td>{{ $case->severity ?? '' }}</td>
                    <td>{{ $case->filed_date ? date('F d, Y', strtotime($case->filed_date)) : '' }}</td>
                    <td>{{ $case->filed_time ?? '' }}</td>
                    <td>{{ ucfirst($case->status ?? '') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
