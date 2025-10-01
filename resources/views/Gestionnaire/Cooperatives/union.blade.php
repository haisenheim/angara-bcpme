@extends('Layouts.gestionnaire')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Cooperatives</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection


@section('page-header')
    <div>
        <input type="hidden" value="{{$item->token}}" id="token">
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="lead">Données de la copérative</p>
    </div>
@endsection

@section('actions')
<div class="btn-group">
    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
       Actions
       <span class="vr"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" data-bs-target="#addModal" data-bs-toggle="modal" href="#">Insérer une cooperative</a></li>
        <li><a class="dropdown-item" data-bs-target="#caisseModal" data-bs-toggle="modal" href="#">Créer une caisse</a></li>
        <li><a class="dropdown-item" data-bs-target="#walletModal" data-bs-toggle="modal" href="#">Créer un wallet</a></li>
        <li><a class="dropdown-item" data-bs-target="#compteModal" data-bs-toggle="modal" href="#">Associer un compte bancaire</a></li>
    </ul>
 </div>
@endsection

@section('content')
    <div class="row">
        <div class="card col-md-3">
            <div class="card-body">
                <div class="d-flex align-items-center position-relative py-3 hv-outline-parent hv-outline-inherit">
                    <div class="flex-shrink-0">
                       <img class="img-md rounded-circle hv-oc" src="{{ $item->photo }}" alt="Profile Picture" loading="lazy">
                    </div>

                 </div>
                 @if($item->is_union)
                 <span class="badge bg-danger">Union</span>
                 @else
                 <span class="badge bg-light">Cooperative</span>
                 @endif
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td>NOM</td>
                            <th>{{ $item->name }}</th>
                        </tr>
                        <tr>
                            <td>FILIERE</td>
                            <th>{{ $item->domaine?->name }}</th>
                        </tr>
                        <tr>
                            <td>TELEPHONE</td>
                            <th>{{ $item->phone }}</th>
                        </tr>
                        <tr>
                            <td>Adresse</td>
                            <th>{{ $item->address }}</th>
                        </tr>
                        <tr>
                            <td>Localite</td>
                            <th>{{ $item->arrondissement?->name }} / {{ $item->departement?->name }}/{{ $item->region?->name }}</th>
                        </tr>
                        <tr>
                            <td>Secteur</td>
                            <th>{{ $item->secteur?->name }}</th>
                        </tr>
                        <tr>
                            <td>STOCK</td>
                            <th>{{ number_format(347.974,2,',','.') }} tonnes</th>
                        </tr>
                        <tr>
                            <td>Lien</td>
                            <th><a target="_blank" href="https://{{ $item->domains[0]->domain }}">{{ $item->domains[0]->domain }}</a></th>
                        </tr>
                    </tbody>
                </table>
                <fieldset>
                    <legend>Comptes utilisateurs</legend>
                    <lu class="list-group">
                        @foreach ($data['users'] as $user)
                            <li class="list-group-item fs-6">
                                <strong class="fs-5">{{$user->name}}</strong>
                                <p><small>{{$user->role?->name}}</small></p>
                                <p><small>{{$user->email}}</small></p>
                            </li>
                        @endforeach
                    </lu>
                </fieldset>
                <p><a href="{{ route('gestionnaire.entreprises.show',$item->entreprise?->token) }}">Cliquer ici pour voir son dossier d'entreprise</a></p>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="tab-base">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Cooperatives membres</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab-2" data-bs-toggle="tab" data-bs-target="#home2" type="button" role="tab" aria-controls="home2" aria-selected="true">Entrepots</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab-3" data-bs-toggle="tab" data-bs-target="#home3" type="button" role="tab" aria-controls="home3" aria-selected="true">Caisses</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab-4" data-bs-toggle="tab" data-bs-target="#home4" type="button" role="tab" aria-controls="home4" aria-selected="true">Wallets</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab-5" data-bs-toggle="tab" data-bs-target="#home5" type="button" role="tab" aria-controls="home5" aria-selected="true">Comptes bancaires</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab-6" data-bs-toggle="tab" data-bs-target="#home6" type="button" role="tab" aria-controls="home6" aria-selected="true">Appels de fonds</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab-7" data-bs-toggle="tab" data-bs-target="#home7" type="button" role="tab" aria-controls="home7" aria-selected="true">Paiements</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab-8" data-bs-toggle="tab" data-bs-target="#home8" type="button" role="tab" aria-controls="home8" aria-selected="true">Transferts de stocks</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>NOM</th>
                                            <th>TELEPHONE</th>
                                            <th>ADRESSE</th>
                                            <th>LOCALITE</th>
                                            <th>FILIERE</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->children as $child)
                                        <tr>
                                            <td>{{ $child->name }}</td>
                                            <td>{{ $child->phone }}</td>
                                            <td>{{ $child->address }}</td>
                                            <td>{{ $child->arrondissement?->name }}</td>
                                            <td>{{ $child->domaine?->name }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                    <span class="vr"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('gestionnaire.cooperatives.show',$child->token) }}">Afficher</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="home2" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>NOM</th>
                                            <th>STOCK EN KG</th>
                                            <th>STOCK EN TONNES</th>
                                            <th>LATITUDE</th>
                                            <th>LONGITUDE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->entrepots as $entrepot)
                                        <tr>
                                            <td>{{ $entrepot->name }}</td>
                                            <td>{{ number_format($entrepot->stock,0,',','.') }}</td>
                                            <td>{{ number_format($entrepot->stock/1000,2,',','.') }}</td>
                                            <td>{{ $entrepot->latitude }}</td>
                                            <td>{{ $entrepot->longitude }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="home3" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>NOM</th>
                                            <th>SOLDE</th>
                                            <th>ENTREPOT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->caisses as $caisse)
                                        <tr>
                                            <td>{{ $caisse->name }}</td>
                                            <td>{{ number_format($caisse->montant,0,',','.') }}</td>
                                            <td>{{ $caisse->entrepot?->name }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="home4" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>NOM</th>
                                            <th>OPERATEUR</th>
                                            <th>SOLDE</th>
                                            <th>ENTREPOT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->wallets as $wallet)
                                        <tr>
                                            <td>{{ $wallet->name }}</td>
                                            <td>{{ $wallet->operateur?->name }}</td>
                                            <td>{{ number_format($wallet->montant,0,',','.') }}</td>
                                            <td>{{ $wallet->entrepot?->name }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="home5" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>NOM</th>
                                            <th>BANQUE</th>
                                            <th>COMPTE</th>
                                            <th>SOLDE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->comptes as $compte)
                                        <tr>
                                            <td>{{ $compte->name }}</td>
                                            <td>{{ $compte->banque?->name }}</td>
                                            <td>{{ $compte->compte }}</td>
                                            <td>{{ number_format($compte->montant,0,',','.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="home6" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>MONTANT</th>
                                            <th>CAISSE</th>
                                            <th>WALLET</th>
                                            <th>STATUT</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->requests as $appel)
                                        <tr>
                                            <td>{{ $appel->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ number_format($appel->montant,0,',','.') }}</td>
                                            <td>{{ $appel->caisse?->name }}</td>
                                            <td>{{ $appel->wallet?->name }}</td>
                                            <td><span class="badge bg-{{ $appel->status['color'] }}">{{ $appel->status['name'] }}</span></td>
                                            <td class="border">
                                                @if(!$appel->cancelled_at && !$appel->validated_at)
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2 fs-6 p-1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                    <span class="vr"></span>
                                                    </button>
                                                    <ul class="dropdown-menu analyse">
                                                        <li><a class="dropdown-item v-btn"  data-token="{{ $appel->token }}" data-bs-toggle="modal" data-bs-target="#validateModal"  href="#">Approuver</a></li>
                                                        <li><a class="dropdown-item c-btn"  data-token="{{ $appel->token }}" data-bs-toggle="modal" data-bs-target="#cancelModal"  href="#">Rejeter</a></li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="home7" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>MONTANT</th>
                                            <th>MODE DE PAIEMENT</th>
                                            <th>SOURCE</th>
                                            <th>CIBLE</th>
                                            <th>BENEFICIARE</th>
                                            <th>PAYEUR</th>
                                            <th>STOCK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['paiements'] as $paiement)
                                        <tr>
                                                <td>{{ $paiement->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ number_format($paiement->montant,0,',','.') }}</td>
                                                <td>{{ $paiement->mode?->name }}</td>
                                                <td>{{ $paiement->source?->name }}</td>
                                                <td>{{ $paiement->cible?->name }}</td>
                                                <td>{{ $paiement->beneficiaire?->name }}</td>
                                                <td>{{ $paiement->payeur?->name }}</td>
                                                <td>{{ $paiement->stock?->name }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="home8" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>COOPERATIVE SOURCE</th>
                                            <th>ENTREPOT SOURCE</th>
                                            <th>ENTREPOT CIBLE</th>
                                            <th>QUANTITE</th>
                                            <th>GAMME</th>
                                            <th>VEHICULE</th>
                                            <th>RESPONSABLE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transferts as $transfert)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($transfert->day)->format('d/m/Y') }}</td>
                                            <td>{{ $transfert->cooperativeSource?->name }}</td>
                                            <td>{{ $transfert->source?->name }}</td>
                                            <td>{{ $transfert->target?->name }}</td>
                                            <td>{{ number_format($transfert->quantity,0,',','.') }}</td>
                                            <td>{{ $transfert->gamme?->name }}</td>
                                            <td>{{ $transfert->vehicule }}</td>
                                            <td>{{ $transfert->responsable }}</td>
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
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvelle Cooperative</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('gestionnaire.cooperatives.store') }}" method="post">
                        @csrf
                        <input type="hidden" id="arr_id" name="arrondissement_id">
                        <input type="hidden" name="union_id" value="{{ $item->id }}">
                        <fieldset>
                            <legend>Infos de l'organisation</legend>
                            <div class="d-flex gap-2 flex-grow">
                                <div class=" w-50">
                                    <label for="">NOM</label>
                                    <input type="text" name="name" placeholder="Saisir le nom de la cooperative" class="form-control">
                                </div>
                                <div class="">
                                    <label for="">Logo/Photo</label>
                                    <input required type="file" name="photo" class="form-control">
                                </div>
                                <div class="w-30">
                                    <label for="">Telephone</label>
                                    <input required type="text" name="phone" placeholder="Numero de telephone de la cooperative" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="">Adresse physique</label>
                                    <input required type="text" name="address" placeholder="Adresse physque de la cooperative" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-grow mt-3">
                                <div class="flex-fill">
                                    <label for="">FILIERE</label>
                                    <select required class="form-select" name="domaine_id" id="">
                                        <option value="">Choisir ...</option>
                                        @foreach ($domaines as $domaine)
                                            <option value="{{ $domaine->id }}">{{ $domaine->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-fill">
                                    <label for="">SECTEUR COOPERATIF</label>
                                    <select required class="form-select" name="secteur_id" id="">
                                        <option value="">Choisir ...</option>
                                        @foreach ($secteurs as $secteur)
                                            <option value="{{ $secteur->id }}">{{ $secteur->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="">
                                    <div style="max-width: 200px" class="form-group">
                                        <select id="arrondissement_id" name="arrondissement_id" class="easyui-combotree form-control" style="max-width:200px;"
                                            data-options="url:'{{ route('util.localites') }}',method:'get',label:'Commune:',labelPosition:'top'">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="mt-5">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="caisseModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvelle caisse</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('gestionnaire.cooperative.caisse.add') }}" method="post">
                        @csrf
                        <div class="d-flex gap-2 flex-grow mt-3">
                            <input type="hidden" value="{{$item->id}}" name="cooperative_id">
                            <div class=" w-30">
                                <label for="">LIBELLE</label>
                                <input required type="text" name="name" placeholder="LIBELLE" class="form-control">
                            </div>
                            <div class="flex-fill">
                                <label for="">ENTREPOT</label>
                                <select {{$item->is_union?'':'required'}} class="form-control" name="entrepot_id" id="">
                                    <option value="">Selectionner un entrepot ...</option>
                                    @foreach ($item->entrepots as $op)
                                        <option value="{{ $op->id }}">{{ $op->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-30">
                                <label for="">Montant initial</label>
                                <input required type="number" name="montant" placeholder="Montant initial" class="form-control">
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="walletModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouveau wallet</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('gestionnaire.wallets.store') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{$item->id}}" name="cooperative_id">
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="">TYPE / OPERATEUR</label>
                                <select required class="form-control" name="operateur_id" id="">
                                    <option value="">Selectionner un operateur ...</option>
                                    @foreach ($operateurs as $op)
                                        <option value="{{ $op->id }}">{{ $op->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="">ENTREPOT</label>
                                <select {{$item->is_union?'':'required'}} class="form-control" name="entrepot_id" id="">
                                    <option value="">Selectionner un entrepot ...</option>
                                    @foreach ($item->entrepots as $op)
                                        <option value="{{ $op->id }}">{{ $op->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="">Solde initial</label>
                                <input required type="number" name="montant" placeholder="Montant initial" class="form-control">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label for="">LIBELLE / TEL</label>
                                <input required type="text" name="name" placeholder="LIBELLE" class="form-control">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label for="">CLE D'API</label>
                                <input required type="text" name="api_key" placeholder="Saisir la cle d'api" class="form-control">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label for="">CLE SECRETE</label>
                                <input required type="password" name="api_secret" placeholder="Saisir la cle secrete" class="form-control">
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

    <div class="modal fade" id="exportPaiementsModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Exporter l'historique des paiements</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('gestionnaire.cooperative.paiements.export') }}" method="post">
                        @csrf
                        <div class="">
                            <input type="hidden" value="{{$item->token}}" name="token">
                            <fieldset>
                                <legend>Periode</legend>
                                <div class="d-flex gap-2">
                                    <div class="flex-fill">
                                    <label for="">DEBUT</label>
                                    <input required type="date" name="from"  class="form-control">
                                </div>
                                <div class="flex-fill">
                                    <label for="">FIN</label>
                                    <input required type="date" name="to"  class="form-control">
                                </div>
                                </div>
                            </fieldset>
                            <div class="mt-3">
                                <label for="">Mode de paiement</label>
                                <select required id="mode" class="form-control"  name="mode_id">
                                    <option value="">Tout mode de paiement</option>
                                    <option value="1">CAISSE</option>
                                    <option value="2">WALLET</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="">CAISSE</label>
                                <select name="caisse_id" id="caisse_id" class="form-control">
                                    <option value="0">Toutes les caisses ...</option>
                                    @foreach($item->caisses as $mbr)
                                        <option value="{{ $mbr->id }}">{{ $mbr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <label for="">WALLET</label>
                                <select name="wallet_id" id="wallet_id" class="form-control">
                                    <option value="0">Tous les wallets ...</option>
                                    @foreach($item->wallets as $mbr)
                                        <option value="{{ $mbr->id }}">{{ $mbr->name }}-{{ $mbr->operateur?->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn-success btn btn-sm p-1">EXPORTER</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade" id="compteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header justify-content-between">
                        <h5 class="modal-title">Associer un compte bancaire</h5>
                        <div style="float: right">
                            <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('gestionnaire.cooperative.comptes.add') }}" method="post">
                            @csrf
                            <div class="">
                                <input type="hidden" name="tenant_id" value="{{$item->id}}" name="token">

                                <div class="mt-3">
                                    <label for="">BANQUE</label>
                                    <select required name="banque_id" id="banque_id" class="form-control">
                                        <option >Selectionner une banque ...</option>
                                        @foreach($banques as $mbr)
                                            <option value="{{ $mbr->id }}">{{ $mbr->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="">NUMERO DE COMPTE</label>
                                    <input required type="text" name="name" placeholder="Numero de compte" class="form-control">
                                </div>
                                <div class="mt-3">
                                    <label for="">Solde initial</label>
                                    <input required type="number" name="montant" placeholder="Solde initial" class="form-control">
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn-success btn btn-sm p-1">ENREGISTRER</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>


    <div class="modal fade" id="exportEntreesModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Exporter l'historique des entrées en stock</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('gestionnaire.cooperative.entrees.export') }}" method="post">
                        @csrf
                        <div class="">
                           <input type="hidden" value="{{$item->token}}" name="token">
                            <fieldset>
                                <legend>Période</legend>
                                <div class="d-flex gap-2">
                                    <div class="flex-fill">
                                    <label for="">DEBUT</label>
                                    <input required type="date" name="from"  class="form-control">
                                </div>
                                <div class="flex-fill">
                                    <label for="">FIN</label>
                                    <input required type="date" name="to"  class="form-control">
                                </div>
                                </div>
                            </fieldset>

                            <div class="mt-3">
                                <label for="">ENTREPOT</label>
                                <select name="entrepot_id" class="form-control">
                                    <option value="0">Tous les entrepots ...</option>
                                    @foreach($item->entrepots as $mbr)
                                        <option value="{{ $mbr->id }}">{{ $mbr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn-success btn btn-sm p-1">EXPORTER</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="validateModal">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Confirmation !</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('gestionnaire.request.validate') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" id="v-token" value="">
                        <input type="hidden" name="tenant_id" value="{{ $item->token}}">
                        <h5>Attention cette operation est irreversible</h5>
                        <p class="fs-6">Voulez-vous vraiment approuver cette demande ?</p>
                        <p class="fs-6">Cette action validera la demande et l'enregistrera dans le systeeme.</p>
                        <div class="mt-3">
                            <label for="">Choix du compte</label>
                            <select name="compte_id" id="compte_id" required class="form-control">
                                <option value="">Veuillez selectionner un compte ...</option>
                                @foreach($item->comptes as $mbr)
                                    <option value="{{ $mbr->id }}">{{ $mbr->name }} - {{ $mbr->banque?->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn-success btn-sm btn">Approuver</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelModal">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Confirmation !</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('gestionnaire.request.cancel') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" id="c-token" value="">
                        <input type="hidden" name="tenant_id" value="{{ $item->token}}">
                        <p>Attention cette operation est irreversible</p>
                        <div class="mt-5">
                            <button type="submit" class="btn-danger btn-sm btn">Rejeter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script src="{{ asset('DataTables/datatables.min.js') }}"></script>
<script>
    //let tps = new DataTable('#paiementsTable');

  //  let tes = new DataTable('#entreesTable');
    // let treq = new DataTable('#requestsTable');
</script>
    <link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/default/easyui.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/icon.css') }}">
    <script type="text/javascript" src="{{ asset('jquery-easyui/jquery.easyui.min.js') }}"></script>

    <script>
        $('li>a.v-btn').click(function(){
            var token = $(this).data('token');
            $('#v-token').val(token);
            console.log(token);
        })
        $('li>a.c-btn').click(function(){
            var token = $(this).data('token');
            $('#c-token').val(token);
        })
    </script>

<style>
    label{
        font-size: 11px;
        font-weight: 700;
    }

</style>
@endsection
