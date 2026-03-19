@extends('Layouts.gestionnaire')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.min.css">
<style>
    .stats-card { transition: transform 0.2s; }
    .stats-card:hover { transform: translateY(-2px); }
    .stats-card .stat-value { font-size: 1.75rem; font-weight: 700; }
    #entitesTable_wrapper .dataTables_processing { padding: 1rem; }
    #entitesTable th:last-child, #entitesTable td:last-child { min-width: 90px; white-space: nowrap; }
    .btn-action { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.65rem; font-size: 0.8rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s; }
    .btn-action:hover { transform: translateY(-1px); }
    .btn-action-view { background: var(--bs-primary); color: white !important; border: none; }
    .btn-action-view:hover { background: var(--bs-primary); opacity: 0.9; color: white !important; }
    #entites-card .paginate_button.current, #entites-card .page-item.active .page-link { background-color: #0d6efd !important; color: #fff !important; }
    #entites-card .paginate_button:hover:not(.disabled):not(.current), #entites-card .page-link:hover { background-color: #0d6efd !important; color: #fff !important; }
    #entitesTable_wrapper .dataTables_info { padding: 0.75rem 0; color: #6c757d; font-size: 0.875rem; }
</style>
@endpush

@section('title', 'Entités individuelles')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Entités individuelles</li>
    </ol>
</nav>
@endsection

@section('actions')
    <a href="{{ route('gestionnaire.entites.create') }}" class="btn btn-primary btn-sm">
        <i class="demo-psi-add me-2"></i>Nouvelle entité
    </a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Entités individuelles</h5>
        <p class="text-body-secondary mb-0 mt-1">Liste et gestion de vos entités individuelles</p>
    </div>
@endsection

@section('content')
    <div class="row g-3 mb-4" id="stats-section">
        <div class="col-6 col-md-3">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="demo-psi-male text-primary fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="stat-value text-primary" id="stat-total">-</div>
                        <small class="text-muted">Total entités</small>
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

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="filter-search" class="form-label small text-muted">Recherche</label>
                    <input type="text" id="filter-search" class="form-control form-control-sm" placeholder="Nom, RCCM, NIU, dirigeant...">
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
                    <label for="filter-caractere" class="form-label small text-muted">Caractère</label>
                    <select id="filter-caractere" class="form-select form-select-sm">
                        <option value="">Tous</option>
                        <option value="Formel">Formel</option>
                        <option value="Informel">Informel</option>
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

    <div class="card border-0 shadow-sm" id="entites-card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="entitesTable" class="table table-hover table-bordered align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>Dénomination</th>
                            <th>RCCM</th>
                            <th>NIU</th>
                            <th>Dirigeant</th>
                            <th>Localisation</th>
                            <th>Taille</th>
                            <th>Capital</th>
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
    const baseUrl = "{{ url('gestionnaire/entites') }}";
    const fetchUrl = "{{ route('gestionnaire.entites.all') }}";
    let table;
    let allData = [];

    function updateStats(data) {
        const filtered = data || allData;
        document.getElementById('stat-total').textContent = filtered.length;
        document.getElementById('stat-formelles').textContent = filtered.filter(r => r.caractere === 'Formel').length;
        document.getElementById('stat-informelles').textContent = filtered.filter(r => r.caractere === 'Informel').length;
        const pet = filtered.filter(r => r.taille === 'PETITE' || r.taille === 'TRES PETITE').length;
        document.getElementById('stat-petites').textContent = pet;
    }

    fetch(fetchUrl)
        .then(r => r.json())
        .then(data => {
            allData = data;
            table = new DataTable('#entitesTable', {
                data: data,
                columns: [
                    { data: 'name', render: function(d, t, row) { return '<a href="' + baseUrl + '/' + (row?.token||'') + '" class="fw-medium text-decoration-none">' + (d || '-') + '</a>'; } },
                    { data: 'rccm', defaultContent: '-' },
                    { data: 'niu', defaultContent: '-' },
                    { data: 'manager', defaultContent: '-' },
                    { data: null, render: function(d, t, row) { return (row?.commune || '-') + (row?.region ? ' / ' + row.region : ''); } },
                    { data: 'taille', defaultContent: '-', render: function(d) { return d ? '<span class="badge bg-secondary">' + d + '</span>' : '-'; } },
                    { data: 'capital', defaultContent: '-', render: function(d) { return d != null ? new Intl.NumberFormat('fr-FR').format(d) : '-'; } },
                    { data: 'token', orderable: false, className: 'text-end', render: function(token) {
                        return '<a href="' + baseUrl + '/' + (token||'') + '" class="btn-action btn-action-view"><i class="demo-psi-eye"></i> Voir</a>';
                    }}
                ],
                order: [[0, 'asc']],
                pageLength: 15,
                lengthMenu: [[10, 15, 25, 50], [10, 15, 25, 50]],
                language: { url: '//cdn.datatables.net/plug-ins/2.3.0/i18n/fr-FR.json', search: '', searchPlaceholder: 'Rechercher...' },
                dom: 'rtip',
                drawCallback: function() {
                    const rows = table.rows({ search: 'applied' });
                    const arr = [];
                    rows.every(function() { arr.push(this.data()); return true; });
                    updateStats(arr);
                }
            });
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const api = new $.fn.dataTable.Api(settings);
                const row = api.row(dataIndex).data();
                const taille = document.getElementById('filter-taille').value;
                const caractere = document.getElementById('filter-caractere').value;
                if (taille && row.taille !== taille) return false;
                if (caractere && row.caractere !== caractere) return false;
                return true;
            });
            updateStats(data);
            document.getElementById('filter-search').addEventListener('input', function() {
                table.search(this.value).draw();
            });
            document.getElementById('filter-taille').addEventListener('change', () => table.draw());
            document.getElementById('filter-caractere').addEventListener('change', () => table.draw());
            document.getElementById('btn-reset-filters').addEventListener('click', function() {
                document.getElementById('filter-search').value = '';
                document.getElementById('filter-taille').value = '';
                document.getElementById('filter-caractere').value = '';
                table.search('').draw();
            });
        })
        .catch(err => console.error('Fetch entites:', err));
});
</script>
@endsection
