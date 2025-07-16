@extends('../Layouts.tenant.payeur')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Paiements</a></li>
       <li class="breadcrumb-item active" aria-current="page">Mes wallets</li>
    </ol>
 </nav>
@endsection


@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Mes Wallets</h5>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Libellé</th>
                        <th>Opérateur</th>
                        <th>Solde</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td> {{ $item->operateur?->name }}</td>
                            <td>{{ number_format($item->montant,0,',','.') }}</td>
                            <td><span class="badge bg-{{ $item->status['color'] }}">{{ $item->status['name'] }}</span></td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
