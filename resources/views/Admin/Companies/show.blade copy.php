@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Entreprises</a></li>
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
        <li><a class="dropdown-item" href="#">Ajouter un appui</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.entreprise.questionnaire',$item->token) }}">Editer le questionnaire de mise en relation</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.entreprise.physique.create',$item->token) }}">Ajouter un tiers personne physique</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.entreprise.morale.create',$item->token) }}">Ajouter un tiers personne morale</a></li>
        <li><a class="dropdown-item" href="#">Editer un engagement de l'entreprise</a></li>
        <li><a class="dropdown-item" href="#">Soumissionner à un programme</a></li>
        <li><a class="dropdown-item" href="#">Editer des information de l'entreprise</a></li>
    </ul>
 </div>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="lead">Dossier de l'entreprise</p>
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
                            <td>&numero; Registre de commerce </td>
                            <th>{{ $item->rccm }}</th>
                        </tr>
                        <tr>
                            <td>&numero;  d’identifiant unique </td>
                            <th>{{ $item->niu }}</th>
                        </tr>
                        <tr>
                            <td>&numero; Employeur ou Assurance volontaire </td>
                            <th>{{ $item->cnps }}</th>
                        </tr>
                        <tr>
                            <td>&numero; principal Mobile Money  </td>
                            <th>{{ $item->mm_phone }}</th>
                        </tr>
                        <tr>
                            <td>Type d'entreprise </td>
                            <th>{{ $item->taille }}</th>
                        </tr>
                        <tr>
                            <td>Caractère </td>
                            <th>{{ $item->caractere }}</th>
                        </tr>
                        <tr>
                            <td>Forme juridique </td>
                            <th>{{ $item->forme?->name }}</th>
                        </tr>
                        <tr>
                            <td>Système comptable </td>
                            <th>{{ $item->systeme }}</th>
                        </tr>
                        <tr>
                            <td>Capital social </td>
                            <th>{{ number_format($item->capital,0,',','.') }} XAF</th>
                        </tr>
                        <tr>
                            <td>Date de création formelle </td>
                            <th>{{ \Carbon\Carbon::parse($item->dt_creation)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($item->dt_creation)->age}}an(s))</th>
                        </tr>
                        <tr>
                            <td>Date début des activités </td>
                            <th>{{ \Carbon\Carbon::parse($item->dt_start)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($item->dt_start)->age}}an(s))</th>
                        </tr>
                        <tr>
                            <td>Ressources propres</td>
                            <th>{{ number_format($item->ressources_propres,0,',','.') }} XAF</th>
                        </tr>
                        <tr>
                            <td>Total actif </td>
                            <th>{{ number_format($item->total_actif,0,',','.') }} XAF</th>
                        </tr>
                        <tr>
                            <td>Nombre d'employés</td>
                            <th>{{ number_format($item->nb_personnel,0,',','.') }} XAF</th>
                        </tr>
                        <tr>
                            <td>Type d'employés</td>
                            <th>{{ $item->tperso }}</th>
                        </tr>
                    </tbody>
                </table>

                <fieldset>
                    <legend>Infos du dirigeant</legend>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>Nom</td>
                                <th>{{ $item->manager }}</th>
                            </tr>
                            <tr>
                                <td>Sexe </td>
                                <th>{{ $item->manager_sexe }}</th>
                            </tr>
                            <tr>
                                <td>Date de naissance </td>
                                <th>{{ \Carbon\Carbon::parse($item->manager_dtn)->format('d/m/Y') }}  ({{ \Carbon\Carbon::parse($item->manager_dtn)->age}}ans)</th>
                            </tr>
                            <tr>
                                <td>Niveau d'instruction </td>
                                <th>{{ $item->manager_niveau }}</th>
                            </tr>
                            <tr>
                                <td>Est-il le promoteur ? </td>
                                <th>{{ $item->manager_promoteur?'Oui':'Non' }} </th>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="mt-2">
                    <legend>Localisation et Contact</legend>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>Quartier/Village  </td>
                                <th>{{ $item->village?->name }} {{ $item->quartier?->name }}</th>
                            </tr>
                            <tr>
                                <td>Commune</td>
                                <th>{{ $item->arrondissement?->name }}</th>
                            </tr>
                            <tr>
                                <td>Departement </td>
                                <th>{{ $item->departement?->name }}</th>
                            </tr>
                            <tr>
                                <td>Region </td>
                                <th>{{ $item->region?->name }}</th>
                            </tr>
                            <tr>
                                <td>Telephone </td>
                                <th>{{ $item->phone }}</th>
                            </tr>
                            <tr>
                                <td>Email </td>
                                <th>{{ $item->email }} </th>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
            </div>
        </div>
        <div class="card flex-fill">
            <div class="card-body">
                <div class="tab-base">
                    <!-- Nav tabs -->
                    <ul class="nav nav-underline nav-component border-bottom" role="tablist">
                       <li class="nav-item" role="presentation">
                          <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">Objet Social</button>
                       </li>
                       <li class="nav-item" role="presentation">
                          <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">Appuis sollicités</button>
                       </li>
                       <li class="nav-item" role="presentation">
                          <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false" tabindex="-1">Les tiers</button>
                       </li>
                       <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab4" type="button" role="tab" aria-controls="tab4" aria-selected="false" tabindex="-1">La mise en relation</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab5" type="button" role="tab" aria-controls="tab5" aria-selected="false" tabindex="-1">Etat des engagements</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab6" type="button" role="tab" aria-controls="tab6" aria-selected="false" tabindex="-1">Programmes</button>
                         </li>
                    </ul>


                    <!-- Tabs content -->
                    <div class="tab-content">
                       <div id="_tab1" class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab">
                            <h4>OBJET SOCIAL</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <span>PRODUIT PRINCIPAL </span><span class="fw-900 fs-5 text-primary"><strong>{{ $item->produit?->name }}</strong></span>
                                        </td>
                                        <td>
                                            <span>Filiere </span><span class="fw-900 fs-5 text-primary"><strong>{{ $item->filiere?->name }}</strong></span>
                                        </td>
                                        <td>
                                            <span>Branche </span><span class="fw-900 fs-5 text-primary"><strong>{{ $item->branche?->name }}</strong></span>
                                        </td>
                                        <td>
                                            <span>Ancienneté dans ce produit: </span><span class="fw-900 fs-5 text-primary">depuis <strong> {{ $item->produit_year_start }}</strong></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5>Autres produits ou services</h5>
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
                            <h5>LISTE DES APPUIS SOLLICITES</h5>
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
                            <h5>LISTES DES TIERS</h5>
                       </div>
                       <div id="_tab4" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                            <h5>RESULTATS DU QUESTIONNAIRE DE MISE EN RELATION</h5>

                            <div class="accordion accordion-flush" id="_dm-flushAccordion">
                                @foreach ($mr as $r)
                                <div style="max-width:700px;" class="accordion-item">
                                    <div class="accordion-header" id="_p_acc_{{ $r['critere']->id }}">
                                       <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#_acc_{{ $r['critere']->id }}" aria-expanded="false" aria-controls="_dm-flushAccCollapseOne">
                                         {{ $r['critere']->name }}
                                       </button>
                                    </div>
                                    <div id="_acc_{{ $r['critere']->id }}" class="accordion-collapse collapse" aria-labelledby="_acc_{{ $r['critere']->id }}" data-bs-parent="#_p_acc_{{ $r['critere']->id }}" style="">
                                       <div class="accordion-body">
                                            {{ $r['critere']->name }}
                                            <div class="tab-base tab-vertical d-flex">
                                                <!-- Nav tabs -->
                                                <ul class="nav nav-tabs w-25" style="height: 60vh; overflow: scroll;" role="tablist">
                                                    @foreach($r['items'] as $it)
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link {{ $loop->index==0?'active':'' }}" data-bs-toggle="tab" data-bs-target="#_vtab_{{ $it['sous_critere']->id }}" type="button" role="tab" aria-controls="tab_{{ $it['sous_critere']->id }}" aria-selected="true">{{ $it['sous_critere']->name }}</button>
                                                     </li>
                                                    @endforeach
                                                </ul>
                                                <!-- Tabs content -->
                                                <div class="tab-content flex-fill">
                                                    @foreach($r['items'] as $it)
                                                    <div id="_vtab_{{ $it['sous_critere']->id }}" class="tab-pane fade {{ $loop->index==0?'show active':'' }}" role="tabpanel" aria-labelledby="v{{ $it['sous_critere']->id }}-tab">
                                                        <h5>{{ $it['sous_critere']->name }}</h5>
                                                        <table class="table table-sm table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Question</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                     </div>
                                                    @endforeach
                                                </div>

                                             </div>
                                            </div>
                                       </div>
                                    </div>
                                 </div>
                                @endforeach
                             </div>
                       </div>
                       <div id="_tab5" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                            <h5>ETAT DES ENGAGEMENTS</h5>
                       </div>
                       <div id="_tab6" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                            <h5>SOUMISSIONS AUX DIFFERENTS PROGRAMMES</h5>
                       </div>
                    </div>
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
