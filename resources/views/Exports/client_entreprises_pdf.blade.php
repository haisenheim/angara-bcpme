<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #222; }
        h1 { font-size: 14px; margin: 0 0 8px 0; }
        .meta { font-size: 8px; color: #555; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 4px 5px; text-align: left; vertical-align: top; }
        th { background: #f0f0f0; font-weight: bold; }
        tr:nth-child(even) { background: #fafafa; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="meta">Généré le {{ now()->format('d/m/Y H:i') }} — {{ count($rows) }} ligne(s)</div>
    <table>
        <thead>
            <tr>
                @foreach($headers as $key => $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($headers as $key => $_label)
                        <td>{{ $row[$key] ?? '' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
