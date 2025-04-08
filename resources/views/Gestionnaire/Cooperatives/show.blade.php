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
        <li><a class="dropdown-item" data-bs-target="#caisseModal" data-bs-toggle="modal" href="#">Créer une caisse</a></li>
        <li><a class="dropdown-item" data-bs-target="#walletModal" data-bs-toggle="modal" href="#">Créer un wallet</a></li>
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
                            <td>STOCK</td>
                            <th>{{ number_format(347.974,2,',','.') }} tonnes</th>
                        </tr>
                    </tbody>
                </table>
                <p><a href="{{ route('gestionnaire.entreprises.show',$item->entreprise?->token) }}">Cliquer ici pour voir son dossier d'entreprise</a></p>
            </div>
        </div>
        <div class="flex-fill">
            <div class="tab-base">
                <!-- Nav tabs -->
                <ul class="nav nav-underline nav-component border-bottom" role="tablist">
                   <li class="nav-item" role="presentation">
                      <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">MEMBRES</button>
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
                        <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab5" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">CAISSES</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab6" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">WALLETS</button>
                    </li>
                </ul>


                <!-- Tabs content -->
                <div class="tab-content">
                   <div id="_tab1" class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab">
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
                                @foreach($item->exploitants as $meb)
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
                                                <li><a class="dropdown-item" href="{{ route('gestionnaire.members.show',$meb->token) }}">Afficher</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                   </div>
                   <div id="_tab2" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="container d-flex gap-4">
                        @foreach ($item->agents as $agent)
                        <div class="card mb-3 w-250px">
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

                    </div>
                    <div id="_tab5" class="tab-pane fade " role="tabpanel" aria-labelledby="home-tab">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>LIBELLE</th>
                                    <th>Solde</th>
                                    <th>Statut</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->caisses as $caisse)
                                    <tr>
                                        <td>{{ $caisse->name }}</td>
                                        <td>{{ number_format($caisse->montant,0,',','.') }}</td>
                                        <td><span class="badge bg-{{ $caisse->status['color'] }}">{{ $caisse->status['name'] }}</span></td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="_tab6" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>NUMERO</th>
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
                                        <td>{{ $wlt->operateur->name }}</td>
                                        <td>{{ number_format($wlt->montant,0,',','.') }}</td>
                                        <td><span class="badge bg-{{ $wlt->status['color'] }}">{{ $wlt->status['name'] }}</span></td>
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
                        <div class="d-flex gap-2 flex-grow mt-3">
                            <input type="hidden" value="{{$item->id}}" name="cooperative_id">
                            <div class="flex-fill">
                                <label for="">Operateur</label>
                                <select required class="form-control" name="operateur_id" id="">
                                    <option value="">Selectionner un operateur ...</option>
                                    @foreach ($operateurs as $op)
                                        <option value="{{ $op->id }}">{{ $op->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class=" w-30">
                                <label for="">Montant initial</label>
                                <input required type="number" name="montant" placeholder="Montant initial" class="form-control">
                            </div>
                            <div class=" w-30">
                                <label for="">LIBELLE / TEL</label>
                                <input required type="text" name="name" placeholder="LIBELLE" class="form-control">
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
@endsection
