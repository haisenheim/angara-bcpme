@extends('Layouts.analyste')

@push('styles')
<style>
    .stats-card { transition: transform 0.2s; }
    .stats-card:hover { transform: translateY(-2px); }
    .stats-card .stat-value { font-size: 1.75rem; font-weight: 700; }
    #prospectsTable th:last-child,
    #prospectsTable td:last-child { min-width: 100px; white-space: nowrap; }
    .btn-action { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.65rem; font-size: 0.8rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s; }
    .btn-action:hover { transform: translateY(-1px); }
</style>
@endpush

@section('title', 'Prospects')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('analyste.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item active" aria-current="page">Prospects</li>
    </ol>
</nav>
@endsection


@section('page-header')
    <div>
        <h5 class="page-title mb-0">Prospects</h5>
        <p class="text-body-secondary mb-0 mt-1">Entreprises à prospecter</p>
    </div>
@endsection

@section('content')
    <div class="row g-3 mb-4 angara-stats-row" id="stats-section">
        <div class="col-6 col-md-3">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="demo-psi-phone-2 text-primary fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-primary" id="stat-total">-</div>
                        <small class="text-muted">Total prospects</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="demo-psi-file-edit text-success fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-success" id="stat-formelles">-</div>
                        <small class="text-muted">Formelles</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="demo-psi-file text-info fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-info" id="stat-informelles">-</div>
                        <small class="text-muted">Informelles</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="demo-psi-two-column-layout text-warning fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-warning" id="stat-petites">-</div>
                        <small class="text-muted">Petites / TPE</small>
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
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-taille" class="form-label small text-muted">Taille</label>
                    <select id="filter-taille" class="form-select form-select-sm">
                        <option value="">Toutes</option>
                        <option value="GRANDE">Grande</option>
                        <option value="MOYENNE">Moyenne</option>
                        <option value="PETITE">Petite</option>
                        <option value="TRES PETITE">Très petite</option>
                        <option value="COOPERATIVE">Coopérative</option>
                        <option value="ASSOCIATION">Association</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-forme" class="form-label small text-muted">Forme juridique</label>
                    <select id="filter-forme" class="form-select form-select-sm">
                        <option value="">Toutes</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-caractere" class="form-label small text-muted">Caractère</label>
                    <select id="filter-caractere" class="form-select form-select-sm">
                        <option value="">Tous</option>
                        <option value="Formel">Formel</option>
                        <option value="Informel">Informel</option>
                    </select>
                </div>
            </div>
            <div class="row g-3 align-items-end mt-1 pt-2 border-top border-light-subtle">
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
                    <label for="filter-submission" class="form-label small text-muted">Soumission</label>
                    <select id="filter-submission" class="form-select form-select-sm">
                        <option value="">Tous</option>
                        <option value="draft">Brouillon</option>
                        <option value="submitted">Soumis</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-created-from" class="form-label small text-muted">Création du</label>
                    <input type="date" id="filter-created-from" class="form-control form-control-sm">
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-created-to" class="form-label small text-muted">Création au</label>
                    <input type="date" id="filter-created-to" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-md-auto ms-md-auto pt-1 pt-md-0">
                    <label class="form-label small text-muted d-block mb-1">&nbsp;</label>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="analyste-prospects-actions" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="analyste-prospects-actions">
                            <li><button type="button" class="dropdown-item" id="btn-export-xlsx-prospects"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Exporter en Excel</button></li>
                            <li><button type="button" class="dropdown-item" id="btn-export-pdf-prospects"><i class="bi bi-file-earmark-pdf me-2"></i>Exporter en PDF</button></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button type="button" class="dropdown-item" id="btn-reset-filters"><i class="bi bi-arrow-counterclockwise me-2"></i>Réinitialiser</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="angara-table" id="prospects-card">
        <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="prospectsTable" class="table table-hover table-bordered align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th data-angara-sort-col="0">Dénomination</th>
                            <th data-angara-sort-col="1">RCCM</th>
                            <th data-angara-sort-col="2">NIU</th>
                            <th data-angara-sort-col="3">Dirigeant</th>
                            <th data-angara-sort-col="4">Localisation</th>
                            <th data-angara-sort-col="5">Taille</th>
                            <th data-angara-sort-col="6">Capital</th>
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
    const paginatedUrl = "{{ route('analyste.prospects.paginated') }}";
    const statsUrl = "{{ route('analyste.prospects.stats') }}";
    const filterOptionsUrl = "{{ route('analyste.prospects.filter-options') }}";
    const exportUrl = "{{ route('analyste.prospects.export') }}";

    let filterTimeout;

    const prospectFilterFields = { region_id: 'filter-region', taille: 'filter-taille', forme_id: 'filter-forme', caractere: 'filter-caractere', agence_id: 'filter-agence', gestionnaire_id: 'filter-gestionnaire', submission: 'filter-submission', created_from: 'filter-created-from', created_to: 'filter-created-to' };

    function appendProspectFilters(params) {
        Object.entries(prospectFilterFields).forEach(([k, id]) => {
            const v = document.getElementById(id)?.value;
            if (v) params.append(k, v);
        });
    }

    function buildProspectsExportQuery(format) {
        const params = new URLSearchParams();
        params.set('format', format);
        appendProspectFilters(params);
        const s = document.getElementById('filter-search')?.value;
        if (s) params.append('search[value]', s);
        return params;
    }

    fetch(filterOptionsUrl)
        .then(r => r.json())
        .then(data => {
            const regionSelect = document.getElementById('filter-region');
            (data.regions || []).forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.id;
                opt.textContent = r.name;
                regionSelect.appendChild(opt);
            });
            const formeSelect = document.getElementById('filter-forme');
            (data.formes || []).forEach(f => {
                const opt = document.createElement('option');
                opt.value = f.id;
                opt.textContent = f.name;
                formeSelect.appendChild(opt);
            });
            const agSelect = document.getElementById('filter-agence');
            (data.agences || []).forEach(a => {
                const opt = document.createElement('option');
                opt.value = a.id;
                opt.textContent = a.name;
                agSelect.appendChild(opt);
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

    function loadStats() {
        const params = new URLSearchParams();
        appendProspectFilters(params);
        fetch(statsUrl + (params.toString() ? '?' + params : ''))
            .then(r => r.json())
            .then(data => {
                document.getElementById('stat-total').textContent = data.total ?? 0;
                document.getElementById('stat-formelles').textContent = data.formelles ?? 0;
                document.getElementById('stat-informelles').textContent = data.informelles ?? 0;
                const pet = (data.par_taille && (data.par_taille['PETITE'] || data.par_taille['TRES PETITE']))
                    ? (parseInt(data.par_taille['PETITE']||0) + parseInt(data.par_taille['TRES PETITE']||0))
                    : 0;
                document.getElementById('stat-petites').textContent = pet;
            })
            .catch(err => console.error('Stats:', err));
    }

    const table = new AngaraTable(document.getElementById('prospectsTable'), {
        ajaxUrl: paginatedUrl,
        pageLength: 15,
        searchDelay: 350,
        filters: prospectFilterFields,
        columns: [
            { data: 'name', name: 'name', render: function(d, _t, row) { return '<a href="' + baseUrl + '/' + (row?.token||'') + '" class="fw-medium text-decoration-none">' + (d || '-') + '</a>'; } },
            { data: 'rccm', name: 'rccm' },
            { data: 'niu', name: 'niu' },
            { data: 'manager', name: 'manager' },
            { data: null, name: 'localisation', orderable: false, render: function(_d, _t, row) { return (row?.commune || '-') + (row?.region ? ' / ' + row.region : ''); } },
            { data: 'taille', name: 'taille', render: function(d) { return d ? '<span class="badge bg-secondary">' + d + '</span>' : '-'; } },
            { data: 'capital', name: 'capital', render: function(d) { return d != null ? new Intl.NumberFormat('fr-FR').format(d) : '-'; } },
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

    document.getElementById('btn-reset-filters').addEventListener('click', function() {
        document.getElementById('filter-search').value = '';
        document.getElementById('filter-region').value = '';
        document.getElementById('filter-taille').value = '';
        document.getElementById('filter-forme').value = '';
        document.getElementById('filter-caractere').value = '';
        document.getElementById('filter-agence').value = '';
        document.getElementById('filter-gestionnaire').value = '';
        document.getElementById('filter-submission').value = '';
        document.getElementById('filter-created-from').value = '';
        document.getElementById('filter-created-to').value = '';
        table.state.search = '';
        table.state.page = 0;
        table.reload();
    });

    document.getElementById('btn-export-xlsx-prospects')?.addEventListener('click', function() {
        window.location.href = exportUrl + '?' + buildProspectsExportQuery('xlsx').toString();
    });
    document.getElementById('btn-export-pdf-prospects')?.addEventListener('click', function() {
        window.location.href = exportUrl + '?' + buildProspectsExportQuery('pdf').toString();
    });

    loadStats();
});
</script>
@endsection
