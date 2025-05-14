@extends('Layouts.gestionnaire')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Entrepots</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Entrepot &numero; {{ $item->name }}</h5>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-12 h-100">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                       <tr>
                            <td>NOM</td>
                            <th class="fw-bold">{{ $item->name }}</th>
                       </tr>
                       <tr>
                            <td>Coord. GPS</td>
                            <th class="fs-6 fw-bold">{{ $item->latitude }}/{{ $item->longitude }}</th>
                       </tr>
                       <tr>
                            <td>STOCK</td>
                            <th class="fw-bold">{{ number_format($item->stock,0,',','.') }}</th>
                       </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-sm-12 h-100">
            <div class="card">
                <div class="card-body">
                <table id="entreesTable" class="table table-sm table-bordered">
                    <thead>
                        <tr class="fs-6 fw-bolder border">
                            <th>DATE</th>
                            <th>NUMERO</th>
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
                        @foreach($entrees as $e)
                        <tr class="fs-6 border">
                            <td>{{ $e->created_at->format('d/m/Y H:i')}}</td>
                            <td><a class="btn-link link-danger" href="{{ route('gestionnaire.cooperative.entrees.show',$e->token) }}">{{$e->name}}</a></td>
                            <td>{{ number_format($e->quantity,0,',','.')}}kg</td>
                            <td>{{number_format($e->pu,0,',','.')}}</td>
                            <td>{{ number_format($e->pu*$e->quantity,0,',','.')}}</td>
                            <td>{{ number_format($e->versements,0,',','.')}}</td>
                            <td>{{ number_format($e->reste,0,',','.')}}</td>
                            <td><a class="btn-link link-danger" href="{{ route('gestionnaire.members.show',$e->exploitant->token) }}">{{ $e->exploitant?->name}}</a></td>
                            <td>{{ $e->agent?->name}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
</div>
<script src="{{ asset('DataTables/datatables.min.js') }}"></script>
<script>
    let tes = new DataTable('#entreesTable');
</script>
@endsection
