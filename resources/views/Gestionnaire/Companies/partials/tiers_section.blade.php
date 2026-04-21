{{-- Styles tiers : public/css/entreprise-fiche.css (chargé par partials/entreprise-fiche-styles sur les fiches show) --}}

@php
    $tiersPhysiques = $item->tiers->where('person_id', '!=', 0)->values();
    $tiersMorales = $item->tiers->where('company_id', '!=', 0)->values();
    $allTiers = $tiersPhysiques->count() + $tiersMorales->count();
    $prospectTiers = $tiersMorales->filter(fn ($tier) => (bool) ($tier->company?->prospect))->count();
    $tiersReadonly = $tiers_readonly ?? false;
    $tiersEntrepriseShowRoute = $tiers_entreprise_show_route ?? 'gestionnaire.entreprises.show';
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
                @unless($tiersReadonly)
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('gestionnaire.entreprise.physique.create', $item->token) }}" class="btn btn-sm btn-outline-primary">
                            <i class="demo-psi-male me-2"></i>Ajouter un tiers personne physique
                        </a>
                        <a href="{{ route('gestionnaire.entreprise.morale.create', $item->token) }}" class="btn btn-sm btn-outline-primary">
                            <i class="demo-psi-building me-2"></i>Ajouter un tiers personne morale
                        </a>
                    </div>
                @endunless
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
                                                    <a href="{{ route($tiersEntrepriseShowRoute, $tier->company->token) }}" class="tiers-item-title-link">
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
                                            <a href="{{ route($tiersEntrepriseShowRoute, $tier->company->token) }}" class="btn btn-sm btn-light border">
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
