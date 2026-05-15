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

@php
    $listeKind = $listeKind ?? 'all';
    $isDgDga = in_array($space['route'] ?? '', ['dg', 'dga'], true);
    $entityListLabel = match ($listeKind) {
        'clients' => 'Clients',
        'prospects' => 'Prospects',
        default => 'Entreprises',
    };
    $pageSubtitle = match ($listeKind) {
        'clients' => 'Vue transverse de tous les clients du portefeuille.',
        'prospects' => 'Vue transverse de tous les prospects du portefeuille.',
        default => 'Vue transverse de toutes les entreprises du portefeuille.',
    };
    $countLabel = match ($listeKind) {
        'clients' => 'client(s)',
        'prospects' => 'prospect(s)',
        default => 'entreprise(s)',
    };
    $listRouteName = $listeKind === 'prospects'
        ? ($portfolioProspectsListRoute ?? $space['route'].'.prospects.index')
        : $space['route'].'.entreprises.index';
    $exportRouteName = $listeKind === 'prospects'
        ? ($portfolioProspectsExportRoute ?? $space['route'].'.prospects.export')
        : $space['route'].'.entreprises.export';
    $showClientOnlyFilters = $listeKind !== 'prospects';
    $emptyTableColspan = 4 + ($listeKind === 'all' ? 1 : 0) + ($showClientOnlyFilters ? 1 : 0);
@endphp

@section('title', $entityListLabel.' - '.$space['title'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $entityListLabel }}</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">{{ $entityListLabel }}</h5>
        <p class="text-muted mb-0">{{ $pageSubtitle }}</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @if($isDgDga)
        <div class="angara-table" id="rolespace-{{ $space['route'] }}-entreprises">
        @endif
        <div class="card {{ $isDgDga ? 'border-0 shadow-sm' : 'shadow-sm border-0' }}">
            <div class="card-body {{ $isDgDga ? 'p-3 p-md-4' : '' }}">
                <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
                    @if(! $isDgDga)
                        <span class="text-muted small">{{ $entreprises->total() }} {{ $countLabel }}</span>
                    @endif
                    <form method="get" action="{{ route($listRouteName) }}" class="d-flex flex-wrap gap-2 align-items-end{{ $isDgDga ? ' ms-0 ms-md-auto' : '' }}">
                        @if($showClientOnlyFilters)
                        <div style="min-width: 14rem;">
                            @include('partials.client-structuration-filter-select', [
                                'id' => 'rolespace_entreprises_struct',
                                'name' => 'client_structuration_status',
                                'selected' => $structurationStatus ?? null,
                            ])
                        </div>
                        @endif
                        <div>
                            <label class="form-label small text-muted mb-0">Agence</label>
                            <select name="agence_id" class="form-select form-select-sm" style="min-width: 11rem;">
                                <option value="">Toutes</option>
                                @foreach($agences ?? [] as $a)
                                    <option value="{{ $a->id }}" @selected((string) request('agence_id') === (string) $a->id)>{{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label small text-muted mb-0">Gestionnaire</label>
                            <select name="gestionnaire_id" class="form-select form-select-sm" style="min-width: 11rem;">
                                <option value="">Tous</option>
                                @foreach($gestionnaires ?? [] as $g)
                                    <option value="{{ $g->id }}" @selected((string) request('gestionnaire_id') === (string) $g->id)>{{ $g->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($showClientOnlyFilters)
                        <div>
                            <label class="form-label small text-muted mb-0">Promu client du</label>
                            <input type="date" name="promu_client_from" value="{{ request('promu_client_from') }}" class="form-control form-control-sm">
                        </div>
                        <div>
                            <label class="form-label small text-muted mb-0">au</label>
                            <input type="date" name="promu_client_to" value="{{ request('promu_client_to') }}" class="form-control form-control-sm">
                        </div>
                        @endif
                        <button type="submit" class="btn btn-sm btn-primary">Filtrer</button>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="rolespace-entreprises-actions" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="rolespace-entreprises-actions">
                                <li><a class="dropdown-item" href="{{ route($exportRouteName, array_merge(request()->except('format'), ['format' => 'xlsx'])) }}"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Exporter en Excel</a></li>
                                <li><a class="dropdown-item" href="{{ route($exportRouteName, array_merge(request()->except('format'), ['format' => 'pdf'])) }}"><i class="bi bi-file-earmark-pdf me-2"></i>Exporter en PDF</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route($listRouteName) }}"><i class="bi bi-arrow-counterclockwise me-2"></i>Réinitialiser</a></li>
                            </ul>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle{{ $isDgDga ? ' table-bordered mb-0' : '' }}" @if($isDgDga) style="width:100%" @endif>
                        <thead @if($isDgDga) class="table-light" @endif>
                            <tr>
                                <th>Entreprise</th>
                                @if($listeKind === 'all')
                                <th>Statut</th>
                                @endif
                                @if($showClientOnlyFilters)
                                <th>Structuration</th>
                                @endif
                                <th>Gestionnaire</th>
                                <th>Dossiers</th>
                                <th class="text-end" style="width: 64px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entreprises as $entreprise)
                                @php($entrepriseShowUrl = route($space['route'].'.entreprises.show', $entreprise->token))
                                <tr>
                                    <td>
                                        @if($isDgDga)
                                            <a href="{{ $entrepriseShowUrl }}" class="fw-medium text-decoration-none text-body">{{ $entreprise->name }}</a>
                                            <div><small class="text-muted">{{ $entreprise->forme?->name ?? '—' }}</small></div>
                                        @else
                                            <div class="fw-semibold">{{ $entreprise->name }}</div>
                                            <small class="text-muted">{{ $entreprise->forme?->name ?? '—' }}</small>
                                        @endif
                                    </td>
                                    @if($listeKind === 'all')
                                    <td>
                                        <span class="badge bg-{{ $entreprise->prospect ? 'warning' : 'success' }}">
                                            {{ $entreprise->prospect ? 'Prospect' : 'Client' }}
                                        </span>
                                    </td>
                                    @endif
                                    @if($showClientOnlyFilters)
                                    <td>
                                        @if($entreprise->promu_client_at)
                                            @include('partials.client-structuration-badge', ['eer' => $entreprise->dossierEntreeRelation])
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    @endif
                                    <td>{{ $entreprise->gestionnaire?->name ?? $entreprise->user?->name ?? '—' }}</td>
                                    <td>{{ $entreprise->dossiers_count }}</td>
                                    <td class="text-end angara-table-actions">
                                        @if($isDgDga)
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Actions">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="{{ $entrepriseShowUrl }}"><i class="bi bi-eye me-2"></i>Ouvrir</a></li>
                                                    <li><button class="dropdown-item" type="button" data-copy-text="{{ $entrepriseShowUrl }}"><i class="bi bi-link-45deg me-2"></i>Copier le lien</button></li>
                                                </ul>
                                            </div>
                                        @else
                                            <a href="{{ $entrepriseShowUrl }}" class="btn btn-sm btn-primary">Ouvrir</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                @if(! $isDgDga)
                                <tr>
                                    <td colspan="{{ $emptyTableColspan }}" class="text-center text-muted py-4">Aucune entreprise trouvée.</td>
                                </tr>
                                @endif
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($isDgDga)
                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
                        <div data-angara-table-info>
                            @if($entreprises->total() > 0)
                                Affichage de {{ $entreprises->firstItem() }} à {{ $entreprises->lastItem() }} sur {{ $entreprises->total() }} entrées
                            @else
                                Affichage de 0 à 0 sur 0 entrées
                            @endif
                        </div>
                        <div data-angara-table-paging>{{ $entreprises->withQueryString()->links() }}</div>
                    </div>
                    <div class="angara-table-empty {{ $entreprises->total() > 0 ? 'd-none' : '' }} px-0 pt-2" data-angara-table-empty>Aucune donnée.</div>
                @else
                    {{ $entreprises->links() }}
                @endif
            </div>
        </div>
        @if($isDgDga)
        </div>
        @endif
    </div>
@endsection
