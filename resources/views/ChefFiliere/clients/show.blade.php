@extends('Layouts.chef_filiere')

@include('partials.entreprise-fiche-styles')

@section('title', $item->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.clients.index') }}">Clients</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 40) }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown button-id="chefFiliereClientShowActions">
        <li><a class="dropdown-item" href="{{ route('chef-filiere.clients.index') }}">Retour à la liste</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('chef-filiere.clients.besoins-produits.edit', $item->token) }}#besoins"><i class="demo-psi-file-edit me-2"></i>Modifier les besoins (appuis)</a></li>
        <li><a class="dropdown-item" href="{{ route('chef-filiere.clients.besoins-produits.edit', $item->token) }}#produit-principal"><i class="demo-psi-file-edit me-2"></i>Modifier le produit principal</a></li>
        <li><a class="dropdown-item" href="{{ route('chef-filiere.clients.besoins-produits.edit', $item->token) }}#produits-secondaires"><i class="demo-psi-file-edit me-2"></i>Modifier les produits secondaires</a></li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
<div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <h5 class="page-title mb-0">{{ $item->name }}</h5>
        <span class="badge bg-success">Client</span>
        <span class="badge bg-secondary">{{ $item->taille ?? '—' }}</span>
        <span class="badge bg-{{ $item->caractere === 'Formel' ? 'success' : 'warning' }}">{{ $item->caractere ?? '—' }}</span>
    </div>
    <p class="text-body-secondary mb-0 mt-1">Portefeuille client — {{ $item->forme?->name ?? '—' }} — {{ $item->agence?->name ?? '—' }}</p>
</div>
@endsection

@section('content')
<div class="cf-page">
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button></div>
@endif
@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show">{{ session('info') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button></div>
@endif

@php
    $eer = $item->dossierEntreeRelation;
    $qualifUrl = route('chef-filiere.qualifications.show', $item->token);
    $isQualifAuthor = $eer && (int) auth()->id() === (int) $eer->qualification_user_id;
    $hasCompletedQualif = $eer && $eer->qualification_completed_at;
    $showQualifDetail = $eer && (
        $eer->qualification_completed_at
        || $eer->programmes_submitted_at
        || $eer->qualification_validated_by_agence_at
        || in_array($eer->statut, [
            \App\Models\DossierEntreeRelation::STATUT_QUALIFIE,
            \App\Models\DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION,
            \App\Models\DossierEntreeRelation::STATUT_QUALIFICATION_AGENCE_VALIDEE,
            \App\Models\DossierEntreeRelation::STATUT_INSTRUCTION_VALIDEE,
        ], true)
    );
@endphp

@include('partials.qualification-section-compact', [
    'item' => $item,
    'eer' => $eer,
    'qualifUrl' => $qualifUrl,
    'hasCompletedQualif' => $hasCompletedQualif,
    'isQualifAuthor' => $isQualifAuthor,
    'showQualifDetail' => $showQualifDetail,
    'workspace' => 'chef-filiere',
])

@php
    $eerClient = $item->dossierEntreeRelation;
    $chefInstructionDossiers = $item->dossiers->filter(fn ($d) => $d->instructionProgrammes->isNotEmpty())->sortByDesc('created_at')->values();
    $canComposeBundle = $eerClient
        && $eerClient->qualification_validated_by_agence_at
        && isset($bundleAvailableProgrammes)
        && $bundleAvailableProgrammes->isNotEmpty();
@endphp

@if($eerClient && $eerClient->qualification_validated_by_agence_at && $chefInstructionDossiers->isNotEmpty())
<div class="card cf-client-card mb-4 border-start border-primary border-4">
    <div class="card-header py-3 border-bottom">
        <h2 class="h6 mb-0">Dossiers d’instruction transmis à l’agence</h2>
        <p class="text-muted small mb-0 mt-1">Chaque structuration transmise est validée ou rejetée par le chef d’agence. Vous pouvez en soumettre d’autres tant qu’il reste des programmes disponibles.</p>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>Programmes</th><th>Engagements</th><th>Transmis</th><th>Statut</th></tr></thead>
                <tbody>
                    @foreach($chefInstructionDossiers as $dChef)
                        <tr>
                            <td class="small">{{ $dChef->programmesLabel() }}</td>
                            <td class="small">
                                @if($dChef->engagements_sollicites_total !== null || $dChef->engagements_en_cours_total !== null)
                                    <span class="d-block">Sollicités : {{ $dChef->engagements_sollicites_total !== null ? number_format((float) $dChef->engagements_sollicites_total, 0, ',', ' ') : '—' }}</span>
                                    <span class="d-block text-muted">En cours : {{ $dChef->engagements_en_cours_total !== null ? number_format((float) $dChef->engagements_en_cours_total, 0, ',', ' ') : '—' }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="small">{{ $dChef->chef_filiere_submitted_to_agence_at?->format('d/m/Y H:i') ?? '—' }} @if($dChef->chefFiliereSubmittedToAgenceBy)<br><span class="text-muted">{{ $dChef->chefFiliereSubmittedToAgenceBy->name }}</span>@endif</td>
                            <td class="small">
                                @if($dChef->isInstructionPendingAgenceValidation())
                                    <span class="badge bg-warning text-dark">{{ app(\App\Services\StructurationClosureService::class)->closureStatutLabel($dChef) }}</span>
                                @elseif($dChef->isInstructionValidatedByAgence())
                                    <span class="badge bg-success">Validé</span>
                                @elseif($dChef->isInstructionRejectedByAgence())
                                    <span class="badge bg-danger">Rejeté</span>
                                    @if($dChef->instruction_agence_reject_motif)
                                        <div class="text-danger mt-1" style="white-space: pre-wrap;">{{ Str::limit($dChef->instruction_agence_reject_motif, 120) }}</div>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($canComposeBundle)
<div class="card cf-client-card mb-4 border-start border-success border-4" id="composition-dossier-instruction">
    <div class="card-header py-3 border-bottom">
        <h2 class="h5 mb-0">Nouveau dossier d’instruction (multi-programmes)</h2>
        <p class="text-muted small mb-0 mt-1">Sélectionnez un ou plusieurs programmes. Pour chacun, indiquez le budget d’appui <strong>financier</strong> et <strong>non financier</strong> (XAF). Vous pouvez soumettre plusieurs dossiers distincts ; chacun est horodaté et envoyé au chef d’agence pour validation.</p>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('chef-filiere.clients.dossier-instruction.submit', $item->token) }}" id="form-dossier-instruction-bundle">
            @csrf
            <div class="row g-3 mb-3 p-3 border rounded bg-white">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="engagements_sollicites_total">Total des engagements sollicités (XAF)</label>
                    <input type="number" name="engagements_sollicites_total" id="engagements_sollicites_total" class="form-control @error('engagements_sollicites_total') is-invalid @enderror" min="0" step="1" value="{{ old('engagements_sollicites_total') }}" required>
                    @error('engagements_sollicites_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Sert au paramétrage de la délégation de pouvoir pour la clôture du dossier d’instruction (fin de parcours).</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="engagements_en_cours_total">Total des engagements en cours (XAF)</label>
                    <input type="number" name="engagements_en_cours_total" id="engagements_en_cours_total" class="form-control @error('engagements_en_cours_total') is-invalid @enderror" min="0" step="1" value="{{ old('engagements_en_cours_total') }}" required>
                    @error('engagements_en_cours_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div id="bundle-lignes" class="mb-3">
                <div class="row g-2 align-items-end bundle-ligne mb-3 p-3 border rounded bg-light">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Programme</label>
                        <select name="lignes[0][programme_id]" class="form-select bundle-programme" required>
                            <option value="">— Choisir —</option>
                            @foreach($bundleAvailableProgrammes as $prg)
                                <option value="{{ $prg->id }}">{{ $prg->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Budget appui financier (XAF)</label>
                        <input type="number" name="lignes[0][budget_appui_financier]" class="form-control" min="0" step="1" value="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Budget appui non financier (XAF)</label>
                        <input type="number" name="lignes[0][budget_appui_non_financier]" class="form-control" min="0" step="1" value="0">
                    </div>
                    <div class="col-md-2 text-md-end">
                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-ligne d-none w-100" title="Retirer">Retirer</button>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-add-programme-ligne">Ajouter un programme</button>
                <button type="submit" class="btn btn-success">Soumettre le dossier au chef d’agence</button>
            </div>
        </form>
    </div>
</div>
<script>
(function () {
    const container = document.getElementById('bundle-lignes');
    const addBtn = document.getElementById('btn-add-programme-ligne');
    if (!container || !addBtn) return;
    const templateOptions = @json($bundleAvailableProgrammes->map(fn ($p) => ['id' => $p->id, 'name' => $p->name])->values());
    let idx = 1;
    function rowHtml(i) {
        let opts = '<option value="">— Choisir —</option>';
        templateOptions.forEach(function (p) {
            opts += '<option value="' + p.id + '">' + (p.name || '').replace(/</g, '&lt;') + '</option>';
        });
        return '<div class="row g-2 align-items-end bundle-ligne mb-3 p-3 border rounded bg-light">' +
            '<div class="col-md-4"><label class="form-label fw-semibold">Programme</label>' +
            '<select name="lignes[' + i + '][programme_id]" class="form-select bundle-programme" required>' + opts + '</select></div>' +
            '<div class="col-md-3"><label class="form-label fw-semibold">Budget appui financier (XAF)</label>' +
            '<input type="number" name="lignes[' + i + '][budget_appui_financier]" class="form-control" min="0" step="1" value="0"></div>' +
            '<div class="col-md-3"><label class="form-label fw-semibold">Budget appui non financier (XAF)</label>' +
            '<input type="number" name="lignes[' + i + '][budget_appui_non_financier]" class="form-control" min="0" step="1" value="0"></div>' +
            '<div class="col-md-2 text-md-end"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-ligne w-100" title="Retirer">Retirer</button></div></div>';
    }
    function refreshRemoveButtons() {
        const rows = container.querySelectorAll('.bundle-ligne');
        rows.forEach(function (row, i) {
            const btn = row.querySelector('.btn-remove-ligne');
            if (!btn) return;
            btn.classList.toggle('d-none', rows.length <= 1);
        });
    }
    addBtn.addEventListener('click', function () {
        container.insertAdjacentHTML('beforeend', rowHtml(idx++));
        refreshRemoveButtons();
        container.lastElementChild.querySelector('.btn-remove-ligne').addEventListener('click', function () {
            if (container.querySelectorAll('.bundle-ligne').length <= 1) return;
            this.closest('.bundle-ligne').remove();
            refreshRemoveButtons();
        });
    });
    container.addEventListener('click', function (e) {
        const t = e.target;
        if (t && t.classList && t.classList.contains('btn-remove-ligne')) {
            if (container.querySelectorAll('.bundle-ligne').length <= 1) return;
            t.closest('.bundle-ligne').remove();
            refreshRemoveButtons();
        }
    });
    refreshRemoveButtons();
})();
</script>
@elseif($eerClient && $eerClient->qualification_validated_by_agence_at && isset($bundleAvailableProgrammes) && $bundleAvailableProgrammes->isEmpty())
<div class="alert alert-light border mb-4">
    <strong>Dossier d’instruction.</strong> Tous les programmes sont déjà rattachés à un dossier pour ce client, ou aucun programme n’est disponible.
</div>
@endif

@php
    $formatMoney = static fn ($value) => ($value !== null && $value !== '') ? number_format((float) $value, 0, ',', ' ') . ' XAF' : '—';
    $formatNumber = static fn ($value) => ($value !== null && $value !== '') ? number_format((float) $value, 0, ',', ' ') : '—';
    $formatDate = static fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '—';
    $villageQuartier = $item->village_ou_quartier
        ?? (trim(($item->village?->name ?? '') . ' ' . ($item->quartier?->name ?? '')) ?: null);
    $appuisFinanciers = $item->appuis->where('financier', 1)->values();
    $appuisNonFinanciers = $item->appuis->where('financier', 0)->values();
@endphp

<div class="cf-stat-grid mb-4">
    <div class="cf-stat-tile">
            <div class="cf-stat-tile__label">Localisation</div>
            <p class="cf-stat-tile__value mb-0">{{ $item->arrondissement?->name ?? '—' }} @if($item->region) / {{ $item->region->name }} @endif</p>
    </div>
    <div class="cf-stat-tile">
            <div class="cf-stat-tile__label">Contact</div>
            <p class="cf-stat-tile__value mb-0">{{ $item->manager ?? '—' }}<br><span class="fw-normal small text-muted">{{ $item->phone ?? '—' }} · {{ $item->email ?? '—' }}</span></p>
    </div>
    <div class="cf-stat-tile">
            <div class="cf-stat-tile__label">Agence</div>
            <p class="cf-stat-tile__value mb-0">{{ $item->agence?->name ?? '—' }}</p>
    </div>
    <div class="cf-stat-tile">
            <div class="cf-stat-tile__label">Produit principal</div>
            <p class="cf-stat-tile__value mb-0">{{ $item->produit?->name ?? '—' }}</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card cf-client-card h-100">
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
        <div class="card cf-client-card h-100">
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
        <div class="card cf-client-card h-100">
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
        <div class="card cf-client-card h-100">
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
        <div class="card cf-client-card h-100">
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
        <div class="card cf-client-card h-100">
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
        @include('Gestionnaire.Companies.partials.tiers_section', ['item' => $item, 'tiers_readonly' => true, 'tiers_entreprise_show_route' => 'chef-filiere.clients.show'])
    </div>
    <div class="col-12">
        <div class="card cf-client-card">
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
            <div class="card relation-questionnaire-summary cf-client-card mb-4">
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

            <div class="card cf-client-card">
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
                                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#client-questionnaire-critere-{{ $loop->index }}" type="button" role="tab">
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
                                            <div id="client-questionnaire-critere-{{ $loop->index }}" class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" role="tabpanel">
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
                                                                                <button class="nav-link text-start rounded {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#client-questionnaire-sc-{{ $loop->parent->index }}-{{ $loop->index }}" type="button" role="tab">
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
                                                                    <div id="client-questionnaire-sc-{{ $loop->parent->index }}-{{ $loop->index }}" class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" role="tabpanel">
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
    <div class="col-12">
        <div class="card cf-client-card">
            <div class="card-header bg-white border-bottom py-3">
                <strong>Dossiers d’instruction</strong>
            </div>
            <div class="card-body">
                @if($item->dossiers->isEmpty())
                    <p class="text-body-secondary mb-0">Aucun dossier lié pour le moment.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Programmes &amp; budgets d’appui (XAF)</th>
                                    <th>Signataire (réf.)</th>
                                    <th>Budget programme (réf.)</th>
                                    <th>Date conv. (réf.)</th>
                                    <th class="text-end"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->dossiers as $dossier)
                                    <tr>
                                        <td>
                                            @if($dossier->instructionProgrammes->isNotEmpty())
                                                <ul class="list-unstyled mb-0 small">
                                                    @foreach($dossier->instructionProgrammes as $dip)
                                                        <li class="mb-1">
                                                            <strong>{{ $dip->programme?->name ?? '—' }}</strong>
                                                            — financier : {{ number_format((float) $dip->budget_appui_financier, 0, ',', ' ') }}
                                                            ; non financier : {{ number_format((float) $dip->budget_appui_non_financier, 0, ',', ' ') }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <strong>{{ $dossier->programme?->name ?? '—' }}</strong>
                                                <span class="text-muted small d-block">Dossier historique (un programme)</span>
                                            @endif
                                        </td>
                                        <td>{{ $dossier->programme?->signataire ?? '—' }}</td>
                                        <td>{{ $dossier->programme?->budget !== null ? number_format((float) $dossier->programme->budget, 0, ',', '.') . ' XAF' : '—' }}</td>
                                        <td>{{ $dossier->programme?->dt_sig_conv ? \Carbon\Carbon::parse($dossier->programme->dt_sig_conv)->format('d/m/Y') : '—' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="demo-psi-list-view"></i></button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="{{ route('chef-filiere.instructions.dossier.show', $dossier->token) }}">Afficher le dossier</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card cf-client-card h-100">
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
        <div class="card cf-client-card h-100">
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
</div>
@endsection
