<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<table>
    <thead>
    <tr>
        <th>ИНН</th>
        <th>Наименование</th>
        <th>Описание</th>
        <th>Степень риска</th>
    </tr>
    </thead>
    <tbody>
    @foreach($companies as $company)
        <tr>
            <td>{{ $company['inn'] }}</td>
            <td>{{ $company['name'] }}</td>
            <td>{{ $company['description'] }}</td>
            <td style="background-color: {{ $company['color_code'] === 'green' ? '#c8e6c9' : '#ffcdd2' }}">
                {{ $company['risk_level'] }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
