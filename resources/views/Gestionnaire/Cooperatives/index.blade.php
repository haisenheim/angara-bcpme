@extends('Layouts.gestionnaire')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Cooperatives</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des cooperatives</li>
    </ol>
 </nav>
@endsection
@section('actions')
    <a href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="btn btn-primary btn-sm"><i class="demo-pli-add me-2 fs-5"></i> Ajouter</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Cooperatives</h5>
        <p class="lead">Liste de toutes les cooperatives</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div id="myGrid" style="height: 400px"></div>
        </div>
    </div>
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvelle cooperative</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('gestionnaire.cooperatives.store') }}" method="post">
                        @csrf
                        <input type="hidden" id="arr_id" name="arrondissement_id">
                        <fieldset>
                            <legend>Infos de la cooperative</legend>
                            <div class="d-flex gap-2 flex-grow">
                                <div class=" w-75">
                                    <label for="">NOM</label>
                                    <input type="text" name="name" placeholder="Saisir le nom de la cooperative" class="form-control">
                                </div>
                                <div class="">
                                    <label for="">Logo/Photo</label>
                                    <input required type="file" name="photo" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-grow mt-3">
                                <div class="flex-fill">
                                    <label for="">FILIERE</label>
                                    <select required class="form-control" name="domaine_id" id="">
                                        <option value="">Choisir ...</option>
                                        @foreach ($domaines as $domaine)
                                            <option value="{{ $domaine->id }}">{{ $domaine->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-fill">
                                    <div style="width: 100%" class="">
                                        <label for="">Commune</label>
                                        <input  id="arrondissement_id" class="form-control"  type="text">
                                    </div>
                                </div>
                                <div class=" w-30">
                                    <label for="">Adresse physique</label>
                                    <input required type="text" name="address" placeholder="Adresse physque de la cooperative" class="form-control">
                                </div>
                                <div class="w-30">
                                    <label for="">Telephone</label>
                                    <input required type="text" name="phone" placeholder="Numero de telephone de la cooperative" class="form-control">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Infos de connexion du compte utilisateur de la cooperative</legend>
                            <div class="d-flex gap-2 flex-grow">
                                <div class=" w-75">
                                    <label for="">NOM DE L'UTILISATEUR</label>
                                    <input required type="text" name="username" placeholder="Saisir le nom de l'utilisateur" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-grow">
                                <div class="form-group w-50">
                                    <label for="">EMAIL DE CONNEXION</label>
                                    <input required type="email" name="email" placeholder="Saisir l'adresse email de connexion de l'utilisateur" class="form-control">
                                </div>
                                <div class="form-group w-50">
                                    <label for="">MOT DE PASSE</label>
                                    <input required type="password" name="password" placeholder="Saisir le mot de passe de connexion de l'utilisateur" class="form-control">
                                </div>
                            </div>
                        </fieldset>
                        <div class="mt-5">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<link rel="stylesheet" href="{{ asset('dropdowncombotree/comboTreeStyle.css') }}">
<script src="{{ asset('js/ag-grid-community.min.js') }}"></script>
<script src="{{ asset('dropdowncombotree/comboTreePlugin.js') }}"></script>
<script>
    $.expr[':'].icontains = function (obj, index, meta, stack) { return (obj.textContent || obj.innerText || jQuery(obj).text() || '').toLowerCase().indexOf(meta[3].toLowerCase()) >= 0; };
</script>
<script>
    $.ajax({
        url:'{{ route("util.localites") }}',
        type:'get',
        dataType:'json',
        success:function(data){
                var arrond = $('#arrondissement_id').comboTree({
                        source : data,
                        collapse: true,
                        isMultiple:false,
                        editable:true,
                });
                $('.ct-arrow-btn').html('<i class="pli-arrow-down"></i>');
                arrond.onChange(function(){
                         var elt = arrond._selectedItem;
                         $('#arr_id').val(elt.id);
                     });
            }
        });


            const columnDefs = [
                        { field: "name", minWidth: 100,headerName:'Designation',filter:true},
                        { field: "commune" },
                        { field: "departement" },
                        { field: "region" },
                        { field: "filiere" },
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
            window.location.href = "cooperatives/"+e.data.token
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

                fetch("{{ route('gestionnaire.cooperatives.all') }}")
                    .then((response) => response.json())
                    .then((data) => gridApi.setGridOption("rowData", data));

            });
    </script>

    <style>
        .form-group{
            margin-top: 1rem;
        }
    </style>
@endsection
