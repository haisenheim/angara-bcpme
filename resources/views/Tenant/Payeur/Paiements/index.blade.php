@extends('Layouts.tenant.payeur')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Paiements des producteurs</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des paiements de la saison</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Historique des paiements</h5>
        <p class="lead">Liste de tous les paiements</p>
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
                        { field: "montant" },
                        { field: "mode" },
                        { field: "caisse" },
                        { field: "wallet" },
                        { field: "compte" },
                        { field: "user",headerName:"Payeur" },
                        { field: "exploitant",headerName:'Beneficiaire' },
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
            //window.location.href = "entrees/"+e.data.token
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

                fetch("{{ route('payeur.paiements.all') }}")
                    .then((response) => response.json())
                    .then((data) => gridApi.setGridOption("rowData", data));

            });
    </script>
@endsection
