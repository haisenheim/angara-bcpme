<html>
    <head>
    </head>
    <body>

            <table>
                <tbody>
                    <tr>
                        <td colspan="1">
                            <img src="{{public_path('img/logo.png')}}" height="180" alt="">
                        </td>
                        <td colspan="4">
                            <h4 style="font-size: 24px; text-align: center;">Historique des entrées en stock</h4>
                            <h5 style="font-size: 16px; text-align: center;">Organisation : {{ $cooperative->name }}</h5>
                            <h6 style="font-size: 14px; text-align: center;">Periode du {{ $start }} au {{ $end }}</h6>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table id="entreesTable" class="table table-sm table-bordered">
                <thead>
                    <tr class="fs-6 fw-bolder border">
                        <th>DATE</th>
                        <th>ENTREPOT</th>
                        <th>QUANTITE</th>
                        <th>PRIX UNITAIRE</th>
                        <th>TOTAL</th>
                        <th>MONTANT PAYE</th>
                        <th>RESTE</th>
                        <th>PRODUCTEUR</th>
                        <th>AGENT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $e)
                    <tr class="fs-6 border">
                        <td>{{ $e->created_at->format('d/m/Y H:i')}}</td>

                        <td>{{ $e->entrepot?->name}}</td>
                        <td>{{ $e->quantity}}kg</td>
                        <td>{{$e->pu}}</td>
                        <td>{{ $e->pu*$e->quantity}}</td>
                        <td>{{ $e->versements}}</td>
                        <td>{{ $e->reste}}</td>
                        <td>{{ $e->exploitant?->name}}</td>
                        <td>{{ $e->agent?->name}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </body>
</html>
