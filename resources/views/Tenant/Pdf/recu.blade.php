<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recu de caisse</title>

</head>
<body>
    <style>
        .table-bordered thead th, .table-bordered thead td {
            border-bottom-width: 2px;
        }
        .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
            border-bottom-width: 2px;
        }
        .table-striped tbody tr:nth-of-type(2n+1) {
        background-color: rgba(0, 0, 0, 0.05);
        }
        .table-bordered th, .table-bordered td {
        border: 1px solid #dee2e6;
            border-bottom-width: 1px;
            border-left-width: 1px;
        }

élément {

}
table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td {

    border-bottom-width: 0;

}
table.table-bordered.dataTable th, table.table-bordered.dataTable td {

    border-left-width: 0;

}
table.dataTable td, table.dataTable th {

    -webkit-box-sizing: content-box;
    box-sizing: content-box;

}
.table-bordered th, .table-bordered td {

    border: 1px solid #dee2e6;
        border-bottom-width: 1px;
        border-left-width: 1px;

}
.table-sm th, .table-sm td {
    font-size: 11px;
    padding: 0.3rem;

}
    </style>

    <div class="card" style="margin-bottom: 30px; font-size: 13px;">

        <div class="card-header" style="max-width: 700px; margin:10px auto;">
            <div class="header"><span style="font-weight: 900; font-size:2rem;"> {{$tenant->name}}</span></div>
            <div><span style="font-weight: 700; font-size:1.2rem;">{{$tenant->address}}</span></div>
            <div><span style="font-weight: 700; font-size:1rem;">{{$tenant->phone}}</span></div>
        </div>
        <h5 style="font-size: 1.5rem; font-weight:900">RECU DE PAIEMENT DU PRODUCTEUR</h5>
        <div class="card-body">
            Code : <b>{{ $paiement->name }}</b>
            <br>Producteur: <b>{{ $paiement->exploitant?->name }}- <small>{{ $paiement->exploitant?->phone }}</small></b></p>
            <br>Livraison : <b style="font-size: 12px">{{ $paiement->entree?->name }}/<small>{{ $paiement->entree?->created_at->format('d/m/Y H:i') }}/{{ $paiement->entree?->entrepot?->name }}/{{$paiement->entree?->quantity}}kg/{{$paiement->entree?->gamme?->name}}/{{ number_format($paiement->entree?->pu,0,',','.') }}/{{ number_format($paiement->entree?->montant,0,',','.') }}</small></b>

            <p style="font-size: 15px">
                 Montant du paiement : <b>{{ number_format($paiement->montant,0,',','.') }} FCFA</b>
            </p>
            <br>Payeur : <b>{{ $paiement->user?->name }} - {{ $paiement->user?->phone }}</b>
            <br>Reste après  paiement : <b>{{ number_format($paiement->entree?->reste,0,',','.') }} FCFA</b>
        </div>
        <div style="margin: 3px 40px;">
                <div style="max-width: 800px; margin:10px auto"><h5>Signature</h5></div>
            <div style="float: left" class="div-signature">
                CAISSE
            </div>
            <div style="float: right" class="div-signature">
                CLIENT
            </div>
        </div>
    </div>

    <div style="margin-top: 20px">
        <h6>{{ date_format($paiement->created_at,'d/m/Y  H:i:s') }}</h6>
    </div>

    <hr>

    <div class="card" style="font-size: 13px;">

        <div class="card-header" style="max-width: 700px; margin:10px auto;">
            <div class="header"><span style="font-weight: 900; font-size:2rem;"> {{$tenant->name}}</span></div>
            <div><span style="font-weight: 700; font-size:1.2rem;">{{$tenant->address}}</span></div>
            <div><span style="font-weight: 700; font-size:1rem;">{{$tenant->phone}}</span></div>
        </div>
        <h5 style="font-size: 1.5rem; font-weight:900">RECU DE PAIEMENT DU PRODUCTEUR</h5>
        <div class="card-body">
            Code : <b>{{ $paiement->name }}</b>
            <br>Producteur: <b>{{ $paiement->exploitant?->name }}- <small>{{ $paiement->exploitant?->phone }}</small></b></p>
            <br>Livraison : <b style="font-size: 12px">{{ $paiement->entree?->name }}/<small>{{ $paiement->entree?->created_at->format('d/m/Y H:i') }}/{{ $paiement->entree?->entrepot?->name }}/{{$paiement->entree?->quantity}}kg/{{$paiement->entree?->gamme?->name}}/{{ number_format($paiement->entree?->pu,0,',','.') }}/{{ number_format($paiement->entree?->montant,0,',','.') }}</small></b>

            <p style="font-size: 15px">
                 Montant du paiement : <b>{{ number_format($paiement->montant,0,',','.') }} FCFA</b>
            </p>
            <br>Payeur : <b>{{ $paiement->user?->name }} - {{ $paiement->user?->phone }}</b>
            <br>Reste après  paiement : <b>{{ number_format($paiement->entree?->reste,0,',','.') }} FCFA</b>
        </div>
        <div style="margin: 3px 40px;">
                <div style="max-width: 800px; margin:10px auto"><h5>Signature</h5></div>
            <div style="float: left" class="div-signature">
                CAISSE
            </div>
            <div style="float: right" class="div-signature">
                CLIENT
            </div>
        </div>
    </div>

    <div style="margin-top: 20px">
        <h6>{{ date_format($paiement->created_at,'d/m/Y  H:i:s') }}</h6>
    </div>


</body>
</html>

