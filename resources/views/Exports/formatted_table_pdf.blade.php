<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 22mm 14mm 18mm 14mm; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8.5pt;
            color: #1a1a1a;
            margin: 0;
        }
        .page-number:before { content: counter(page); }
        .page-count:before { content: counter(pages); }
        .pdf-header {
            position: fixed;
            top: -18mm;
            left: 0;
            right: 0;
            height: 16mm;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 4px;
        }
        .pdf-header-inner {
            display: table;
            width: 100%;
        }
        .pdf-header-logo, .pdf-header-text {
            display: table-cell;
            vertical-align: middle;
        }
        .pdf-header-logo { width: 38mm; }
        .pdf-header-logo img { max-height: 14mm; max-width: 36mm; }
        .doc-title {
            font-size: 16pt;
            font-weight: bold;
            color: #0b3d6d;
            letter-spacing: 0.02em;
            margin: 0 0 2px 0;
            line-height: 1.15;
        }
        .doc-sub {
            font-size: 8pt;
            color: #444;
            margin: 0;
        }
        .pdf-footer {
            position: fixed;
            bottom: -14mm;
            left: 0;
            right: 0;
            font-size: 7.5pt;
            color: #555;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 3px;
        }
        .pdf-footer .sep { padding: 0 6px; color: #999; }
        .content-wrap { margin-top: 4mm; }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2mm;
        }
        table.data thead { display: table-header-group; }
        table.data tbody { display: table-row-group; }
        table.data th, table.data td {
            border: 0.4pt solid #999;
            padding: 4px 5px;
            text-align: left;
            vertical-align: top;
        }
        table.data th {
            background: #e8f1fb;
            font-weight: bold;
            font-size: 8pt;
        }
        table.data tr:nth-child(even) td { background: #fafafa; }
        .meta-bar {
            font-size: 7.5pt;
            color: #444;
            margin-bottom: 2mm;
        }
    </style>
</head>
<body>
    <div class="pdf-header">
        <div class="pdf-header-inner">
            <div class="pdf-header-logo">
                @if(!empty($logoDataUri))
                    <img src="{{ $logoDataUri }}" alt="">
                @endif
            </div>
            <div class="pdf-header-text">
                <div class="doc-title">{{ $title }}</div>
                @if(!empty($subtitle))
                    <p class="doc-sub">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="pdf-footer">
        Angara
        <span class="sep">—</span>
        Document généré le {{ $generatedAt }}
        <span class="sep">—</span>
        {{ count($rows) }} ligne(s)
        <span class="sep">—</span>
        Page <span class="page-number"></span>/<span class="page-count"></span>
    </div>

    <div class="content-wrap">
        <div class="meta-bar">
            Liste détaillée (export tableau) — {{ count($rows) }} enregistrement(s)
        </div>
        <table class="data">
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
    </div>
</body>
</html>
