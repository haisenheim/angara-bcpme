@extends('../Layouts.tenant.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Paiements</a></li>
       <li class="breadcrumb-item active" aria-current="page">Historique de tous les reglements</li>
    </ol>
 </nav>
@endsection


@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Historique des reglements</h5>
        <p class="lead">Historique de tous les reglements de la banque </p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>DATE DE PAIEMENT</th>
                        <th>BANQUE</th>
                        <th>ACHETEUR</th>
                        <th>FACTURE</th>
                        <th>QUANTITE</th>
                        <th>TOTAL FACTURE</th>
                        <th>MONTANT</th>
                        <th>COMPTE</th>
                        <th>JUSTIFICATIF</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $p)
                    <tr>
                        <td>{{ $p->created_at->format('d/m/Y H:i')  }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->day)->format('d/m/Y')  }}</td>
                        <td>{{ $p->banque->name  }}</td>
                        <td>{{ $p->item->client->name }}</td>
                        <td><a href="{{ route('admin.invoices.show',$p->item->token) }}">{{ $p->order->name }}</a></td>

                        <th>{{ $p->item->quantity }} tonnes</th>
                        <th>{{ number_format($p->item->total,0,',','.') }} FCFA</th>
                        <th>{{ number_format($p->montant,0,',','.') }} FCFA</th>
                        <td>{{ $p->compte }}</td>
                        <td><a href="{{ $p->justificatif }}">cliquer ici</a></td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
