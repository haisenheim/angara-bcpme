@extends('../Layouts.tenant.agent')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">MEMBRES</a></li>
       <li class="breadcrumb-item active" aria-current="page">Gestion du verger</li>
    </ol>
 </nav>
@endsection

@section('actions')
<div class="btn-group">
    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
       Actions
       <span class="vr"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('agent.verger.edit.scoring',$item->token) }}">Editer la grille de notation</a></li>
        <li><a class="dropdown-item" data-bs-target="#addCampagneModal" data-bs-toggle="modal" href="#">Editer une nouvelle campagne</a></li>
    </ul>
 </div>
@endsection


@section('content')
    <div class="row">
       <div class="col-md-4 col-sm-12">
        <div class="card h-100 mb-3">
            <div class="card-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>LIBELLE</td>
                            <th class="fw-bold border-start border-4 border-black">{{$item->name}}</th>
                        </tr>
                        <tr>
                            <td>ANNEE DE CULTURE</td>
                            <th class="fw-bold border-start border-4 border-black">{{$item->annee}} / {{$item->age}}ans</th>
                        </tr>
                        <tr>
                            <td>SUPERFICIE</td>
                            <th class="fw-bold border-start border-4 border-black">{{$item->size}}ha</th>
                        </tr>
                        <tr>
                            <td>NB. DE PIEDS/HA</td>
                            <th class="fw-bold border-start border-4 border-black">{{$item->nbph}}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
         </div>
       </div>
       <div class="col-md-8 col-sm-12 ps-2">
            <div class="">
                <div class="tab-base">
                    <ul class="nav nav-underline nav-component border-bottom justify-content-end" role="tablist">
                       @foreach($campagnes as $cmp)
                            @if($loop->index == 0)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab_{{$loop->index}}" type="button" role="tab" aria-controls="tab_{{$loop->index}}" aria-selected="true">{{ $cmp->saison->name }}</button>
                            </li>
                            @else
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab_{{$loop->index}}" type="button" role="tab" aria-controls="tab_{{$loop->index}}" aria-selected="false" tabindex="-1">{{ $cmp->saison->name }}</button>
                            </li>
                            @endif
                        @endforeach
                    </ul>


                    <!-- Tabs content -->
                    <div class="tab-content">
                        @foreach($campagnes as $cmp)
                        <div id="_tab_{{$loop->index}}" class="tab-pane fade {{ $loop->index==0?'show active':''}}" role="tabpanel" aria-labelledby="home-tab">
                            <div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-xs btn-dark dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                    <span class="vr"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item btn-cmp" data-id="{{ $cmp['id']}}" data-bs-target="#addTravailModal" data-bs-toggle="modal" href="#">Declarer un travail</a></li>
                                        <li><a class="dropdown-item btn-cmp" data-id="{{ $cmp['id']}}" data-bs-target="#addTraitementModal" data-bs-toggle="modal" href="#">Declarer un traitement</a></li>
                                        <li><a class="dropdown-item btn-cmp" data-id="{{ $cmp['id']}}" data-bs-target="#addVisiteModal" data-bs-toggle="modal" href="#">Declarer une visite</a></li>
                                         <li><a class="dropdown-item btn-cmp" data-id="{{ $cmp['id']}}" data-bs-target="#addRendementModal" data-bs-toggle="modal" href="#">Editer le rendement</a></li>
                                    </ul>
                                </div>
                            </div>
                            <p>NOMBRE DE PLANTS DE MORTS : <strong class="fw-bold">{{ $cmp['morts']}}</strong></p>
                            <p>NOMBRE DE PLANTS DE REPLANTATIONS : <strong class="fw-bold">{{ $cmp['replantations']}}</strong></p>
                            <p>RENDEMENT : <strong class="fw-bold">{{ $cmp['rendement']}} tonne(s)</strong></p>
                            <div class="hr mt-2"></div>
                            <div class="pt-2">
                                <ul class="nav nav-underline nav-component border-bottom justify-content-start" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab_etat" type="button" role="tab" aria-controls="tab_etat" aria-selected="true">ETAT DU VERGER</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab_obs" type="button" role="tab" aria-controls="tab_obs" aria-selected="false" tabindex="-1">OBSERVERVATIONS</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab_travaux" type="button" role="tab" aria-controls="tab_travaux" aria-selected="false" tabindex="-1">TRAVAUX</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab_traitements" type="button" role="tab" aria-controls="tab_traitements" aria-selected="false" tabindex="-1">TRAITEMENTS</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab_visites" type="button" role="tab" aria-controls="tab_visites" aria-selected="false" tabindex="-1">VISITES</button>
                                        </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="_tab_etat" class="tab-pane fade show active" role="tabpanel" aria-labelledby="home-tab">
                                        <p>
                                            <div class="rounded-2 bg-light p-4">
                                                <h6 class="fs-5">Etat global du verger</h6>
                                                {{ $cmp['etat'] }}
                                            </div>
                                        </p>
                                    </div>
                                    <div id="_tab_obs" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                        <p>
                                            <div class="rounded-2 bg-light p-4">
                                                <h6 class="fs-5">Observations</h6>
                                                {{ $cmp['observations'] }}
                                            </div>
                                        </p>
                                    </div>
                                    <div id="_tab_travaux" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>DATE</th>
                                                            <th>TYPE</th>
                                                            <th>METHODE</th>
                                                            <th>NOTES</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($cmp['travaux'] as $trv)
                                                            <tr>
                                                                <td>{{$trv['date']}}</td>
                                                                <td>{{$trv->type?->name}}</td>
                                                                <td>{{$trv['methode']}}</td>
                                                                <td class="fs-6">{{$trv['description']}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>
                                    <div id="_tab_traitements" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="p-4 table-responsive">
                                                <h6 class="fs-5">Traitements</h6>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="border-bottom border-3 border-black">
                                                            <th>DATE</th>
                                                            <th>PRODUIT</th>
                                                            <th>DOSAGE</th>
                                                            <th>NOTES</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($cmp['traitements'] as $trv)
                                                            <tr>
                                                                <td>{{$trv['date']}}</td>
                                                                <td>{{$trv['produit']}}</td>
                                                                <td>{{$trv['dosage']}}</td>
                                                                <td class="fs-6">{{$trv['description']}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>
                                    <div id="_tab_visites" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>DATE</th>
                                                        <th>LIBELLE</th>
                                                        <th>TECHNICIEN</th>
                                                        <th>PV</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($cmp['visites'] as $trv)
                                                        <tr>
                                                            <td>{{$trv['date']}}</td>
                                                            <td>{{$trv['name']}}</td>
                                                            <td>{{$trv['technicien']}}</td>
                                                            <td class="fs-6">{{$trv['description']}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                       @endforeach
                    </div>
                </div>
            </div>
       </div>
    </div>

    <hr>
    <div>
        <div style="width: 90%;" id="map"></div>
    </div>

    <div class="modal fade" id="addCampagneModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvelle campagne</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('agent.verger.campagne.add') }}" method="post">
                        @csrf
                        <input type="hidden" name="verger_id" value="{{ $item->id }}">
                        <div class="mt-3">
                            <label for="">NB. PLANTS MORTS</label>
                            <input type="number" name="morts" class="form-control">
                        </div>
                        <div class="mt-3">
                            <label for="">NB. REPLANTATIONS</label>
                            <input type="number" name="replantations" class="form-control">
                        </div>
                        <div class="mt-3">
                            <label for="">TAUX D'OMBRAGE</label>
                            <input type="text" name="ombrage" placeholder="exple : 76" class="form-control">
                        </div>
                        <div class="mt-3">
                            <label for="">ETAT GLOBAL DU VERGER</label>
                            <textarea type="number" name="etat" class="form-control"></textarea>
                        </div>
                        <div class="mt-3">
                            <label for="">AUTRES OBSERVATIONS</label>
                            <textarea type="number" name="observations" class="form-control"></textarea>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary p-2">ENREGISTRER</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade" id="addRendementModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title fs-5">Edition du rendement en tonnes</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('agent.campagne.rendement') }}" method="post">
                        @csrf
                        <input type="hidden" class="campagne_id" name="campagne_id" value="0">

                        <div class="">
                            <input type="text" required name="rendement" class="form-control">
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary p-2">ENREGISTRER</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade" id="addVisiteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Declaration d'une visite</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('agent.campagne.visite.add') }}" method="post">
                        @csrf
                        <input type="hidden" class="campagne_id" name="campagne_id" value="0">
                        <div class="">
                            <label for="">LIBELLE</label>
                            <input type="text" required name="name" class="form-control">
                        </div>
                        <div class="row">
                            <div class="mt-2 col-md-5 col-sm-12">
                                <label for="">DATE</label>
                                <input type="date" required name="jour" class="form-control">
                            </div>
                            <div class="mt-2 col-md-7 col-sm-12">
                                <label for="">TECHNICIEN</label>
                                <input type="text" required name="technicien" class="form-control">
                            </div>
                        </div>

                        <div class="mt-2">
                            <label for="">PROCES VERBAL</label>
                            <textarea name="description" required class="form-control"></textarea>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary p-2">ENREGISTRER</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addTravailModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Declaration d'une tache</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('agent.campagne.travail.add') }}" method="post">
                        @csrf
                        <input type="hidden" class="campagne_id" name="campagne_id" value="0">
                        <div class="">
                            <label for="">LIBELLE</label>
                            <input type="text" required name="name" class="form-control">
                        </div>
                        <div class="row">
                            <div class="mt-2 col-md-5 col-sm-12">
                            <label for="">DATE</label>
                            <input type="date" required name="jour" class="form-control">
                        </div>
                        <div class="mt-2 col-md-7 col-sm-12">
                            <label for="">TYPE</label>
                            <select name="type_id" required class="form-control" id="">
                                <option value="">Selectionner un type de tache ....</option>
                                @foreach($travaux as $trv)
                                    <option value="{{$trv->id}}">{{$trv->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                        <div class="mt-2">
                            <label for="">METHODE</label>
                            <input type="text" name="methode" class="form-control">
                        </div>
                        <div class="mt-2">
                            <label for="">AUTRES OBSERVATIONS</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary p-2">ENREGISTRER</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade" id="addTraitementModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Declaration d'un traitement</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('agent.campagne.traitement.add') }}" method="post">
                        @csrf
                        <input type="hidden" class="campagne_id" name="campagne_id" value="0">
                        <div class="">
                            <label for="">LIBELLE</label>
                            <input type="text" required name="name" class="form-control">
                        </div>
                        <div class="row">
                            <div class="mt-2 col-md-5 col-sm-12">
                            <label for="">DATE</label>
                            <input type="date" required name="jour" class="form-control">
                        </div>
                        <div class="mt-2 col-md-7 col-sm-12">
                            <label for="">PRODUIT</label>
                            <select name="produit_id" required class="form-control" id="">
                                <option value="">Selectionner un produit ....</option>
                                @foreach($produits as $trv)
                                    <option value="{{$trv->id}}">{{$trv->name}} ({{$trv->type?->name}})</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                        <div class="mt-2">
                            <label for="">DOSAGE</label>
                            <input type="text" name="dosage" class="form-control">
                        </div>
                        <div class="mt-2">
                            <label for="">AUTRES OBSERVATIONS</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary p-2">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        label{
            font-weight: 500;
            font-size: .75rem;
        }

        p{
            margin-top: 1rem;
        }
    </style>

    <script>
        $('.btn-cmp').click(function(){
            var id = $(this).data('id');
            $('.campagne_id').val(id);
        })
    </script>

        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        /* $(document).ready(function(){
                    const latitude = "{{ $item->latitude }}";
                    const longitude = "{{ $item->longitude }}";
                    console.log(latitude)
                     const map = L.map('map').setView([4.5, 12], 7); // Vue centrée sur le Cameroun
                    //const map = L.map('map').setView([latitude, longitude], 8);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
                        maxZoom: 18
                    }).addTo(map);

                    const markerGroup = L.featureGroup().addTo(map);
                    const marker = L.marker([verger.latitude, verger.longitude]).addTo(markerGroup);
                    marker.bindPopup(`
                        <strong>{{$item->name}}</strong><br>
                        Latitude : {{$item->latitude}}<br>
                        Longitude : {{$item->longitude}}<br>
                    `);

        }) */
    </script>

@endsection
