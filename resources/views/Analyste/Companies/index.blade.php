@extends('Layouts.analyste')

@push('styles')
<style>
    .stats-card { transition: transform 0.2s; }
    .stats-card:hover { transform: translateY(-2px); }
    .stats-card .stat-value { font-size: 1.75rem; font-weight: 700; }
    #entreprisesTable th:last-child,
    #entreprisesTable td:last-child { min-width: 100px; white-space: nowrap; }
    .btn-action { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.65rem; font-size: 0.8rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s; }
    .btn-action:hover { transform: translateY(-1px); }
</style>
@endpush

@section('title', 'Entreprises')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Entreprises</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Entreprises</h5>
        <p class="text-body-secondary mb-0 mt-1">Entreprises pour lesquelles vous avez des dossiers d'instruction</p>
    </div>
@endsection

@section('content')
    <div class="row g-3 mb-4 angara-stats-row" id="stats-section">
        <div class="col-6 col-md-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="demo-psi-building text-primary fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-primary" id="stat-total">-</div>
                        <small class="text-muted">Total entreprises</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="demo-psi-file text-success fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-success" id="stat-avec-rccm">-</div>
                        <small class="text-muted">Avec RCCM</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="demo-psi-credit-card-2 text-info fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-info" id="stat-avec-niu">-</div>
                        <small class="text-muted">Avec NIU</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 angara-filter-card">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label for="filter-search" class="form-label small text-muted">Recherche</label>
                    <input type="text" id="filter-search" class="form-control form-control-sm" placeholder="Nom, RCCM, NIU, dirigeant...">
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-region" class="form-label small text-muted">Région</label>
                    <select id="filter-region" class="form-select form-select-sm">
                        <option value="">Toutes</option>
                        @foreach($regions as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-departement" class="form-label small text-muted">Département</label>
                    <select id="filter-departement" class="form-select form-select-sm">
                        <option value="">Tous</option>
                        @foreach($departements as $d)
                            <option value="{{ $d->id }}" data-region="{{ $d->region_id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-forme" class="form-label small text-muted">Forme juridique</label>
                    <select id="filter-forme" class="form-select form-select-sm">
                        <option value="">Toutes</option>
                        @foreach($formes as $f)
                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    @include('partials.client-structuration-filter-select', ['id' => 'filter-client-structuration'])
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-agence" class="form-label small text-muted">Agence</label>
                    <select id="filter-agence" class="form-select form-select-sm">
                        <option value="">Toutes</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-gestionnaire" class="form-label small text-muted">Gestionnaire</label>
                    <select id="filter-gestionnaire" class="form-select form-select-sm">
                        <option value="">Tous</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-promu-from" class="form-label small text-muted">Promu client du</label>
                    <input type="date" id="filter-promu-from" class="form-control form-control-sm">
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-promu-to" class="form-label small text-muted">au</label>
                    <input type="date" id="filter-promu-to" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-md-auto ms-md-auto pt-1 pt-md-0">
                    <label class="form-label small text-muted d-block mb-1">&nbsp;</label>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="analyste-entreprises-actions" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="analyste-entreprises-actions">
                            <li><button type="button" class="dropdown-item" id="btn-export-xlsx-analyste"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Exporter en Excel</button></li>
                            <li><button type="button" class="dropdown-item" id="btn-export-pdf-analyste"><i class="bi bi-file-earmark-pdf me-2"></i>Exporter en PDF</button></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button type="button" class="dropdown-item" id="btn-reset-filters"><i class="bi bi-arrow-counterclockwise me-2"></i>Réinitialiser</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="angara-table" id="entreprises-card">
        <div class="card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="entreprisesTable" class="table table-hover table-bordered align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th data-angara-sort-col="0">Désignation</th>
                            <th data-angara-sort-col="1">RCCM</th>
                            <th data-angara-sort-col="2">NIU</th>
                            <th data-angara-sort-col="3">Dirigeant</th>
                            <th data-angara-sort-col="4">Région</th>
                            <th data-angara-sort-col="5">Forme</th>
                            <th>Structuration client</th>
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
    const baseUrl = "{{ url('analyste/entreprises') }}";
    const paginatedUrl = "{{ route('analyste.entreprises.paginated') }}";
    const statsUrl = "{{ route('analyste.entreprises.stats') }}";
    const filterOptionsUrl = "{{ route('analyste.entreprises.filter-options') }}";
    const exportUrl = "{{ route('analyste.entreprises.export') }}";

    let filterTimeout;

    function buildClientsExportQuery(format) {
        const params = new URLSearchParams();
        params.set('format', format);
        const rid = document.getElementById('filter-region')?.value;
        if (rid) params.append('region_id', rid);
        const did = document.getElementById('filter-departement')?.value;
        if (did) params.append('departement_id', did);
        const fid = document.getElementById('filter-forme')?.value;
        if (fid) params.append('forme_id', fid);
        const sid = document.getElementById('filter-client-structuration')?.value;
        if (sid) params.append('client_structuration_status', sid);
        const aid = document.getElementById('filter-agence')?.value;
        if (aid) params.append('agence_id', aid);
        const gid = document.getElementById('filter-gestionnaire')?.value;
        if (gid) params.append('gestionnaire_id', gid);
        const pf = document.getElementById('filter-promu-from')?.value;
        if (pf) params.append('promu_client_from', pf);
        const pt = document.getElementById('filter-promu-to')?.value;
        if (pt) params.append('promu_client_to', pt);
        const s = document.getElementById('filter-search')?.value;
        if (s) params.append('search[value]', s);
        return params;
    }

    fetch(filterOptionsUrl)
        .then(r => r.json())
        .then(data => {
            const agenceSelect = document.getElementById('filter-agence');
            (data.agences || []).forEach(a => {
                const opt = document.createElement('option');
                opt.value = a.id;
                opt.textContent = a.name;
                agenceSelect.appendChild(opt);
            });
            const gestSelect = document.getElementById('filter-gestionnaire');
            (data.gestionnaires || []).forEach(u => {
                const opt = document.createElement('option');
                opt.value = u.id;
                opt.textContent = u.name;
                gestSelect.appendChild(opt);
            });
        })
        .catch(err => console.error('Filter options:', err));

    function filterDepartementsByRegion() {
        const regionId = document.getElementById('filter-region').value;
        const opts = document.getElementById('filter-departement').querySelectorAll('option');
        opts.forEach(o => {
            if (o.value === '') {
                o.style.display = '';
            } else {
                o.style.display = (!regionId || o.dataset.region === regionId) ? '' : 'none';
            }
        });
        document.getElementById('filter-departement').value = '';
    }

    function loadStats() {
        const params = new URLSearchParams();
        const rid = document.getElementById('filter-region')?.value;
        const did = document.getElementById('filter-departement')?.value;
        const fid = document.getElementById('filter-forme')?.value;
        if (rid) params.append('region_id', rid);
        if (did) params.append('departement_id', did);
        if (fid) params.append('forme_id', fid);
        const sid = document.getElementById('filter-client-structuration')?.value;
        if (sid) params.append('client_structuration_status', sid);
        const aid = document.getElementById('filter-agence')?.value;
        if (aid) params.append('agence_id', aid);
        const gid = document.getElementById('filter-gestionnaire')?.value;
        if (gid) params.append('gestionnaire_id', gid);
        const pf = document.getElementById('filter-promu-from')?.value;
        if (pf) params.append('promu_client_from', pf);
        const pt = document.getElementById('filter-promu-to')?.value;
        if (pt) params.append('promu_client_to', pt);
        fetch(statsUrl + (params.toString() ? '?' + params : ''))
            .then(r => r.json())
            .then(data => {
                document.getElementById('stat-total').textContent = data.total ?? 0;
                document.getElementById('stat-avec-rccm').textContent = data.avec_rccm ?? 0;
                document.getElementById('stat-avec-niu').textContent = data.avec_niu ?? 0;
            })
            .catch(err => console.error('Stats:', err));
    }

    const table = new AngaraTable(document.getElementById('entreprisesTable'), {
        ajaxUrl: paginatedUrl,
        pageLength: 25,
        searchDelay: 350,
        filters: { region_id: 'filter-region', departement_id: 'filter-departement', forme_id: 'filter-forme', client_structuration_status: 'filter-client-structuration', agence_id: 'filter-agence', gestionnaire_id: 'filter-gestionnaire', promu_client_from: 'filter-promu-from', promu_client_to: 'filter-promu-to' },
        columns: [
            { data: 'name', name: 'name', render: function(d, _t, row) {
                return '<a href="' + baseUrl + '/' + (row?.token||'') + '" class="fw-medium text-decoration-none">' + (d || '-') + '</a>';
            }},
            { data: 'rccm', name: 'rccm' },
            { data: 'niu', name: 'niu' },
            { data: 'manager', name: 'manager' },
            { data: 'region', name: 'region' },
            { data: 'forme', name: 'forme' },
            { data: 'client_structuration_label', name: 'client_structuration_label', orderable: false, render: function(d) { return d ? '<span class="badge text-bg-light text-dark border">' + d + '</span>' : '—'; } },
            { data: 'token', name: 'token', orderable: false, className: 'text-end angara-table-actions', width: '64px', render: function(token) {
                const href = baseUrl + '/' + (token||'');
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
        filterTimeout = setTimeout(() => {
            table.state.search = v;
            table.state.page = 0;
            table.reload();
        }, 350);
    });

    document.getElementById('filter-region').addEventListener('change', function() {
        filterDepartementsByRegion();
        table.state.page = 0;
        table.reload();
    });

    document.getElementById('filter-departement').addEventListener('change', () => table.reload());
    document.getElementById('filter-forme').addEventListener('change', () => table.reload());

    document.getElementById('btn-export-xlsx-analyste')?.addEventListener('click', function() {
        window.location = exportUrl + '?' + buildClientsExportQuery('xlsx').toString();
    });
    document.getElementById('btn-export-pdf-analyste')?.addEventListener('click', function() {
        window.location = exportUrl + '?' + buildClientsExportQuery('pdf').toString();
    });

    document.getElementById('btn-reset-filters').addEventListener('click', function() {
        document.getElementById('filter-search').value = '';
        document.getElementById('filter-region').value = '';
        document.getElementById('filter-departement').value = '';
        document.getElementById('filter-forme').value = '';
        document.getElementById('filter-client-structuration').value = '';
        document.getElementById('filter-agence').value = '';
        document.getElementById('filter-gestionnaire').value = '';
        document.getElementById('filter-promu-from').value = '';
        document.getElementById('filter-promu-to').value = '';
        document.querySelectorAll('#filter-departement option').forEach(o => { o.style.display = ''; });
        table.state.search = '';
        table.state.page = 0;
        table.reload();
    });

    loadStats();
});
</script>
@endsection
