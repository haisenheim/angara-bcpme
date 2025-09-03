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
            <table class="table table-sm table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>DATE</th>
                                    <th>PRODUCTEUR</th>
                                    <th>MODE PAIEMENT</th>
                                    <th>MONTANT</th>
                                    <th>CAISSE</th>
                                    <th>WALLET</th>
                                    <th>NUMERO CIBLE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paiements->reverse() as $pp)
                                    <tr>
                                        <td>{{ $pp->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $pp->membre?->name}}</td>
                                        <td>{{ $pp->mode?->name }}</td>
                                        <th>{{ number_format($pp->montant,0,',','.') }} FCFA</th>
                                        <td>{{ $pp->caisse?->name }}</td>
                                        <td>{{ $pp->wallet?->name }}</td>
                                        <td>{{ $pp->phone }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
        </div>
    </div>
@endsection
