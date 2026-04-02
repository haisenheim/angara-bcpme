@extends('Layouts.analyste')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.min.css">
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
                <div class="col-12 col-md-2">
                    <button type="button" id="btn-reset-filters" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="demo-psi-arrow-left"></i> Réinitialiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm angara-dt-card" id="entreprises-card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="entreprisesTable" class="table table-hover table-bordered align-middle mb-0 angara-dt-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Désignation</th>
                            <th>RCCM</th>
                            <th>NIU</th>
                            <th>Dirigeant</th>
                            <th>Région</th>
                            <th>Forme</th>
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
    const baseUrl = "{{ url('analyste/entreprises') }}";
    const paginatedUrl = "{{ route('analyste.entreprises.paginated') }}";
    const statsUrl = "{{ route('analyste.entreprises.stats') }}";

    let table;
    let filterTimeout;

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
        fetch(statsUrl + (params.toString() ? '?' + params : ''))
            .then(r => r.json())
            .then(data => {
                document.getElementById('stat-total').textContent = data.total ?? 0;
                document.getElementById('stat-avec-rccm').textContent = data.avec_rccm ?? 0;
                document.getElementById('stat-avec-niu').textContent = data.avec_niu ?? 0;
            })
            .catch(err => console.error('Stats:', err));
    }

    table = new DataTable('#entreprisesTable', AngaraDataTables.mergeDefaults({
        serverSide: true,
        ajax: {
            url: paginatedUrl,
            data: function(d) {
                d.region_id = document.getElementById('filter-region').value;
                d.departement_id = document.getElementById('filter-departement').value;
                d.forme_id = document.getElementById('filter-forme').value;
            }
        },
        columns: [
            { data: 'name', name: 'name', render: function(d, t, row) {
                return '<a href="' + baseUrl + '/' + (row?.token||'') + '" class="fw-medium text-decoration-none">' + (d || '-') + '</a>';
            }},
            { data: 'rccm', name: 'rccm', defaultContent: '-' },
            { data: 'niu', name: 'niu', defaultContent: '-' },
            { data: 'manager', name: 'manager', defaultContent: '-' },
            { data: 'region', name: 'region', defaultContent: '-' },
            { data: 'forme', name: 'forme', defaultContent: '-' },
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

    document.getElementById('filter-region').addEventListener('change', function() {
        filterDepartementsByRegion();
        table.ajax.reload();
    });

    document.getElementById('filter-departement').addEventListener('change', () => table.ajax.reload());
    document.getElementById('filter-forme').addEventListener('change', () => table.ajax.reload());

    document.getElementById('btn-reset-filters').addEventListener('click', function() {
        document.getElementById('filter-search').value = '';
        document.getElementById('filter-region').value = '';
        document.getElementById('filter-departement').value = '';
        document.getElementById('filter-forme').value = '';
        document.querySelectorAll('#filter-departement option').forEach(o => { o.style.display = ''; });
        table.search('').ajax.reload();
    });

    loadStats();
});
</script>
@endsection
