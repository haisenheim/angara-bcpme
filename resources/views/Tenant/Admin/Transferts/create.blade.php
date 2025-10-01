@extends('...Layouts.tenant.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Transferts de stocks</a></li>
       <li class="breadcrumb-item active" aria-current="page">Nouveau transfert de stock</li>
    </ol>
 </nav>
@endsection


@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouveau transfert de stock</h5>
        <p class="lead">Saisie d'un nouveau transfert de stock </p>
    </div>
@endsection

@section('content')
    <div class="card form-md">
        <div class="card-header">
            <h4 class="text-primary text-center">Saisie d'un transfert de stock</h4>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('admin.transferts.store')}}">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label>COOPERATIVE SOURCE</label>
                        <select required name="tenant_source_id" class="form-select">
                            <option value="">Selectionner la cooperative source ...</option>
                            @foreach($children as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>ENTREPROT SOURCE</label>
                        <select required name="source_id" class="form-select">
                            <option value="">Selectionner l'entrepot source ...</option>

                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>ENTREPROT CIBLE</label>
                       <select required name="target_id" class="form-select">
                            <option value="">Selectionner l'entrepot cible ...</option>
                            @foreach($targets as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>DATE</label>
                        <input type="date" placeholder="Saisir la date..." class="form-control" required name="day" />
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-2">
                        <label>GAMME</label>
                        <select required name="gamme_id" class="form-select">
                            <option value="">Selectionner une gamme ...</option>
                            @foreach($gammes as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>QUANTITE EN KG</label>
                        <input type="number" placeholder="Saisir la quantite ici..." class="form-control" required name="quantity" />
                    </div>
                    <div class="col-md-3">
                        <label>VEHICULE</label>
                        <input type="text" placeholder="Saisir le vehicule..." class="form-control" required name="vehicule" />
                    </div>
                    <div class="col-md-5">
                        <label>RESPONSABLE</label>
                        <input type="text" placeholder="Saisir le responsable de la cargaison ... " class="form-control" required name="responsable" />
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="pli-save fs-4 me-2"></i> ENREGISTRER</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('select[name="tenant_source_id"]').change(function() {
                var tenant_id = $(this).val();
                $.get('{{ route('admin.transfert.source',['token'=>':token']) }}'.replace(':token',tenant_id), function(data) {
                    $('select[name="source_id"]').html('<option value="">Selectionner l\'entrepot source ...</option>');
                    data.forEach(function(item) {
                        $('select[name="source_id"]').append('<option value="' + item.id + '">' + item.name + '</option>');
                    });
                });
            });
        });
    </script>
@endsection
