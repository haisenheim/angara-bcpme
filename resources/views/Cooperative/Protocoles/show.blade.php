@extends('Layouts.cooperative')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Angara</a></li>
        <li class="breadcrumb-item"><a href="#">Contrats</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $protocole->name }}</li>
     </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $protocole->name }}</h5>
        <p class="lead">Données du contrat</p>
    </div>
@endsection

@section('content')
    <div class="d-flex gap-1">
        <div class="card" style="width:45%">
            <div class="card-body">
                <div class="d-flex gap-2">
                    <div class="d-flex justify-content-center flex-column">
                        <img style="vertical-align: middle" class="image-circle image-thumnail image-75"  src="{{ $protocole->cooperative->photo }}" alt="">
                    </div>
                    <div id="d-text" class="d-flex justify-content-center flex-column">
                        <p>SAISON : {{ $protocole->saison->name }}</p>
                        <p>TONNAGE ATTENDU : <span class="bg-info badge">{{ $protocole->quantity }} tonne(s)</span></p>
                        <p>TONNAGE LIVRE : <span class="bg-info badge">{{ $protocole->qtyl }} tonne(s)</span></p>
                        <p>TAUX D'EXECUTION : <span class="bg-success badge">{{ $protocole->percentage }} %</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card w-100">
            <div class="card-body d-flex flex-column justify-content-center">
                @if($protocole->calendrier)
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Tonnage potentiel</th>
                            <th>Village</th>
                            <th>Arrondissement</th>
                            <th>Livraisons</th>
                            <th>Qté livrée</th>
                            <th>
                                <a data-bs-target="#addModal" data-bs-toggle="modal" class="btn btn-xs btn-outline-dark fs-6" href=""><i class="pli-add fs-6 me-2"> Inserer une ligne</i></a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php $qty = 0; ?>
                            @foreach($protocole->calendrier->items as $item)
                            <?php $qty += $item->quantity;  ?>
                                <tr>
                                    <td>{{ $item->day? \Carbon\Carbon::parse($item->day)->format('d/m/Y'):'-' }}</td>
                                    <td>{{ $item->quantity }} tonne(s)</td>
                                    <td>{{ $item->village?$item->village->name:'-' }}</td>
                                    <td>{{ $item->arrondissement?$item->arrondissement->name:'-' }}</td>
                                    <td>{{ $item->livraisons->count() }}</td>
                                    <td>{{ $item->qtyl }} tonne(s)</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                               Actions
                                               <span class="vr"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                               <li><a class="dropdown-item" href="{{ route('cooperative.protocole.calendar.item.show',$item->token) }}">Afficher</a></li>
                                                                                  
                                            </ul>
                                         </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th>Total</th>
                                <th>{{ $qty }} tonnes</th>
                                <td colspan="3"></td>
                            </tr>
                       
                    </tbody>
                </table>
                @else
                    <div class="">
                        <div class="text-center">
                            <a href="{{ route('cooperative.protocole.calendar',$protocole->token) }}" class="btn btn-sm btn-primary"><i class="pli-file-edit"></i> Editer le calendrier de la saison</a>
                        </div>
                    </div>  
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvelle ligne du calendrier</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('cooperative.protocole.calendar.item') }}" method="post">
                        @csrf
                        <input type="hidden" name="calendrier_id" value="{{ $protocole->calendrier?$protocole->calendrier->id:0 }}">
                        <div>
                            <div class="form-group">
                                <label for="">DATE</label>
                                <input type="date" name="day" placeholder="Jour du marche" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Tonnage potentiel</label>
                                <input type="number" name="quantity" placeholder="Saisir la quantite de tonnes attendue" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">REGION</label>
                                <select required name="region_id" id="region_id" class="form-control">
                                    <option value="">Selectionner une region</option>
                                    @foreach($regions as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">DEPARTEMENT</label>
                                <select required name="departement_id" id="departement_id" class="form-control">
                                    <option value="">Selectionner un departement</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">ARRONDISSEMENT</label>
                                <select required name="arrondissement_id" id="arrondissement_id" class="form-control">
                                    <option value="">Selectionner un arrondissement</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Village</label>
                                <select name="village_id" id="village_id" class="form-control">
                                    <option value="">Selectionner un village ..</option>

                                </select>
                            </div>
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


            $('#arrondissement_id').change(function(){
                var _id = $('#arrondissement_id').val();
                $.ajax({
                    url:"{{ route('util.arrondissement.villages') }}",
                    type:'get',
                    dataType:'json',
                    data:{id:_id},
                    success:function(data){
                        $('#villages_id').html("<option value=0>Choisir un village ...</option>");
                        data.forEach(element => {
                            $('#village_id').append(`<option value=${element.id}>${element.name}</option>`);
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
            margin-top: 10px;
        }
        #d-text p{
            margin: 2px 2px; 
            font-weight: 600;
        }
    </style>

@endsection
