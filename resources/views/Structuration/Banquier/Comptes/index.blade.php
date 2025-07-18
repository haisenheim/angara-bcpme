@extends('Layouts.structuration_gestionnaire')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Tableau de bord</a></li>
       <li class="breadcrumb-item active" aria-current="page">comptes</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Comptes de cooperatives</h5>

    </div>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Cooperative</th>
                            <th>Numero</th>
                            <th scope="col">Solde</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comptes as $compte)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $compte->tenant?->name }}</td>
                                <td class="p-2"><a class="btn-link" href="{{ route('structuration_gestionnaire.comptes.show', $compte->token) }}">{{ $compte->name }}</a></td>
                                <td>{{ number_format($compte->montant, 0,',','.') }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
