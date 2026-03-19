@extends('Layouts.ca')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.min.css">
<style>
    .stats-card { transition: transform 0.2s; }
    .stats-card:hover { transform: translateY(-2px); }
    .stats-card .stat-value { font-size: 1.75rem; font-weight: 700; }
    #prospectsTable_wrapper .dataTables_processing { padding: 1rem; }
    #prospectsTable th:last-child,
    #prospectsTable td:last-child { min-width: 100px; white-space: nowrap; }
    .btn-action { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.65rem; font-size: 0.8rem; border-radius: 0.375rem; text-decoration: none; transition: all 0.2s; }
    .btn-action:hover { transform: translateY(-1px); }
    .btn-action-view { background: var(--bs-primary); color: white !important; border: none; }
    .btn-action-view:hover { background: var(--bs-primary); opacity: 0.9; color: white !important; }
    #prospects-card .paginate_button,
    #prospects-card .page-link,
    #prospects-card .dt-paging a,
    #prospects-card .dt-paging span,
    #prospects-card .dataTables_paginate a,
    #prospects-card .dataTables_paginate span:not(.ellipsis),
    #prospects-card ul.pagination li a,
    #prospects-card ul.pagination li span {
        background-color: #ffffff !important; background: #ffffff !important;
        color: #495057 !important; border-color: #dee2e6 !important;
    }
    #prospects-card .paginate_button.current,
    #prospects-card .page-item.active .page-link,
    #prospects-card .page-item.active span,
    #prospects-card ul.pagination .page-item.active .page-link {
        background-color: #0d6efd !important; background: #0d6efd !important;
        color: #ffffff !important; border-color: #0d6efd !important;
    }
    #prospects-card .paginate_button:hover:not(.disabled):not(.current),
    #prospects-card .page-link:hover,
    #prospects-card ul.pagination .page-link:hover {
        background-color: #0d6efd !important; background: #0d6efd !important;
        color: #ffffff !important; border-color: #0d6efd !important;
    }
    #prospects-card .paginate_button.disabled {
        background-color: #f8f9fa !important; background: #f8f9fa !important; color: #adb5bd !important; opacity: 0.7;
    }
    #prospectsTable_wrapper .dataTables_info { padding: 0.75rem 0; color: #6c757d; font-size: 0.875rem; }
</style>
@endpush

@section('title', 'Prospects')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.entreprises.index') }}">Entreprises</a></li>
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
    {{-- Section statistiques dynamiques --}}
    <div class="row g-3 mb-4" id="stats-section">
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

    {{-- Filtres dynamiques --}}
    <div class="card border-0 shadow-sm mb-4">
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
                <div class="col-12 col-md-1">
                    <button type="button" id="btn-reset-filters" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="demo-psi-arrow-left"></i> Réinit.
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau avec pagination AJAX --}}
    <div class="card border-0 shadow-sm" id="prospects-card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="prospectsTable" class="table table-hover table-bordered align-middle mb-0" style="width:100%">
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
    const baseUrl = "{{ url('ca/entreprises') }}";
    const paginatedUrl = "{{ route('ca.prospects.paginated') }}";
    const statsUrl = "{{ route('ca.prospects.stats') }}";
    const filterOptionsUrl = "{{ route('ca.entreprises.filter-options') }}";

    let table;
    let filterTimeout;

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
        })
        .catch(err => console.error('Filter options:', err));

    function loadStats() {
        const params = new URLSearchParams();
        const filters = { region_id: 'filter-region', taille: 'filter-taille', forme_id: 'filter-forme', caractere: 'filter-caractere' };
        Object.entries(filters).forEach(([k, id]) => {
            const v = document.getElementById(id)?.value;
            if (v) params.append(k, v);
        });
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

    table = new DataTable('#prospectsTable', {
        serverSide: true,
        ajax: {
            url: paginatedUrl,
            data: function(d) {
                d.region_id = document.getElementById('filter-region').value;
                d.taille = document.getElementById('filter-taille').value;
                d.forme_id = document.getElementById('filter-forme').value;
                d.caractere = document.getElementById('filter-caractere').value;
            }
        },
        columns: [
            { data: 'name', name: 'name', render: function(d, t, row) { return '<a href="' + baseUrl + '/' + (row?.token||'') + '" class="fw-medium text-decoration-none">' + (d || '-') + '</a>'; } },
            { data: 'rccm', name: 'rccm', defaultContent: '-' },
            { data: 'niu', name: 'niu', defaultContent: '-' },
            { data: 'manager', name: 'manager', defaultContent: '-' },
            { data: null, orderable: false, render: function(d, t, row) { return (row?.commune || '-') + (row?.region ? ' / ' + row.region : ''); } },
            { data: 'taille', name: 'taille', defaultContent: '-', render: function(d) { return d ? '<span class="badge bg-secondary">' + d + '</span>' : '-'; } },
            { data: 'capital', name: 'capital', defaultContent: '-', render: function(d) { return d != null ? new Intl.NumberFormat('fr-FR').format(d) : '-'; } },
            { data: 'token', orderable: false, className: 'text-end', width: '100px', render: function(token) {
                return '<a href="' + baseUrl + '/' + (token||'') + '" class="btn-action btn-action-view"><i class="demo-psi-eye"></i> Voir</a>';
            }}
        ],
        order: [[0, 'asc']],
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50], [10, 15, 25, 50]],
        language: {
            url: '//cdn.datatables.net/plug-ins/2.3.0/i18n/fr-FR.json',
            search: '',
            searchPlaceholder: 'Rechercher...'
        },
        processing: true,
        drawCallback: function() {
            loadStats();
            var btns = document.querySelectorAll('#prospects-card .paginate_button, #prospects-card .page-link, #prospects-card .dt-paging button, #prospects-card .dt-paging a');
            btns.forEach(function(el) {
                if (el.classList.contains('current') || (el.closest && el.closest('.page-item.active'))) {
                    el.style.setProperty('background-color', '#0d6efd', 'important');
                    el.style.setProperty('color', '#fff', 'important');
                } else if (!el.classList.contains('disabled')) {
                    el.style.setProperty('background-color', '#fff', 'important');
                    el.style.setProperty('color', '#495057', 'important');
                }
            });
        },
        pagingType: 'simple_numbers'
    });

    document.getElementById('filter-search').addEventListener('input', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => table.search(this.value).draw(), 350);
    });

    ['filter-region','filter-taille','filter-forme','filter-caractere'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => table.ajax.reload());
    });

    document.getElementById('btn-reset-filters').addEventListener('click', function() {
        document.getElementById('filter-search').value = '';
        document.getElementById('filter-region').value = '';
        document.getElementById('filter-taille').value = '';
        document.getElementById('filter-forme').value = '';
        document.getElementById('filter-caractere').value = '';
        table.search('').ajax.reload();
    });

    loadStats();
});
</script>
@endsection
