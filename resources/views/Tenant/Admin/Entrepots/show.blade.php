@extends('../Layouts.tenant.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">KeKa</a></li>
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
    <div class="d-flex gap-1">
        <div class="card w-25">
            <div class="card-body">
                <h5>ENTREPROT : {{ $item->name }}</h5>
                <h6>COOPERATIVE : {{ $item->cooperative?$item->cooperative->name:'-' }}</h6>
            </div>
        </div>
        <div class="card w-75">
            <div class="card-body">
                <table class="table table-sm table-bordered table-condensed table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Gamme</th>
                            <th>Producteur</th>
                            <th>Quantite</th>
                            <th>Prix unitaire</th>
                            <th>Total</th>
                            <th>Total versement</th>
                            <th>Total reste</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entrees as $it)
                            <tr>
                                <td>{{ $it->created_at->format('d/m/Y H:i')}}</td>
                                <td>{{ $it->gamme?->name}}</td>
                                <td>{{ $it->exploitant?->name}}</td>
                                <td>{{ number_format($it->quantity,0,',','.') }}</td>
                                <td>{{ number_format($it->pu,0,',','.') }}</td>
                                <td>{{ number_format($it->pu*$it->quantity,0,',','.') }}</td>
                                <td>{{ number_format($it->versements,0,',','.') }}</td>
                                <td>{{ number_format($it->reste,0,',','.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
