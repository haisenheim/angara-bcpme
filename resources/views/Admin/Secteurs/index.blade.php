@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="#">Secteurs</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des secteurs</li>
    </ol>
 </nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="dropdown-item"><i class="demo-pli-add me-2 fs-5"></i> Ajouter</a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Secteurs</h5>
        <p class="lead">Liste de tous les secteurs cooperatifs</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Secteur</th>
                        <th>Agence</th>
                        <th>Contacts</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr class="border">
                            <td><a href="{{ route('admin.secteurs.show',$item->token) }}">{{ $item->name }}</a></td>
                            <td>{{ $item->agence?->name }}</td>
                            <td>{{ $item->contacts }}</td>
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
                    <h5 class="modal-title">Nouveau Secteur</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.secteurs.store') }}" method="post">
                        @csrf
                            <div class="">
                                <div class="mb-3">
                                    <label for="">NOM</label>
                                    <input type="text" name="name" placeholder="Saisir le nom du departement" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="">AGENCE DE TUTELLE</label>
                                    <select required name="agence_id" id="agence_id" class="form-control">
                                        <option value="">Selectionner une agence</option>
                                        @foreach($agences as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="">LOCALISATION</label>
                                    <input type="text" name="localisation" class="form-control">
                                </div>
                                <div class="mb-2">
                                    <label for="">CONTACTS</label>
                                    <textarea name="contacts" class="form-control" placeholder="Saisir ici les informations de contact ..." id=""></textarea>
                                </div>
                            </div>
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
