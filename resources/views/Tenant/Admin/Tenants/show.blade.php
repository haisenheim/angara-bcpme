@extends('Layouts.tenant.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">EFFECTIFS</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection

@section('actions')
<div class="btn-group">
    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
    Actions
    <span class="vr"></span>
    </button>
    <ul class="dropdown-menu analyse">
        <li><a class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#addModal"  href="#">Créer un entrepot</a></li>
        <li><a class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#addMembreModal"  href="#">Créer un membre</a></li>
    </ul>
</div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Informations sur la coopérative</h5>
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td>Nom</td>
                                    <td><strong>{{ $item->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Téléphone</td>
                                    <td><strong>{{ $item->phone }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Adresse</td>
                                    <td><strong>{{ $item->address }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Localité</td>
                                    <td><strong>{{ $item->arrondissement?->name }} / {{ $item->departement?->name }} / {{ $item->region?->name }}</strong></td>
                                </tr>

                                <tr>
                                    <td>Entrepôts</td>
                                    <td><strong>{{ $item->entrepots->count() }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                       <div class="tabs">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Entrepôts</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="membre-tab" data-bs-toggle="tab" data-bs-target="#membre" type="button" role="tab" aria-controls="membre" aria-selected="false">Membres</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="entree-tab" data-bs-toggle="tab" data-bs-target="#entree" type="button" role="tab" aria-controls="entree" aria-selected="false">Entrées en stock</button>
                            </li>
                        </ul>
                       </div>
                       <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Stock en Kg</th>
                                            <th>Stock en tonnes</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->entrepots as $ent)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ number_format($ent->stock,0,',','.') }}</td>
                                                <td>{{ number_format($ent->stock/1000,2,',','.') }}</td>
                                                <td>{{ $ent->latitude }}</td>
                                                <td>{{ $ent->longitude }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="membre" role="tabpanel" aria-labelledby="membre-tab">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Age</th>
                                            <th>Téléphone</th>
                                            <th>Village</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($membres as $meb)
                                            <tr>
                                                <td><a href="{{ route('admin.members.show',$meb->token) }}">{{ $meb->name }}</a></td>
                                                <td>{{ $meb->age }} ans</td>
                                                <td>{{ $meb->phone }}</td>
                                                <td>{{ $meb->village?->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="entree" role="tabpanel" aria-labelledby="entree-tab">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Numéro</th>
                                            <th>Gamme</th>
                                            <th>Quantité</th>
                                            <th>Prix unitaire</th>
                                            <th>Total</th>
                                            <th>Producteur</th>
                                            <th>Entrepot</th>
                                            <th>Agent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->entrees as $entree)
                                            <tr>
                                                <td>{{ $entree->created_at->format('d/m/Y') }}</td>
                                                <td>{{ $entree->name }}</td>
                                                <td>{{ $entree->gamme?->name }}</td>
                                                <td>{{ $entree->quantity }}</td>
                                                <td>{{ $entree->pu }}</td>
                                                <td>{{ number_format($entree->montant,0,',','.') }}</td>
                                                <td>{{ $entree->exploitant?->name }}</td>
                                                <td>{{ $entree->entrepot?->name }}</td>
                                                <td>{{ $entree->agent?->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addMembreModal" tabindex="-1" aria-labelledby="addMembreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMembreModalLabel">Créer un membre</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.cooperative.membre') }}" method="post">
                        @csrf
                        <input type="hidden" name="tenant_id" value="{{ $item->id }}">
                        <fieldset>
                            <legend>Etat civil</legend>
                            <div class="d-flex gap-2 flex-grow">
                                <div class="w-50">
                                    <label for="">NOM</label>
                                    <input required type="text" name="last_name" class="form-control">
                                </div>
                                <div class="w-50">
                                    <label for="">PRENOM</label>
                                    <input required type="text" name="first_name" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-3 flex-grow">
                                <div class="w-25">
                                    <label for="">DATE DE NAISSANCE</label>
                                    <input required type="date" name="dtn" class="form-control">
                                </div>
                                <div class="w-50">
                                    <label for="">LIEU DE NAISSANCE</label>
                                    <input required type="text" name="lieu" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">PHOTO</label>
                                    <input type="file" name="photo" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-1 mt-4">
                                <div class="row flex-fill">
                                    <label class="col-sm-3 col-form-label">Genre</label>
                                    <div class="col-sm-9 pt-2">
                                       <div class="form-check form-check-inline">
                                          <input id="_dm-inlineRadio1" class="form-check-input" type="radio" name="male" value="1" checked="">
                                          <label for="_dm-inlineRadio1" class="form-check-label">Homme</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                          <input id="_dm-inlineRadio2" class="form-check-input" type="radio" name="male" value="0">
                                          <label for="_dm-inlineRadio2" class="form-check-label">Femme</label>
                                       </div>
                                    </div>
                                 </div>
                                <div class="w-25">
                                    <label for="">SITUATION MATRIMONIALE</label>
                                    <select required name="situation_matrimoniale" id="" class="form-control">
                                        <option value="">Choisir un statut ...</option>
                                        <option value="Celibataire">Celibataire</option>
                                        <option value="Veuf/Veuve">Veuf</option>
                                        <option value="Marie(e)">Marie(e)</option>
                                    </select>
                                </div>
                                <div class="w-25">
                                    <label for="">NOMBRE D'ENFANTS</label>
                                    <input type="number" name="nb_enfants" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="w-25">
                                    <label for="">DATE D'ADHESION</label>
                                    <input type="date" name="dt_adhesion" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">&numero; de CNI</label>
                                    <input type="text" name="cni" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">DATE D'EXPIRATION DE LA CNI</label>
                                    <input type="date" name="dt_expiration_cni" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">NIVEAU SCOLAIRE</label>
                                    <select required name="niveau_id" id="" class="form-control">
                                        <option value="">Selectionner ...</option>
                                        @foreach($niveaux as $niv)
                                            <option value="{{ $niv->id }}">{{ $niv->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mt-3">
                            <legend>AUTRES INFOS</legend>
                            <div class="d-flex gap-2">
                                <div class="w-25">
                                    <label for="">VILLAGE DE RESIDENCE</label>
                                    <select required name="village_id" id="" class="form-control">
                                        <option value="0">Selectionner ...</option>
                                        @foreach($villages as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-50">
                                    <label for="">&numero; de compte bancaire</label>
                                    <input type="text" name="compte_bancaire" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">TELEPHONE </label>
                                    <input type="text" name="phone" placeholder="Numero de telephone" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">Email </label>
                                    <input type="email" name="email" placeholder="Adresse electronique" class="form-control">
                                </div>
                            </div>
                        </fieldset>



                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Créer</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Créer un entrepot</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.cooperative.entrepot') }}" method="post">
                        @csrf
                        <input type="hidden" name="tenant_id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" name="latitude" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" name="longitude" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Créer</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection


