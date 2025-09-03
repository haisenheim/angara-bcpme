@extends('...Layouts.tenant.agent')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Prévisions de stocks</a></li>
       <li class="breadcrumb-item active" aria-current="page">Nouvelle prévision de stock</li>
    </ol>
 </nav>
@endsection


@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouvelle Prévision de stock</h5>
        <p class="lead">Saisie d'une nouvelle prévision de stock </p>
    </div>
@endsection

@section('content')
    <div class="card form-md">
        <div class="card-header">
            <h4 class="text-primary text-center">Saisie d'une prévision de stock</h4>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('agent.previsions.store')}}">
                @csrf
                <div class="d-flex gap-2">

                    <div class="w-50">
                        <label>MEMBRE</label>
                        <select required name="membre_id" class="form-control">
                            <option value="">Selectionner un membre ...</option>
                            @foreach($exploitants as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-30">
                        <label>ENTREPROT</label>
                        <select required name="entrepot_id" class="form-control">
                            <option value="">Selectionner l'entrepot de stockage ...</option>
                            @foreach($entrepots as $item)
                                <option value="{{ $item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-20">
                        <label>MODE DE PAIEMENT</label>
                        <select required name="mode" class="form-control">
                            <option value="">Mode de paiement...</option>
                            <option value="ESPECES">ESPECES</option>
                            <option value="DIGITAL">DIGITAL</option>
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
@endsection
