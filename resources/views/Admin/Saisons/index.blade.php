@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Parametres</a></li>
       <li class="breadcrumb-item"><a href="#">Saisons</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des saisons</li>
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
        <h5 class="page-title mb-0 mt-2">Saisons</h5>
        <p class="lead">Liste de toutes les saisons </p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Annee</th>
                        <th>Saison</th>
                        <th>Debut</th>
                        <th>Fin</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($saisons as $saison)
                        <tr><td>{{ $saison->year }}</td>
                            <td><a href="{{ route('admin.saisons.show',$saison->id) }}">{{ $saison->name }}</a></td>
                            <td>{{ $saison->start?\Carbon\Carbon::parse($saison->start)->format('d/m/Y'):'-' }}</td>
                            <td>{{ $saison->end?\Carbon\Carbon::parse($saison->end)->format('d/m/Y'):'-' }}</td>
                            <td><span class="badge bg-{{ $saison->status['color']}}">{{ $saison->status['name'] }}</span></td>
                            <td>
                                @if(!$saison->closed_at)
                                    <a href="{{ route('admin.saison.close',$saison->id) }}" class="btn btn-xs btn-danger">Cloturer</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvelle saison</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.saisons.store') }}" method="post">
                        @csrf
                            <div class="">
                                <div class="">
                                    <label for="">NOM</label>
                                    <input required type="text" name="name" placeholder="Saisir l'intitule de la saison" class="form-control">
                                </div>
                                <div class="">
                                    <label for="">ANNEE</label>
                                    <input required type="number" value="{{ date('Y') }}" name="year" class="form-control">
                                </div>
                                <div class="">
                                    <label for="">DEBUT</label>
                                    <input required type="date" name="start" class="form-control">
                                </div>
                                <div class="">
                                    <label for="">END</label>
                                    <input required type="date" name="end" class="form-control">
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
@endsection
