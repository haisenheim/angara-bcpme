@extends('Layouts.analyste')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Prospects</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des Entreprises A Prospecter</li>
    </ol>
 </nav>
@endsection
@section('actions')
    <a href="{{ route('analyste.entreprises.create') }}"  class="btn btn-primary btn-sm"><i class="demo-pli-add me-2 fs-5"></i> Ajouter</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Prospects</h5>
        <p class="lead">Liste de toutes les Entreprises à prospecter</p>
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
                        { field: "token",hide:true},


            ];

            let gridApi;

            const gridOptions = {
            theme: agGrid.themeBalham.withParams({
                headerBackgroundColor: '#0f85f2',
                headerHeight: '30px',
                headerTextColor: 'white',
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
                hideDisabledCheckboxes: true
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
            document.addEventListener("DOMContentLoaded", function () {
                const gridDiv = document.querySelector("#myGrid");
                gridApi = agGrid.createGrid(gridDiv, gridOptions);
                gridApi.addEventListener('rowSelected',rowSelected)

                fetch("{{ route('analyste.prospects.all') }}")
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
