@extends('Layouts.chef_filiere')

@section('title', 'Clients')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active" aria-current="page">Clients</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">Clients</h5>
    <p class="text-body-secondary mb-0 mt-1 small">Portefeuille des entreprises promues au statut client pour votre agence.</p>
</div>
@endsection

@section('content')
<div class="cf-page">
    <div class="cf-hero mb-4">
        <h1 class="cf-hero__title">Liste des clients</h1>
        <p class="cf-hero__lead">Suivez l’état de structuration et ouvrez chaque dossier pour le détail.</p>
        <div class="cf-hero__meta">
            <span class="cf-kpi"><span class="text-muted fw-normal">Total</span> <span class="cf-kpi__val">{{ $items->count() }}</span></span>
        </div>
    </div>

    <div class="cf-panel">
        <div class="cf-panel__toolbar flex-wrap gap-3 align-items-end">
            <p class="cf-panel__toolbar-label mb-0 flex-grow-1">Répertoire</p>
            <form method="get" action="{{ route('chef-filiere.clients.index') }}" class="d-flex flex-wrap gap-2 align-items-end">
                <div style="min-width: 14rem;">
                    @include('partials.client-structuration-filter-select', [
                        'id' => 'chef_filiere_clients_struct_filter',
                        'name' => 'client_structuration_status',
                        'selected' => $structurationStatus ?? null,
                    ])
                </div>
                <div>
                    <label class="form-label small text-muted mb-0">Gestionnaire</label>
                    <select name="gestionnaire_id" class="form-select form-select-sm" style="min-width: 12rem;">
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
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="chef-filiere-clients-actions" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chef-filiere-clients-actions">
                        <li><a class="dropdown-item" href="{{ route('chef-filiere.clients.export', array_merge(request()->except('format'), ['format' => 'xlsx'])) }}"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Exporter en Excel</a></li>
                        <li><a class="dropdown-item" href="{{ route('chef-filiere.clients.export', array_merge(request()->except('format'), ['format' => 'pdf'])) }}"><i class="bi bi-file-earmark-pdf me-2"></i>Exporter en PDF</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('chef-filiere.clients.index') }}"><i class="bi bi-arrow-counterclockwise me-2"></i>Réinitialiser</a></li>
                    </ul>
                </div>
            </form>
        </div>
        <div class="cf-table-wrap">
            <table class="table cf-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th scope="col">Client</th>
                        <th scope="col">Agence</th>
                        <th scope="col">Gestionnaire</th>
                        <th scope="col">Promu client</th>
                        <th scope="col">État structuration</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php($eer = $item->dossierEntreeRelation)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $item->name }}</td>
                            <td>{{ $item->agence?->name ?? '—' }}</td>
                            <td>{{ $item->gestionnaire?->name ?? '—' }}</td>
                            <td><span class="text-nowrap">{{ optional($item->promu_client_at)->format('d/m/Y H:i') ?? '—' }}</span></td>
                            <td>
                                @include('partials.client-structuration-badge', ['eer' => $eer, 'showNonStructureHint' => true])
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('chef-filiere.clients.show', $item->token) }}" class="btn btn-sm btn-primary">Consulter</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="cf-empty border-0">
                                <i class="cf-empty__icon demo-pli-building" aria-hidden="true"></i>
                                Aucun client enregistré pour cette agence.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
