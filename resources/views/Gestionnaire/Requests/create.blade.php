@extends('Layouts.gestionnaire')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Appels de fonds</a></li>
       <li class="breadcrumb-item active" aria-current="page">Nouvel appel de fonds</li>
    </ol>
 </nav>
@endsection


@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouvel appel de fonds</h5>
        <p class="lead">Saisie d'un appel de fonds</p>
    </div>
@endsection

@section('content')
    <div style="max-width: 540px; margin: 0 auto;" class="card">
        <div class="card-header">
            <h4 class="text-primary text-center">Saisie d'une appel de fonds</h4>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('gestionnaire.requests.store') }}">
                @csrf
                <div class="">
                    <div class="">
                        <label>Opérateur de mobile</label>
                        <select required name="token" class="form-control">
                            <option value="">Selectionner un opérateur mobile ...</option>
                            @foreach($items as $item)
                                <option value="{{ $item->token}}"> {{$item->operateur?->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3">
                        <label>Montant</label>
                        <input type="number" placeholder="Saisir le montant ..." class="form-control" required name="montant" />
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="pli-save fs-4 me-2"></i> ENREGISTRER</button>
                </div>
            </form>
        </div>
    </div>
@endsection
