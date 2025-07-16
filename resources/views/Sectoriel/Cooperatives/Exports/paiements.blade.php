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
                            <h4 style="font-size: 24px; text-align: center;">Historique des paiements</h4>
                            <h5 style="font-size: 16px; text-align: center;">Organisation : {{ $cooperative->name }}</h5>
                            <h6 style="font-size: 14px; text-align: center;">Periode du {{ $start }} au {{ $end }}</h6>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table id="paiementsTable" class="table table-sm table-bordered">
                <thead>
                    <tr class="fs-6 fw-bolder border">
                        <th>DATE</th>
                        <th>MONTANT</th>
                        <th>MODE DE PAIEMENT</th>
                        <th>SOURCE</th>
                        <th>CIBLE</th>
                        <th>BENEFICIARE</th>
                        <th>PAYEUR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paiements as $p)
                    <tr class="border fs-6">
                        <td>{{$p->created_at->format('d/m/Y H:i')}}</td>
                        <td>{{ $p->montant}}</td>
                        <td>{{ $p->mode?$p->mode->name:'-' }}</td>
                        @if($p->caisse)
                        <td>{{ $p->caisse->name  }}</td>
                        @elseif($p->wallet)
                        <td>{{$p->wallet->name}}</td>
                        @else
                        <td>-</td>
                        @endif
                        <td>{{$p->phone}}</td>
                        <td>{{$p->exploitant?->name}}</td>
                        <td>{{$p->user?$p->user->name:'-'}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </body>
</html>
