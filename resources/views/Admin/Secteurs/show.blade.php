@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="#">Secteurs cooperatifs</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="lead">Details</p>
    </div>
@endsection

@section('content')
    <div class="d-flex gap-1">
        <div class="card w-25">
            <div class="card-body">
                <h5>NOM : {{ $item->name }}</h5>
                <h6>LOCALISATION : {{ $item->localisation }}</h6>
                <p>{{$item->contacts}}</p>
                <p>AGENCE DE TUTUELLE : <strong>{{$item->agence?->name}}</strong></p>
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
                        @foreach($item->cooperatives as $it)
                        <tr>
                            <td>{{ $it->id }}</td>
                            <td><a href="{{ route('admin.cooperatives.show',$it->id) }}">{{ $it->name }}</a></td>
                            <td>{{ $it->phone }}</td>
                            <td>{{ $it->arrondissement?$item->arrondissement->name:'-' }}</td>
                            <td>{{ $it->departement?$item->departement->name:'-' }}</td>
                            <td>{{ $it->region?$item->region->name:'-' }}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
