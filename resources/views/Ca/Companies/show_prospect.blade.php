@extends('Layouts.ca')

@include('partials.entreprise-fiche-styles')

@section('title', $item->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.entreprises.prospects') }}">Prospects</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 40) }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="demo-psi-dot-vertical me-1"></i> Actions
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('ca.entreprises.prospects') }}">Retour liste</a></li>
            <li><a class="dropdown-item" href="{{ route('ca.entreprise.get.engagements', $item->token) }}"><i class="demo-psi-file-text-image me-2"></i>État des engagements</a></li>
            @if($item->juridique_avis_at && $item->conformite_avis_at && ! $item->promu_client_at && ! $item->prospect_rejected_at)
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-success fw-semibold" href="{{ route('ca.workflow.prospects.show', $item->token) }}">Validation / refus prospect (chef d'agence)</a></li>
            @endif
        </ul>
    </div>
@endsection

@section('page-header')
<div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <h5 class="page-title mb-0">{{ $item->name }}</h5>
        <span class="badge bg-danger">Prospect</span>
        @if($item->prospect_submitted_at)
            <span class="badge bg-info">Soumis {{ \Illuminate\Support\Carbon::parse($item->prospect_submitted_at)->format('d/m/Y H:i') }}</span>
        @else
            <span class="badge bg-secondary">Brouillon — non soumis</span>
        @endif
    </div>
    <p class="text-body-secondary mb-0 mt-1">Workflow entrée en relation — avis juridique &amp; conformité</p>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show">{{ session('info') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@include('partials.entreprise-chef-agence-decision', ['item' => $item])

@php
    $formatMoney = static fn ($value) => ($value !== null && $value !== '') ? number_format((float) $value, 0, ',', ' ') . ' XAF' : '—';
    $formatNumber = static fn ($value) => ($value !== null && $value !== '') ? number_format((float) $value, 0, ',', ' ') : '—';
    $formatDate = static fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '—';
    $villageQuartier = $item->village_ou_quartier
        ?? (trim(($item->village?->name ?? '') . ' ' . ($item->quartier?->name ?? '')) ?: null);
    $appuisFinanciers = $item->appuis->where('financier', 1)->values();
    $appuisNonFinanciers = $item->appuis->where('financier', 0)->values();
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted text-uppercase">Localisation</small>
                <p class="mb-0 mt-1">{{ $item->arrondissement?->name ?? '—' }} @if($item->region) / {{ $item->region->name }} @endif</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted text-uppercase">Contact</small>
                <p class="mb-0 mt-1">{{ $item->manager ?? '—' }}<br><span class="text-body-secondary">{{ $item->phone ?? '—' }} · {{ $item->email ?? '—' }}</span></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted text-uppercase">Agence</small>
                <p class="mb-0 mt-1">{{ $item->agence?->name ?? '—' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted text-uppercase">Produit principal</small>
                <p class="mb-0 mt-1">{{ $item->produit?->name ?? '—' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <strong>Identite de l'entreprise</strong>
            </div>
            <div class="card-body">
                <dl class="row mb-0 g-2">
                    <dt class="col-sm-5 text-muted small">Denomination</dt>
                    <dd class="col-sm-7">{{ $item->name ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">RCCM</dt>
                    <dd class="col-sm-7">{{ $item->rccm ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">NIU</dt>
                    <dd class="col-sm-7">{{ $item->niu ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Numero employeur / assurance</dt>
                    <dd class="col-sm-7">{{ $item->cnps ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Forme juridique</dt>
                    <dd class="col-sm-7">{{ $item->forme?->name ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Taille</dt>
                    <dd class="col-sm-7">{{ $item->taille ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Caractere</dt>
                    <dd class="col-sm-7">{{ $item->caractere ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Systeme comptable</dt>
                    <dd class="col-sm-7">{{ $item->systeme ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Mobile Money</dt>
                    <dd class="col-sm-7">{{ $item->mm_phone ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <strong>Localisation et contacts</strong>
            </div>
            <div class="card-body">
                <dl class="row mb-0 g-2">
                    <dt class="col-sm-5 text-muted small">Region</dt>
                    <dd class="col-sm-7">{{ $item->region?->name ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Departement</dt>
                    <dd class="col-sm-7">{{ $item->departement?->name ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Commune</dt>
                    <dd class="col-sm-7">{{ $item->arrondissement?->name ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Village ou quartier</dt>
                    <dd class="col-sm-7">{{ $villageQuartier ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Latitude</dt>
                    <dd class="col-sm-7">{{ $item->latitude ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Longitude</dt>
                    <dd class="col-sm-7">{{ $item->longitude ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Telephone</dt>
                    <dd class="col-sm-7">{{ $item->phone ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">E-mail</dt>
                    <dd class="col-sm-7">{{ $item->email ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Agence</dt>
                    <dd class="col-sm-7">{{ $item->agence?->name ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Direction</dt>
                    <dd class="col-sm-7">{{ $item->agence?->representation?->name ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <strong>Profil du dirigeant</strong>
            </div>
            <div class="card-body">
                <dl class="row mb-0 g-2">
                    <dt class="col-sm-5 text-muted small">Nom du dirigeant</dt>
                    <dd class="col-sm-7">{{ $item->manager ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Contact dirigeant</dt>
                    <dd class="col-sm-7">{{ $item->manager_contact ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Sexe</dt>
                    <dd class="col-sm-7">{{ $item->manager_sexe ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Niveau d'instruction</dt>
                    <dd class="col-sm-7">{{ $item->manager_niveau ?? '—' }}</dd>

                    <dt class="col-sm-5 text-muted small">Date de naissance</dt>
                    <dd class="col-sm-7">{{ $formatDate($item->manager_dtn) }}</dd>

                    <dt class="col-sm-5 text-muted small">Dirigeant promoteur</dt>
                    <dd class="col-sm-7">
                        @if($item->manager_promoteur === null)
                            —
                        @else
                            {{ $item->manager_promoteur ? 'Oui' : 'Non' }}
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <strong>Structure et indicateurs</strong>
            </div>
            <div class="card-body">
                <dl class="row mb-0 g-2">
                    <dt class="col-sm-5 text-muted small">Date de creation formelle</dt>
                    <dd class="col-sm-7">{{ $formatDate($item->dt_creation) }}</dd>

                    <dt class="col-sm-5 text-muted small">Debut des activites</dt>
                    <dd class="col-sm-7">{{ $formatDate($item->dt_start) }}</dd>

                    <dt class="col-sm-5 text-muted small">Capital social</dt>
                    <dd class="col-sm-7">{{ $formatMoney($item->capital) }}</dd>

                    <dt class="col-sm-5 text-muted small">Chiffre d'affaire</dt>
                    <dd class="col-sm-7">{{ $formatMoney($item->chiffre_affaire) }}</dd>

                    <dt class="col-sm-5 text-muted small">Ressources propres</dt>
                    <dd class="col-sm-7">{{ $formatMoney($item->ressources_propres) }}</dd>

                    <dt class="col-sm-5 text-muted small">Total actif</dt>
                    <dd class="col-sm-7">{{ $formatMoney($item->total_actif) }}</dd>

                    <dt class="col-sm-5 text-muted small">Nombre total de personnes</dt>
                    <dd class="col-sm-7">{{ $formatNumber($item->nb_personnel) }}</dd>

                    <dt class="col-sm-5 text-muted small">Personnel permanent</dt>
                    <dd class="col-sm-7">{{ $formatNumber($item->nb_personnel_permanent) }}</dd>

                    <dt class="col-sm-5 text-muted small">Personnel saisonnier</dt>
                    <dd class="col-sm-7">{{ $formatNumber($item->nb_personnel_saisonier) }}</dd>

                    <dt class="col-sm-5 text-muted small">Type de personnel dominant</dt>
                    <dd class="col-sm-7">{{ $item->tperso !== 'xxx' ? ucfirst($item->tperso) : '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <strong>Activites</strong>
            </div>
            <div class="card-body">
                <dl class="row g-2">
                    <dt class="col-sm-5 text-muted small">Produit principal</dt>
                    <dd class="col-sm-7">
                        @if($item->produit)
                            {{ trim(($item->produit->code ? $item->produit->code . ' ' : '') . $item->produit->name) }}
                        @else
                            —
                        @endif
                    </dd>

                    <dt class="col-sm-5 text-muted small">Anciennete produit principal</dt>
                    <dd class="col-sm-7">
                        @if($item->produit_year_start !== null && $item->produit_year_start !== '')
                            {{ $item->produit_year_start }} an(s)
                        @else
                            —
                        @endif
                    </dd>
                </dl>

                <h6 class="mt-4 mb-2 fw-semibold">Produits secondaires</h6>
                @if($item->produits->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($item->produits as $produit)
                            <span class="badge bg-light text-dark border">
                                {{ trim(($produit->code ? $produit->code . ' ' : '') . $produit->name) }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-body-secondary mb-0">Aucun produit secondaire renseigne.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <strong>Besoins exprimes</strong>
            </div>
            <div class="card-body">
                <h6 class="mb-2 fw-semibold">Appuis financiers souhaites</h6>
                @if($appuisFinanciers->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach($appuisFinanciers as $service)
                            <span class="badge bg-light text-dark border">
                                {{ $service->name }}@if($service->type) ({{ $service->type->name }}) @endif
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-body-secondary">Aucun appui financier renseigne.</p>
                @endif

                <h6 class="mb-2 fw-semibold">Appuis non financiers souhaites</h6>
                @if($appuisNonFinanciers->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($appuisNonFinanciers as $service)
                            <span class="badge bg-light text-dark border">
                                {{ $service->name }}@if($service->type) ({{ $service->type->name }}) @endif
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-body-secondary mb-0">Aucun appui non financier renseigne.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12">
        @include('Gestionnaire.Companies.partials.tiers_section', ['item' => $item, 'tiers_readonly' => true, 'tiers_entreprise_show_route' => 'ca.entreprises.show'])
    </div>
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <strong>Checklist des pieces exigibles</strong>
            </div>
            <div class="card-body">
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
    <div class="col-12">
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

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <strong>Questionnaire d'entree en relation</strong>
                </div>
                <div class="card-body">
                    @if($mr->isEmpty())
                        <p class="text-body-secondary mb-0">Aucune reponse enregistree pour le moment.</p>
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
                                            @foreach ($mr as $result)
                                                @php
                                                    $criterionTotal = collect($result['items'])->sum(fn ($group) => $group['items']->count());
                                                @endphp
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#prospect-questionnaire-critere-{{ $loop->index }}" type="button" role="tab">
                                                        <span class="relation-questionnaire-nav-label">
                                                            <span class="relation-questionnaire-nav-index">{{ $loop->iteration }}</span>
                                                            <span class="relation-questionnaire-nav-title">{{ $result['critere']?->name ?? 'Critère' }}</span>
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
                                        @foreach ($mr as $result)
                                            <div id="prospect-questionnaire-critere-{{ $loop->index }}" class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" role="tabpanel">
                                                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
                                                    <div>
                                                        <p class="text-uppercase text-muted small fw-semibold mb-1">Critère {{ $loop->iteration }}</p>
                                                        <h5 class="fw-semibold mb-1 text-success">{{ $result['critere']?->name ?? 'Critère' }}</h5>
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
                                                                        @foreach($result['items'] as $group)
                                                                            <li class="nav-item" role="presentation">
                                                                                <button class="nav-link text-start rounded {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#prospect-questionnaire-sc-{{ $loop->parent->index }}-{{ $loop->index }}" type="button" role="tab">
                                                                                    {{ $group['sous_critere']?->name ?? 'Sous-critère' }}
                                                                                </button>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8 col-lg-8">
                                                            <div class="tab-content p-4" style="min-height: 24rem;">
                                                                @foreach($result['items'] as $group)
                                                                    <div id="prospect-questionnaire-sc-{{ $loop->parent->index }}-{{ $loop->index }}" class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" role="tabpanel">
                                                                        <h6 class="fw-semibold mb-3 text-success">{{ $group['sous_critere']?->name ?? 'Sous-critère' }}</h6>
                                                                        @foreach ($group['items'] as $rep)
                                                                            <div class="relation-questionnaire-question-card">
                                                                                <div class="relation-questionnaire-question-label">{{ $rep->question?->name ?? '—' }}</div>
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
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <strong>Avis juridique</strong>
                @if($item->juridique_avis_at)
                    <span class="badge bg-success ms-2">{{ \Illuminate\Support\Carbon::parse($item->juridique_avis_at)->format('d/m/Y H:i') }}</span>
                @else
                    <span class="badge bg-light text-dark ms-2">En attente</span>
                @endif
            </div>
            <div class="card-body">
                @if($item->juridique_avis)
                    <p class="mb-2 small text-muted">{{ $item->juridiqueAvisUser?->name ?? '—' }}</p>
                        <div class="text-body"><div class="rich-text-rendered"> <?php echo $item->juridique_avis; ?></div></div>
                @else
                    <p class="text-body-secondary mb-0">Aucun avis enregistré pour l’instant.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <strong>Avis conformité</strong>
                @if($item->conformite_avis_at)
                    <span class="badge bg-success ms-2">{{ \Illuminate\Support\Carbon::parse($item->conformite_avis_at)->format('d/m/Y H:i') }}</span>
                @else
                    <span class="badge bg-light text-dark ms-2">En attente</span>
                @endif
            </div>
            <div class="card-body">
                @if($item->conformite_avis)
                    <p class="mb-2 small text-muted">{{ $item->conformiteAvisUser?->name ?? '—' }}</p>
                        <div class="text-body"> <div class="rich-text-rendered"> <?= $item->conformite_avis; ?></div></div>
                @else
                    <p class="text-body-secondary mb-0">Aucun avis enregistré pour l’instant.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
