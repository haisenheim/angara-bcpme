@once
    @push('styles')
    <style>
    .tiers-section {
        --tiers-accent: #88b824;
        --tiers-accent-dark: #6f9a1d;
        --tiers-accent-soft: rgba(136, 184, 36, 0.12);
        --tiers-border: #e5e7eb;
        --tiers-muted: #64748b;
    }

    .tiers-summary-card {
        border: 1px solid rgba(136, 184, 36, 0.16);
        background: linear-gradient(135deg, rgba(136, 184, 36, 0.12), rgba(255, 255, 255, 0.96));
    }

    .tiers-stat-card {
        height: 100%;
        padding: 1rem 1.1rem;
        border: 1px solid var(--tiers-border);
        border-radius: 1rem;
        background: #fff;
    }

    .tiers-stat-label {
        display: block;
        margin-bottom: 0.35rem;
        color: var(--tiers-muted);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .tiers-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1;
        color: #0f172a;
    }

    .tiers-group-card {
        height: 100%;
        border: 1px solid var(--tiers-border);
        border-radius: 1rem;
        background: #fff;
        overflow: hidden;
    }

    .tiers-group-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid var(--tiers-border);
        background: #f8fafc;
    }

    .tiers-group-header h6 {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
    }

    .tiers-group-count {
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

    .tiers-group-body {
        padding: 1.25rem;
    }

    .tiers-list {
        display: grid;
        gap: 1rem;
    }

    .tiers-item-card {
        padding: 1rem 1.05rem;
        border: 1px solid var(--tiers-border);
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    }

    .tiers-item-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.9rem;
    }

    .tiers-item-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
    }

    .tiers-item-card--linkable {
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }

    .tiers-item-card--linkable:hover {
        transform: translateY(-2px);
        border-color: rgba(136, 184, 36, 0.35);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
    }

    .tiers-item-title-link {
        color: inherit;
        text-decoration: none;
    }

    .tiers-item-title-link:hover,
    .tiers-item-title-link:focus {
        color: var(--tiers-accent-dark);
        text-decoration: underline;
    }

    .tiers-item-subtitle {
        margin: 0.2rem 0 0;
        color: var(--tiers-muted);
        font-size: 0.9rem;
    }

    .tiers-link-badge {
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

    .tiers-item-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.8rem 1rem;
    }

    .tiers-item-meta-label {
        display: block;
        margin-bottom: 0.15rem;
        color: var(--tiers-muted);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .tiers-item-meta-value {
        color: #0f172a;
        font-size: 0.93rem;
        word-break: break-word;
    }

    .tiers-comment {
        margin-top: 0.95rem;
        padding: 0.85rem 0.95rem;
        border-radius: 0.85rem;
        background: #f8fafc;
        color: #334155;
        font-size: 0.92rem;
    }

    .tiers-item-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-top: 0.95rem;
        padding-top: 0.95rem;
        border-top: 1px dashed #d7dee7;
    }

    .tiers-empty-state {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.55rem;
        padding: 1.1rem;
        border: 1px dashed #cbd5e1;
        border-radius: 1rem;
        background: #f8fafc;
        color: var(--tiers-muted);
    }

    @media (max-width: 767.98px) {
        .tiers-item-grid {
            grid-template-columns: 1fr;
        }

        .tiers-group-header,
        .tiers-item-header,
        .tiers-item-footer {
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
    $prospectTiers = $tiersMorales->filter(fn ($tier) => (bool) ($tier->company?->prospect))->count();
@endphp

<div class="tiers-section">
    <div class="card tiers-summary-card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <p class="text-uppercase text-muted small fw-semibold mb-2">Reseau relationnel</p>
                    <h4 class="mb-2">Les tiers rattaches a cette fiche</h4>
                    <p class="text-body-secondary mb-0">
                        Consultez les personnes physiques et morales rattachees, ainsi que la nature de leurs liens avec
                        <strong>{{ $item->name }}</strong>.
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('gestionnaire.entreprise.physique.create', $item->token) }}" class="btn btn-sm btn-outline-primary">
                        <i class="demo-psi-male me-2"></i>Ajouter un tiers personne physique
                    </a>
                    <a href="{{ route('gestionnaire.entreprise.morale.create', $item->token) }}" class="btn btn-sm btn-outline-primary">
                        <i class="demo-psi-building me-2"></i>Ajouter un tiers personne morale
                    </a>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="tiers-stat-card">
                        <span class="tiers-stat-label">Total tiers</span>
                        <div class="tiers-stat-value">{{ $allTiers }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tiers-stat-card">
                        <span class="tiers-stat-label">Personnes physiques</span>
                        <div class="tiers-stat-value">{{ $tiersPhysiques->count() }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tiers-stat-card">
                        <span class="tiers-stat-label">Personnes morales prospect</span>
                        <div class="tiers-stat-value">{{ $prospectTiers }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="tiers-group-card shadow-sm">
                <div class="tiers-group-header">
                    <div>
                        <h6><i class="demo-psi-male me-2 text-primary"></i>Tiers personne physique</h6>
                        <p class="text-body-secondary mb-0 small">Contacts individuels lies au dossier.</p>
                    </div>
                    <span class="tiers-group-count">{{ $tiersPhysiques->count() }}</span>
                </div>
                <div class="tiers-group-body">
                    @if($tiersPhysiques->isNotEmpty())
                        <div class="tiers-list">
                            @foreach($tiersPhysiques as $tier)
                                <article class="tiers-item-card">
                                    <div class="tiers-item-header">
                                        <div>
                                            <h6 class="tiers-item-title">{{ $tier->person?->name ?? 'Nom non renseigne' }}</h6>
                                            <p class="tiers-item-subtitle">{{ $tier->person?->address ?? 'Adresse non renseignee' }}</p>
                                        </div>
                                        <span class="tiers-link-badge">
                                            <i class="demo-psi-link"></i>{{ $tier->lien ?? 'Lien non renseigne' }}
                                        </span>
                                    </div>

                                    <div class="tiers-item-grid">
                                        <div>
                                            <span class="tiers-item-meta-label">NIU</span>
                                            <div class="tiers-item-meta-value">{{ $tier->person?->niu ?? '—' }}</div>
                                        </div>
                                        <div>
                                            <span class="tiers-item-meta-label">Telephone</span>
                                            <div class="tiers-item-meta-value">{{ $tier->person?->phone ?? '—' }}</div>
                                        </div>
                                        <div>
                                            <span class="tiers-item-meta-label">Email</span>
                                            <div class="tiers-item-meta-value">{{ $tier->person?->email ?? '—' }}</div>
                                        </div>
                                        <div>
                                            <span class="tiers-item-meta-label">Adresse</span>
                                            <div class="tiers-item-meta-value">{{ $tier->person?->address ?? '—' }}</div>
                                        </div>
                                    </div>
                                    @if(! empty($tier->commentaire))
                                        <div class="tiers-comment">
                                            <span class="tiers-item-meta-label">Commentaire</span>
                                            <div>{{ $tier->commentaire }}</div>
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="tiers-empty-state">
                            <strong>Aucun tiers personne physique</strong>
                            <span>Ajoutez un contact individuel pour documenter les relations autour de cette entreprise.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="tiers-group-card shadow-sm">
                <div class="tiers-group-header">
                    <div>
                        <h6><i class="demo-psi-building me-2 text-primary"></i>Tiers personne morale</h6>
                        <p class="text-body-secondary mb-0 small">Entreprises rattachees a la fiche, prospects ou clients.</p>
                    </div>
                    <span class="tiers-group-count">{{ $tiersMorales->count() }}</span>
                </div>
                <div class="tiers-group-body">
                    @if($tiersMorales->isNotEmpty())
                        <div class="tiers-list">
                            @foreach($tiersMorales as $tier)
                                <article class="tiers-item-card {{ $tier->company?->token ? 'tiers-item-card--linkable' : '' }}">
                                    <div class="tiers-item-header">
                                        <div>
                                            <h6 class="tiers-item-title">
                                                @if($tier->company?->token)
                                                    <a href="{{ route('gestionnaire.entreprises.show', $tier->company->token) }}" class="tiers-item-title-link">
                                                        {{ $tier->company?->name ?? 'Denomination non renseignee' }}
                                                    </a>
                                                @else
                                                    {{ $tier->company?->name ?? 'Denomination non renseignee' }}
                                                @endif
                                            </h6>
                                            <p class="tiers-item-subtitle">
                                                {{ $tier->company?->manager ?? 'Dirigeant non renseigne' }}
                                            </p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                                            <span class="tiers-link-badge">
                                                <i class="demo-psi-link"></i>{{ $tier->lien ?? 'Lien non renseigne' }}
                                            </span>
                                            @if($tier->company)
                                                <span class="badge bg-{{ $tier->company->prospect ? 'warning text-dark' : 'success' }}">
                                                    {{ $tier->company->prospect ? 'Prospect' : 'Client' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="tiers-item-grid">
                                        <div>
                                            <span class="tiers-item-meta-label">Telephone</span>
                                            <div class="tiers-item-meta-value">{{ $tier->company?->phone ?? '—' }}</div>
                                        </div>
                                        <div>
                                            <span class="tiers-item-meta-label">Email</span>
                                            <div class="tiers-item-meta-value">{{ $tier->company?->email ?? '—' }}</div>
                                        </div>
                                        <div>
                                            <span class="tiers-item-meta-label">Produit principal</span>
                                            <div class="tiers-item-meta-value">{{ $tier->company?->produit?->name ?? '—' }}</div>
                                        </div>
                                        <div>
                                            <span class="tiers-item-meta-label">Dirigeant</span>
                                            <div class="tiers-item-meta-value">{{ $tier->company?->manager ?? '—' }}</div>
                                        </div>
                                    </div>
                                    @if(! empty($tier->commentaire))
                                        <div class="tiers-comment">
                                            <span class="tiers-item-meta-label">Commentaire</span>
                                            <div>{{ $tier->commentaire }}</div>
                                        </div>
                                    @endif

                                    <div class="tiers-item-footer">
                                        <span class="text-body-secondary small">Fiche entreprise rattachee au reseau de {{ $item->name }}</span>
                                        @if($tier->company?->token)
                                            <a href="{{ route('gestionnaire.entreprises.show', $tier->company->token) }}" class="btn btn-sm btn-light border">
                                                Ouvrir la fiche
                                            </a>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="tiers-empty-state">
                            <strong>Aucun tiers personne morale</strong>
                            <span>Ajoutez une entreprise liee pour enrichir la cartographie relationnelle du dossier.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
