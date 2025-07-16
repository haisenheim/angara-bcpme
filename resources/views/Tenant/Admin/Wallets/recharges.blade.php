@extends('../Layouts.tenant.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Agents de terrain</a></li>
       <li class="breadcrumb-item active" aria-current="page">Historique des recharges de wallets</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Recharges de Wallets</h5>
        <p class="lead">Historique de toutes les recharges de wallets des agents de terrains</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Agent</th>
                        <th>Opérateur</th>
                        <th>Montant</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td> <span><img class="rounded-circle" src="{{ $item->agent?->photo }}" width="20" alt=""></span>{{ $item->agent?->name }}</td>
                            <td> <img src="{{ $item->operateur?->photo }}" width="20" alt=""> {{ $item->operateur?->name }}</td>
                            <td>{{ number_format($item->montant,0,',','.') }}</td>

                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection
