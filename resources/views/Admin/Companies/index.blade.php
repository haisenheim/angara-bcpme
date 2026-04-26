@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Entreprises</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des Entreprises</li>
    </ol>
 </nav>
@endsection
@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="{{ route('admin.entreprises.create') }}" class="dropdown-item"><i class="demo-pli-add me-2 fs-5"></i> Ajouter</a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Entreprises</h5>
        <p class="lead">Liste de toutes les Entreprises</p>
    </div>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body py-2">
            <label for="filter-struct-client" class="form-label small text-muted mb-1">Filtrer par structuration client (promus)</label>
            <select id="filter-struct-client" class="form-select form-select-sm" style="max-width: 26rem;">
                <option value="">Tous</option>
                @foreach(\App\Models\DossierEntreeRelation::clientStructurationStatusFilterLabels() as $val => $lab)
                    <option value="{{ $val }}">{{ $lab }}</option>
                @endforeach
            </select>
            <div class="mt-2">
                <div class="dropdown d-inline-block">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="admin-entreprises-actions" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                    <ul class="dropdown-menu" aria-labelledby="admin-entreprises-actions">
                        <li><button type="button" class="dropdown-item" id="btn-admin-entreprises-xlsx"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Exporter en Excel</button></li>
                        <li><button type="button" class="dropdown-item" id="btn-admin-entreprises-pdf"><i class="bi bi-file-earmark-pdf me-2"></i>Exporter en PDF</button></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><button type="button" class="dropdown-item" id="btn-admin-entreprises-reset"><i class="bi bi-arrow-counterclockwise me-2"></i>Réinitialiser</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div id="myGrid" style="height: 400px"></div>
        </div>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/ag-grid-community.min.js') }}"></script>
    @section('script')
    <script>
        const myTheme = agGrid.themeQuartz.withParams({
            /* Low spacing = very compact */
            spacing: 2,
            /* Changes the color of the grid text */
            foregroundColor: "rgb(14, 68, 145)",
            /* Changes the color of the grid background */
            backgroundColor: "rgb(241, 247, 255)",
            /* Changes the header color of the top row */
            headerBackgroundColor: "#e8f5d9",
            /* Changes the hover color of the row*/
            rowHoverColor: "rgba(136, 184, 36, 0.14)",
            });
            const columnDefs = [
                        { field: "name", minWidth: 100,headerName:'Designation',filter:true},
                        { field: "rccm" },
                        { field: "niu" },
                        { field: "cnps" },
                        { field: "manager" },
                        { field: "commune" },
                        { field: "departement" },
                        { field: "region" },
                        { field: "capital" },
                        { field: "forme" },
                        { field: "caractere" },
                        { field: "client_structuration_label", minWidth: 160, headerName: "Structuration client", filter: true },
                        { field: "token",hide:true},


            ];

            let gridApi;
            let allRowData = [];

            const gridOptions = {
            theme: agGrid.themeBalham.withParams({
                headerBackgroundColor: "#88b824",
                headerHeight: '30px',
                headerTextColor: '#ffffff',
            }),
            rowData: null,
            columnDefs: columnDefs,
            defaultColDef: {
                filter: true,
            },
            autoSizeStrategy: {
                type: 'fitCellContents',
                defaultMinWidth: 100,
                columnLimits: [
                    {
                        colId: 'name',
                        minWidth: 150
                    }
                ]
            },
            pagination: true,
            paginationPageSize: 200,
            paginationPageSizeSelector: [100, 200, 1000],
            rowSelection: {
                mode: 'singleRow',
                checkboxes: false,
                enableClickSelection: true,
            },
        };

        function rowSelected(e){
            console.log(e.data)
            window.location.href = "entreprises/"+e.data.token
        }

        function onFilterTextBoxChanged() {
            gridApi.setGridOption(
                "quickFilterText",
                document.getElementById("header-search-input").value,
            );
        }

            // setup the grid after the page has finished loading
            function applyStructurationClientFilter() {
                if (!gridApi) return;
                const code = document.getElementById('filter-struct-client')?.value || '';
                if (!code) {
                    gridApi.setGridOption('rowData', allRowData);
                    return;
                }
                const filtered = allRowData.filter(function (row) {
                    if (code === 'non_structure') {
                        return row.client_structuration_code !== 'structure';
                    }
                    return row.client_structuration_code === code;
                });
                gridApi.setGridOption('rowData', filtered);
            }

            document.addEventListener("DOMContentLoaded", function () {
                const gridDiv = document.querySelector("#myGrid");
                gridApi = agGrid.createGrid(gridDiv, gridOptions);
                gridApi.addEventListener('rowSelected',rowSelected)

                fetch("{{ route('admin.entreprises.all') }}")
                    .then((response) => response.json())
                    .then((data) => {
                        allRowData = Array.isArray(data) ? data : (data.data || []);
                        gridApi.setGridOption("rowData", allRowData);
                    });

                document.getElementById('filter-struct-client')?.addEventListener('change', applyStructurationClientFilter);

                const exportBase = "{{ route('admin.entreprises.export') }}";
                function adminExportUrl(format) {
                    const u = new URL(exportBase, window.location.origin);
                    u.searchParams.set('format', format);
                    const code = document.getElementById('filter-struct-client')?.value || '';
                    if (code) u.searchParams.set('client_structuration_status', code);
                    return u.toString();
                }
                document.getElementById('btn-admin-entreprises-xlsx')?.addEventListener('click', function() {
                    window.location = adminExportUrl('xlsx');
                });
                document.getElementById('btn-admin-entreprises-pdf')?.addEventListener('click', function() {
                    window.location = adminExportUrl('pdf');
                });
                document.getElementById('btn-admin-entreprises-reset')?.addEventListener('click', function() {
                    const sel = document.getElementById('filter-struct-client');
                    if (sel) sel.value = '';
                    applyStructurationClientFilter();
                });
            });
    </script>
    @endsection


    <style>
        .form-group{
            margin-top: 1rem;
        }
    </style>
@endsection
