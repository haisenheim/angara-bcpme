@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Producteurs</a></li>
       <li class="breadcrumb-item"><a href="#">Contrats</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des contrats producteurs</li>
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
        <h5 class="page-title mb-0 mt-2">Contrats des producteurs</h5>
        <p class="lead">Liste de tous les contrats des producteurs </p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Producteur</th>
                        <th>Saison</th>
                        <th>Tonnage Prévisionnel</th>
                        <th>Tonnage Exécuté</th>
                        <th>Taux d'exécution</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($protocoles as $item)
                        <tr>
                            <td>
                                <div>
                                    <img class="image-25 image-circle" src="{{ $item->cooperative->photo }}" />
                                    <span style="vertical-align:middle">{{ $item->cooperative?$item->cooperative->name:'-' }}</span>
                                </div>
                            </td>
                            <td><a href="{{ route('admin.saisons.show',$item->saison_id) }}">{{ $item->saison?$item->saison->name:'-' }}</a></td>
                            <td>{{ number_format($item->quantity,0,',','.') }} tonne(s)</td>
                            <td>{{ number_format($item->qtyl,0,',','.') }} tonne(s)</td>
                            <td>{{ $item->percentage }}%</td>
                            <td><span class="badge bg-{{ $item->status['color']}}">{{ $item->status['name'] }}</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                       Actions
                                       <span class="vr"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                       <li><a class="dropdown-item" href="{{ route('admin.protocoles.show',$item->id) }}">Afficher</a></li>
                                                                          
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouveau contrat de producteur</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.protocoles.store') }}" method="post">
                        @csrf
                            <div class="">
                                <div class="form-group">
                                    <label for="">Producteur</label>
                                    <select required name="cooperative_id"  class="form-control">
                                        <option value="">Selectionner un producteur ...</option>
                                        @foreach($cooperatives as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Saison</label>
                                    <select required name="saison_id"  class="form-control">
                                        <option value="">Selectionner une saison ...</option>
                                        @foreach($saisons as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Tonnage attendu</label>
                                    <input required type="number" name="quantity" placeholder="Saisir la quantite de tonnes attendue" class="form-control">
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
        tbody td{
            vertical-align:middle;
        }
    </style>
@endsection
