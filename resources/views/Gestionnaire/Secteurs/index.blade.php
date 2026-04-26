@extends('Layouts.gestionnaire')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="#">Secteurs</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des secteurs</li>
    </ol>
 </nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="dropdown-item"><i class="demo-pli-add me-2 fs-5"></i> Ajouter</a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Secteurs</h5>
        <p class="lead">Liste de tous les secteurs cooperatifs</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Secteur</th>
                        <th>Agence</th>
                        <th>Contacts</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr class="">
                            <td><a class="btn-link" href="{{ route('gestionnaire.secteurs.show',$item->token) }}">{{ $item->name }}</a></td>
                            <td>{{ $item->agence?->name }}</td>
                            <td>{{ $item->contacts }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
