@extends('../Layouts.tenant.rstock')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Entrepots</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des entrepots</li>
    </ol>
 </nav>
@endsection

@section('actions')
    <a href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="btn btn-primary btn-sm"><i class="demo-pli-add me-2 fs-5"></i> Ajouter</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Entrepots</h5>
        <p class="lead">Liste de tous les entrepots du bassin</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Intitule</th>
                        <th>Stock en Kg</th>
                        <th>Stock en tonnes</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td><a href="{{ route('rstock.entrepots.show',$item->token) }}">{{ $item->name }}</a></td>
                            <td>{{ number_format($item->stock,0,',','.') }}</td>
                            <td>{{ number_format($item->stock/1000,2,',','.') }}</td>
                            <td>{{ $item->latitude }}</td>
                            <td>{{ $item->longitude }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvel entrepot</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('rstock.entrepots.store') }}" method="post">
                        @csrf
                            <div class="mb-3">
                                <div class="">
                                    <label for="">NOM</label>
                                    <input type="text" name="name" placeholder="Saisir le nom de l'entrepot" class="form-control">
                                </div>
                            </div>
                            <fieldset>
                                <legend>Coordonnées GPS</legend>
                                <div class="">
                                    <div class="">
                                        <label for="">Latitude</label>
                                        <input type="text" name="latitude" placeholder="Saisir la latitude" class="form-control">
                                    </div>
                                    <div class="">
                                        <label for="">Longitude</label>
                                        <input type="text" name="longitude" placeholder="Saisir la longitude" class="form-control">
                                    </div>
                                </div>
                            </fieldset>
                        <div class="mt-2">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#region_id').change(function(){
                 var _id = $('#region_id').val();
                $.ajax({
                    url: "{{ route('util.region.departements') }}",
                    type:'get',
                    dataType:'json',
                    data:{id:_id},
                    success:function(data){
                        $('#departement_id').html("<option value=0>Choisir un departement ...</option>");
                        data.forEach(element => {
                            $('#departement_id').append(`<option value=${element.id}>${element.name}</option>`);
                        });

                    },
                    error:function(err){
                        console.log(err)
                    }
                });
            })

            $('#departement_id').change(function(){
                var _id = $('#departement_id').val();
                $.ajax({
                    url:"{{ route('util.departement.arrondissements') }}",
                    type:'get',
                    dataType:'json',
                    data:{id:_id},
                    success:function(data){
                        $('#arrondissement_id').html("<option value=0>Choisir un arrondissement ...</option>");
                        data.forEach(element => {
                            $('#arrondissement_id').append(`<option value=${element.id}>${element.name}</option>`);
                        });

                    },
                    error:function(err){
                        console.log(err)
                    }
                });
            })
        })
    </script>

    <style>
        .form-group{
            margin-top: 1rem;
        }
    </style>
@endsection
