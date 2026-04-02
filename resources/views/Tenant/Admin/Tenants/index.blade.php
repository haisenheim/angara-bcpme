@extends('Layouts.tenant.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Structures</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des structures</li>
    </ol>
 </nav>
@endsection



@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Liste des structures de l'union</h5>
        <p class="lead">Liste des structures de l'union</p>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <table class="table table-sm table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Structure</th>
                            <th>Telephone</th>
                            <th>Arrondissement</th>
                            <th>Departement</th>
                            <th>Region</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td><a href="{{ route('admin.tenants.show',$item->token) }}">{{ $item->name }}</a></td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->arrondissement?$item->arrondissement->name:'-' }}</td>
                                <td>{{ $item->departement?$item->departement->name:'-' }}</td>
                                <td>{{ $item->region?$item->region->name:'-' }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
