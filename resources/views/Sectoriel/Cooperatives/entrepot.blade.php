@extends('Layouts.sectoriel')

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
                            <th class="fw-bold"></th>
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
                            <th></th>
                            <th>NUMERO</th>
                            <th>QUANTITE</th>
                            <th>PRIX UNITAIRE</th>
                            <th>TOTAL</th>
                            <th>PRODUCTEUR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entrees as $e)
                        <tr class="fs-6 border">
                            <td>{{$e['date']}}</td>
                            <td><a class="btn-link link-danger" href="{{ route('sectoriel.cooperative.entrees.show',$e['token']) }}">{{$e['name']}}</a></td>
                            <td>{{ number_format($e['quantity'],0,',','.')}}kg</td>
                            <td>{{number_format($e['pu'],0,',','.')}}</td>
                            <td>{{ number_format($e['total'],0,',','.')}}</td>
                            <td>{{$e['producteur']}}</td>
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
