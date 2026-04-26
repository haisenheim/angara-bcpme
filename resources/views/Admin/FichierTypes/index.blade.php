@extends('Layouts.admin')

@section('title', 'Repertoire des pieces et documents')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Repertoire des pieces et documents</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0 mt-2">Repertoire des pieces et documents</h5>
    <p class="lead">Gestion des types de fichiers (table <code>fichiers_types</code>).</p>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <strong>{{ $editItem ? 'Modifier un type' : 'Nouveau type' }}</strong>
            </div>
            <div class="card-body">
                <form method="post" action="{{ $editItem ? route('admin.fichiers-types.update', $editItem) : route('admin.fichiers-types.store') }}">
                    @csrf
                    @if($editItem)
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $editItem->name ?? '') }}"
                            maxlength="100"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ $editItem ? 'Mettre a jour' : 'Ajouter' }}</button>
                        @if($editItem)
                            <a href="{{ route('admin.fichiers-types.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body py-3">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-7">
                        <label for="filter-search" class="form-label small text-muted mb-1">Recherche</label>
                        <input type="search" id="filter-search" class="form-control form-control-sm" placeholder="Rechercher un type…" autocomplete="off">
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="filter-active" class="form-label small text-muted mb-1">Statut</label>
                        <select id="filter-active" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            <option value="1">Actifs</option>
                            <option value="0">Inactifs</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="angara-table" id="fichiers-types-card">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <div class="table-responsive">
                        <table id="fichiersTypesTable" class="table table-hover table-bordered align-middle mb-0" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th data-angara-sort-col="0">Nom</th>
                                    <th data-angara-sort-col="1" style="width: 140px;">Statut</th>
                                    <th class="text-end" style="width: 64px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
                        <div data-angara-table-info></div>
                        <div data-angara-table-paging></div>
                    </div>
                    <div class="angara-table-empty d-none" data-angara-table-empty>Aucune donnée.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = "{{ url('admin/fichiers-types') }}";
    const paginatedUrl = "{{ route('admin.fichiers-types.paginated') }}";

    let filterTimeout;

    const table = new AngaraTable(document.getElementById('fichiersTypesTable'), {
        ajaxUrl: paginatedUrl,
        pageLength: 15,
        searchDelay: 350,
        filters: { active_filter: 'filter-active' },
        columns: [
            { data: 'name', name: 'name', render: function(d, _t, row) {
                const id = row && row.id != null ? row.id : '';
                const label = d != null && d !== '' ? d : '—';
                return '<a href="' + baseUrl + '/' + id + '/edit" class="fw-medium text-decoration-none">' + label + '</a>';
            }},
            { data: 'active', name: 'active', render: function(d) {
                const ok = !!d;
                return '<span class="badge bg-' + (ok ? 'success' : 'secondary') + '">' + (ok ? 'Actif' : 'Inactif') + '</span>';
            }},
            { data: 'id', name: 'id', orderable: false, className: 'text-end angara-table-actions', width: '64px', render: function(id, _t, row) {
                const editHref = baseUrl + '/' + (id || '') + '/edit';
                const enableHref = baseUrl + '/' + (id || '') + '/enable';
                const disableHref = baseUrl + '/' + (id || '') + '/disable';
                const isActive = row && row.active;

                return ''
                    + '<div class="dropdown">'
                    + '  <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'
                    + '    <i class="bi bi-three-dots-vertical"></i>'
                    + '  </button>'
                    + '  <ul class="dropdown-menu dropdown-menu-end">'
                    + '    <li><a class="dropdown-item" href="' + editHref + '"><i class="bi bi-pencil me-2"></i>Modifier</a></li>'
                    + (isActive
                        ? '    <li><a class="dropdown-item" href="' + disableHref + '"><i class="bi bi-slash-circle me-2"></i>Désactiver</a></li>'
                        : '    <li><a class="dropdown-item" href="' + enableHref + '"><i class="bi bi-check-circle me-2"></i>Activer</a></li>')
                    + '  </ul>'
                    + '</div>';
            }},
        ],
        order: [0, 'asc'],
    });

    document.getElementById('filter-search').addEventListener('input', function() {
        clearTimeout(filterTimeout);
        const v = this.value || '';
        filterTimeout = setTimeout(function() {
            table.state.search = v;
            table.state.page = 0;
            table.reload();
        }, 350);
    });
});
</script>
@endsection

