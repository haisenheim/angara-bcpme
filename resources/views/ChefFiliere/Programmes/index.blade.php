@extends('Layouts.chef_filiere')

@section('title', 'Programmes')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active" aria-current="page">Programmes</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">Programmes</h5>
    <p class="text-body-secondary mb-0 mt-1 small">Consultez l'ensemble des programmes et ouvrez une fiche au clic sur une ligne.</p>
</div>
@endsection

@section('content')
<div class="cf-page">
    <div class="cf-hero mb-4">
        <h1 class="cf-hero__title">Référentiel des programmes</h1>
        <p class="cf-hero__lead">Recherche rapide, tri et filtres sur les colonnes — sélectionnez une ligne pour afficher la fiche.</p>
    </div>

    <div class="cf-panel">
        <div class="cf-panel__toolbar">
            <div class="flex-grow-1" style="max-width: 28rem;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="demo-psi-magnifier-2 text-muted"></i></span>
                    <input type="search" id="programmes-search-input" class="form-control border-start-0" placeholder="Rechercher un programme…" aria-label="Recherche">
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="cf-kpi mb-0"><span class="text-muted fw-normal">Total</span> <span class="cf-kpi__val" id="programmes-count">—</span></span>
            </div>
        </div>
        <div class="p-0">
            <div id="myGrid" class="ag-theme-balham" style="height: min(70vh, 520px); width: 100%;"></div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/ag-grid-community.min.js') }}"></script>
    @section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const columnDefs = [
                { field: "name", minWidth: 180, headerName: 'Désignation', filter: true, flex: 1 },
                { field: "convention", headerName: 'Convention', minWidth: 120 },
                { field: "signataire", headerName: 'Signataire', minWidth: 140 },
                { field: "dt_sig_conv", headerName: "Date de signature", minWidth: 120 },
                {
                    field: "budget",
                    headerName: "Budget (XAF)",
                    minWidth: 130,
                    valueFormatter: (p) => p.value ? new Intl.NumberFormat('fr-FR').format(p.value) : '—'
                },
                { field: "type_pp", headerName: "Bénéficiaires PP", minWidth: 120 },
                { field: "type_pm", headerName: "Bénéficiaires PM", minWidth: 120 },
                { field: "token", hide: true }
            ];

            const gridOptions = {
                theme: agGrid.themeBalham.withParams({
                    headerBackgroundColor: '#157347',
                    headerHeight: '38px',
                    headerTextColor: 'white',
                    rowHoverColor: 'rgba(21, 115, 71, 0.1)'
                }),
                rowData: null,
                columnDefs: columnDefs,
                defaultColDef: { filter: true, sortable: true },
                autoSizeStrategy: {
                    type: 'fitCellContents',
                    defaultMinWidth: 100,
                    columnLimits: [{ colId: 'name', minWidth: 180 }]
                },
                pagination: true,
                paginationPageSize: 25,
                paginationPageSizeSelector: [25, 50, 100, 200],
                rowSelection: { mode: 'singleRow', hideDisabledCheckboxes: true },
                suppressCellFocus: true,
                domLayout: 'normal'
            };

            const gridDiv = document.querySelector("#myGrid");
            const gridApi = agGrid.createGrid(gridDiv, gridOptions);

            gridApi.addEventListener('rowSelected', function(e) {
                if (e.data?.token) window.location.href = "{{ url('chef-filiere/programmes') }}/" + e.data.token;
            });

            const searchInput = document.getElementById("programmes-search-input");
            if (searchInput) {
                searchInput.addEventListener("input", function() {
                    gridApi.setGridOption("quickFilterText", this.value);
                });
            }

            fetch("{{ route('chef-filiere.programmes.all') }}")
                .then(r => r.json())
                .then(data => {
                    gridApi.setGridOption("rowData", data);
                    document.getElementById("programmes-count").textContent = data?.length ?? 0;
                });
        });
    </script>
    @endsection

    <style>
        .ag-theme-balham .ag-root-wrapper { border: none; border-radius: 0 0 var(--cf-radius, 0.75rem) var(--cf-radius, 0.75rem); }
        #programmes-search-input:focus { box-shadow: none; border-color: #ced4da; }
    </style>
@endsection
