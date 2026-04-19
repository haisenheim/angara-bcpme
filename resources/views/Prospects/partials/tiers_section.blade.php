@once
    @push('styles')
    <style>
    .review-tiers-section {
        --tiers-accent: #88b824;
        --tiers-accent-dark: #6f9a1d;
        --tiers-accent-soft: rgba(136, 184, 36, 0.12);
        --tiers-border: #e5e7eb;
        --tiers-muted: #64748b;
    }

    .review-tiers-summary {
        border: 1px solid rgba(136, 184, 36, 0.16);
        background: linear-gradient(135deg, rgba(136, 184, 36, 0.12), rgba(255, 255, 255, 0.96));
    }

    .review-tiers-stat {
        height: 100%;
        padding: 1rem 1.1rem;
        border: 1px solid var(--tiers-border);
        border-radius: 1rem;
        background: #fff;
    }

    .review-tiers-stat-label {
        display: block;
        margin-bottom: 0.35rem;
        color: var(--tiers-muted);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .review-tiers-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1;
        color: #0f172a;
    }

    .review-tiers-group {
        height: 100%;
        border: 1px solid var(--tiers-border);
        border-radius: 1rem;
        background: #fff;
        overflow: hidden;
    }

    .review-tiers-group-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid var(--tiers-border);
        background: #f8fafc;
    }

    .review-tiers-group-title {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
    }

    .review-tiers-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        padding: 0.3rem 0.65rem;
        border-radius: 999px;
        background: var(--tiers-accent-soft);
        color: #17310b;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .review-tiers-body {
        padding: 1.25rem;
    }

    .review-tiers-list {
        display: grid;
        gap: 1rem;
    }

    .review-tiers-item {
        padding: 1rem 1.05rem;
        border: 1px solid var(--tiers-border);
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    }

    .review-tiers-item-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.9rem;
    }

    .review-tiers-item-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
    }

    .review-tiers-item-subtitle {
        margin: 0.2rem 0 0;
        color: var(--tiers-muted);
        font-size: 0.9rem;
    }

    .review-tiers-link-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.75rem;
        border-radius: 999px;
        background: var(--tiers-accent-soft);
        color: #17310b;
        font-size: 0.82rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .review-tiers-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.8rem 1rem;
    }

    .review-tiers-meta-label {
        display: block;
        margin-bottom: 0.15rem;
        color: var(--tiers-muted);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .review-tiers-meta-value {
        color: #0f172a;
        font-size: 0.93rem;
        word-break: break-word;
    }

    .review-tiers-comment {
        margin-top: 0.95rem;
        padding: 0.85rem 0.95rem;
        border-radius: 0.85rem;
        background: #f8fafc;
        color: #334155;
        font-size: 0.92rem;
    }

    .review-tiers-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-top: 0.95rem;
        padding-top: 0.95rem;
        border-top: 1px dashed #d7dee7;
    }

    .review-tiers-empty {
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
        padding: 1.1rem;
        border: 1px dashed #cbd5e1;
        border-radius: 1rem;
        background: #f8fafc;
        color: var(--tiers-muted);
    }

    @media (max-width: 767.98px) {
        .review-tiers-grid {
            grid-template-columns: 1fr;
        }

        .review-tiers-group-header,
        .review-tiers-item-header,
        .review-tiers-footer {
            flex-direction: column;
            align-items: flex-start;
        }
    }
    </style>
    @endpush
@endonce

@php
    $tiersPhysiques = $item->tiers->where('person_id', '!=', 0)->values();
    $tiersMorales = $item->tiers->where('company_id', '!=', 0)->values();
    $allTiers = $tiersPhysiques->count() + $tiersMorales->count();
    $linkedProspects = $tiersMorales->filter(fn ($tier) => (bool) ($tier->company?->prospect))->count();
    $prospectRouteName = $role === 'juridique' ? 'juridique.prospects.show' : 'conformite.prospects.show';
    $companyRouteName = $role === 'juridique' ? 'juridique.entreprises.show' : null;
@endphp

<div class="review-tiers-section">
    <div class="card review-tiers-summary border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <p class="text-uppercase text-muted small fw-semibold mb-2">Réseau relationnel</p>
                    <h5 class="mb-2">Les tiers rattachés à ce prospect</h5>
                    <p class="text-body-secondary mb-0">Visualisez les personnes physiques et morales liées au dossier avant de formuler votre avis.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="review-tiers-stat">
                        <span class="review-tiers-stat-label">Total tiers</span>
                        <div class="review-tiers-stat-value">{{ $allTiers }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="review-tiers-stat">
                        <span class="review-tiers-stat-label">Personnes physiques</span>
                        <div class="review-tiers-stat-value">{{ $tiersPhysiques->count() }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="review-tiers-stat">
                        <span class="review-tiers-stat-label">Tiers moraux prospect</span>
                        <div class="review-tiers-stat-value">{{ $linkedProspects }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="review-tiers-group shadow-sm">
                <div class="review-tiers-group-header">
                    <div>
                        <h6 class="review-tiers-group-title"><i class="demo-psi-male me-2 text-primary"></i>Tiers personne physique</h6>
                        <p class="text-body-secondary mb-0 small">Contacts individuels liés au prospect.</p>
                    </div>
                    <span class="review-tiers-count">{{ $tiersPhysiques->count() }}</span>
                </div>
                <div class="review-tiers-body">
                    @if($tiersPhysiques->isNotEmpty())
                        <div class="review-tiers-list">
                            @foreach($tiersPhysiques as $tier)
                                <article class="review-tiers-item">
                                    <div class="review-tiers-item-header">
                                        <div>
                                            <h6 class="review-tiers-item-title">{{ $tier->person?->name ?? 'Nom non renseigné' }}</h6>
                                            <p class="review-tiers-item-subtitle">{{ $tier->person?->address ?? 'Adresse non renseignée' }}</p>
                                        </div>
                                        <span class="review-tiers-link-badge">
                                            <i class="demo-psi-link"></i>{{ $tier->lien ?? 'Lien non renseigné' }}
                                        </span>
                                    </div>

                                    <div class="review-tiers-grid">
                                        <div>
                                            <span class="review-tiers-meta-label">NIU</span>
                                            <div class="review-tiers-meta-value">{{ $tier->person?->niu ?? '—' }}</div>
                                        </div>
                                        <div>
                                            <span class="review-tiers-meta-label">Téléphone</span>
                                            <div class="review-tiers-meta-value">{{ $tier->person?->phone ?? '—' }}</div>
                                        </div>
                                        <div>
                                            <span class="review-tiers-meta-label">Email</span>
                                            <div class="review-tiers-meta-value">{{ $tier->person?->email ?? '—' }}</div>
                                        </div>
                                        <div>
                                            <span class="review-tiers-meta-label">Adresse</span>
                                            <div class="review-tiers-meta-value">{{ $tier->person?->address ?? '—' }}</div>
                                        </div>
                                    </div>

                                    @if(! empty($tier->commentaire))
                                        <div class="review-tiers-comment">
                                            <span class="review-tiers-meta-label">Commentaire</span>
                                            <div>{{ $tier->commentaire }}</div>
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="review-tiers-empty">
                            <strong>Aucun tiers personne physique</strong>
                            <span>Aucun contact individuel n’a encore été rattaché à ce prospect.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="review-tiers-group shadow-sm">
                <div class="review-tiers-group-header">
                    <div>
                        <h6 class="review-tiers-group-title"><i class="demo-psi-building me-2 text-primary"></i>Tiers personne morale</h6>
                        <p class="text-body-secondary mb-0 small">Entreprises liées au prospect, déjà connues ou en cours d’instruction.</p>
                    </div>
                    <span class="review-tiers-count">{{ $tiersMorales->count() }}</span>
                </div>
                <div class="review-tiers-body">
                    @if($tiersMorales->isNotEmpty())
                        <div class="review-tiers-list">
                            @foreach($tiersMorales as $tier)
                                @php
                                    $company = $tier->company;
                                    $prospectUrl = ($company?->prospect && $company?->prospect_submitted_at) ? route($prospectRouteName, $company->token) : null;
                                    $companyUrl = (! $company?->prospect && $companyRouteName && $company?->token) ? route($companyRouteName, $company->token) : null;
                                    $openUrl = $prospectUrl ?? $companyUrl;
                                @endphp
                                <article class="review-tiers-item">
                                    <div class="review-tiers-item-header">
                                        <div>
                                            <h6 class="review-tiers-item-title">{{ $company?->name ?? 'Dénomination non renseignée' }}</h6>
                                            <p class="review-tiers-item-subtitle">{{ $company?->manager ?? 'Dirigeant non renseigné' }}</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                                            <span class="review-tiers-link-badge">
                                                <i class="demo-psi-link"></i>{{ $tier->lien ?? 'Lien non renseigné' }}
                                            </span>
                                            @if($company)
                                                <span class="badge bg-{{ $company->prospect ? 'warning text-dark' : 'success' }}">
                                                    {{ $company->prospect ? 'Prospect' : 'Client' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="review-tiers-grid">
                                        <div>
                                            <span class="review-tiers-meta-label">Téléphone</span>
                                            <div class="review-tiers-meta-value">{{ $company?->phone ?? '—' }}</div>
                                        </div>
                                        <div>
                                            <span class="review-tiers-meta-label">Email</span>
                                            <div class="review-tiers-meta-value">{{ $company?->email ?? '—' }}</div>
                                        </div>
                                        <div>
                                            <span class="review-tiers-meta-label">Produit principal</span>
                                            <div class="review-tiers-meta-value">{{ $company?->produit?->name ?? '—' }}</div>
                                        </div>
                                        <div>
                                            <span class="review-tiers-meta-label">Dirigeant</span>
                                            <div class="review-tiers-meta-value">{{ $company?->manager ?? '—' }}</div>
                                        </div>
                                    </div>

                                    @if(! empty($tier->commentaire))
                                        <div class="review-tiers-comment">
                                            <span class="review-tiers-meta-label">Commentaire</span>
                                            <div>{{ $tier->commentaire }}</div>
                                        </div>
                                    @endif

                                    @if($openUrl)
                                        <div class="review-tiers-footer">
                                            <span class="text-body-secondary small">Consulter la fiche liée depuis votre espace métier.</span>
                                            <a href="{{ $openUrl }}" class="btn btn-sm btn-light border">Ouvrir la fiche</a>
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="review-tiers-empty">
                            <strong>Aucun tiers personne morale</strong>
                            <span>Aucune entreprise liée n’a encore été rattachée à ce prospect.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
