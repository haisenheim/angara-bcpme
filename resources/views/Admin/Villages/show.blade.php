@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Arrondissements</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $arrondissement->name }}</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $arrondissement->name }}</h5>
        <p class="lead">Details sur l'arrondissement</p>
    </div>
@endsection

@section('content')
    <div class="d-flex gap-1">
        <div class="card w-25">
            <div class="card-body">
                <h5>ARRONDISSEMENT : {{ $arrondissement->name }}</h5>
                <h6>ABBREVIATION : {{ $arrondissement->abb }}</h6>
            </div>
        </div>
        <div class="card w-75">
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cooperative</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($arrondissement->cooperatives as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->arrondissement?$item->arrondissement->name:'-' }}</td>
                            <td>{{ $item->departement?$item->departement->name:'-' }}</td>
                            <td>{{ $item->region?$item->region->name:'-' }}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
