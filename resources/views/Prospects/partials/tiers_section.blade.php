{{-- Styles review-tiers : public/css/entreprise-fiche.css (chargé par review_show via partials/entreprise-fiche-styles) --}}

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
