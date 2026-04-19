@extends($role === 'juridique' ? 'Layouts.juridique' : 'Layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css">
<style>
.summernote-wrapper .note-editor.note-frame {
    border-color: #d8dee6;
    border-radius: 0.75rem;
}

.summernote-wrapper .note-toolbar {
    background: #f8fafc;
    border-bottom-color: #e5e7eb;
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.summernote-wrapper .note-editing-area .note-editable {
    min-height: 240px;
    color: #1f2937;
}

.summernote-wrapper .note-editor .note-toolbar,
.summernote-wrapper .note-editor .note-statusbar {
    display: block !important;
}

.summernote-wrapper .note-editor .note-btn-group {
    display: inline-flex !important;
    align-items: center;
    gap: 0.25rem;
    margin-right: 0.4rem;
}

.summernote-wrapper .note-editor .note-btn {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    min-width: 2rem;
    min-height: 2rem;
    color: #334155 !important;
    background: #fff !important;
    border: 1px solid #d8dee6 !important;
    border-radius: 0.5rem !important;
}

.summernote-wrapper .note-editor .note-btn:hover,
.summernote-wrapper .note-editor .note-btn:focus {
    color: #17310b !important;
    background: rgba(136, 184, 36, 0.10) !important;
    border-color: rgba(136, 184, 36, 0.45) !important;
}

.summernote-wrapper .note-editor .dropdown-toggle::after {
    margin-left: 0.35rem;
}

.summernote-wrapper .note-editor .note-icon-caret,
.summernote-wrapper .note-editor [class^="note-icon-"],
.summernote-wrapper .note-editor [class*=" note-icon-"] {
    display: inline-block !important;
    visibility: visible !important;
}

.relation-questionnaire {
    --accent-color: #88b824;
    --accent-color-dark: #6f9a1d;
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

.rich-text-rendered {
    color: #1f2937;
}

.rich-text-rendered p:last-child {
    margin-bottom: 0;
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

@section('title', 'Prospect — '.$item->name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            @if($role === 'juridique')
                <li class="breadcrumb-item"><a href="{{ route('juridique.prospects.index') }}">File avis juridique</a></li>
            @else
                <li class="breadcrumb-item"><a href="{{ route('conformite.prospects.index') }}">File avis conformité</a></li>
            @endif
            <li class="breadcrumb-item active">{{ Str::limit($item->name, 35) }}</li>
        </ol>
    </nav>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    @php
        $formatMoney = static fn ($value) => ($value !== null && $value !== '') ? number_format((float) $value, 0, ',', ' ') . ' XAF' : '—';
        $formatNumber = static fn ($value) => ($value !== null && $value !== '') ? number_format((float) $value, 0, ',', ' ') : '—';
        $formatDate = static fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '—';
        $formatDateTime = static fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y H:i') : '—';
        $villageQuartier = $item->village_ou_quartier
            ?? (trim(($item->village?->name ?? '') . ' ' . ($item->quartier?->name ?? '')) ?: null);
        $appuisFinanciers = $item->appuis->where('financier', 1)->values();
        $appuisNonFinanciers = $item->appuis->where('financier', 0)->values();
        $totalQuestionnaireItems = $mr->sum(fn ($result) => collect($result['items'])->sum(fn ($group) => $group['items']->count()));
        $answeredQuestionnaireItems = $totalQuestionnaireItems;
        $questionnaireCompletionRate = $totalQuestionnaireItems > 0 ? 100 : 0;
    @endphp

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        <h4 class="mb-0">{{ $item->name }}</h4>
                        <span class="badge bg-danger">Prospect</span>
                        <span class="badge bg-info">Soumis {{ $formatDateTime($item->prospect_submitted_at) }}</span>
                    </div>
                    <p class="small text-body-secondary mb-1">Le responsable {{ $role === 'juridique' ? 'juridique' : 'conformité' }} consulte l’intégralité du dossier avant avis.</p>
                    <p class="mb-0 small">{{ $item->manager ?? '—' }} · {{ $item->phone ?? '—' }} · {{ $item->email ?? '—' }}</p>
                </div>
                <div class="text-md-end small text-body-secondary">
                    <div>Agence: <strong class="text-dark">{{ $item->agence?->name ?? '—' }}</strong></div>
                    <div>Direction: <strong class="text-dark">{{ $item->agence?->representation?->name ?? '—' }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted text-uppercase">Localisation</small><p class="mb-0 mt-1">{{ $item->arrondissement?->name ?? '—' }} @if($item->region) / {{ $item->region->name }} @endif</p></div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted text-uppercase">Contact</small><p class="mb-0 mt-1">{{ $item->manager ?? '—' }}<br><span class="text-body-secondary">{{ $item->phone ?? '—' }} · {{ $item->email ?? '—' }}</span></p></div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted text-uppercase">Agence</small><p class="mb-0 mt-1">{{ $item->agence?->name ?? '—' }}</p></div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted text-uppercase">Produit principal</small><p class="mb-0 mt-1">{{ $item->produit?->name ?? '—' }}</p></div></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3"><strong>Identité de l'entreprise</strong></div>
                <div class="card-body">
                    <dl class="row mb-0 g-2">
                        <dt class="col-sm-5 text-muted small">Dénomination</dt><dd class="col-sm-7">{{ $item->name ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">RCCM</dt><dd class="col-sm-7">{{ $item->rccm ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">NIU</dt><dd class="col-sm-7">{{ $item->niu ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Numéro employeur / assurance</dt><dd class="col-sm-7">{{ $item->cnps ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Forme juridique</dt><dd class="col-sm-7">{{ $item->forme?->name ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Taille</dt><dd class="col-sm-7">{{ $item->taille ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Caractère</dt><dd class="col-sm-7">{{ $item->caractere ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Système comptable</dt><dd class="col-sm-7">{{ $item->systeme ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Mobile Money</dt><dd class="col-sm-7">{{ $item->mm_phone ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3"><strong>Localisation et contacts</strong></div>
                <div class="card-body">
                    <dl class="row mb-0 g-2">
                        <dt class="col-sm-5 text-muted small">Région</dt><dd class="col-sm-7">{{ $item->region?->name ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Département</dt><dd class="col-sm-7">{{ $item->departement?->name ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Commune</dt><dd class="col-sm-7">{{ $item->arrondissement?->name ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Village ou quartier</dt><dd class="col-sm-7">{{ $villageQuartier ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Latitude</dt><dd class="col-sm-7">{{ $item->latitude ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Longitude</dt><dd class="col-sm-7">{{ $item->longitude ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Téléphone</dt><dd class="col-sm-7">{{ $item->phone ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">E-mail</dt><dd class="col-sm-7">{{ $item->email ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Agence</dt><dd class="col-sm-7">{{ $item->agence?->name ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Direction</dt><dd class="col-sm-7">{{ $item->agence?->representation?->name ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3"><strong>Profil du dirigeant</strong></div>
                <div class="card-body">
                    <dl class="row mb-0 g-2">
                        <dt class="col-sm-5 text-muted small">Nom du dirigeant</dt><dd class="col-sm-7">{{ $item->manager ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Contact dirigeant</dt><dd class="col-sm-7">{{ $item->manager_contact ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Sexe</dt><dd class="col-sm-7">{{ $item->manager_sexe ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Niveau d'instruction</dt><dd class="col-sm-7">{{ $item->manager_niveau ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Date de naissance</dt><dd class="col-sm-7">{{ $formatDate($item->manager_dtn) }}</dd>
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
                <div class="card-header bg-white border-bottom py-3"><strong>Structure et indicateurs</strong></div>
                <div class="card-body">
                    <dl class="row mb-0 g-2">
                        <dt class="col-sm-5 text-muted small">Date de création formelle</dt><dd class="col-sm-7">{{ $formatDate($item->dt_creation) }}</dd>
                        <dt class="col-sm-5 text-muted small">Début des activités</dt><dd class="col-sm-7">{{ $formatDate($item->dt_start) }}</dd>
                        <dt class="col-sm-5 text-muted small">Capital social</dt><dd class="col-sm-7">{{ $formatMoney($item->capital) }}</dd>
                        <dt class="col-sm-5 text-muted small">Chiffre d'affaire</dt><dd class="col-sm-7">{{ $formatMoney($item->chiffre_affaire) }}</dd>
                        <dt class="col-sm-5 text-muted small">Ressources propres</dt><dd class="col-sm-7">{{ $formatMoney($item->ressources_propres) }}</dd>
                        <dt class="col-sm-5 text-muted small">Total actif</dt><dd class="col-sm-7">{{ $formatMoney($item->total_actif) }}</dd>
                        <dt class="col-sm-5 text-muted small">Nombre total de personnes</dt><dd class="col-sm-7">{{ $formatNumber($item->nb_personnel) }}</dd>
                        <dt class="col-sm-5 text-muted small">Personnel permanent</dt><dd class="col-sm-7">{{ $formatNumber($item->nb_personnel_permanent) }}</dd>
                        <dt class="col-sm-5 text-muted small">Personnel saisonnier</dt><dd class="col-sm-7">{{ $formatNumber($item->nb_personnel_saisonier) }}</dd>
                        <dt class="col-sm-5 text-muted small">Type de personnel dominant</dt><dd class="col-sm-7">{{ $item->tperso !== 'xxx' ? ucfirst($item->tperso) : '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3"><strong>Activités</strong></div>
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
                        <dt class="col-sm-5 text-muted small">Ancienneté produit principal</dt>
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
                                <span class="badge bg-light text-dark border">{{ trim(($produit->code ? $produit->code . ' ' : '') . $produit->name) }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-body-secondary mb-0">Aucun produit secondaire renseigné.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3"><strong>Besoins exprimés</strong></div>
                <div class="card-body">
                    <h6 class="mb-2 fw-semibold">Appuis financiers souhaités</h6>
                    @if($appuisFinanciers->isNotEmpty())
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @foreach($appuisFinanciers as $service)
                                <span class="badge bg-light text-dark border">{{ $service->name }}@if($service->type) ({{ $service->type->name }}) @endif</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-body-secondary">Aucun appui financier renseigné.</p>
                    @endif

                    <h6 class="mb-2 fw-semibold">Appuis non financiers souhaités</h6>
                    @if($appuisNonFinanciers->isNotEmpty())
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($appuisNonFinanciers as $service)
                                <span class="badge bg-light text-dark border">{{ $service->name }}@if($service->type) ({{ $service->type->name }}) @endif</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-body-secondary mb-0">Aucun appui non financier renseigné.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            @include('Prospects.partials.tiers_section', ['item' => $item, 'role' => $role])
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3"><strong>Checklist des pièces exigibles</strong></div>
                <div class="card-body">
                    @if($checklist->isEmpty())
                        <p class="text-body-secondary mb-0">Aucune pièce exigible paramétrée.</p>
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
                                                    <span class="badge bg-{{ $row['fourni'] ? 'success' : 'secondary' }}">{{ $row['fourni'] ? 'Fourni' : 'Non fourni' }}</span>
                                                </div>
                                                @if($definition->description)
                                                    <small class="text-body-secondary d-block mt-1">{{ $definition->description }}</small>
                                                @endif
                                                @if($row['fourni'])
                                                    <div class="mt-2 small text-body-secondary">
                                                        @if($entreprisePiece?->provided_at)
                                                            <div>Fourni le {{ $formatDateTime($entreprisePiece->provided_at) }}</div>
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
            <div class="relation-questionnaire">
                <div class="card relation-questionnaire-summary border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-7">
                                <p class="text-uppercase text-muted small fw-semibold mb-2">Questionnaire de mise en relation</p>
                                <h5 class="mb-2">Réponses enregistrées</h5>
                                <p class="text-body-secondary mb-0">Consultez les réponses structurées par critère et sous-critère avant de rendre votre avis.</p>
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
                    <div class="card-header bg-white border-bottom py-3"><strong>Questionnaire d'entrée en relation</strong></div>
                    <div class="card-body">
                        @if($mr->isEmpty())
                            <p class="text-body-secondary mb-0">Aucune réponse enregistrée pour le moment.</p>
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
                                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#review-questionnaire-critere-{{ $loop->index }}" type="button" role="tab">
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
                                                <div id="review-questionnaire-critere-{{ $loop->index }}" class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" role="tabpanel">
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
                                                                                    <button class="nav-link text-start rounded {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#review-questionnaire-sc-{{ $loop->parent->index }}-{{ $loop->index }}" type="button" role="tab">
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
                                                                        <div id="review-questionnaire-sc-{{ $loop->parent->index }}-{{ $loop->index }}" class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" role="tabpanel">
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
                        <span class="badge bg-success ms-2">{{ $formatDateTime($item->juridique_avis_at) }}</span>
                    @else
                        <span class="badge bg-light text-dark ms-2">En attente</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($item->juridique_avis)
                        <p class="mb-2 small text-muted">{{ $item->juridiqueAvisUser?->name ?? '—' }}</p>
                        <div class="text-body rich-text-rendered">{!! $item->juridique_avis !!}</div>
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
                        <span class="badge bg-success ms-2">{{ $formatDateTime($item->conformite_avis_at) }}</span>
                    @else
                        <span class="badge bg-light text-dark ms-2">En attente</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($item->conformite_avis)
                        <p class="mb-2 small text-muted">{{ $item->conformiteAvisUser?->name ?? '—' }}</p>
                        <div class="text-body rich-text-rendered">{!! $item->conformite_avis !!}</div>
                    @else
                        <p class="text-body-secondary mb-0">Aucun avis enregistré pour l’instant.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            @if($role === 'juridique')
                @if($pending)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white"><strong>Votre avis juridique</strong></div>
                        <div class="card-body">
                            <form action="{{ route('juridique.prospects.avis', $item->token) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="juridique_avis" class="form-label">Avis <span class="text-danger">*</span></label>
                                    <div class="summernote-wrapper">
                                        <textarea name="juridique_avis" id="juridique_avis" rows="8" class="form-control js-summernote-fr @error('juridique_avis') is-invalid @enderror" required>{{ old('juridique_avis') }}</textarea>
                                    </div>
                                    @error('juridique_avis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Enregistrer l’avis</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-success mb-0">
                        Avis juridique enregistré le {{ $formatDateTime($item->juridique_avis_at) }}.
                        <a href="{{ route('juridique.prospects.index') }}" class="alert-link">Retour à la liste</a>
                    </div>
                @endif
            @else
                @if($pending)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white"><strong>Votre avis conformité</strong></div>
                        <div class="card-body">
                            <form action="{{ route('conformite.prospects.avis', $item->token) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="conformite_avis" class="form-label">Avis <span class="text-danger">*</span></label>
                                    <div class="summernote-wrapper">
                                        <textarea name="conformite_avis" id="conformite_avis" rows="8" class="form-control js-summernote-fr @error('conformite_avis') is-invalid @enderror" required>{{ old('conformite_avis') }}</textarea>
                                    </div>
                                    @error('conformite_avis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Enregistrer l’avis</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-success mb-0">
                        Avis conformité enregistré le {{ $formatDateTime($item->conformite_avis_at) }}.
                        <a href="{{ route('conformite.prospects.index') }}" class="alert-link">Retour à la liste</a>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-fr-FR.min.js"></script>
<script>
    jQuery(document).ready(function($) {
        console.log('summernote loaded');
        jQuery('.js-summernote-fr').summernote({
            lang: 'fr-FR',
            height: 260,
            placeholder: 'Saisissez votre avis...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'hr']],
                ['view', ['codeview']]
            ],
            callbacks: {
                onChange: function (contents) {
                    jQuery(this).val(contents);
                }
            }
        });
    });
</script>
@endsection
