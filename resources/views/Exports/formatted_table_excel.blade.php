<table>
    @php $colCount = max(1, count($headers)); @endphp
    @if(!empty($logoDataUri))
        <tr>
            <td colspan="{{ $colCount }}" style="vertical-align:middle;padding-bottom:8px;">
                <img src="{{ $logoDataUri }}" alt="" height="48" style="display:block;">
            </td>
        </tr>
    @endif
    <tr>
        <td colspan="{{ $colCount }}" style="font-size:18pt;font-weight:bold;color:#0b3d6d;padding:6px 0 2px 0;">{{ $title }}</td>
    </tr>
    @if(!empty($subtitle))
        <tr>
            <td colspan="{{ $colCount }}" style="font-size:10pt;color:#444;padding-bottom:6px;">{{ $subtitle }}</td>
        </tr>
    @endif
    <tr>
        <td colspan="{{ $colCount }}" style="font-size:9pt;color:#555;padding-bottom:10px;">Généré le {{ $generatedAt }} — {{ count($rows) }} ligne(s)</td>
    </tr>
    <thead>
        <tr>
            @foreach($headers as $key => $label)
                <th style="background:#e8f1fb;font-weight:bold;border:1px solid #999;padding:6px;">{{ $label }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
            <tr>
                @foreach($headers as $key => $_label)
                    <td style="border:1px solid #ccc;padding:5px;">{{ $row[$key] ?? '' }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
