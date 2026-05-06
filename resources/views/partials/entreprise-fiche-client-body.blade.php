@php
    $__piecesUrl = $piecesUrl ?? route('gestionnaire.entreprises.pieces-exigibles.index', $item->token);
    $__dossierShow = $dossierShowCallback ?? fn ($d) => route('gestionnaire.dossiers.show', $d->token);
    $__programmeShow = $programmeShowCallback ?? fn ($t) => $t ? route('gestionnaire.programmes.show', $t) : '#';
    $ficheProgrammeReadonly = $ficheProgrammeReadonly ?? false;
    $__piecesBtn = $piecesButtonLabel ?? 'Gerer les pieces exigibles';
@endphp

@include('partials.entreprise-chef-agence-decision', ['item' => $item])

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <small class="text-muted text-uppercase d-block mb-1">Capital social</small>
                <p class="mb-0 fw-semibold">{{ number_format($item->capital ?? 0, 0, ',', ' ') }} XAF</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <small class="text-muted text-uppercase d-block mb-1">Effectif</small>
                <p class="mb-0 fw-semibold">{{ ($item->nb_personnel_permanent ?? 0) + ($item->nb_personnel_saisonier ?? 0) }} employés</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <small class="text-muted text-uppercase d-block mb-1">Dirigeant</small>
                <p class="mb-0 fw-semibold">{{ $item->manager ?? '—' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <small class="text-muted text-uppercase d-block mb-1">Localisation</small>
                <p class="mb-0 fw-semibold">{{ $item->arrondissement?->name ?? $item->region?->name ?? '—' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-information me-2 text-primary"></i>Identification</h6>
            </div>
            <div class="card-body">
                                <dl class="row mb-0 g-2">
                    <dt class="col-sm-5 text-muted small">Dénomination</dt>
                    <dd class="col-sm-7">{{ $item->name }}</dd>
                    <dt class="col-sm-5 text-muted small">RCCM</dt>
                    <dd class="col-sm-7">{{ $item->rccm }}</dd>
                    <dt class="col-sm-5 text-muted small">NIU</dt>
                    <dd class="col-sm-7">{{ $item->niu }}</dd>
                    <dt class="col-sm-5 text-muted small">CNPS</dt>
                    <dd class="col-sm-7">{{ $item->cnps }}</dd>
                    <dt class="col-sm-5 text-muted small">Mobile Money</dt>
                    <dd class="col-sm-7">{{ $item->mm_phone }}</dd>
                    <dt class="col-sm-5 text-muted small">Forme juridique</dt>
                    <dd class="col-sm-7">{{ $item->forme?->name ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Système comptable</dt>
                    <dd class="col-sm-7">{{ $item->systeme ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Capital social</dt>
                    <dd class="col-sm-7">{{ number_format($item->capital ?? 0, 0, ',', ' ') }} XAF</dd>
                    <dt class="col-sm-5 text-muted small">Création</dt>
                    <dd class="col-sm-7">{{ $item->dt_creation ? \Carbon\Carbon::parse($item->dt_creation)->format('d/m/Y') : '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Début activités</dt>
                    <dd class="col-sm-7">{{ $item->dt_start ? \Carbon\Carbon::parse($item->dt_start)->format('d/m/Y') : '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Ressources propres</dt>
                    <dd class="col-sm-7">{{ number_format($item->ressources_propres ?? 0, 0, ',', ' ') }} XAF</dd>
                    <dt class="col-sm-5 text-muted small">Total actif</dt>
                    <dd class="col-sm-7">{{ number_format($item->total_actif ?? 0, 0, ',', ' ') }} XAF</dd>
                    <dt class="col-sm-5 text-muted small">Effectif permanent</dt>
                    <dd class="col-sm-7">{{ number_format($item->nb_personnel_permanent ?? 0, 0, ',', ' ') }}</dd>
                    <dt class="col-sm-5 text-muted small">Effectif saisonnier</dt>
                    <dd class="col-sm-7">{{ number_format($item->nb_personnel_saisonier ?? 0, 0, ',', ' ') }}</dd>
                </dl>

                <h6 class="fw-semibold mt-4 mb-2"><i class="demo-psi-male me-2 text-primary"></i>Dirigeant</h6>
                <dl class="row mb-0 g-2">
                    <dt class="col-sm-5 text-muted small">Nom</dt>
                    <dd class="col-sm-7">{{ $item->manager ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Sexe</dt>
                    <dd class="col-sm-7">{{ $item->manager_sexe ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Naissance</dt>
                    <dd class="col-sm-7">{{ $item->manager_dtn ? \Carbon\Carbon::parse($item->manager_dtn)->format('d/m/Y') . ' (' . \Carbon\Carbon::parse($item->manager_dtn)->age . ' ans)' : '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Niveau</dt>
                    <dd class="col-sm-7">{{ $item->manager_niveau ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Promoteur</dt>
                    <dd class="col-sm-7">{{ $item->manager_promoteur ? 'Oui' : 'Non' }}</dd>
                </dl>

                <h6 class="fw-semibold mt-4 mb-2"><i class="demo-psi-map me-2 text-primary"></i>Localisation & contact</h6>
                <dl class="row mb-0 g-2">
                    <dt class="col-sm-5 text-muted small">Quartier / Village</dt>
                    <dd class="col-sm-7">{{ trim(($item->village?->name ?? '') . ' ' . ($item->quartier?->name ?? '')) ?: '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Commune</dt>
                    <dd class="col-sm-7">{{ $item->arrondissement?->name ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Département</dt>
                    <dd class="col-sm-7">{{ $item->departement?->name ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Région</dt>
                    <dd class="col-sm-7">{{ $item->region?->name ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Téléphone</dt>
                    <dd class="col-sm-7">{{ $item->phone ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Email</dt>
                    <dd class="col-sm-7">{{ $item->email ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
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
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tabSitesEquipe" type="button" role="tab" aria-controls="_tabSitesEquipe" aria-selected="false" tabindex="-1">Sites &amp; Équipe</button>
                       </li>
                       <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab4" type="button" role="tab" aria-controls="tab4" aria-selected="false" tabindex="-1">La mise en relation</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab6" type="button" role="tab" aria-controls="tab6" aria-selected="false" tabindex="-1">Dossiers d'instruction par programme</button>
                         </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab7" type="button" role="tab" aria-controls="tab7" aria-selected="false" tabindex="-1">Checklist pièces exigibles</button>
                         </li>
                    </ul>


                    <!-- Tabs content -->
                    <div class="tab-content">
                       <div id="_tab1" class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab">
                            <h5 class="fw-semibold mb-3"><i class="demo-psi-box me-2 text-primary"></i>Objet social</h5>
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
                            <h6 class="fw-semibold mt-4 mb-2">Autres produits ou services</h6>
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
                            <div class="mt-3">
                                @include('Gestionnaire.Companies.partials.tiers_section', ['item' => $item])
                            </div>
                       </div>
                       <div id="_tabSitesEquipe" class="tab-pane fade" role="tabpanel">
                            <div class="mt-3">
                                @include('partials.entreprise-sites-et-equipe', ['item' => $item, 'canCrud' => true])
                            </div>
                       </div>
                       <div id="_tab4" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                            @php
                                $totalQuestionnaireItems = $mr->sum(fn ($result) => collect($result['items'])->sum(fn ($group) => $group['items']->count()));
                                $answeredQuestionnaireItems = $totalQuestionnaireItems;
                                $questionnaireCompletionRate = $totalQuestionnaireItems > 0 ? 100 : 0;
                            @endphp

                            <div class="relation-questionnaire">
                                <div class="card relation-questionnaire-summary border-0 shadow-sm mb-4">
                                    <div class="card-body p-4">
                                        <div class="row g-3 align-items-center">
                                            <div class="col-lg-7">
                                                <p class="text-uppercase text-muted small fw-semibold mb-2">Questionnaire de mise en relation</p>
                                                <h5 class="mb-2">Résultats du questionnaire renseigné</h5>
                                                <p class="text-body-secondary mb-0">Consultez les réponses enregistrées par critère et par sous-critère.</p>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="row g-3">
                                                    <div class="col-4">
                                                        <div class="d-flex flex-column gap-1">
                                                            <span class="text-muted small">Critères</span>
                                                            <strong class="fs-4">{{ $mr->count() }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="d-flex flex-column gap-1">
                                                            <span class="text-muted small">Réponses</span>
                                                            <strong class="fs-4">{{ $answeredQuestionnaireItems }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="d-flex flex-column gap-1">
                                                            <span class="text-muted small">Complétion</span>
                                                            <strong class="fs-4">{{ $questionnaireCompletionRate }}%</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <div class="relation-questionnaire-progress">
                                                        <div class="relation-questionnaire-progress-bar" style="width: {{ $questionnaireCompletionRate }}%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($mr->isEmpty())
                                    <div class="alert alert-light border mb-0">Aucune réponse enregistrée pour le moment.</div>
                                @else
                                    <div class="relation-questionnaire-shell">
                                        <div class="row g-0">
                                            <div class="col-lg-4 col-xl-3 relation-questionnaire-sidebar">
                                                <div class="relation-questionnaire-sidebar-header">
                                                    <h6 class="text-muted text-uppercase small mb-1">Critères</h6>
                                                    <p class="text-body-secondary small mb-0">Navigation par critère du questionnaire.</p>
                                                </div>
                                                <div class="relation-questionnaire-nav">
                                                    <ul class="nav flex-column border-0" role="tablist">
                                                        @foreach ($mr as $r)
                                                            @php
                                                                $criterionTotal = collect($r['items'])->sum(fn ($group) => $group['items']->count());
                                                            @endphp
                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#show-questionnaire-critere-{{ $loop->index }}" type="button" role="tab">
                                                                    <span class="relation-questionnaire-nav-label">
                                                                        <span class="relation-questionnaire-nav-index">{{ $loop->iteration }}</span>
                                                                        <span class="relation-questionnaire-nav-title">{{ $r['critere']?->name ?? 'Critère' }}</span>
                                                                    </span>
                                                                    <span class="relation-questionnaire-nav-count">{{ $criterionTotal }}</span>
                                                                </button>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-xl-9">
                                                <div class="tab-content relation-questionnaire-pane">
                                                    @foreach ($mr as $r)
                                                        <div id="show-questionnaire-critere-{{ $loop->index }}" class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" role="tabpanel">
                                                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
                                                                <div>
                                                                    <p class="text-uppercase text-muted small fw-semibold mb-1">Critère {{ $loop->iteration }}</p>
                                                                    <h5 class="fw-semibold mb-1 text-success">{{ $r['critere']?->name ?? 'Critère' }}</h5>
                                                                    <p class="text-body-secondary mb-0">Visualisation des réponses enregistrées pour ce critère.</p>
                                                                </div>
                                                            </div>

                                                            <div class="tab-base tab-vertical">
                                                                <div class="row g-0">
                                                                    <div class="col-md-4 col-lg-4">
                                                                        <div class="border-end bg-light h-100">
                                                                            <div class="p-3">
                                                                                <h6 class="text-muted text-uppercase small mb-3">Sous-critères</h6>
                                                                                <ul class="nav nav-tabs flex-column border-0" role="tablist">
                                                                                    @foreach($r['items'] as $it)
                                                                                        <li class="nav-item" role="presentation">
                                                                                            <button class="nav-link text-start rounded {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#show-questionnaire-sc-{{ $loop->parent->index }}-{{ $loop->index }}" type="button" role="tab">
                                                                                                {{ $it['sous_critere']?->name ?? 'Sous-critère' }}
                                                                                            </button>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8 col-lg-8">
                                                                        <div class="tab-content p-4" style="min-height: 24rem;">
                                                                            @foreach($r['items'] as $it)
                                                                                <div id="show-questionnaire-sc-{{ $loop->parent->index }}-{{ $loop->index }}" class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" role="tabpanel">
                                                                                    <h6 class="fw-semibold mb-3 text-success">{{ $it['sous_critere']?->name ?? 'Sous-critère' }}</h6>
                                                                                    @foreach ($it['items'] as $rep)
                                                                                        <div class="relation-questionnaire-question-card">
                                                                                            <div class="relation-questionnaire-question-label">{{ $rep->question->name }}</div>
                                                                                            <div class="relation-questionnaire-question-meta">{{ $rep->choice?->name ?? '—' }}</div>
                                                                                        </div>
                                                                                    @endforeach
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
                                        </div>
                                    </div>
                                @endif
                            </div>
                       </div>

                       <div id="_tab6" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
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
                                            <th>
                                                @if($ficheProgrammeReadonly)
                                                    {{ $dossier->programme?->name }}
                                                @else
                                                    <a href="{{ $__programmeShow($dossier->programme?->token) }}">{{ $dossier->programme?->name }}</a>
                                                @endif
                                            </th>
                                            <td>{{ $dossier->programme?->signataire }}</td>
                                            <td>{{ number_format($dossier->programme?->budget,0,',','.') }} XAF</td>
                                            <td>{{ $dossier->programme?->dt_sig_conv ? \Carbon\Carbon::parse($dossier->programme?->dt_sig_conv)->format('d/m/Y') : '—' }}</td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false"><i class="demo-psi-list-view"></i></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ $__dossierShow($dossier) }}">Afficher le dossier</a></li>
                                                        @if(! $ficheProgrammeReadonly)
                                                        <li><a class="dropdown-item" href="{{ $__programmeShow($dossier->programme?->token) }}">Afficher le programme</a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                       </div>
                       <div id="_tab7" class="tab-pane fade" role="tabpanel" aria-labelledby="fichier-tab">
                        <div class="card border-0 bg-light-subtle mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                    <div>
                                        <h5 class="fw-semibold mb-1">Checklist des pieces exigibles</h5>
                                        <p class="text-body-secondary mb-0">Visualisation des pieces fournies et de celles encore attendues.</p>
                                    </div>
                                    <a href="{{ $__piecesUrl }}" class="btn btn-sm btn-outline-primary">
                                        {{ $__piecesBtn }}
                                    </a>
                                </div>

                                @if($checklist->isEmpty())
                                    <p class="text-body-secondary mb-0">Aucune piece exigible parametree.</p>
                                @else
                                    <div class="row g-3">
                                        @foreach ($checklist as $row)
                                            @php
                                                $definition = $row['definition'];
                                                $entreprisePiece = $row['entreprise_piece'];
                                            @endphp
                                            <div class="col-md-6">
                                                <div class="border rounded-3 bg-white p-3 h-100">
                                                    <div class="form-check d-flex align-items-start gap-3 mb-0">
                                                        <input class="form-check-input mt-1" type="checkbox" disabled {{ $row['fourni'] ? 'checked' : '' }}>
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                                                                <label class="form-check-label fw-semibold text-dark mb-0">{{ $definition->label }}</label>
                                                                <span class="badge bg-{{ $row['fourni'] ? 'success' : 'secondary' }}">
                                                                    {{ $row['fourni'] ? 'Fourni' : 'Non fourni' }}
                                                                </span>
                                                            </div>
                                                            @if($definition->description)
                                                                <small class="text-body-secondary d-block mt-1">{{ $definition->description }}</small>
                                                            @endif
                                                            @if($row['fourni'])
                                                                <div class="mt-2 small text-body-secondary">
                                                                    @if($entreprisePiece?->provided_at)
                                                                        <div>Fourni le {{ \Carbon\Carbon::parse($entreprisePiece->provided_at)->format('d/m/Y H:i') }}</div>
                                                                    @endif
                                                                    @if($entreprisePiece?->fichier?->path)
                                                                        <a href="{{ $entreprisePiece->fichier->path }}" target="_blank" class="btn btn-link btn-sm p-0 mt-1">Consulter le document</a>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                   </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
