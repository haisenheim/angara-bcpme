@extends('../Layouts.tenant.agent')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Prévisions de stocks</a></li>
       <li class="breadcrumb-item active" aria-current="page">Prévisions de stock</li>
    </ol>
 </nav>
@endsection
@section('actions')
<div class="btn-group">
    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
    Actions
    <span class="vr"></span>
    </button>
    <ul class="dropdown-menu analyse">
        <li><a class="dropdown-item"  href="{{ route('agent.previsions.create') }}">Saisir une prévision</a></li>
    </ul>
</div>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Prévisions de stock</h5>
        <p class="lead">Liste des prévisions de stocks</p>
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
    <script>
            const columnDefs = [
                        { field: "date" },
                        { field: "entrepot",headerName:'Entrepot cible'},
                        { field: "quantity",headerName:"Quantite"},
                        { field: "gamme" },
                        { field: "agent" },
                        { field: "producteur" },
                        { field: "pu" ,headerName:'Prix unitaire'},
                        { field: "montant",headerName:'Montant total' },
                        { field: "mode" ,headerName:'Mode de paiement' },
                        { field: "token",hide:true},
            ];

            let gridApi;

            const gridOptions = {
            theme: agGrid.themeBalham.withParams({
                headerBackgroundColor: "#88b824",
                headerHeight: '30px',
                headerTextColor: '#fff',
            }),
            rowData: null,
            columnDefs: columnDefs,
            defaultColDef: {
                filter: true,
            },
            autoSizeStrategy: {
                type: 'fitGridWidth',
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
            window.location.href = "previsions/"+e.data.token
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

                fetch("{{ route('agent.previsions.all') }}")
                    .then((response) => response.json())
                    .then((data) => gridApi.setGridOption("rowData", data));

            });
    </script>
@endsection
