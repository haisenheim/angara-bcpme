@extends('../Layouts.tenant.agent')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="#">Entreprots</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="lead">Details sur l'entrepot</p>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="card col-md-4 col-sm-12">
            <div class="card-body">
                <h5>ENTREPROT : {{ $item->name }}</h5>
                <p>STOCK : <strong>{{ $item->stock }} kg</strong> / <strong>{{ number_format($item->stock/1000,2,',','.') }} tonnes</strong> </p>
            </div>
        </div>
        <div class="col-md-8 col-sm-12 ps-2">
            <div class="card">
                <div class="card-body table-responsive">
                    <h6>Historique des entres en stock</h6>
                    <table class="table table-sm table-bordered table-condensed table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Quantite</th>
                                <th>Gamme</th>
                                <th>Producteur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entrees as $it)
                                <tr>
                                    <td>{{ $it->created_at->format('d/m/Y H:i')}}</td>
                                    <td>{{ number_format($it->quantity,0,',','.') }}</td>
                                    <td>{{ $it->gamme?->name}}</td>
                                    <td>{{ $it->exploitant?->name}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-sm>tbody>tr>td{
            font-size: 0.9rem;
            font-weight: 400;
            padding: 3px 5px;
        }
    </style>

@endsection
