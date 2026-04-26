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
    default => 'Layouts.app',
})

@php
    $isDgDga = in_array($space['route'] ?? '', ['dg', 'dga'], true);
    $entityListLabel = $isDgDga ? 'Clients' : 'Entreprises';
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
        <p class="text-muted mb-0">{{ $isDgDga ? 'Vue transverse de tous les clients du portefeuille.' : 'Vue transverse de toutes les entreprises du portefeuille.' }}</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
                    <span class="text-muted small">{{ $entreprises->total() }} {{ $isDgDga ? 'client(s)' : 'entreprise(s)' }}</span>
                    <form method="get" action="{{ route($space['route'].'.entreprises.index') }}" class="d-flex flex-wrap gap-2 align-items-end">
                        <div style="min-width: 14rem;">
                            @include('partials.client-structuration-filter-select', [
                                'id' => 'rolespace_entreprises_struct',
                                'name' => 'client_structuration_status',
                                'selected' => $structurationStatus ?? null,
                            ])
                        </div>
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
                        <div>
                            <label class="form-label small text-muted mb-0">Promu client du</label>
                            <input type="date" name="promu_client_from" value="{{ request('promu_client_from') }}" class="form-control form-control-sm">
                        </div>
                        <div>
                            <label class="form-label small text-muted mb-0">au</label>
                            <input type="date" name="promu_client_to" value="{{ request('promu_client_to') }}" class="form-control form-control-sm">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Filtrer</button>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="rolespace-entreprises-actions" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="rolespace-entreprises-actions">
                                <li><a class="dropdown-item" href="{{ route($space['route'].'.entreprises.export', array_merge(request()->except('format'), ['format' => 'xlsx'])) }}"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Exporter en Excel</a></li>
                                <li><a class="dropdown-item" href="{{ route($space['route'].'.entreprises.export', array_merge(request()->except('format'), ['format' => 'pdf'])) }}"><i class="bi bi-file-earmark-pdf me-2"></i>Exporter en PDF</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route($space['route'].'.entreprises.index') }}"><i class="bi bi-arrow-counterclockwise me-2"></i>Réinitialiser</a></li>
                            </ul>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Entreprise</th>
                                <th>Statut</th>
                                <th>Structuration</th>
                                <th>Gestionnaire</th>
                                <th>Dossiers</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entreprises as $entreprise)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $entreprise->name }}</div>
                                        <small class="text-muted">{{ $entreprise->forme?->name ?? '—' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $entreprise->prospect ? 'warning' : 'success' }}">
                                            {{ $entreprise->prospect ? 'Prospect' : 'Client' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($entreprise->promu_client_at)
                                            @include('partials.client-structuration-badge', ['eer' => $entreprise->dossierEntreeRelation])
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $entreprise->gestionnaire?->name ?? $entreprise->user?->name ?? '—' }}</td>
                                    <td>{{ $entreprise->dossiers_count }}</td>
                                    <td class="text-end">
                                        <a href="{{ route($space['route'].'.entreprises.show', $entreprise->token) }}" class="btn btn-sm btn-primary">Ouvrir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Aucune entreprise trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $entreprises->links() }}
            </div>
        </div>
    </div>
@endsection
