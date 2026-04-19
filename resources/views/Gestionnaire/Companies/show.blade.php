@extends('Layouts.gestionnaire')

@push('styles')
<style>
.relation-questionnaire {
    --accent-color: #88b824;
    --accent-color-dark: #6f9a1d;
    --accent-soft: rgba(136, 184, 36, 0.10);
}

.relation-questionnaire-summary {
    border: 1px solid rgba(136, 184, 36, 0.16);
    background: linear-gradient(135deg, rgba(136, 184, 36, 0.12), rgba(255, 255, 255, 0.94));
}

.relation-questionnaire-progress {
    height: 0.55rem;
    border-radius: 999px;
    background: #e9eef5;
    overflow: hidden;
}

.relation-questionnaire-progress-bar {
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(90deg, var(--accent-color), var(--accent-color-dark));
}

.relation-questionnaire-shell {
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    overflow: hidden;
}

.relation-questionnaire-sidebar {
    background: #f8fafc;
    border-right: 1px solid #e5e7eb;
}

.relation-questionnaire-sidebar-header {
    padding: 1rem 1rem 0.5rem;
}

.relation-questionnaire-nav {
    padding: 0 0.75rem 1rem;
}

.relation-questionnaire-nav .nav-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    width: 100%;
    margin-bottom: 0.5rem;
    padding: 0.8rem 0.9rem;
    border: 1px solid transparent;
    border-radius: 0.85rem;
    color: #334155;
    background: transparent;
    transition: all 0.2s ease;
}

.relation-questionnaire-nav .nav-link:hover {
    border-color: #d9e2ec;
    background: #fff;
}

.relation-questionnaire-nav .nav-link.active {
    color: #17310b;
    border-color: rgba(136, 184, 36, 0.28);
    background: rgba(136, 184, 36, 0.12);
    box-shadow: inset 0 0 0 1px rgba(136, 184, 36, 0.08);
}

.relation-questionnaire-nav-label {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    min-width: 0;
}

.relation-questionnaire-nav-index {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.65rem;
    height: 1.65rem;
    border-radius: 999px;
    background: #e9eef5;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 700;
    flex: 0 0 auto;
}

.relation-questionnaire-nav .nav-link.active .relation-questionnaire-nav-index {
    color: #fff;
    background: var(--accent-color);
}

.relation-questionnaire-nav-title {
    min-width: 0;
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.25;
    text-align: left;
}

.relation-questionnaire-nav-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 3.1rem;
    padding: 0.2rem 0.55rem;
    border-radius: 999px;
    background: #fff;
    color: #64748b;
    font-size: 0.72rem;
    font-weight: 700;
}

.relation-questionnaire-nav .nav-link.active .relation-questionnaire-nav-count {
    color: #4e6d14;
    background: rgba(255, 255, 255, 0.72);
}

.relation-questionnaire-pane {
    padding: 1.5rem;
}

.relation-questionnaire-question-card {
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    padding: 1rem;
    background: #fff;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
}

.relation-questionnaire-question-card + .relation-questionnaire-question-card {
    margin-top: 1rem;
}

.relation-questionnaire-question-label {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.35rem;
}

.relation-questionnaire-question-meta {
    font-size: 0.82rem;
    color: #64748b;
}

@media (max-width: 991.98px) {
    .relation-questionnaire-sidebar {
        border-right: 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .relation-questionnaire-pane {
        padding: 1rem;
    }
}
</style>
@endpush

@section('title', $item->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.index') }}">Entreprises</a></li>
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
        <li><a class="dropdown-item" data-bs-target="#addAppuiModal" data-bs-toggle="modal" href="#"><i class="demo-psi-add me-2"></i>Ajouter un appui</a></li>
        <li><a class="dropdown-item" data-bs-target="#addElementModal" data-bs-toggle="modal" href="#"><i class="demo-psi-file me-2"></i>Ajouter une pièce</a></li>
        <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.pieces-exigibles.index',$item->token) }}"><i class="demo-psi-file-text-image me-2"></i>Checklist pièces exigibles</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprise.questionnaire',$item->token) }}"><i class="demo-psi-file-edit me-2"></i>Questionnaire de mise en relation</a></li>
        <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprise.physique.create',$item->token) }}"><i class="demo-psi-male me-2"></i>Tiers personne physique</a></li>
        <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprise.morale.create',$item->token) }}"><i class="demo-psi-building me-2"></i>Tiers personne morale</a></li>
        <li><a class="dropdown-item" data-bs-target="#addProgModal" data-bs-toggle="modal" href="#"><i class="demo-psi-affiliate me-2"></i>Affecter à un programme</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.edit',$item->token) }}"><i class="demo-psi-pen-5 me-2"></i>Completer la fiche</a></li>
        <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprise.get.engagements',$item->token) }}"><i class="demo-psi-file-text-image me-2"></i>État des engagements</a></li>
        <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.analyse-critique.show',$item->token) }}"><i class="demo-psi-file-edit me-2"></i>Dossier d'analyse critique</a></li>
    </ul>
</div>
@endsection

@section('page-header')
<div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <h5 class="page-title mb-0">{{ $item->name }}</h5>
        @if($item->prospect)
            <span class="badge bg-danger">Prospect</span>
        @endif
        <span class="badge bg-secondary">{{ $item->taille }}</span>
        <span class="badge bg-{{ $item->caractere === 'Formel' ? 'success' : 'warning' }}">{{ $item->caractere }}</span>
    </div>
    <p class="text-body-secondary mb-0 mt-1">Dossier entreprise — {{ $item->forme?->name ?? '—' }}</p>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif

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
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab4" type="button" role="tab" aria-controls="tab4" aria-selected="false" tabindex="-1">La mise en relation</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab6" type="button" role="tab" aria-controls="tab6" aria-selected="false" tabindex="-1">Programmes</button>
                         </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab7" type="button" role="tab" aria-controls="tab7" aria-selected="false" tabindex="-1">Pièces constitutives</button>
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
                                            <th><a href="{{ route('gestionnaire.programmes.show',$dossier->programme?->token) }}">{{ $dossier->programme?->name }}</a></th>
                                            <td>{{ $dossier->programme?->signataire }}</td>
                                            <td>{{ number_format($dossier->programme?->budget,0,',','.') }} XAF</td>
                                            <td>{{ \Carbon\Carbon::parse($dossier->programme?->dt_sig_conv)->format('d/m/Y') }}</td>
                                            <td>
                                                <div>
                                                    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false"><i class="demo-psi-list-view"></i></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('gestionnaire.dossiers.show',$dossier->token) }}">Afficher le dossier</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('gestionnaire.programmes.show',$dossier->programme?->token) }}">Afficher le programme</a></li>

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
                                    <a href="{{ route('gestionnaire.entreprises.pieces-exigibles.index', $item->token) }}" class="btn btn-sm btn-outline-primary">
                                        Gerer les pieces exigibles
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

                        <h5 class="fw-semibold mb-3">Pieces constitutives versees au dossier</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <th>DOCUMENT</th>
                                    <th>LIEN</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($item->elements as $element)
                                       <tr>
                                            <td>{{ $element->type?->name }}</td>
                                            <td><a class="btn-link" href="{{ $element->path }}">Cliquer ici </a></td>
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

    <div class="modal fade" id="addProgModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Affectation à un programme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('gestionnaire.entreprise.programme.save') }}" method="post">
                        @csrf
                        <input type="hidden" name="entreprise_id" value="{{ $item->id }}">
                            <div class="">
                                <div class="mb-3">
                                    <label for="programme_id" class="form-label">Programme</label>
                                    <select required name="programme_id" id="programme_id" class="form-select">
                                        <option value="0">Selectionner un programme ...</option>
                                        @foreach($programmes as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="analyste_id" class="form-label">Analyste financier</label>
                                    <select required name="analyste_id" id="analyste_id" class="form-select">
                                        <option value="0">Selectionner un analyste ...</option>
                                        @foreach($analystes as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }}-{{ $it->agence?->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addAppuiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un appui</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('gestionnaire.entreprise.appui.save') }}" method="post">
                        @csrf
                        <input type="hidden" name="entreprise_id" value="{{ $item->id }}">
                            <div class="">
                                <div class="form-group">
                                    <label class="form-label">Appui</label>
                                    <select required name="appui_id" id="appui_id" class="form-select">
                                        <option value="0">Selectionner un appui ...</option>
                                        @foreach($appuis as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }} ({{ $it->financier?'financier':'non financier' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addElementModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une pièce constitutive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('gestionnaire.entreprise.element.save') }}" method="post">
                        @csrf
                        <input type="hidden" name="entreprise_id" value="{{ $item->id }}">
                            <div class="mb-3">
                                    <label for="type_id" class="form-label">Type de pièce</label>
                                    <select required name="type_id" id="type_id" class="form-select">
                                        <option value="0">Selectionner un type de pièce  ...</option>
                                        @foreach($elements as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="fichier" class="form-label">Fichier</label>
                                    <input type="file" name="fichier" id="fichier" class="form-control">
                                </div>
                            </div>
                        <div class="mt-5 d-grid">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
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
