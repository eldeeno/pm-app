<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Status Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
<h1>Project Status Report</h1>
<table>
    <thead>
    <tr>
        <th>Project Name</th>
        <th>Pending Tasks</th>
        <th>In Progress Tasks</th>
        <th>Completed Tasks</th>
        <th>Overdue Tasks</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($projects as $project)
        <tr>
            <td>{{ $project['name'] }}</td>
            <td>{{ $project['pending_tasks'] }}</td>
            <td>{{ $project['in_progress_tasks'] }}</td>
            <td>{{ $project['completed_tasks'] }}</td>
            <td>{{ $project['overdue_tasks'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
