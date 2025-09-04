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
                @foreach ($columns as $col)
                    <th>{{ ucwords(str_replace('_', ' ', $col)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    @foreach ($columns as $col)
                        <td>{{ $student->$col ?? '' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
