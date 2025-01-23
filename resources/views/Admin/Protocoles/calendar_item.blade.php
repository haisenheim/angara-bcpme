@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
        <li class="breadcrumb-item"><a href="#">Producteurs</a></li>
        <li class="breadcrumb-item"><a href="#">Contrats</a></li>
        <li class="breadcrumb-item"><a href="#">Calendrier de collecte - {{ $item->cooperative->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Journée du {{ $item->day->format('d/m/Y') }}</li>
     </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Journée du {{ $item->day->format('d/m/Y') }}</h5>
        <p class="lead">Détails de la Journée du {{ $item->day->format('d/m/Y') }}</p>
    </div>
@endsection

@section('content')
    <div class="d-flex gap-1">
        <div class="card" style="width:45%">
            <div class="card-body">
                <div class="d-flex gap-2">
                    <div class="d-flex justify-content-center flex-column">
                        <img style="vertical-align: middle" class="image-circle image-thumnail image-75"  src="{{ $item->cooperative->photo }}" alt="">
                    </div>
                    <div id="d-text" class="d-flex justify-content-center flex-column">
                        <p class="lh-base">COOPERATIVE : {{ $item->cooperative->name }}</p>
                        <p class="lh-base">SAISON : {{ $item->saison->name }}</p>
                        <p class="lh-base">Tonnage attendu : <span class="badge bg-dark">{{ $item->quantity }} tonne(s)</span></p>
                        <p class="lh-base">Tonnage livré : <span class="badge bg-dark">{{ $item->qtyl }} tonne(s)</span></p>
                        <p class="lh-base">Taux d'exécution : <span class="badge bg-dark">{{ $item->percentage }} %</span></p>
                        <p class="lh-base">Village : <span class="badge bg-dark">{{ $item->village?$item->village->name:'-' }} </span></p>
                        <p class="lh-base">Arrondissement : <span class="badge bg-dark">{{ $item->arrondissement?$item->arrondissement->name:'-' }} </span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card w-100">
            <div class="card-header">
                <a style="float:right" href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="btn btn-sm btn-primary"><i class="pli-file-edit"></i> Ordonner une livraison</a>
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
                @if($item->livraisons->count())
                    <div class="d-flex gap-2 justify-content-center">
                        @foreach($item->livraisons as $liv)
                            <div  class="card bg-light w-25">
                                <div class="card-body">
                                    <p class="lh-base">Date de livraison : <span class="badge bg-primary">{{ $liv->day->format('d/m/Y') }}</span></p>
                                    <p class="lh-base">Quantité : <span class="badge bg-primary">{{ $liv->quantity }} tonne(s)</span></p>
                                    <p class="lh-base">Lieu : <span class="badge bg-primary">{{ $liv->village?$liv->village->name:$liv->arrondissement->name }}</span></p>
                                </div>
                                <div class="card-footer bg-{{ $liv->status['color']}}">
                                    <p class="text-center text-white">{{ $liv->status['name'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="">
                        <div class="text-center">
                            <p class="lh-base">Aucune livraison faite</p>
                            <a href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="btn btn-sm btn-danger"><i class="pli-file-edit"></i> Ordonner une préparation de livraison</a>
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
                    <h5 class="modal-title">Nouvel ordre de préparation</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.protocole.livraison.init') }}" method="post">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <div>
                            <div class="form-group">
                                <label for="">CLIENT</label>
                                <select required name="contrat_id" id="contrat_id" class="form-control">
                                    <option value="0">Selectionner un client</option>
                                    @foreach($contrats as $option)
                                        <option value="{{ $option->id }}">
                                            <div>
                                                <img src="{{ $option->client->photo }}" alt="">
                                                <span>{{ $option->client->name }}</span>
                                            </div>
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">DATE DE LA COLLECTE</label>
                                <input type="date" required name="day" value="{{ $item->day }}" placeholder="Jour du marche" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Tonnage attendu</label>
                                <input type="number" required name="quantity" placeholder="Saisir la quantite de tonnes attendue" value="{{ $item->quantity }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Prix du Kilo</label>
                                <input type="number" required name="price" placeholder="Saisir le prix du kilogramme de cette commande" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">ARRONDISSEMENT</label>
                                <select required name="arrondissement_id" id="arrondissement_id" class="form-control">
                                    <option value="0">Selectionner un arrondissement</option>
                                    @foreach($arrondissements as $option)
                                        <option value="{{ $option->id }}">{{ $option->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Village</label>
                                <select name="village_id" id="village_id" class="form-control">
                                    <option value="0">Selectionner un village ..</option>

                                </select>
                            </div>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn-success btn"><i class="pli-envelope fs-5 me-2"></i> Envoyer</button>
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
                        $('#villages_id').html("<option value='0'>Choisir un village ...</option>");
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

        .card p{
            margin: 2px 2px;
        }
    </style>

@endsection
