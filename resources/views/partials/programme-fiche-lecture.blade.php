{{--
    Fiche programme en lecture (Analyste / Gestionnaire).
    @var \App\Models\Programme $item
    @var string $space  analyste|gestionnaire
--}}
@php
    $fmtMoney = fn ($v) => $v !== null && $v !== '' ? number_format((float) $v, 0, ',', '.').' XAF' : '—';
    $dossiersCount = $item->dossiers->count();
    $indexUrl = route($space.'.programmes.index');
    $dossierUrl = fn ($token) => route($space.'.dossiers.show', $token);
    $tid = fn (string $suffix) => 'tab_'.$space.'_'.$suffix;
@endphp

<div class="programme-fiche-hero">
    <div class="row g-3 align-items-center">
        <div class="col-lg-8">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Programme</span>
                @if(isset($item->active))
                    <span class="badge rounded-pill {{ $item->active ? 'text-bg-success' : 'text-bg-secondary' }}">
                        {{ $item->active ? 'Actif' : 'Inactif' }}
                    </span>
                @endif
            </div>
            <p class="mb-0 text-muted small">
                <span class="fw-semibold text-dark">Convention :</span> {{ $item->convention ?? '—' }}
                <span class="mx-2">·</span>
                <span class="fw-semibold text-dark">Signataire :</span> {{ $item->signataire ?? '—' }}
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ $indexUrl }}" class="btn btn-sm btn-outline-secondary">← Liste des programmes</a>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="programme-fiche-kpi">
            <div class="programme-fiche-kpi__label">Budget total</div>
            <div class="programme-fiche-kpi__val text-primary">{{ $fmtMoney($item->budget ?? null) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="programme-fiche-kpi">
            <div class="programme-fiche-kpi__label">Dossiers d'instruction</div>
            <div class="programme-fiche-kpi__val">{{ $dossiersCount }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="programme-fiche-kpi">
            <div class="programme-fiche-kpi__label">Entreprises liées</div>
            <div class="programme-fiche-kpi__val">{{ $item->entreprises->count() }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="programme-fiche-kpi">
            <div class="programme-fiche-kpi__label">Secteurs (produits)</div>
            <div class="programme-fiche-kpi__val">{{ $item->produits->count() }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="programme-fiche-info-card mb-4 mb-lg-0">
            <div class="px-3 py-2 border-bottom bg-light bg-opacity-50">
                <span class="fw-bold text-dark small text-uppercase">Données contractuelles</span>
            </div>
            <table class="table table-sm align-middle">
                <tbody>
                    <tr><td>Désignation</td><th class="text-dark">{{ $item->name }}</th></tr>
                    <tr><td>Réf. convention</td><th>{{ $item->convention ?? '—' }}</th></tr>
                    <tr><td>Date signature</td><th>@if($item->dt_sig_conv){{ \Carbon\Carbon::parse($item->dt_sig_conv)->format('d/m/Y') }}@else — @endif</th></tr>
                    <tr><td>Institution signataire</td><th>{{ $item->signataire ?? '—' }}</th></tr>
                    <tr><td>Budget AF</td><th>{{ $fmtMoney($item->budget_af ?? null) }}</th></tr>
                    <tr><td>Budget ANF</td><th>{{ $fmtMoney($item->budget_anf ?? null) }}</th></tr>
                    <tr><td>Coordination</td><th>{{ $fmtMoney($item->budget_coord ?? null) }}</th></tr>
                    <tr><td>Bénéficiaires PM</td><th>{{ $item->type_pm ?? '—' }}</th></tr>
                    <tr><td>Bénéficiaires PP</td><th>{{ $item->type_pp ?? '—' }}</th></tr>
                    <tr><td>Début activités</td><th>@if($item->dt_start){{ \Carbon\Carbon::parse($item->dt_start)->format('d/m/Y') }}@else — @endif</th></tr>
                    <tr><td>Contact</td><th>{{ $item->contact ?? '—' }}</th></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="programme-fiche-main-card">
            <div class="tab-base programme-fiche-tabs">
                <ul class="nav nav-underline nav-component border-bottom flex-nowrap overflow-auto px-2 pt-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2 active" data-bs-toggle="tab" data-bs-target="#{{ $tid('dossiers') }}" type="button" role="tab">Dossiers</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2" data-bs-toggle="tab" data-bs-target="#{{ $tid('secteurs') }}" type="button" role="tab">Secteurs</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2" data-bs-toggle="tab" data-bs-target="#{{ $tid('appuis') }}" type="button" role="tab">Appuis</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2" data-bs-toggle="tab" data-bs-target="#{{ $tid('comp') }}" type="button" role="tab">Composantes</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2" data-bs-toggle="tab" data-bs-target="#{{ $tid('res') }}" type="button" role="tab">Résultats</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2" data-bs-toggle="tab" data-bs-target="#{{ $tid('ent') }}" type="button" role="tab">Entreprises</button>
                    </li>
                </ul>

                <div class="tab-content p-3 p-md-4">
                    <div id="{{ $tid('dossiers') }}" class="tab-pane fade show active" role="tabpanel">
                        <p class="programme-fiche-section-title mb-2">Instruction — dossiers rattachés (plus récents en premier)</p>
                        <div class="programme-fiche-table-wrap">
                            <table class="table table-hover align-middle mb-0 programme-fiche-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Entreprise</th>
                                        <th>Agence</th>
                                        <th>Gestionnaire</th>
                                        <th>Analyste</th>
                                        <th>Statut</th>
                                        <th>Créé le</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->dossiers as $dossier)
                                        @php $st = $dossier->status; @endphp
                                        <tr>
                                            <td class="fw-semibold">{{ $dossier->entreprise?->name ?? '—' }}</td>
                                            <td>{{ $dossier->agence?->name ?? '—' }}</td>
                                            <td>{{ $dossier->gestionnaire?->name ?? '—' }}</td>
                                            <td>{{ $dossier->analyste?->name ?? '—' }}</td>
                                            <td><span class="badge rounded-pill bg-secondary bg-opacity-75">{{ $st['name'] ?? '—' }}</span></td>
                                            <td class="text-nowrap small">{{ optional($dossier->created_at)->format('d/m/Y H:i') ?? '—' }}</td>
                                            <td class="text-end">
                                                <a href="{{ $dossierUrl($dossier->token) }}" class="btn btn-sm btn-primary">Ouvrir</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="programme-fiche-empty border-0">Aucun dossier d'instruction pour ce programme.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="{{ $tid('secteurs') }}" class="tab-pane fade" role="tabpanel">
                        <p class="programme-fiche-section-title mb-2">Secteurs cibles (produits / filières / branches)</p>
                        <div class="programme-fiche-table-wrap">
                            <table class="table table-hover table-sm mb-0 programme-fiche-table">
                                <thead class="table-light">
                                    <tr><th>Produit</th><th>Filière</th><th>Branche</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->produits as $p)
                                        <tr>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->filiere?->name ?? '—' }}</td>
                                            <td>{{ $p->branche?->name ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="programme-fiche-empty border-0">Aucun secteur cible.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="{{ $tid('appuis') }}" class="tab-pane fade" role="tabpanel">
                        <p class="programme-fiche-section-title mb-2">Appuis proposés</p>
                        <div class="programme-fiche-table-wrap">
                            <table class="table table-hover table-sm mb-0 programme-fiche-table">
                                <thead class="table-light">
                                    <tr><th>Service</th><th>Type</th><th>Nature</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->appuis as $service)
                                        <tr>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ $service->type?->name ?? '—' }}</td>
                                            <td><span class="badge bg-{{ $service->financier ? 'success' : 'info' }} bg-opacity-10 text-{{ $service->financier ? 'success' : 'info' }}">{{ $service->financier ? 'Financier' : 'Non financier' }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="programme-fiche-empty border-0">Aucun appui paramétré.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="{{ $tid('comp') }}" class="tab-pane fade" role="tabpanel">
                        <p class="programme-fiche-section-title mb-2">Composantes</p>
                        <div class="programme-fiche-table-wrap">
                            <table class="table table-hover table-sm mb-0 programme-fiche-table">
                                <thead class="table-light">
                                    <tr><th>Entité</th><th>Nature</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->composantes as $cmp)
                                        <tr>
                                            <td>{{ $cmp->name }}</td>
                                            <td>{{ $cmp->type ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="programme-fiche-empty border-0">Aucune composante.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="{{ $tid('res') }}" class="tab-pane fade" role="tabpanel">
                        <p class="programme-fiche-section-title mb-2">Résultats attendus</p>
                        <div class="programme-fiche-table-wrap">
                            <table class="table table-hover table-sm mb-0 programme-fiche-table">
                                <thead class="table-light">
                                    <tr><th>Indicateur</th><th>Attentes</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->resultats as $r)
                                        <tr>
                                            <td>{{ $r->indicateur?->name ?? '—' }}</td>
                                            <td>{{ $r->attente ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="programme-fiche-empty border-0">Aucun résultat attendu.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="{{ $tid('ent') }}" class="tab-pane fade" role="tabpanel">
                        <p class="programme-fiche-section-title mb-2">Entreprises</p>
                        <div class="programme-fiche-table-wrap">
                            <table class="table table-hover table-sm mb-0 programme-fiche-table">
                                <thead class="table-light">
                                    <tr><th>Entreprise</th><th>Localité</th><th>Taille</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->entreprises as $ent)
                                        <tr>
                                            <td>{{ $ent->name }}</td>
                                            <td>{{ $ent->localite ?? '—' }}</td>
                                            <td>{{ $ent->taille ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="programme-fiche-empty border-0">Aucune entreprise liée.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
