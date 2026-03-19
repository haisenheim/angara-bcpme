@extends('Layouts.gestionnaire')

@section('title', 'Programmes')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Programmes</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des programmes</li>
    </ol>
</nav>
@endsection
@section('actions')
    {{-- Les gestionnaires ne peuvent pas créer de nouveaux programmes --}}
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Programmes</h5>
        <p class="text-body-secondary mb-0 mt-1">Consultez et gérez l'ensemble des programmes</p>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center g-2">
                <div class="col-12 col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="demo-psi-magnifier-2"></i></span>
                        <input type="text" id="programmes-search-input" class="form-control border-start-0 bg-light" placeholder="Rechercher un programme..." aria-label="Recherche">
                    </div>
                </div>
                <div class="col-12 col-md-6 text-md-end">
                    <span class="badge bg-primary bg-opacity-10 text-primary" id="programmes-count">—</span>
                    <small class="text-muted ms-1">programme(s)</small>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div id="myGrid" class="ag-theme-balham" style="height: 480px; width: 100%;"></div>
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
                    headerBackgroundColor: '#0f85f2',
                    headerHeight: '36px',
                    headerTextColor: 'white',
                    rowHoverColor: '#f1f5f9'
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
                if (e.data?.token) window.location.href = "{{ url('gestionnaire/programmes') }}/" + e.data.token;
            });

            const searchInput = document.getElementById("programmes-search-input");
            if (searchInput) {
                searchInput.addEventListener("input", function() {
                    gridApi.setGridOption("quickFilterText", this.value);
                });
            }

            fetch("{{ route('gestionnaire.programmes.all') }}")
                .then(r => r.json())
                .then(data => {
                    gridApi.setGridOption("rowData", data);
                    document.getElementById("programmes-count").textContent = data?.length ?? 0;
                });
        });
    </script>
    @endsection

    <style>
        .ag-theme-balham .ag-root-wrapper { border-radius: 0 0 0.375rem 0.375rem; }
        #programmes-search-input:focus { box-shadow: none; }
    </style>
@endsection
