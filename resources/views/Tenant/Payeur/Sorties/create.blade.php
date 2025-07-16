@extends('../Layouts.tenant.payeur')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Sorties de stocks</a></li>
       <li class="breadcrumb-item active" aria-current="page">Nouvelle Sortie en stock</li>
    </ol>
 </nav>
@endsection


@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouvelle sortie de stock</h5>
        <p class="lead">Saisie d'une nouvelle sortie de stock </p>
    </div>
@endsection

@section('content')
    <div class="card form-md">
        <div class="card-header">
            <h4 class="text-primary text-center">Saisie d'une sortie de stock</h4>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('payeur.sorties.store')}}">
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
                    <div class="w-50">
                        <label>MEMBRE</label>
                        <select required name="exploitant_id" class="form-control">
                            <option value="">Selectionner un membre ...</option>
                            @foreach($exploitants as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-30">
                        <label>ENTREPROT DE DEPART</label>
                        <select required name="entrepot_id" class="form-control">
                            <option value="">Selectionner l'entrepot de stockage ...</option>
                            @foreach($entrepots as $item)
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
                        <label>QUANTITE EN TONNE</label>
                        <input type="number" placeholder="Saisir la quantite ici..." class="form-control" required name="quantity" />
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="pli-save fs-4 me-2"></i> ENREGISTRER</button>
                </div>
            </form>
        </div>
    </div>
@endsection





