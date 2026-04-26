@extends('Layouts.ca')

@push('styles')
<style>
    .stats-card { transition: transform 0.2s; }
    .stats-card:hover { transform: translateY(-2px); }
    .stats-card .stat-value { font-size: 1.75rem; font-weight: 700; }
</style>
@endpush

@section('title', 'Dossiers d\'instruction')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dossiers d'instruction</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Dossiers d’instruction</h5>
        <p class="text-body-secondary mb-0 mt-1">Liste des dossiers (un ou plusieurs programmes par dossier ; budgets d’appui visibles dans la fiche dossier). Les dossiers soumis par le chef de filière sont à valider sous <a href="{{ route('ca.workflow.instruction-dossiers.index') }}">Dossiers instruction (multi-programmes)</a>.</p>
    </div>
@endsection

@section('content')
    {{-- Statistiques --}}
    <div class="row g-3 mb-4 angara-stats-row" id="stats-section">
        <div class="col-6 col-md-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="demo-psi-folder text-primary fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-primary" id="stat-total">-</div>
                        <small class="text-muted">Total dossiers</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="demo-psi-file-edit text-success fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-success" id="stat-avec-analyste">-</div>
                        <small class="text-muted">Avec analyste</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="demo-psi-file text-warning fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-warning" id="stat-sans-analyste">-</div>
                        <small class="text-muted">Sans analyste</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="card border-0 shadow-sm mb-4 angara-filter-card">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label for="filter-search" class="form-label small text-muted">Recherche</label>
                    <input type="text" id="filter-search" class="form-control form-control-sm" placeholder="Entreprise, programme, analyste...">
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-programme" class="form-label small text-muted">Programme</label>
                    <select id="filter-programme" class="form-select form-select-sm">
                        <option value="">Tous</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filter-analyste" class="form-label small text-muted">Analyste</label>
                    <select id="filter-analyste" class="form-select form-select-sm">
                        <option value="">Tous</option>
                    </select>
                </div>
                <div class="col-12 col-md-1">
                    <button type="button" id="btn-reset-filters" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="demo-psi-arrow-left"></i> Réinit.
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau (AngaraTable) --}}
    <div class="angara-table" id="dossiers-card">
        <div class="card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="dossiersTable" class="table table-hover table-bordered align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th data-angara-sort-col="0">Programme(s)</th>
                            <th data-angara-sort-col="1">Signataire</th>
                            <th data-angara-sort-col="2">Entreprise</th>
                            <th data-angara-sort-col="3">Analyste</th>
                            <th data-angara-sort-col="4">Agence</th>
                            <th data-angara-sort-col="5">Secteur</th>
                            <th data-angara-sort-col="6">Date</th>
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
    const baseUrl = "{{ url('ca/dossiers') }}";
    const paginatedUrl = "{{ route('ca.dossiers.paginated') }}";
    const statsUrl = "{{ route('ca.dossiers.stats') }}";
    const filterOptionsUrl = "{{ route('ca.dossiers.filter-options') }}";

    let filterTimeout;

    fetch(filterOptionsUrl)
        .then(r => r.json())
        .then(data => {
            const progSelect = document.getElementById('filter-programme');
            (data.programmes || []).forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.name;
                progSelect.appendChild(opt);
            });
            const analSelect = document.getElementById('filter-analyste');
            (data.analystes || []).forEach(a => {
                const opt = document.createElement('option');
                opt.value = a.id;
                opt.textContent = a.name;
                analSelect.appendChild(opt);
            });
        })
        .catch(err => console.error('Filter options:', err));

    function loadStats() {
        const params = new URLSearchParams();
        const pid = document.getElementById('filter-programme')?.value;
        const aid = document.getElementById('filter-analyste')?.value;
        if (pid) params.append('programme_id', pid);
        if (aid) params.append('analyste_id', aid);
        fetch(statsUrl + (params.toString() ? '?' + params : ''))
            .then(r => r.json())
            .then(data => {
                document.getElementById('stat-total').textContent = data.total ?? 0;
                document.getElementById('stat-avec-analyste').textContent = data.avec_analyste ?? 0;
                document.getElementById('stat-sans-analyste').textContent = data.sans_analyste ?? 0;
            })
            .catch(err => console.error('Stats:', err));
    }

    const table = new AngaraTable(document.getElementById('dossiersTable'), {
        ajaxUrl: paginatedUrl,
        pageLength: 25,
        searchDelay: 350,
        filters: { programme_id: 'filter-programme', analyste_id: 'filter-analyste' },
        columns: [
            { data: 'programme', name: 'programme' },
            { data: 'signataire', name: 'signataire' },
            { data: 'entreprise', name: 'entreprise', render: function(d, _t, row) {
                return '<a href="' + baseUrl + '/' + (row?.token||'') + '" class="fw-medium text-decoration-none">' + (d || '-') + '</a>';
            }},
            { data: 'analyste', name: 'analyste' },
            { data: 'agence', name: 'agence' },
            { data: 'produit', name: 'produit', render: function(d) { return d ? '<span class="badge bg-secondary">' + d + '</span>' : '-'; } },
            { data: 'created', name: 'created' },
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
            }},
        ],
        order: [6, 'desc'],
        drawCallback: function() { loadStats(); },
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
        document.getElementById('filter-programme').value = '';
        document.getElementById('filter-analyste').value = '';
        table.state.search = '';
        table.state.page = 0;
        table.reload();
    });

    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('[data-copy-text]');
        if (!btn) return;
        const text = btn.getAttribute('data-copy-text') || '';
        try {
            await navigator.clipboard.writeText(text);
        } catch (_) {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        }
    });

    loadStats();
});
</script>
@endsection
