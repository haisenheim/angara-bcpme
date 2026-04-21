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
    <button type="button" class="btn btn-xs btn- dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
       Actions
       <span class="vr"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#">Ajouter un appui</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.entreprise.questionnaire',$item->token) }}">Editer le questionnaire de mise en relation</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.entreprise.physique.create',$item->token) }}">Ajouter un tiers personne physique</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.entreprise.morale.create',$item->token) }}">Ajouter un tiers personne morale</a></li>
        <li><a class="dropdown-item" href="#">Editer un engagement de l'entreprise</a></li>
        <li><a class="dropdown-item" href="#">Editer des information de l'entreprise</a></li>
    </ul>
 </div>
@endsection

@section('page-header')
    <div class="angara-card top-info single">
        <div class="infos-enterprise d-flex justify-content-between flex-column flex-md-row">
            <div class="infos-left d-flex flex-column mb-3 mb-md-0 w-100"> 
                <div class="society-name d-flex align-items-center">
                    <h2 class="title-1 mb-0">{{ $item->name }}</h2>
                    <div class="badges d-flex">
                         @if($item->prospect)
                            <div class="mt-4"><span class="badge bg-danger">prospect</span></div>
                        @endif
                        <span style="width: 24px; height: 25px;"><img src="/img/new/icons/badges/check-badge.svg"></span>
                        <span style="width: 24px; height: 25px;"><img src="/img/new/icons/badges/shield-check.svg"></span>
                    </div>
                    
                </div>
                {{-- <p class="lead">Dossier de l'entreprise</p> --}}
                <div class="agent">
                    <span>Enregistré par : Jackie Flore OMGBA ESSAMA</span>
                </div>
                <div class="step">
                    <span>Etape : Conseiller d'Entreprises </span>
                </div>
                <div class="status done status-accommpagne">
                    <span class="">Demande d'appui préliminaire</span>
                </div>
            </div>
            {{-- <div class="notes d-flex flex-column w-100">
                <div class="d-flex gap-4">
                    <x-note-box moyenne="0" :up="false" />
                    <x-note-box moyenne="SME4" label="Notation PME" />
                </div>
            </div> --}}
            
            {{-- <div class="angara-card-border infos-right d-flex flex-column justify-content-center align-items-center">
                <a href="/admin/enterprises/notes/576">
                    <div class="moyenne">
                        0
                    </div>
                    <div class="txt-moyenne">
                        Moyenne pondérée
                    </div>
                </a>
                
            </div> --}}
        </div>
        {{-- <div class="accordion accordion-flush mt-3" id="_dm-transAccordion">
            <div class="accordion-item bg-transparent">
                <div class="accordion-header" id="_dm-transAccHeadingOne">
                    <button class="accordion-button bg-transparent px-3 border rounded collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#_dm-transAccCollapseOne" aria-expanded="false" aria-controls="_dm-transAccCollapseOne">
                        <h4 class="mb-0">Notes des Sous-critères</h4>
                    </button>
                </div>
                <div id="_dm-transAccCollapseOne" class="accordion-collapse bg-transparent collapse" aria-labelledby="_dm-transAccHeadingOne" data-bs-parent="#_dm-transAccordion" style="">
                    <div class="sous-criteres pt-4 d-flex flex-column gap-3 border-top">
            
                        <div class="d-flex flex-wrap gap-4">
                            <x-note-box moyenne="1.03" label="Activités" :down="false" />
                            <x-note-box moyenne="1.45" label="Gestion et Stratégie" :down="false" />
                            <x-note-box moyenne="1.93" label="Finance" :up="false" />
                            <x-note-box moyenne="0.3" label="Qualité de l’information financière" />
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    
@endsection

@section('content')

    @include('partials.entreprise-chef-agence-decision', ['item' => $item])

    @include('partials.entreprise-qualification-chef-filiere', ['item' => $item, 'qualificationContext' => 'admin'])

    <div class="" style="margin-top: -16px;">
        <div class="card">
            <div class="card-body p-0">
                <div class="tab-base">
                    <div class="nav-container position-relative">
                        <!-- Flèche gauche -->
                        <button class="scroll-arrow left-arrow" id="scroll-left">
                            &lt;
                        </button>

                        <!-- Navigation -->
                        <!-- Nav tabs -->
                        <ul class="nav nav-underline nav-component d-block border-bottom scrollmenu" id="nav-menu" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">Identification</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab2" type="button" role="tab" aria-controls="tab2" aria-selected="true">Objet Social</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false" tabindex="-1">Appuis sollicités</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab4" type="button" role="tab" aria-controls="tab4" aria-selected="false" tabindex="-1">Les tiers</button>
                        </li>
                        <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab5" type="button" role="tab" aria-controls="tab5" aria-selected="false" tabindex="-1">La mise en relation</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab6" type="button" role="tab" aria-controls="tab6" aria-selected="false" tabindex="-1">Etat des engagements</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab7" type="button" role="tab" aria-controls="tab7" aria-selected="false" tabindex="-1">Dossiers d'instruction par programme</button>
                            </li>
                        </ul>

                        <!-- Flèche droite -->
                        <button class="scroll-arrow right-arrow" id="scroll-right">
                            &gt;
                        </button>
                    </div>


                    <!-- Tabs content -->
                    <div class="tab-content">
                       <div id="_tab1" class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab">
                            <h4>IDENTIFICATION</h4>
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
                       <div id="_tab2" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
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
                       <div id="_tab3" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-tab">
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
                       <div id="_tab4" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                            <fieldset class="mt-4">
                                <legend>LISTES DES TIERS PERSONNE PHYSIQUE</legend>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>NOM</th>
                                            <th>NIU</th>
                                            <th>EMAIL</th>
                                            <th>TELEPHONE</th>
                                            <th>ADRESSE</th>
                                            <th>LIEN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->tiers->where('person_id','!=',0) as $tier)
                                            <tr>
                                                <th>{{ $tier->person->name }}</th>
                                                <td>{{ $tier->person->niu }}</td>
                                                <td>{{ $tier->person->email }}</td>
                                                <td>{{ $tier->person->phone }}</td>
                                                <td>{{ $tier->person->address }}</td>
                                                <th>{{ $tier->lien }}</th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </fieldset>

                            <fieldset class="mt-4">
                                <legend>LISTES DES TIERS PERSONNE MORALE</legend>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>DESIGNATION</th>
                                            <th>EMAIL</th>
                                            <th>TELEPHONE</th>
                                            <th>PRODUIT/SERVICE</th>
                                            <th>MANAGER</th>
                                            <th>LIEN</th>
                                            <th>I/E</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->tiers->where('company_id','!=',0) as $tier)
                                            <tr>
                                                <th>{{ $tier->company->name }}</th>
                                                <td>{{ $tier->company->email }}</td>
                                                <td>{{ $tier->company->phone }}</td>
                                                <td>{{ $tier->company->produit?->name }}</td>
                                                <td>{{ $tier->company->manager }}</td>
                                                <th>{{ $tier->lien }}</th>
                                                <td><span class="badge bg-{{ $tier->company->prospect?'danger':'success' }}">{{ $tier->company->prospect?'E':'I' }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </fieldset>

                       </div>
                       <div id="_tab5" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
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
                                                <div class="tab-base tab-vertical d-flex">
                                                    <!-- Nav tabs -->
                                                    <ul class="nav nav-tabs" style="max-height: 60vh; overflow: scroll; width: 300px;" role="tablist">
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
                                                                <table class="table table-sm table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Question</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($it['items'] as $rep)
                                                                            <tr>
                                                                                <td>{{ $rep->question->name }}</td>
                                                                                <td>{{ $rep->choice?->name }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                       </div>
                                    </div>
                                @endforeach
                             </div>
                       </div>
                       <div id="_tab6" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                            <h5>ETAT DES ENGAGEMENTS</h5>
                       </div>
                       <div id="_tab7" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Programme</th>
                                        <th>signataire</th>
                                        <th>Budget</th>
                                        <th>Date signature conv.</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->dossiers as $dossier)
                                        <tr>
                                            <th><a href="{{ route('admin.programmes.show',$dossier->programme?->token) }}">{{ $dossier->programme?->name }}</a></th>
                                            <td>{{ $dossier->programme?->signataire }}</td>
                                            <td>{{ number_format($dossier->programme?->budget,0,',','.') }} XAF</td>
                                            <td>{{ \Carbon\Carbon::parse($dossier->programme?->dt_sig_conv)->format('d/m/Y') }}</td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false"><i class="demo-psi-list-view"></i></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('admin.dossiers.show',$dossier->token) }}">Afficher le dossier</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('admin.programmes.show',$dossier->programme?->token) }}">Afficher le programme</a></li>

                                                    </ul>
                                                </div>
                                            </td>
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

    <style>
        .form-group{
            margin-top: 1rem;
        }
    </style>

    {{-- NAV MENU SLIDER --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const scrollMenu = document.getElementById("nav-menu");
            const scrollLeft = document.getElementById("scroll-left");
            const scrollRight = document.getElementById("scroll-right");
            const navContainer = document.querySelector(".nav-container");

            const scrollAmount = 100; // Nombre de pixels à défiler

            // Fonction pour mettre à jour la visibilité des flèches
            function updateArrows() {
                const scrollLeftMax = scrollMenu.scrollLeft;
                const scrollRightMax = scrollMenu.scrollWidth - scrollMenu.clientWidth;

                // Afficher ou masquer les flèches en fonction de la position du défilement
                scrollLeft.style.display = scrollLeftMax > 0 ? "flex" : "none";

                // Utiliser une comparaison plus précise pour détecter la fin du défilement à droite
                scrollRight.style.display = scrollLeftMax < scrollRightMax - 1 ? "flex" : "none";
            }

            // Gérer le clic sur les flèches
            scrollLeft.addEventListener("click", () => {
                scrollMenu.scrollBy({ left: -scrollAmount, behavior: "smooth" });
            });

            scrollRight.addEventListener("click", () => {
                scrollMenu.scrollBy({ left: scrollAmount, behavior: "smooth" });
            });

            // Mettre à jour la visibilité des flèches à chaque défilement
            scrollMenu.addEventListener("scroll", updateArrows);

            // Initialiser l'état des flèches
            updateArrows();

            // Masquer les flèches si le curseur n'est pas sur le conteneur
            navContainer.addEventListener("mouseenter", () => {
                updateArrows();
                scrollLeft.style.opacity = "1";
                scrollRight.style.opacity = "1";
            });

            navContainer.addEventListener("mouseleave", () => {
                scrollLeft.style.opacity = "0";
                scrollRight.style.opacity = "0";
            });
        });
    </script>
@endsection
