@extends('Layouts.admin')

@push('styles')
<style>
    .admin-programmes-stats .stats-card { transition: transform 0.2s; }
    .admin-programmes-stats .stats-card:hover { transform: translateY(-2px); }
    .admin-programmes-stats .stat-value { font-size: 1.75rem; font-weight: 700; }
    #programmesTable th:last-child,
    #programmesTable td:last-child { min-width: 100px; white-space: nowrap; }
    .btn-action { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.65rem; font-size: 0.8rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s; }
    .btn-action:hover { transform: translateY(-1px); }
    .admin-programmes-filters .form-label { font-weight: 500; }
</style>
@endpush

@section('title', 'Programmes')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.programmes.index') }}">Programmes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Liste</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="{{ route('admin.programmes.create') }}" class="dropdown-item"><i class="demo-pli-add me-2 fs-5"></i> Ajouter</a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Programmes</h5>
        <p class="text-body-secondary mb-0 mt-1">Liste paginée avec recherche et filtres dynamiques.</p>
    </div>
@endsection

@section('content')
    <div class="row g-3 mb-4 admin-programmes-stats" id="stats-section">
        <div class="col-6 col-md-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="demo-psi-folder text-primary fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-primary" id="stat-total">—</div>
                        <small class="text-muted">Total programmes</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="demo-psi-check text-success fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-success" id="stat-actifs">—</div>
                        <small class="text-muted">Actifs</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="demo-psi-file text-info fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-info" id="stat-avec-convention">—</div>
                        <small class="text-muted">Avec convention</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 admin-programmes-filters">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-5">
                    <label for="filter-search" class="form-label small text-muted mb-1">Recherche</label>
                    <input type="search" id="filter-search" class="form-control form-control-sm" placeholder="Désignation, convention, signataire, types…" autocomplete="off">
                </div>
                <div class="col-12 col-md-4">
                    <label for="filter-signataire" class="form-label small text-muted mb-1">Signataire</label>
                    <select id="filter-signataire" class="form-select form-select-sm">
                        <option value="">Tous les signataires</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <button type="button" id="btn-reset-filters" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="demo-psi-arrow-left me-1"></i> Réinitialiser les filtres
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="angara-table" id="programmes-card">
        <div class="card border-0 shadow-sm">
        <div class="card-body p-3 p-md-4">
            <div class="table-responsive">
                <table id="programmesTable" class="table table-hover table-bordered align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th data-angara-sort-col="0">Désignation</th>
                            <th data-angara-sort-col="1">Convention</th>
                            <th data-angara-sort-col="2">Signataire</th>
                            <th data-angara-sort-col="3">Date de signature</th>
                            <th data-angara-sort-col="4">Budget (XAF)</th>
                            <th data-angara-sort-col="5">Bénéficiaires PP</th>
                            <th data-angara-sort-col="6">Bénéficiaires PM</th>
                            <th class="text-end">Actions</th>
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
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = "{{ url('admin/programmes') }}";
    const paginatedUrl = "{{ route('admin.programmes.paginated') }}";
    const statsUrl = "{{ route('admin.programmes.stats') }}";
    const filterOptionsUrl = "{{ route('admin.programmes.filter-options') }}";

    let filterTimeout;

    fetch(filterOptionsUrl)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            const sel = document.getElementById('filter-signataire');
            (data.signataires || []).forEach(function(name) {
                const opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                sel.appendChild(opt);
            });
        })
        .catch(function(err) { console.error('Filter options:', err); });

    function loadStats() {
        const params = new URLSearchParams();
        const sig = document.getElementById('filter-signataire') && document.getElementById('filter-signataire').value;
        if (sig) params.append('signataire_filter', sig);
        fetch(statsUrl + (params.toString() ? '?' + params : ''))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                document.getElementById('stat-total').textContent = data.total != null ? data.total : '—';
                document.getElementById('stat-actifs').textContent = data.actifs != null ? data.actifs : '—';
                document.getElementById('stat-avec-convention').textContent = data.avec_convention != null ? data.avec_convention : '—';
            })
            .catch(function(err) { console.error('Stats:', err); });
    }

    const table = new AngaraTable(document.getElementById('programmesTable'), {
        ajaxUrl: paginatedUrl,
        pageLength: 15,
        searchDelay: 350,
        filters: { signataire_filter: 'filter-signataire' },
        columns: [
            { data: 'name', name: 'name', render: function(d, _t, row) {
                const token = row && row.token ? row.token : '';
                const label = d != null && d !== '' ? d : '—';
                return '<a href="' + baseUrl + '/' + token + '" class="fw-medium text-decoration-none">' + label + '</a>';
            }},
            { data: 'convention', name: 'convention' },
            { data: 'signataire', name: 'signataire' },
            { data: 'dt_sig_conv', name: 'dt_sig_conv' },
            { data: 'budget', name: 'budget', render: function(d) {
                return d != null && d !== '' ? new Intl.NumberFormat('fr-FR').format(d) : '—';
            }},
            { data: 'type_pp', name: 'type_pp' },
            { data: 'type_pm', name: 'type_pm' },
            { data: 'token', name: 'token', orderable: false, className: 'text-end angara-table-actions', width: '64px', render: function(token) {
                const href = baseUrl + '/' + (token || '');
                return ''
                    + '<div class="dropdown">'
                    + '  <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'
                    + '    <i class="bi bi-three-dots-vertical"></i>'
                    + '  </button>'
                    + '  <ul class="dropdown-menu dropdown-menu-end">'
                    + '    <li><a class="dropdown-item" href="' + href + '"><i class="bi bi-eye me-2"></i>Ouvrir</a></li>'
                    + '    <li><button class="dropdown-item" type="button" data-copy-text="' + href + '"><i class="bi bi-link-45deg me-2"></i>Copier le lien</button></li>'
                    + '  </ul>'
                    + '</div>';
            }}
        ],
        order: [0, 'asc'],
        drawCallback: function() { loadStats(); }
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

    document.getElementById('btn-reset-filters').addEventListener('click', function() {
        document.getElementById('filter-search').value = '';
        document.getElementById('filter-signataire').value = '';
        table.state.search = '';
        table.state.page = 0;
        table.reload();
    });

    loadStats();
});
</script>
@endsection
