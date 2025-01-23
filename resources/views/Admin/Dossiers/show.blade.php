@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="#">INSTRUCTION</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $dossier['name'] }}</li>
    </ol>
 </nav>
@endsection

@section('actions')
<div class="btn-group">
    <button type="button" class="btn btn-xs btn-dark mr-3"  data-bs-target="#importDsfModal" data-bs-toggle="modal">
    Importer DSF</button>
    <button type="button" class="btn btn-xs btn-primary dropdown-toggle hstack gap-3" data-bs-toggle="dropdown" aria-expanded="false">
    Actions
    <span class="vr"></span>
    </button>
    <ul class="dropdown-menu analyse">
        <li><a data-sequence="1" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Brèves données générales actualisées sur l'emprunteur</a></li>
        <li><a data-sequence="2" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Analyse critique d'ensemble</a></li>
        <li><a data-sequence="3" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Analyse financière de l'emprunteur</a></li>
        <li><a data-sequence="4" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Appuis financiers et non-financiers proposés</a></li>
        <li><a data-sequence="5" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Analyse du risque et de la capacité de remboursement de l'emprunteur</a></li>
        <li><a data-sequence="6" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Rentabilité de la relation pour l’établissement</a></li>
        <li><a data-sequence="7" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Conclusions motivées, recommandations de l’Analyste Financier</a></li>
    </ul>
</div>
@endsection

@section('page-header')
    <div class="d-flex justify-content-between w-100">
        <h5 class="page-title mb-0 mt-3">DOSSIER D'INSTRUCTION</h5>

    </div>

    <div class="angara-card top-info single mt-4">
        <div class="infos-enterprise d-flex justify-content-between flex-column flex-md-row">
            <div class="infos-left d-flex flex-column mb-3 mb-md-0 w-100"> 
                <div class="society-name d-flex align-items-center">
                    <h2 class="title-1 mb-0">{{ $entreprise['name'] }}</h2>
                    
                    <div class="badges d-flex">
                        <span style="width: 24px; height: 25px;"><img src="/img/new/icons/badges/check-badge.svg"></span>
                        <span style="width: 24px; height: 25px;"><img src="/img/new/icons/badges/shield-check.svg"></span>
                    </div>
                    
                </div>
                <h3>Dossier : {{ $dossier['programme']['name'] }}</h3>
                <div class="agent">
                    <span>Créé par : Jackie Flore OMGBA ESSAMA</span>
                </div>
            </div>
            <div class="notes d-flex flex-column w-100">
                <div class="d-flex justify-content-end gap-4">
                    <div class="notation pointer">
                        <div
                            class="d-flex flex-column align-items-center justify-content-center angara-card-border infos-right"
                        >
                            <a class="text-black">
                                <div class="note d-flex justify-content-between align-items-center gap-4">
                                    @if($sme) <div class="moyenne">{{ $sme['note'] }}</div> @endif
                                    <div class="indicator d-flex flex-colum gap-1">
                                        
                                            <span class="up"></span>
                                       
                                            <span class="down"></span>
                                      
                                    </div>
                                </div>

                                <div class="txt-moyenne truncate">Moyenne pondérée finale</div>
                            </a>
                        </div>
                    </div>
                    <div class="notation pointer"  data-bs-target="#notePonderee" data-bs-toggle="modal">
                        <div
                            class="d-flex flex-column align-items-center justify-content-center angara-card-border infos-right"
                        >
                            <a class="text-black">
                                <div class="note d-flex justify-content-between align-items-center gap-4">
                                    @if($sme) <div class="moyenne">{{ $sme['name'] }}</div> @endif
                                    <div class="indicator d-flex flex-colum gap-1">
                                        
                                            <span class="up"></span>
                                       
                                            <span class="down"></span>
                                      
                                    </div>
                                </div>

                                <div class="txt-moyenne truncate" *ngIf="label">Notation PME</div>
                            </a>
                        </div>
                    </div>
                    {{-- <x-note-box moyenne="0" :up="false" />
                    <x-note-box moyenne="SME4" label="Notation PME" /> --}}
                </div>
            </div>
            
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
        <div class="accordion accordion-flush mt-3" id="_dm-transAccordion">
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
        </div>
    </div>
@endsection


@section('content')
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
                                <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_dm-coTabsBaseHome" type="button" role="tab" aria-controls="home" aria-selected="true">GRILLE DE NOTATION</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_dm-coTabsBaseProfile" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">RAPPORT D'ANALYSE CRITIQUE</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_dm-coTabsBaseContact" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">ETAT DES ENGAGEMENTS</button>
                            </li>
                        
                        </ul>

                        <!-- Flèche droite -->
                        <button class="scroll-arrow right-arrow" id="scroll-right">
                            &gt;
                        </button>
                    </div>


                    <!-- Tabs content -->
                       <div class="tab-content">
                          <div id="_dm-coTabsBaseHome" class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab">

                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Critere Principaux</th>
                                        <td>&numero; sous-critere</td>
                                        <td>Pourcentage</td>
                                        <th>Sous - critere</th>
                                        <th>Valeur</th>
                                        <th>Note par sous-critere</th>
                                        <th>Note ponderee</th>
                                        <th>Note critere pondere</th>

                                    </tr>
                                </thead>
                                @if(count($indicateurs))
                                <tbody>
                                    <tr>
                                        <th rowspan="{{ count($criteres[0]['souscriteres'])+1 }}">{{ $criteres[0]['name'] }}</th>
                                    </tr>
                                    @foreach($criteres[0]['souscriteres'] as $sc)
                                        <tr>
                                            <td>{{ $sc['sequence'] }}</td>
                                            <td>{{ $sc['default'] }}%</td>
                                            <td>{{ $sc['name'] }}</td>
                                            <td>{{ isset($sc['reponses'][0])?$sc['reponses'][0]['choice']['valeur']:'-' }} <span class="float-right"><button data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] }}" data-dossier_id="{{ $item->id }}" data-id="{{ $sc['id'] }}" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                            <td>{{ isset($sc['reponses'][0])?$sc['reponses'][0]['note']:'-' }}</td>
                                            <td>{{ isset($sc['reponses'][0])?($sc['reponses'][0]['note']*$sc['default']/100):'-' }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="7"></td>
                                        <th>{{ isset($criteres[0]['note'])?$criteres[0]['note']:0 }}</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="{{ count($criteres[1]['souscriteres'])+1 }}">{{ $criteres[1]['name'] }}</th>
                                    </tr>
                                    @foreach($criteres[1]['souscriteres'] as $sc)
                                        <tr>
                                            <td>{{ $sc['sequence'] }}</td>
                                            <td>{{ $sc['default'] }}%</td>
                                            <td>{{ $sc['name'] }}</td>
                                            <td>{{ isset($sc['reponses'][0])?$sc['reponses'][0]['choice']['valeur']:'-' }} <span class="float-right"><button data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] }}" data-dossier_id="{{ $item->id }}" data-id="{{ $sc['id'] }}" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                            <td>{{ isset($sc['reponses'][0])?$sc['reponses'][0]['note']:'-' }}</td>
                                            <td>{{ isset($sc['reponses'][0])?($sc['reponses'][0]['note']*$sc['default']/100):'-' }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="7"></td>
                                        <th>{{ isset($criteres[1]['note'])?$criteres[1]['note']:0  }}</th>
                                    </tr>
                                    <tr>
                                        <td rowspan="{{ count($indicateurs[0]['notation']['details'])+1 }}">FINANCE</td>
                                    </tr>
                                        @foreach($indicateurs[0]['notation']['details'] as $sc)
                                        <tr>
                                            <td>{{ $sc['sequence'] }}</td>
                                            <td>{{ $sc['pourcentage'] }}%</td>
                                            <td>{{ $sc['critere'] }}</td>
                                            <td>{{ $sc['valeur'] }}</td>
                                            <td>{{ $sc['note'] }}</td>
                                            <td>{{ $sc['pondere'] }}</td>
                                            <td></td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="7"></td>
                                            <th >{{ $indicateurs[0]['notation']['note'] }}</th>
                                        </tr>
                                        <tr>
                                            <th rowspan="{{ count($criteres[3]['souscriteres'])+1 }}">{{ $criteres[3]['name'] }}</th>
                                        </tr>
                                        @foreach($criteres[3]['souscriteres'] as $sc)
                                            <tr>
                                                <td>{{ $sc['sequence'] }}</td>
                                                <td>{{ $sc['default'] }}%</td>
                                                <td>{{ $sc['name'] }}</td>
                                                <td>{{ isset($sc['reponses'][0])?$sc['reponses'][0]['choice']['valeur']:'-' }} <span class="float-right"><button data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] }}" data-dossier_id="{{ $item->id }}" data-id="{{ $sc['id'] }}" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                                <td>{{ isset($sc['reponses'][0])?$sc['reponses'][0]['note']:'-' }}</td>
                                                <td>{{ isset($sc['reponses'][0])?($sc['reponses'][0]['note']*$sc['default']/100):'-' }}</td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="7"></td>
                                            <th>{{ isset($criteres[3]['note'])?$criteres[3]['note']:0  }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4"></th>
                                            <th colspan="2">NOTE PONDERE FINALE/CRITERE</th>
                                            <th colspan="2">NOTATION PME</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4"></th>
                                            <th colspan="2">{{ $dossier['note'] }}</th>
                                            <th colspan="2">{{ $sme['name'] }}</th>
                                        </tr>
                                </tbody>
                                @endif
                            </table>
                          </div>
                          <div id="_dm-coTabsBaseProfile" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="mt-3 border rounded rounded-2 p-3">
                                    <h4>1. INFORMATIONS GENERALES</h4>
                                    <p class="lh-base"><?= $dossier['donneesGenerales'] ?></p>
                                </div>
                                <div class="mt-3 border rounded rounded-2 p-3">
                                    <h4>2. ANALYSE D'ENSEMBLE</h4>
                                    <p class="lh-base"><?= $dossier['analyseEnsemble'] ?></p>
                                </div>
                                <div class="mt-3 border rounded rounded-2 p-3">
                                    <h4>3. ANALYSE FINANCIERE</h4>
                                    <p class="lh-base"><?= $dossier['analyseFinanciere'] ?></p>
                                </div>
                                <div class="mt-3 border rounded rounded-2 p-3">
                                    <h4>4. APPUIS FINANCIERS ET NON FINANCIERS</h4>
                                    <p class="lh-base"><?= $dossier['appuis'] ?></p>
                                </div>
                                <div class="mt-3 border rounded rounded-2 p-3">
                                    <h4>5. ANALYSE DU RISQUE ET DE LA CAPACITE DE REMBOURSEMENT</h4>
                                    <p class="lh-base"><?= $dossier['analyseRisque'] ?></p>
                                </div>
                                <div class="mt-3 border rounded rounded-2 p-3">
                                    <h4>6. RENTABILITE DE LA RELATION POUR L'ETABILISSEMENT</h4>
                                    <p class="lh-base"><?= $dossier['analyseRentabilite'] ?></p>
                                </div>
                                <div class="mt-3 border rounded rounded-2 p-3">
                                    <h4>7. CONCLUSIONS GENERALES POUR L'ANALYSTE</h4>
                                    <p class="lh-base"><?= $dossier['conclusionsAnalyste'] ?></p>
                                </div>
                          </div>
                          <div id="_dm-coTabsBaseContact" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                            <table class="table sm table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="1"></th>
                                        <th colspan="3">ENCOURS</th>
                                        <th colspan="3">SOLLICITES</th>
                                        <th colspan="2">TOTAL</th>
                                    </tr>
                                    <tr>
                                        <th>ENGAGEMENT</th>
                                        <th>MONTANT</th>
                                        <th>IMPAYES</th>
                                        <th>DATE DE VALIDITE</th>

                                        <th>MONTANT</th>
                                        <th>DATE DE VALIDITE</th>
                                        <th>VARIATION</th>

                                        <th>MONTANT</th>
                                        <th>DATE DE VALIDITE</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($engagements as $eng)
                                        <x-engagement :eng="json_encode($eng)"></x-engagement>
                                    @endforeach
                                </tbody>
                            </table>
                          </div>
                       </div>
                 </div>
            </div>
        </div>
    </div>  

    <div class="modal fade" id="importDsfModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form enctype="multipart/form-data" id="form" method="post">
                    @csrf
                    <div class="modal-body">
                        
                            <div class="form-group">
                                <input type="hidden" id="dossier_id" value="{{ $item->id }}" name="dossier_id" class="form-control">
                                <input type="hidden" id="token" value="{{ $item->token }}">
                            </div>
                            <div class="form-group">
                                <label for="">ANNEE N</label>
                                <input type="number" name="annee" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">FICHIER DSF</label>
                                <input type="file" name="upload" class="form-control">
                            </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"><i class="pli-save"></i>ENREGISTRER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="notePonderee">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notation PME</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
                <div class="modal-body">
                    
                    @if($sme)
                    <h3 class="title-1">{{ $sme['name'] }}</h3>
                    <h4>{{ $sme['mention'] }}</h4>
                    <p class="lh-base">{{ $sme['description'] }}</p>
                @endif
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            
            </div>
        </div>
    </div>

    <div class="modal fade" id="report1Modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header justify-content-between">
                    <div class="d-flex justify-content-end">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <p id="description"></p>
                    <form action="{{ route('admin.entreprise.dossier.analyse') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $dossier['id'] }}" name="dossier_id">
                        <input type="hidden" id="sequence" name="sequence">
                        <div class="mt-3">
                            <x-quill :name="'content'"></x-quill>
                        </div>
                        <div class="mt-1">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="critereModal">
        <div class="modal-dialog modal-dialog-scrollable modal-sm modal-dialog-center">
            <div class="modal-content ">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Choix de la valeur</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.instruction.critere.reponse') }}" method="post">
                        @csrf
                        <input type="hidden" id="_dossier_id" name="dossier_id">
                        <input type="hidden" id="id" name="critere_id">
                        <div class="mt-3">
                            <label id="name" for=""></label>
                            <select required name="choice_id" id="critere_id" class="form-control">

                            </select>
                        </div>
                        <div class="mt-1">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Editer</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.entreprise.set.engagement') }}" method="post">
                        <input type="hidden" name="engagement_id" id="engagement_id">
                        <input type="hidden" name="entreprise_id" value="{{ $entreprise['id'] }}">
                        @csrf
                            <div class="">
                                <div>
                                    <label for="">Banque</label>
                                    <select required name="banque_id" id="banque_id" class="form-control">
                                        <option value="">Selectionner la banque</option>
                                        @foreach ($banques as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-1">
                                    <fieldset>
                                        <legend>Enagagements en cours</legend>
                                        <div class="mt-1">
                                            <label for="">Montant</label>
                                            <input required value="0" type="number" class="form-control" name="encours_montant">
                                        </div>
                                        <div class="mt-1">
                                            <label for="">Impayé</label>
                                            <input required value="0" type="number" class="form-control" name="encours_impaye">
                                        </div>
                                        <div class="mt-1">
                                            <label for="">Date de validité</label>
                                            <input required value="0" type="date" class="form-control" name="encours_dt_validite">
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend>Enagagements en sollicités</legend>
                                        <div class="mt-1">
                                            <label for="">Montant</label>
                                            <input required value="0" type="number" class="form-control" name="sollicite_montant">
                                        </div>
                                        <div class="mt-1">
                                            <label for="">Date de validité</label>
                                            <input required value="0" type="date" class="form-control" name="sollicite_dt_validite">
                                        </div>
                                    </fieldset>
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

    <script>
        const labels = [
            "Brèves données générales actualisées sur l'emprunteur / Diagnostic des options stratégiques et politiques (Vision du ou des promoteur(s), aspect juridique, répartition du capital, organigramme -à joindre éventuellement en annexe-, administrateurs, équipe de direction, aspects économiques et commerciaux macro et méso, relations avec d'autres Institutions financières ou Directions de l’établissement, principaux banquiers, auditeurs).",
            "Analyse critique d'ensemble / Diagnostic opérationnel de l'emprunteur, aspects non financiers (ses activités, son processus de production, ses relations d’affaires, le climat social de l’entreprise, perspectives de développement à moyen terme, etc.)",
            "Analyse financière de l'emprunteur (passé récent, présent, futur) / Aspects financiers du Diagnostic opérationnel (analyse du compte de résultats) ; Diagnostic financier (analyse du bilan) ; Diagnostic prévisionnel (analyse des états financiers prévisionnels)",
            `Appuis financiers et non-financiers proposés - (* quand applicable) :
            - Type, objet (description détaillée), montant, syndication (*), validité des concours.
            - Formes d'utilisation, autres clients autorisés dans le groupe ou la filière (*), sous-limites (*).
            - Modalités de remboursement, amortissement ou de suivi-évaluation (*).
            - Garanties, protections (telles que sûretés réelles, personnelles, cautionnement mutuel, garantie souveraine, lettres d'intention, covenants, ...), liens entre les sûretés et les formes d'utilisation. `,
            `Analyse du risque et de la capacité de remboursement de l'emprunteur (risque global, spécifique, technique, juridique, de marché), sorties des crédits.`,
            `Rentabilité de la relation pour l’établissement (conditions, commissions, retombées attendues, soldes moyens, commentaires ratios de couverture de risques).`,
            `Conclusions motivées, recommandations de l’Analyste Financier`,
        ]
        $('.analyse .dropdown-item').click(function(){
            var seq = $(this).data('sequence')
            console.log(seq)
            description = labels[seq-1]
            $('#description').text(description)
            $('#sequence').val(seq)
        })

        $('.btn-edit').click(function(){
            var id = $(this).data('engagement_id')
            $('#engagement_id').val(id)
        })
    </script>

    <script>
            $('.btn-critere').click(function(){
            var url = "{{ route('admin.instruction.critere.choices') }}"
            var name = $(this).data('name')
            var dossier_id = $(this).data('dossier_id')
            var id = $(this).data('id')
            $('#name').text(name)
            $('#_dossier_id').val(dossier_id)
            $('#id').val(id)

            $.ajax({
                url: url,
                type: 'get',
                dataType:'json',
                data: {id:id},
                success:function(data){
                    console.log(data)
                    $('#critere_id').html('')
                    $('#critere_id').append(`<option value="">Choisir ...</option>`)
                    data.forEach(choice => {
                        $('#critere_id').append(`<option value=${choice.id}>${choice.valeur}</option>`)
                    });
                    //window.location.replace('/admin/instruction/dossier/'+dossier_id)
                },
                //processData: false,
               // contentType: false
            } );
        })
        //var url = "{{ route('admin.instruction.dsf') }}"

        $( '#form' )
        .submit( function( e ) {
            var _url = "http://angara.pft-keka.com:8080/dossier"
            var dossier_id = $('#dossier_id').val()
            var token = $('#token').val()
            console.log(dossier_id)
            $.ajax( {
            url: _url,
            type: 'POST',
            data: new FormData( this ),
            success:function(data){
                console.log(data)
                window.location.replace('/admin/dossiers/'+token)
            },
            processData: false,
            contentType: false
            } );
            e.preventDefault();
        } );
    </script>

@endsection
