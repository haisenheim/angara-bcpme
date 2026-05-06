@extends('Layouts.regional')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Programmes</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des Programmes</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Programmes</h5>
        <p class="lead">Liste de tous les Programmes</p>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm mb-3">
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
                <div class="col-12 col-md-3 d-flex align-items-end justify-content-md-end gap-2">
                    <button type="button" id="btn-reset-filters" class="btn btn-outline-secondary btn-sm">
                        <i class="demo-psi-arrow-left me-1"></i> Réinitialiser
                    </button>
                    <span class="badge bg-primary bg-opacity-10 text-primary" id="programmes-count">—</span>
                    <small class="text-muted">programme(s)</small>
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
    const baseUrl = "{{ url('regional/programmes') }}";
    const paginatedUrl = "{{ route('regional.programmes.paginated') }}";
    const statsUrl = "{{ route('regional.programmes.stats') }}";
    const filterOptionsUrl = "{{ route('regional.programmes.filter-options') }}";

    let filterTimeout;

    function loadStats() {
        const params = new URLSearchParams();
        const sig = document.getElementById('filter-signataire')?.value;
        if (sig) params.append('signataire_filter', sig);
        fetch(statsUrl + (params.toString() ? '?' + params : ''))
            .then(r => r.json())
            .then(data => {
                document.getElementById('programmes-count').textContent = data?.total ?? 0;
            })
            .catch(err => console.error('Stats:', err));
    }

    fetch(filterOptionsUrl)
        .then(r => r.json())
        .then(data => {
            const sel = document.getElementById('filter-signataire');
            (data.signataires || []).forEach(name => {
                const opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                sel.appendChild(opt);
            });
        })
        .catch(err => console.error('Filter options:', err));

    const table = new AngaraTable(document.getElementById('programmesTable'), {
        ajaxUrl: paginatedUrl,
        pageLength: 15,
        searchDelay: 350,
        filters: { signataire_filter: 'filter-signataire' },
        columns: [
            { data: 'name', name: 'name', render: function(d, _t, row) {
                const token = row?.token || '';
                const label = d || '—';
                return '<a href="' + baseUrl + '/' + token + '" class="fw-medium text-decoration-none">' + label + '</a>';
            }},
            { data: 'convention', name: 'convention' },
            { data: 'signataire', name: 'signataire' },
            { data: 'dt_sig_conv', name: 'dt_sig_conv' },
            { data: 'budget', name: 'budget', render: function(d) {
                return d != null ? new Intl.NumberFormat('fr-FR').format(d) : '—';
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
