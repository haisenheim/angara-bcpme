@extends('...Layouts.tenant.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Entrées de stocks</a></li>
       <li class="breadcrumb-item active" aria-current="page">Nouvelle entrée en stock</li>
    </ol>
 </nav>
@endsection


@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouvelle Entrée en stock</h5>
        <p class="lead">Saisie d'une nouvelle entrée en stock </p>
    </div>
@endsection

@section('content')
    <div class="card form-md">
        <div class="card-header">
            <h4 class="text-primary text-center">Saisie d'une entrée en stock</h4>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('admin.entrees.store')}}">
                @csrf
                <div class="d-flex gap-2">
                    <div class="w-25">
                        <label>AGENT</label>
                        <select required name="agent_id" class="form-control">
                            <option value="">Selectionner un agent ...</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id}}">{{$agent->name}}</option>
                            @endforeach
                        </select>
                    </div>


                    @if(tenant()->is_union)
                    <div class="w-30">
                        <label>COOPERATIVE</label>
                        <select required name="tenant_id" class="form-control">
                            <option value="">Selectionner une cooperative ...</option>
                            @foreach($children as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="w-flex-fill">
                        <label>ENTREPROT</label>
                        <select required name="entrepot_id" class="form-control">
                            <option value="">Selectionner l'entrepot de stockage ...</option>
                            @foreach($entrepots as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-30">
                        <label>MEMBRE</label>
                        <select required name="exploitant_id" class="form-control">
                            <option value="">Selectionner un membre ...</option>
                            @foreach($exploitants as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <div class="w-30">
                        <label>GAMME</label>
                        <select required name="gamme_id" class="form-control">
                            <option value="">Selectionner une gamme ...</option>
                            @foreach($gammes as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-30">
                        <label>QUANTITE EN KG</label>
                        <input type="number" placeholder="Saisir la quantite ici..." class="form-control" required name="quantity" />
                    </div>
                    <div class="w-30">
                        <label>PRIX UNITAIRE</label>
                        <input type="number" placeholder="Saisir le prix au KG..." class="form-control" required name="pu" />
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="pli-save fs-4 me-2"></i> ENREGISTRER</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('select[name="tenant_id"]').change(function(){
                var tenant_id = $(this).val();
                $.get('{{ route('admin.transfert.source',['token'=>':token']) }}'.replace(':token',tenant_id), function(data) {
                   // $('select[name="entrepot_id"]').html('<option value="">Selectionner l\'entrepot source ...</option>');
                    data.forEach(function(item) {
                        $('select[name="entrepot_id"]').append('<option value="' + item.id + '">' + item.name + '</option>');
                    });
                });
                $.get('{{ route('admin.tenant.membres',['id'=>':token']) }}'.replace(':token',tenant_id), function(data) {
                    $('select[name="exploitant_id"]').html('<option value="">Selectionner l\'entrepot source ...</option>');
                    data.forEach(function(item) {
                        $('select[name="exploitant_id"]').append('<option value="' + item.id + '">' + item.name + '</option>');
                    });
                });
            });
        });
    </script>
@endsection
