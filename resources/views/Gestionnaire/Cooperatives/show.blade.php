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
        @if(!$item->is_union)
            <li><a class="dropdown-item" data-bs-target="#caisseModal" data-bs-toggle="modal" href="#">Créer une caisse</a></li>
            <li><a class="dropdown-item" data-bs-target="#walletModal" data-bs-toggle="modal" href="#">Créer un wallet</a></li>
            <li><a class="dropdown-item" data-bs-target="#compteModal" data-bs-toggle="modal" href="#">Associer un compte bancaire</a></li>
        @endif
    </ul>
 </div>
@endsection

@section('content')
    <div class="d-flex gap-2">
        <div class="card w-300px">
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
                <p><a href="{{ route('gestionnaire.entreprises.show',$item->entreprise?->token) }}">Cliquer ici pour voir son dossier d'entreprise</a></p>
            </div>
        </div>
        <div class="flex-fill">

            <div class="card">
                <div class="card-body">
                    <div class="tab-base">
                        <!-- Nav tabs -->
                        <ul class="nav nav-underline nav-component border-bottom" role="tablist">
                           <li class="nav-item" role="presentation">
                              <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab_req" type="button" role="tab" aria-controls="tabreq" aria-selected="true">APPELS DE FONDS</button>
                           </li>
                          <li class="nav-item" role="presentation">
                              <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">MEMBRES</button>
                           </li>
                           <li class="nav-item" role="presentation">
                              <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">AGENTS</button>
                           </li>
                           <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab3" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">ENTREPOTS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab4" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">ENTREES EN STOCK</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab5" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">PAIEMENTS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab6" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">CAISSES</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab7" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">WALLETS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab8" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">COMPTES UTILISATEURS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab9" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">COMPTES BANCAIRES</button>
                            </li>
                        </ul>

                        <!-- Tabs content -->
                        <div class="tab-content">
                            <div id="_tab_req" class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab">
                                <table id="requestsTable" class="table table-sm">
                                    <thead>
                                        <tr class="fs-6 fw-bolder border">
                                            <th>DATE</th>
                                            <th>CAISSE</th>
                                            <th>WALLET</th>
                                            <th>MONTANT</th>
                                            <th>STATUS</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->requests as $it)
                                        <tr class="fs-6 border">
                                                <td class="border">{{ $it->created_at->format('d/m/Y à H:i') }}</td>
                                                <td>{{ $it->caisse?->name }}</td>
                                                <td><img src="{{ $it->wallet?->operateur?->photo }}" width="20" alt=""> {{ $it->wallet?->name }}</td>
                                                <td>{{ number_format($it->montant,0,',','.') }}</td>
                                                <td><span class="badge bg-{{ $it->status['color'] }}">{{ $it->status['name'] }}</span></td>
                                                <td class="border">
                                                    @if(!$it->cancelled_at && !$it->validated_at)
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2 fs-6 p-1" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                        <span class="vr"></span>
                                                        </button>
                                                        <ul class="dropdown-menu analyse">
                                                            <li><a class="dropdown-item v-btn"  data-token="{{ $it->token }}" data-bs-toggle="modal" data-bs-target="#validateModal"  href="#">Approuver</a></li>
                                                            <li><a class="dropdown-item c-btn"  data-token="{{ $it->token }}" data-bs-toggle="modal" data-bs-target="#cancelModal"  href="#">Rejeter</a></li>
                                                        </ul>
                                                    </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                           <div id="_tab1" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>NOM</th>
                                            <th>PRENOM</th>
                                            <th>AGE</th>
                                            <th>VILLAGE</th>
                                            <th>TELEPHONE</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->membres as $meb)
                                            <tr>
                                                <td>{{ $meb->last_name }}</td>
                                                <td>{{ $meb->first_name }}</td>
                                                <td>{{ $meb->age }} ans</td>
                                                <td>{{ $meb->village?->name }}</td>
                                                <td>{{ $meb->phone }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                        <span class="vr"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('gestionnaire.members.show',['token'=>$meb->token,'tenant_id'=>$item->token]) }}">Afficher</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                           </div>
                           <div id="_tab2" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="container flex-wrap d-flex gap-4">
                                @foreach ($data['agents'] as $agent)
                                <div class="card bg-light mb-3 w-250px">
                                    <div class="card-body">
                                       <!-- Profile picture and short information -->
                                       <div class="text-center position-relative hv-outline-parent hv-grow-parent">
                                          <div class="pt-2 pb-3">
                                             <img class="img-lg hv-oc hv-gc rounded-circle" src="{{ $agent->photo }}" alt="Profile Picture" loading="lazy">
                                          </div>
                                          <a href="#" class="h5 stretched-link btn-link">{{ $agent->name }}</a>
                                          <p class="text-body-secondary">{{ $agent->phone }}</p>
                                       </div>
                                       <!-- END : Profile picture and short information -->


                                       <!-- Social media buttons -->
                                       <div class="mt-4 pt-3 d-flex justify-content-around border-top">
                                          <div class="text-center">
                                             <h5 class="mb-0">1.345 XAF</h5>
                                             <small class="text-body-secondary">SOLDE</small>
                                          </div>
                                          <div class="text-center">
                                             <h5 class="mb-0">23k</h5>
                                             <small class="text-body-secondary">Collecte</small>
                                          </div>
                                          <div class="text-center">
                                             <h5 class="mb-0">34.000 XAF</h5>
                                             <small class="text-body-secondary">Paiements</small>
                                          </div>
                                       </div>
                                       <!-- END : Social media buttons -->


                                    </div>
                                 </div>
                                @endforeach
                            </div>
                           </div>
                           <div id="_tab3" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Intitule</th>
                                            <th>Stock en Kg</th>
                                            <th>Stock en tonnes</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->entrepots as $ent)
                                            <tr>
                                                <td><a href="{{ route('gestionnaire.entrepots.show',$ent->token) }}">{{ $ent->name }}</a></td>
                                                <td>{{ number_format($ent->stock,0,',','.') }}</td>
                                                <td>{{ number_format($ent->stock/1000,2,',','.') }}</td>
                                                <td>{{ $ent->latitude }}</td>
                                                <td>{{ $ent->longitude }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="_tab4" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                <div>
                                    <div>
                                        <button data-bs-toggle="modal" data-bs-target="#exportEntreesModal" class="btn btn-sm btn-light p-1"><i class="pli-file-excel text-success"></i> Exporter</button>
                                    </div>
                                    <table id="entreesTable" class="table table-sm table-bordered">
                                        <thead>
                                            <tr class="fs-6 fw-bolder border">
                                                <th>DATE</th>
                                                <th>NUMERO</th>
                                                <th>ENTREPOT</th>
                                                <th>QUANTITE</th>
                                                <th>PRIX UNITAIRE</th>
                                                <th>TOTAL</th>
                                                <th>MONTANT PAYE</th>
                                                <th>RESTE</th>
                                                <th>PRODUCTEUR</th>
                                                <th>AGENT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['entrees'] as $e)
                                            <tr class="fs-6 border">
                                                <td>{{ $e->created_at->format('d/m/Y H:i')}}</td>
                                                <td><a class="btn-link link-danger" href="{{ route('gestionnaire.cooperative.entrees.show',$e->token) }}">{{$e->name}}</a></td>
                                                <td><a class="btn-link link-dark" href="#">{{ $e->entrepot?->name}}</a></td>
                                                <td>{{ number_format($e->quantity,0,',','.')}}kg</td>
                                                <td>{{number_format($e->pu,0,',','.')}}</td>
                                                <td>{{ number_format($e->pu*$e->quantity,0,',','.')}}</td>
                                                <td>{{ number_format($e->versements,0,',','.')}}</td>
                                                <td>{{ number_format($e->reste,0,',','.')}}</td>
                                                <td><a class="btn-link link-danger" href="#">{{ $e->exploitant?->name}}</a></td>
                                                <td>{{ $e->agent?->name}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="_tab5" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                <div>
                                    <button data-bs-toggle="modal" data-bs-target="#exportPaiementsModal" class="btn btn-sm btn-light p-1"><i class="pli-file-excel text-success"></i> Exporter</button>
                                </div>
                            <table id="paiementsTable" class="table table-sm table-bordered">
                                    <thead>
                                        <tr class="fs-6 fw-bolder border">
                                            <th>DATE</th>
                                            <th>MONTANT</th>
                                            <th>MODE DE PAIEMENT</th>
                                            <th>SOURCE</th>
                                            <th>COMPTE CIBLE</th>
                                            <th>BENEFICIARE</th>
                                            <th>PAYEUR</th>
                                            <th>STOCK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['paiements'] as $p)
                                        <tr class="border fs-6">
                                            <td>{{$p->created_at->format('d/m/Y H:i')}}</td>
                                            <td>{{ number_format($p->montant,0,',','.')}}</td>
                                            <td>{{ $p->mode?$p->mode->name:'-' }}</td>
                                            @if($p->caisse)
                                            <td>{{ $p->caisse->name  }}</td>
                                            @elseif($p->wallet)
                                            <td>{{$p->wallet->name}}</td>
                                            @else
                                            <td>-</td>
                                            @endif
                                            <td>{{$p->compte}}</td>
                                            <td>{{$p->exploitant?->name}}</td>
                                            <td>{{$p->user?$p->user->name:'-'}}</td>
                                            <td><a class="link-danger btn-link" href="{{ route('gestionnaire.cooperative.entrees.show',$p->entree->token) }}">{{$p->entree->name}}</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="_tab6" class="tab-pane fade " role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>LIBELLE</th>
                                            <th>ENTREPOT</th>
                                            <th>SOLDE</th>
                                            <th>STATUT</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->caisses as $caisse)
                                            <tr>
                                                <td>{{ $caisse->name }}</td>
                                                <td>{{ $caisse->entrepot?->name }}</td>
                                                <td>{{ number_format($caisse->montant,0,',','.') }}</td>
                                                <td><span class="badge bg-{{ $caisse->status['color'] }}">{{ $caisse->status['name'] }}</span></td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="_tab7" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>NUMERO</th>
                                            <th>ENTREPOT</th>
                                            <th>OPERATEUR</th>
                                            <th>SOLDE</th>
                                            <th>STATUS</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->wallets as $wlt)
                                            <tr>
                                                <td>{{ $wlt->name }}</td>
                                                <td>{{ $caisse->entrepot?->name }}</td>
                                                <td>{{ $wlt->operateur->name }}</td>
                                                <td>{{ number_format($wlt->montant,0,',','.') }}</td>
                                                <td><span class="badge bg-{{ $wlt->status['color'] }}">{{ $wlt->status['name'] }}</span></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                        <span class="vr"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if ($wlt->active)
                                                                <li><a class="dropdown-item" href="{{ route('gestionnaire.wallet.disable',['tenant_id'=>$item->token,'wallet_id'=>$wlt->token]) }}" >Verrouiller</a></li>
                                                            @else
                                                                <li><a class="dropdown-item" href="{{ route('gestionnaire.wallet.enable',['tenant_id'=>$item->token,'wallet_id'=>$wlt->token]) }}" >Debloquer</a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="_tab8" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>NOM</th>
                                            <th>EMAIL</th>
                                            <th>ROLE</th>
                                            <th>ENTREPOT</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['users'] as $usr)
                                            <tr>
                                                <td>{{ $usr->name }}</td>
                                                <td>{{ $usr->email }}</td>
                                                <td>{{ $usr->role?->name }}</td>
                                                <td>{{ $usr->entrepot?->name }}</td>
                                                <td><span class="badge bg-{{ $usr->status['color'] }}">{{ $usr->status['name'] }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="_tab9" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>NUMERO DE COMPTE</th>
                                            <th>BANQUE</th>
                                            <th>SOLDE</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->comptes as $usr)
                                            <tr>
                                                <td>{{ $usr->name }}</td>
                                                <td>{{ $usr->banque?->name }}</td>
                                                <td>{{ number_format($usr->montant,0,',','.') }}</td>
                                                <td></td>
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
                                <select required class="form-control" name="entrepot_id" id="">
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
                                <select required class="form-control" name="entrepot_id" id="">
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
                            <input type="hidden" value="{{$item->id}}" name="tenant_id">
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
    let tps = new DataTable('#paiementsTable');

    let tes = new DataTable('#entreesTable');
     let treq = new DataTable('#requestsTable');
</script>
<script>
        $('#mode').change(function(){
            var id = $('#mode').val();
            if(id == 1){
                $('#wallet_id').prop('disabled',true);
                $('#caisse_id').prop('disabled',false);
                $('#phone').prop('disabled',true);
            }

            if(id == 2){
                $('#wallet_id').prop('disabled',false);
                $('#phone').prop('disabled',false);
                $('#caisse_id').prop('disabled',true);
            }
        })

    </script>
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
