@extends('Layouts.regional')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Dossiers d'instruction</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des Dossiers d'instruction</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Dossiers d'instruction</h5>
        <p class="lead">Liste de tous les Dossiers d'instruction de l'agence</p>
    </div>
@endsection

@section('content')
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
            headerBackgroundColor: "rgb(228, 237, 250)",
            /* Changes the hover color of the row*/
            rowHoverColor: "rgb(216, 226, 255)",
            });
            const columnDefs = [
                        { field: "programme", minWidth: 100,filter:true},
                        { field: "signataire"},
                        { field: "entreprise"},
                        { field: "ca"},
                        { field: "ca" },
                        { field: "agence" },
                        { field: "direction" },
                        { field: "produit",headerName:"Secteur d'activité"},
                        { field: "created",headerName:"Date d'ouverture"},
                        { field: "token",hide:true},


            ];

            let gridApi;

            const gridOptions = {
            theme: agGrid.themeBalham.withParams({
                headerBackgroundColor: "var(--bs-primary)",
                headerHeight: '30px',
                headerTextColor: 'var(--nf-mainnav-link-color)',
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
            window.location.href = "dossiers/"+e.data.token
        }

        function onFilterTextBoxChanged() {
            gridApi.setGridOption(
                "quickFilterText",
                document.getElementById("header-search-input").value,
            );
        }

            // setup the grid after the page has finished loading
            document.addEventListener("DOMContentLoaded", function () {
                const gridDiv = document.querySelector("#myGrid");
                gridApi = agGrid.createGrid(gridDiv, gridOptions);
                gridApi.addEventListener('rowSelected',rowSelected)

                fetch("{{ route('regional.dossiers.all') }}")
                    .then((response) => response.json())
                    .then((data) => gridApi.setGridOption("rowData", data));

            });
    </script>
    @endsection


    <style>
        .form-group{
            margin-top: 1rem;
        }
    </style>
@endsection
