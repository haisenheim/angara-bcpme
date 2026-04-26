@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Agences</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des Agences</li>
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
        <h5 class="page-title mb-0 mt-2">Agences</h5>
        <p class="lead">Liste de toutes les agences du pays</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Agence</th>
                        <th>Ville</th>
                        <th>DEPARTEMENT</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->ville?$item->ville->name:'-' }}</td>
                            <td>{{ $item->departement?$item->departement->name:'-' }}</td>
                            <td><span class="badge bg-{{ $item->status['color'] }}">{{ $item->status['name'] }}</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                       Actions
                                       <span class="vr"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.agences.show',$item->id) }}">Afficher</a></li>
                                        @if($item->active)
                                            <li><a class="dropdown-item" href="{{ route('admin.agence.disable',$item->id) }}">Verrouiller</a></li>
                                        @else
                                            <li><a class="dropdown-item" href="{{ route('admin.agence.enable',$item->id) }}">Activer</a></li>
                                        @endif
                                                                          
                                    </ul>
                                 </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvelle Agence</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.agences.store') }}" method="post">
                        @csrf
                            <div class="d-flex gap-2 flex-grow">
                                <div class=" w-75">
                                    <label for="">NOM</label>
                                    <input type="text" required name="name" placeholder="Saisir le nom de l'agence" class="form-control">
                                </div>
                                <div>
                                    <label for="">Ville</label>
                                    <select required name="ville_id" id="ville_id" class="form-control">
                                        <option value="">Selectionner une ville</option>
                                        @foreach($villes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="">Departement</label>
                                    <select required name="departement_id" id="departement_id" class="form-control">
                                        <option value="">Selectionner un departement</option>
                                        @foreach($departements as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <div class="mt-5">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <style>
        .form-group{
            margin-top: 1rem;
        }
    </style>
@endsection
