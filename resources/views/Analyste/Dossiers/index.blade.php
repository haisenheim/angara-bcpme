@extends('Layouts.analyste')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.min.css">
<style>
    .stats-card { transition: transform 0.2s; }
    .stats-card:hover { transform: translateY(-2px); }
    .stats-card .stat-value { font-size: 1.75rem; font-weight: 700; }
    #dossiersTable_wrapper .dataTables_processing { padding: 1rem; }
    #dossiers-card .paginate_button,
    #dossiers-card .page-link,
    #dossiers-card .dt-paging a,
    #dossiers-card .dt-paging span,
    #dossiers-card .dataTables_paginate a,
    #dossiers-card .dataTables_paginate span:not(.ellipsis),
    #dossiers-card ul.pagination li a,
    #dossiers-card ul.pagination li span {
        background-color: #ffffff !important; background: #ffffff !important;
        color: #495057 !important; border-color: #dee2e6 !important;
    }
    #dossiers-card .paginate_button.current,
    #dossiers-card .page-item.active .page-link,
    #dossiers-card .page-item.active span,
    #dossiers-card ul.pagination .page-item.active .page-link {
        background-color: #0d6efd !important; background: #0d6efd !important;
        color: #ffffff !important; border-color: #0d6efd !important;
    }
    #dossiers-card .paginate_button:hover:not(.disabled):not(.current),
    #dossiers-card .page-link:hover,
    #dossiers-card ul.pagination .page-link:hover {
        background-color: #0d6efd !important; background: #0d6efd !important;
        color: #ffffff !important; border-color: #0d6efd !important;
    }
    #dossiers-card .paginate_button.disabled {
        background-color: #f8f9fa !important; background: #f8f9fa !important; color: #adb5bd !important; opacity: 0.7;
    }
    #dossiersTable_wrapper .dataTables_info { padding: 0.75rem 0; color: #6c757d; font-size: 0.875rem; }
</style>
@endpush

@section('title', 'Dossiers d\'instruction')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dossiers d'instruction</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Dossiers d'instruction</h5>
        <p class="text-body-secondary mb-0 mt-1">Liste de vos dossiers d'instruction</p>
    </div>
@endsection

@section('content')
    {{-- Statistiques --}}
    <div class="row g-3 mb-4" id="stats-section">
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
    <div class="card border-0 shadow-sm mb-4">
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

    {{-- Tableau --}}
    <div class="card border-0 shadow-sm" id="dossiers-card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="dossiersTable" class="table table-hover table-bordered align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>Programme</th>
                            <th>Signataire</th>
                            <th>Entreprise</th>
                            <th>Analyste</th>
                            <th>Agence</th>
                            <th>Secteur</th>
                            <th>Date</th>
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
    const baseUrl = "{{ url('analyste/dossiers') }}";
    const paginatedUrl = "{{ route('analyste.dossiers.paginated') }}";
    const statsUrl = "{{ route('analyste.dossiers.stats') }}";
    const filterOptionsUrl = "{{ route('analyste.dossiers.filter-options') }}";

    let table;
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

    table = new DataTable('#dossiersTable', {
        serverSide: true,
        ajax: {
            url: paginatedUrl,
            data: function(d) {
                d.programme_id = document.getElementById('filter-programme').value;
                d.analyste_id = document.getElementById('filter-analyste').value;
            }
        },
        columns: [
            { data: 'programme', name: 'programme', defaultContent: '-' },
            { data: 'signataire', name: 'signataire', defaultContent: '-' },
            { data: 'entreprise', name: 'entreprise', render: function(d, t, row) {
                return '<a href="' + baseUrl + '/' + (row?.token||'') + '" class="fw-medium text-decoration-none">' + (d || '-') + '</a>';
            }},
            { data: 'analyste', name: 'analyste', defaultContent: '-' },
            { data: 'agence', name: 'agence', defaultContent: '-' },
            { data: 'produit', name: 'produit', defaultContent: '-', render: function(d) { return d ? '<span class="badge bg-secondary">' + d + '</span>' : '-'; } },
            { data: 'created', name: 'created', defaultContent: '-' },
            { data: 'token', orderable: false, className: 'text-end', width: '100px', render: function(token) {
                return '<a href="' + baseUrl + '/' + (token||'') + '" class="btn btn-sm btn-outline-primary"><i class="demo-psi-eye"></i> Voir</a>';
            }}
        ],
        order: [[6, 'desc']],
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50], [10, 15, 25, 50]],
        language: {
            url: '//cdn.datatables.net/plug-ins/2.3.0/i18n/fr-FR.json',
            search: '',
            searchPlaceholder: 'Rechercher...'
        },
        processing: true,
        drawCallback: function() { loadStats(); },
        pagingType: 'simple_numbers'
    });

    document.getElementById('filter-search').addEventListener('input', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => table.search(this.value).draw(), 350);
    });

    ['filter-programme','filter-analyste'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => table.ajax.reload());
    });

    document.getElementById('btn-reset-filters').addEventListener('click', function() {
        document.getElementById('filter-search').value = '';
        document.getElementById('filter-programme').value = '';
        document.getElementById('filter-analyste').value = '';
        table.search('').ajax.reload();
    });

    loadStats();
});
</script>
@endsection
