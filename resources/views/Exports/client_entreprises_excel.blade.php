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
