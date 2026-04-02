@extends('Layouts.analyste')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.min.css">
<style>
    .stats-card { transition: transform 0.2s; }
    .stats-card:hover { transform: translateY(-2px); }
    .stats-card .stat-value { font-size: 1.75rem; font-weight: 700; }
    #programmesTable th:last-child,
    #programmesTable td:last-child { min-width: 100px; white-space: nowrap; }
    .btn-action { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.65rem; font-size: 0.8rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s; }
    .btn-action:hover { transform: translateY(-1px); }
</style>
@endpush

@section('title', 'Programmes')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Programmes</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des programmes</li>
    </ol>
</nav>
@endsection

@section('actions')
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Programmes</h5>
        <p class="text-body-secondary mb-0 mt-1">Consultez l'ensemble des programmes</p>
    </div>
@endsection

@section('content')
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
                        <div class="stat-value text-success" id="stat-actifs">-</div>
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
                        <div class="stat-value text-info" id="stat-avec-convention">-</div>
                        <small class="text-muted">Avec convention</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 angara-filter-card">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="filter-search" class="form-label small text-muted">Recherche</label>
                    <input type="text" id="filter-search" class="form-control form-control-sm" placeholder="Désignation, convention, signataire...">
                </div>
                <div class="col-12 col-md-4">
                    <label for="filter-signataire" class="form-label small text-muted">Signataire</label>
                    <select id="filter-signataire" class="form-select form-select-sm">
                        <option value="">Tous</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <button type="button" id="btn-reset-filters" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="demo-psi-arrow-left"></i> Réinit.
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm angara-dt-card" id="programmes-card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="programmesTable" class="table table-hover table-bordered align-middle mb-0 angara-dt-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Désignation</th>
                            <th>Convention</th>
                            <th>Signataire</th>
                            <th>Date de signature</th>
                            <th>Budget (XAF)</th>
                            <th>Bénéficiaires PP</th>
                            <th>Bénéficiaires PM</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = "{{ url('analyste/programmes') }}";
    const paginatedUrl = "{{ route('analyste.programmes.paginated') }}";
    const statsUrl = "{{ route('analyste.programmes.stats') }}";
    const filterOptionsUrl = "{{ route('analyste.programmes.filter-options') }}";

    let table;
    let filterTimeout;

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

    function loadStats() {
        const params = new URLSearchParams();
        const sig = document.getElementById('filter-signataire')?.value;
        if (sig) params.append('signataire_filter', sig);
        fetch(statsUrl + (params.toString() ? '?' + params : ''))
            .then(r => r.json())
            .then(data => {
                document.getElementById('stat-total').textContent = data.total ?? 0;
                document.getElementById('stat-actifs').textContent = data.actifs ?? 0;
                document.getElementById('stat-avec-convention').textContent = data.avec_convention ?? 0;
            })
            .catch(err => console.error('Stats:', err));
    }

    table = new DataTable('#programmesTable', AngaraDataTables.mergeDefaults({
        serverSide: true,
        ajax: {
            url: paginatedUrl,
            data: function(d) {
                d.signataire_filter = document.getElementById('filter-signataire').value;
            }
        },
        columns: [
            { data: 'name', name: 'name', render: function(d, t, row) {
                return '<a href="' + baseUrl + '/' + (row?.token||'') + '" class="fw-medium text-decoration-none">' + (d || '-') + '</a>';
            }},
            { data: 'convention', name: 'convention', defaultContent: '-' },
            { data: 'signataire', name: 'signataire', defaultContent: '-' },
            { data: 'dt_sig_conv', name: 'dt_sig_conv', defaultContent: '-' },
            { data: 'budget', name: 'budget', defaultContent: '-', render: function(d) {
                return d != null ? new Intl.NumberFormat('fr-FR').format(d) : '—';
            }},
            { data: 'type_pp', name: 'type_pp', defaultContent: '-' },
            { data: 'type_pm', name: 'type_pm', defaultContent: '-' },
            { data: 'token', orderable: false, className: 'text-end', width: '100px', render: function(token) {
                return '<a href="' + baseUrl + '/' + (token||'') + '" class="btn-action btn-action-view"><i class="demo-psi-eye"></i> Voir</a>';
            }}
        ],
        order: [[0, 'asc']],
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50], [10, 15, 25, 50]],
        drawCallback: function() { loadStats(); }
    }));

    document.getElementById('filter-search').addEventListener('input', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => table.search(this.value).draw(), 350);
    });

    document.getElementById('filter-signataire').addEventListener('change', () => table.ajax.reload());

    document.getElementById('btn-reset-filters').addEventListener('click', function() {
        document.getElementById('filter-search').value = '';
        document.getElementById('filter-signataire').value = '';
        table.search('').ajax.reload();
    });

    loadStats();
});
</script>
@endsection
