@extends('Layouts.gestionnaire')

@section('title', 'Accueil')
@section('breadcrumb')
<link rel="stylesheet" href="{{ asset('dropdowncombotree/comboTreeStyle.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/default/easyui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/icon.css') }}">
<script type="text/javascript" src="{{ asset('jquery-easyui/jquery.easyui.min.js') }}"></script>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Entreprises</a></li>
       <li class="breadcrumb-item active" aria-current="page">Nouveau tiers personne physique</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouveau tiers personne physique</h5>
    </div>
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="d-flex gap-0" style="width: 900px">
            <div style="width: 20px;" class="bg-dark">

            </div>
            <div class="card flex-fill">
                <div class="card-header">
                    <h5>ENTREPRISE: <span class="text-danger">{{ $item->name }}</span></h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('gestionnaire.entreprise.physique.save') }}">
                        @csrf
                        <input type="hidden" name="entreprise_id" value="{{ $item->id }}">
                        <input type="hidden" name="token" value="{{ $item->token }}">
                        <div>
                            <div class="d-flex gap-2">
                                <div class="form-group flex-fill">
                                    <label for="">Nom et Prénom</label>
                                    <input required type="text" id="name" name="name" placeholder="Nom et Prénom" class="form-control">
                                </div>
                                <div class="form-group w-300px">
                                    <label for="">&numero; d’identifiant unique</label>
                                    <input required type="text" id="niu" name="niu" placeholder="Numéro d’identifiant unique (NIU/Impôt)" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="form-group w-50">
                                    <label for="">Email</label>
                                    <input required type="email" id="email" name="email" placeholder="Adresse email" class="form-control">
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">Téléphone</label>
                                    <input required type="text" id="phone" name="phone" placeholder="Numéro de telephone" class="form-control">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="">Adresse/domicile</label>
                                <textarea required name="address" id="address" class="form-control" cols="30" placeholder="Adresse physique" rows="3"></textarea>
                            </div>
                            <div class="">
                                <label class="col-form-label">Lien avec le dirigeant principal</label>
                                <div class="">
                                   <div class="form-check form-check-inline">
                                      <input id="_dm-inlineRadio2" checked class="form-check-input" type="radio" name="lien" value="Familial">
                                      <label for="_dm-inlineRadio2" class="form-check-label">Familial</label>
                                   </div>
                                   <div class="form-check form-check-inline">
                                      <input id="_dm-inlineRadio3" class="form-check-input" type="radio" name="lien" value="Professionnel">
                                      <label for="_dm-inlineRadio3" class="form-check-label">Professionnel</label>
                                   </div>
                                   <div class="form-check form-check-inline">
                                        <input id="_dm-inlineRadio4" class="form-check-input" type="radio" name="lien" value="Associatif">
                                        <label for="_dm-inlineRadio4" class="form-check-label">Associatif</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input id="_dm-inlineRadio5" class="form-check-input" type="radio" name="lien" value="Amical">
                                        <label for="_dm-inlineRadio5" class="form-check-label">Amical</label>
                                    </div>
                                </div>
                             </div>
                        </div>
                        <div>
                            <button class="btn btn-primary mt-2">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
