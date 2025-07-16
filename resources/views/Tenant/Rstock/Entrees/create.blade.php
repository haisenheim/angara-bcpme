@extends('...Layouts.tenant.rstock')

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
            <form method="post" action="{{route('rstock.entrees.store')}}">
                @csrf
                <div class="row">
                    <div class="col-md-5 col-sm-12 pe-2">
                        <label>AGENT</label>
                        <select required name="agent_id" class="form-control">
                            <option value="">Selectionner un agent ...</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id}}">{{$agent->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-7 col-sm-12">
                        <label>MEMBRE</label>
                        <select required name="exploitant_id" class="form-control">
                            <option value="">Selectionner un membre ...</option>
                            @foreach($exploitants as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-md-4 col-sm-12 pe-2">
                        <label>GAMME</label>
                        <select required name="gamme_id" class="form-control">
                            <option value="">Selectionner une gamme ...</option>
                            @foreach($gammes as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-12 pe-2">
                        <label>QUANTITE EN KG</label>
                        <input type="number" placeholder="Saisir la quantite ici..." class="form-control" required name="quantity" />
                    </div>
                    <div class="col-md-4 col-sm-12">
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
@endsection
