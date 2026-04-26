@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Caisses</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des Caisses</li>
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
        <h5 class="page-title mb-0 mt-2">Caisses</h5>
        <p class="lead">Liste de toutes les caisses</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Intitule</th>
                        <th>Compte</th>
                        <th>Agence</th>
                        <th>Ville</th>
                        <th>Departement</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td><a href="{{ route('admin.caisses.show',$item->id) }}">{{ $item->code }}</a></td>
                            <td><a href="{{ route('admin.caisses.show',$item->id) }}">{{ $item->name }}</a></td>
                            <td>{{ $item->compte }}</td>
                            <td>{{ $item->agence?$item->agence->name:'-' }}</td>
                            <td>{{ $item->ville?$item->ville->name:'-' }}</td>
                            <td>{{ $item->departement?$item->departement->name:'-' }}</td>
                            <td><span class="badge bg-{{ $item->status['color'] }}">{{ $item->status['name'] }}</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                       Actions
                                       <span class="vr"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if($item->active)
                                            <li><a class="dropdown-item" href="{{ route('admin.caisse.disable',$item->id) }}">Desactiver</a></li>
                                        @else
                                            <li><a class="dropdown-item" href="{{ route('admin.caisse.enable',$item->id) }}">Activer</a></li>
                                        @endif

                                    </ul>
                                 </div>
                            </td>
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
                    <h5 class="modal-title">Nouvelle Caisse</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.caisses.store') }}" method="post">
                        @csrf
                        <div class="mt-3">
                            <label for="">CODE</label>
                            <input type="text" required name="code" placeholder="Saisir le code" class="form-control">
                        </div>
                        <div class="mt-3">
                            <label for="">INTITULE</label>
                            <input type="text" required name="name" placeholder="Saisir l'intitule de la caisse" class="form-control">
                        </div>
                        <div class="mt-3">
                            <label for="">COMPTE DE COMPENSATION</label>
                            <input type="text" required name="compte" placeholder="Saisir le numero de compte" class="form-control">
                        </div>
                        <div class="mt-3">
                            <label for="">SOLDE INITIAL</label>
                            <input type="number" required name="solde" placeholder="Saisir le solde initial" class="form-control">
                        </div>
                        <div class="mt-3">
                            <label for="">Ville</label>
                            <select required name="ville_id" id="ville_id" class="form-control">
                                <option value="">Selectionner une ville</option>
                                @foreach($villes as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="">Agence</label>
                            <select required name="agence_id" id="agence_id" class="form-control">
                                <option value="">Selectionner une agence</option>

                            </select>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#ville_id').change(function(){
                 var _id = $('#ville_id').val();
                $.ajax({
                    url: "{{ route('util.ville.agences') }}",
                    type:'get',
                    dataType:'json',
                    data:{id:_id},
                    success:function(data){
                        $('#agence_id').html("<option value=0>Choisir une agence ...</option>");
                        data.forEach(element => {
                            $('#agence_id').append(`<option value=${element.id}>${element.name}</option>`);
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
