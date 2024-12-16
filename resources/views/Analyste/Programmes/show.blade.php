@extends('Layouts.analyste')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Programmes</a></li>
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
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" data-bs-target="#addModal" data-bs-toggle="modal" href="#">Ajouter une composante</a></li>
        <li><a class="dropdown-item" data-bs-target="#addIndModal" data-bs-toggle="modal" href="#">Ajouter un objectif</a></li>
        <li><a class="dropdown-item" href="#">Editer des informations du programme</a></li>
    </ul>
 </div>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="lead">Dossier du programme</p>
    </div>
@endsection

@section('content')

    <div class="d-flex gap-2">
        <div style="height: 80vh; overflow: scroll;" class="card w-400px">
            <div class="card-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>Designation</td>
                            <th>{{ $item->name }}</th>
                        </tr>
                        <tr>
                            <td>&numero; Référence de la Convention cadre du programme </td>
                            <th>{{ $item->convention}}</th>
                        </tr>
                        <tr>
                            <td>Date de signature de la convention cadre </td>
                            <th>{{ \Carbon\Carbon::parse($item->dt_sig_conv)->format('d/m/Y')}}</th>
                        </tr>
                        <tr>
                            <td>Institution signataire </td>
                            <th>{{ $item->signataire }}</th>
                        </tr>
                        <tr>
                            <td>Budget des appuis financiers</td>
                            <th>{{ number_format($item->budget_af,0,',','.') }} XAF</th>
                        </tr>
                        <tr>
                            <td>Budget des appuis non financiers</td>
                            <th>{{ number_format($item->budget_anf,0,',','.') }} XAF</th>
                        </tr>
                        <tr>
                            <td>Budget de la coordination</td>
                            <th>{{ number_format($item->budget_coord,0,',','.') }} XAF</th>
                        </tr>
                        <tr>
                            <td>Budget total</td>
                            <th>{{ number_format($item->budget,0,',','.') }} XAF</th>
                        </tr>
                        <tr>
                            <td>Bénéficiaires cibles personnes morales</td>
                            <th>{{ $item->type_pm }}</th>
                        </tr>
                        <tr>
                            <td>Bénéficiaires cibles personnes physiques</td>
                            <th>{{ $item->type_pp }}</th>
                        </tr>
                        <tr>
                            <td>Date début des activités </td>
                            <th>{{ \Carbon\Carbon::parse($item->dt_start)->format('d/m/Y') }}</th>
                        </tr>
                        <tr>
                            <td>Personnes ressources et contact du programme</td>
                            <th>{{ $item->contact }}</th>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="card flex-fill">
            <div class="card-body">
                <div class="tab-base">
                    <!-- Nav tabs -->
                    <ul class="nav nav-underline nav-component border-bottom" role="tablist">
                       <li class="nav-item" role="presentation">
                          <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">Secteurs cibles</button>
                       </li>
                       <li class="nav-item" role="presentation">
                          <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">Appuis proposés</button>
                       </li>
                       <li class="nav-item" role="presentation">
                          <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false" tabindex="-1">Les composantes</button>
                       </li>
                       <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab4" type="button" role="tab" aria-controls="tab4" aria-selected="false" tabindex="-1">Resultats attendus</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab5" type="button" role="tab" aria-controls="tab5" aria-selected="false" tabindex="-1">Entreprises</button>
                         </li>
                    </ul>


                    <!-- Tabs content -->
                    <div class="tab-content">
                       <div id="_tab1" class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab">

                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Filiere</th>
                                        <th>Branche</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->produits as $p)
                                        <tr>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->filiere?->name }}</td>
                                            <td>{{ $p->branche?->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                       </div>
                       <div id="_tab2" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-tab">

                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Type</th>
                                        <th>Nature</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->appuis as $service)
                                        <tr>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ $service->type?->name }}</td>
                                            <td>{{ $service->financier?'Financier':'Non financier' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                       </div>
                       <div id="_tab3" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Entité</th>
                                    <th>Nature</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->composantes as $cmp)
                                    <tr>
                                        <td>{{ $cmp->name }}</td>
                                        <td>{{ $cmp->type }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                       </div>
                       <div id="_tab4" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">

                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Indicateur</th>
                                    <th>Attentes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->resultats as $cmp)
                                    <tr>
                                        <td>{{ $cmp->indicateur?->name }}</td>
                                        <td>{{ $cmp->attente }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                       </div>
                       <div id="_tab5" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Entreprise</th>
                                    <th>Localité</th>
                                    <th>Taille</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->entreprises as $ent)
                                    <tr>
                                        <td>{{ $ent->name }}</td>
                                        <td>{{ $ent->localite }}</td>
                                        <td>{{ $ent->taille }}</td>
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

    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvelle composante</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('analyste.programme.composante.save') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" value="{{ $item->token }}">
                        <input type="hidden" name="programme_id" value="{{ $item->id }}">
                            <div class="">
                                <div class="mb-3">
                                    <label for="">Nom </label>
                                    <input type="text" name="name"  id="name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Organisme</label>
                                    <select  name="organisme_id" id="organisme_id" class="form-control cmp">
                                        <option value="0">Selectionner un bailleur de fonds ...</option>
                                        @foreach($organismes as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Banque</label>
                                    <select  name="banque_id" id="banque_id"  class="form-control cmp">
                                        <option value="0">Selectionner une banque ...</option>
                                        @foreach($banques as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label class="col-form-label">Axe d'intervention </label>
                                    <div class="">
                                       <div class="form-check form-check-inline">
                                          <input id="_dm-inlineRadio2" checked class="form-check-input" type="radio" name="type" value="Coordination">
                                          <label for="_dm-inlineRadio2" class="form-check-label">Coordination</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                          <input id="_dm-inlineRadio3" class="form-check-input" type="radio" name="type" value="Appuis financiers">
                                          <label for="_dm-inlineRadio3" class="form-check-label">Appuis financiers</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                            <input id="_dm-inlineRadio4" class="form-check-input" type="radio" name="type" value="Appuis non financiers">
                                            <label for="_dm-inlineRadio4" class="form-check-label">Appuis non financiers</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input id="_dm-inlineRadio3" class="form-check-input" type="radio" name="type" value="Formation technique et professionnelle">
                                            <label for="_dm-inlineRadio3" class="form-check-label">Formation technique et professionnelle</label>
                                         </div>
                                    </div>
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

    <div class="modal fade" id="addIndModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvel objectif</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('analyste.programme.resultat.save') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" value="{{ $item->token }}">
                        <input type="hidden" name="programme_id" value="{{ $item->id }}">
                            <div class="">
                                <div class="form-group">
                                    <label for="">Indicateur</label>
                                    <select required  name="indicateur_id" id="indicateur_id" class="form-control cmp">
                                        <option value="0">Selectionner un indicateur ...</option>
                                        @foreach($indicateurs as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="">Objectif attendu </label>
                                <input required type="text" name="attente"  id="name" class="form-control">
                            </div>
                        <div class="mt-5">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#banque_id').change(function(){
            $('#name').val($('#banque_id option:selected').text())
            $('#organisme_id').prop('disabled',true)
        })

        $('#organisme_id').change(function(){
            $('#name').val($('#organisme_id option:selected').text())
            $('#banque_id').prop('disabled',true)
        })
    </script>
@endsection


