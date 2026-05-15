@extends(match ($space['route'] ?? '') {
    'respexp' => 'Layouts.respexp',
    'juridique' => 'Layouts.juridique',
    'analyste-juridique' => 'Layouts.analyste-juridique',
    'reng' => 'Layouts.reng',
    'analyste-credit' => 'Layouts.analyste-credit',
    'analyste-risques' => 'Layouts.analyste-risques',
    'rerx' => 'Layouts.rerx',
    'dg' => 'Layouts.dg',
    'dga' => 'Layouts.dga',
    'conformite' => 'Layouts.conformite',
    default => 'Layouts.app',
})

@section('title', 'Dossiers - '.$space['title'])

@php
    $isDgDga = in_array($space['route'] ?? '', ['dg', 'dga'], true);
    $dgDgaVue = $dossiersVue ?? 'default';
    $dossiersListUsesProgrammesLabel = $dossiersListUsesProgrammesLabel ?? $isDgDga;
    $dossiersIndexRouteName = $portfolioDossiersIndexRoute ?? (
        $isDgDga && ($dossiersVue ?? '') === 'direction_pending'
            ? $space['route'].'.dossiers.en-attente-direction'
            : ($isDgDga
                ? $space['route'].'.dossiers.valides-chef-agence'
                : $space['route'].'.dossiers.index')
    );
    $dossiersExportRouteName = $portfolioDossiersExportRoute ?? $space['route'].'.dossiers.export';
    $dossiersShowRouteName = $portfolioDossiersShowRoute ?? $space['route'].'.dossiers.show';
    $isJuridiquePortefeuilleListe = ($space['route'] ?? '') === 'juridique'
        && str_starts_with((string) (request()->route()?->getName() ?? ''), 'juridique.portefeuille.dossiers');
@endphp

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active" aria-current="page">
            @if($isDgDga && $dgDgaVue === 'direction_pending')
                En attente avis direction
            @elseif($isDgDga)
                Dossiers validés par le chef d’agence
            @else
                Dossiers d'instruction
            @endif
        </li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">
            @if($isDgDga && $dgDgaVue === 'direction_pending')
                Dossiers en attente de votre avis (direction)
            @elseif($isDgDga)
                Tous les dossiers d’instruction validés par le chef d’agence
            @else
                Dossiers d'instruction
            @endif
        </h5>
        <p class="text-muted mb-0">
            @if($space['route'] === 'respexp' && ($dossiersFilter ?? null) === 'a_affecter')
                Liste des dossiers <strong>sans analyste affecté</strong> (à coter) — périmètre banque.
            @elseif($space['route'] === 'respexp')
                Tous les dossiers d’instruction — consultation, cotation analyste, avis de crédit et validation des engagements.
            @elseif($space['route'] === 'juridique' && $isJuridiquePortefeuilleListe)
                Vue transverse : <strong>tous</strong> les dossiers d’instruction (consultation).
            @elseif($space['route'] === 'juridique')
                Dossiers transmis au pôle juridique — même présentation que pour le responsable exploitation (consultation).
            @elseif($space['route'] === 'analyste-juridique')
                Dossiers qui vous sont affectés par le responsable juridique.
            @elseif($space['route'] === 'reng')
                Dossiers transmis par le pôle juridique au responsable engagements.
            @elseif($space['route'] === 'analyste-credit')
                Dossiers du pôle engagements qui vous sont affectés en tant qu’analyste crédit.
            @elseif($space['route'] === 'rerx')
                Dossiers transmis par le responsable engagements au responsable risques.
            @elseif($space['route'] === 'analyste-risques')
                Dossiers du pôle risques qui vous sont affectés en tant qu’analyste risques.
            @elseif($space['route'] === 'dg' && ($dossiersVue ?? '') === 'direction_pending')
                Dossiers transmis à la <strong>direction</strong> par le responsable risques, sans conclusions direction significatives enregistrées (avis DG / DGA attendu).
            @elseif($space['route'] === 'dga' && ($dossiersVue ?? '') === 'direction_pending')
                Dossiers transmis à la <strong>direction</strong> par le responsable risques, sans conclusions direction significatives enregistrées (avis DG / DGA attendu).
            @elseif($space['route'] === 'dg')
                <strong>Tous</strong> les dossiers d’instruction ayant reçu la validation du chef d’agence, à tous les stades du circuit (consultation DG).
            @elseif($space['route'] === 'dga')
                <strong>Tous</strong> les dossiers d’instruction ayant reçu la validation du chef d’agence, à tous les stades du circuit (consultation DGA).
            @elseif($space['route'] === 'conformite')
                Vue transverse : <strong>tous</strong> les dossiers d’instruction (toutes agences, tous stades) — consultation.
            @else
                Vue transverse des dossiers liés aux entreprises.
            @endif
        </p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @if($isDgDga)
        <div class="angara-table" id="rolespace-{{ $space['route'] }}-dossiers">
        @endif
        <div class="card {{ $isDgDga ? 'border-0 shadow-sm' : 'shadow-sm border-0' }}">
            <div class="card-body {{ $isDgDga ? 'p-3 p-md-4' : '' }}">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <a href="{{ route($space['route'].'.dashboard') }}" class="btn btn-sm btn-outline-secondary">Retour au tableau de bord</a>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        @if($isDgDga)
                            <div class="btn-group btn-group-sm" role="group" aria-label="Vues dossiers direction">
                                <a href="{{ route($space['route'].'.dossiers.en-attente-direction') }}" class="btn {{ $dgDgaVue === 'direction_pending' ? 'btn-primary' : 'btn-outline-primary' }}">En attente avis direction</a>
                                <a href="{{ route($space['route'].'.dossiers.valides-chef-agence') }}" class="btn {{ $dgDgaVue === 'valides_chef_agence' ? 'btn-primary' : 'btn-outline-primary' }}">Tous (validés agence)</a>
                            </div>
                        @else
                            <span class="text-muted small">{{ $dossiers->total() }} dossier(s)</span>
                        @endif
                    </div>
                </div>

                @php
                    $exportParams = array_merge(request()->except('format'), ['vue' => $dossiersVue ?? 'default']);
                    $resetRoute = $dossiersIndexRouteName;
                @endphp

                <form method="get" action="{{ route($resetRoute) }}" class="row g-2 align-items-end mb-3">
                    <div class="col-12 col-md-4 col-lg-3">
                        <label class="form-label small text-muted mb-0">Recherche</label>
                        <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Entreprise, programme…">
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <label class="form-label small text-muted mb-0">Étape</label>
                        <select name="instruction_state" class="form-select form-select-sm">
                            <option value="">Toutes</option>
                            <option value="pending" @selected(request('instruction_state') === 'pending')>En attente</option>
                            <option value="in_progress" @selected(request('instruction_state') === 'in_progress')>En cours</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <label class="form-label small text-muted mb-0">Statut</label>
                        <select name="instruction_statut" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            @foreach(\App\Services\DossierInstructionStatutService::filterLabels() as $code => $label)
                                <option value="{{ $code }}" @selected(request('instruction_statut') === $code)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <label class="form-label small text-muted mb-0">Création du</label>
                        <input type="date" name="created_from" value="{{ request('created_from') }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <label class="form-label small text-muted mb-0">au</label>
                        <input type="date" name="created_to" value="{{ request('created_to') }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-primary">Filtrer</button>
                    </div>
                    <div class="col-auto">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="rolespace-dossiers-actions" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="rolespace-dossiers-actions">
                                <li><a class="dropdown-item" href="{{ route($dossiersExportRouteName, array_merge($exportParams, ['format' => 'xlsx'])) }}"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Exporter en Excel</a></li>
                                <li><a class="dropdown-item" href="{{ route($dossiersExportRouteName, array_merge($exportParams, ['format' => 'pdf'])) }}"><i class="bi bi-file-earmark-pdf me-2"></i>Exporter en PDF</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route($resetRoute) }}"><i class="bi bi-arrow-counterclockwise me-2"></i>Réinitialiser</a></li>
                            </ul>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle{{ $isDgDga ? ' table-bordered mb-0' : '' }}" @if($isDgDga) style="width:100%" @endif>
                        <thead @if($isDgDga) class="table-light" @endif>
                            <tr>
                                <th>Entreprise</th>
                                <th>Programme</th>
                                <th>Analyste</th>
                                @if($space['route'] === 'respexp')
                                    <th class="text-nowrap">Cotation du dossier</th>
                                @endif
                                <th>Gestionnaire</th>
                                <th>Statut</th>
                                <th>Étape</th>
                                <th class="text-end" style="width: 64px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dossiers as $dossier)
                                @php($dossierShowUrl = route($dossiersShowRouteName, $dossier->token))
                                <tr>
                                    <td>
                                        @if($isDgDga)
                                            <a href="{{ $dossierShowUrl }}" class="fw-medium text-decoration-none text-body">{{ $dossier->entreprise?->name ?? '—' }}</a>
                                        @else
                                            {{ $dossier->entreprise?->name ?? '—' }}
                                        @endif
                                    </td>
                                    <td>{{ $dossiersListUsesProgrammesLabel ? ($dossier->programmesLabel() ?: '—') : ($dossier->programme?->name ?? '—') }}</td>
                                    <td>{{ $dossier->analyste?->name ?? '—' }}</td>
                                    @if($space['route'] === 'respexp')
                                        <td class="small text-nowrap">
                                            @if($dossier->exploitation_analyste_assigned_at)
                                                {{ $dossier->exploitation_analyste_assigned_at->format('d/m/Y') }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                    @endif
                                    <td>{{ $dossier->gestionnaire?->name ?? '—' }}</td>
                                    <td><x-statut-badge :statut="$dossier->instructionStatutPresentation()" :show-detail="false" /></td>
                                    <td class="small text-muted">{{ $dossier->status['name'] ?? '—' }}</td>
                                    <td class="text-end angara-table-actions">
                                        @if($isDgDga)
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Actions">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="{{ $dossierShowUrl }}"><i class="bi bi-eye me-2"></i>Ouvrir</a></li>
                                                    <li><button class="dropdown-item" type="button" data-copy-text="{{ $dossierShowUrl }}"><i class="bi bi-link-45deg me-2"></i>Copier le lien</button></li>
                                                </ul>
                                            </div>
                                        @else
                                            <a href="{{ $dossierShowUrl }}" class="btn btn-sm btn-primary">Ouvrir</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                @if(! $isDgDga)
                                <tr>
                                    <td colspan="{{ $space['route'] === 'respexp' ? 8 : 7 }}" class="text-center text-muted py-4">Aucun dossier trouvé.</td>
                                </tr>
                                @endif
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($isDgDga)
                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
                        <div data-angara-table-info>
                            @if($dossiers->total() > 0)
                                Affichage de {{ $dossiers->firstItem() }} à {{ $dossiers->lastItem() }} sur {{ $dossiers->total() }} entrées
                            @else
                                Affichage de 0 à 0 sur 0 entrées
                            @endif
                        </div>
                        <div data-angara-table-paging>{{ $dossiers->withQueryString()->links() }}</div>
                    </div>
                    <div class="angara-table-empty {{ $dossiers->total() > 0 ? 'd-none' : '' }} px-0 pt-2" data-angara-table-empty>Aucune donnée.</div>
                    {{ $dossiers->links() }}
                @endif
            </div>
        </div>
        @if($isDgDga)
        </div>
        @endif
    </div>
@endsection
