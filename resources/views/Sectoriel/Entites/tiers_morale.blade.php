@extends('Layouts.sectoriel')

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
       <li class="breadcrumb-item active" aria-current="page">Nouveau tiers personne morale</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouveau tiers personne morale</h5>
    </div>
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="d-flex gap-0" style="width: 900px">
            <div style="width: 20px;" class="bg-dark">

            </div>
            <div class="card flex-fill">
                <div class="card-header">
                    <h5>POUR L'ENTREPRISE: <span class="text-danger">{{ $item->name }}</span></h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('sectoriel.entreprise.morale.save') }}">
                        @csrf
                        <input type="hidden" name="entreprise_id" value="{{ $item->id }}">
                        <input type="hidden" name="token" value="{{ $item->token }}">
                        <section data-step="step-1">
                            <div class="d-flex gap-2">
                                <div class="form-group w-300px">
                                    <label for="">&numero; Registre de commerce</label>
                                    <input  type="text" id="rccm" name="rccm" placeholder="Numero de Registre de commerce de l'entreprise" class="form-control">
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">&numero; d’identifiant unique</label>
                                    <input  type="text" id="niu" name="niu" placeholder="Numéro d’identifiant unique (NIU/Impôt)" class="form-control">
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">&numero; Employeur ou Assurance volontaire</label>
                                    <input  type="text" id="cnps" name="cnps" placeholder="Numero Employeur ou Assurance volontaire (CNPS)" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="form-group mt-3 flex-fill">
                                    <label for="">Dénomination</label>
                                    <input required type="text" id="name" placeholder="Dénomination de l'entreprise" name="name" class="form-control">
                                </div>
                                <div style="width: 300px" class="form-group mt-0">
                                    <select id="produit_id" name="produit_id" class="easyui-combotree form-control" style="width:300px;"
                                        data-options="url:'{{ route('util.produits.list') }}',method:'get',label:'Produit principal:',labelPosition:'top'">
                                    </select>
                                </div>
                                <div class="form-group mt-3 w-flex-fill">
                                    <label for="">Capital social de l'entreprise</label>
                                    <input required type="number" id="capital" placeholder="Capital social de l'entreprise" name="capital" class="form-control">
                                </div>



                            </div>
                            <div class="d-flex gap-2">
                                <div class="form-group w-200px">
                                    <label for="">Type d'entreprise</label>
                                    <select required name="taille" id="taille" class="form-control">
                                        <option value="">Choisir ...</option>
                                        <option value="GRANDE">GRANDE</option>
                                        <option value="MOYENNE">MOYENNE</option>
                                        <option value="PETITE">PETITE</option>
                                        <option value="TRES PETITE">TRES PETITE</option>
                                        <option value="COOPERATIVE">COOPERATIVE</option>
                                        <option value="ASSOCIATION">ASSOCIATION</option>
                                    </select>
                                </div>
                                <div style="margin-left: 20px" class="form-group w-200px">
                                    <div class="">
                                        <label class="col-form-label">Caractère : </label>
                                        <div class="">
                                           <div class="form-check form-check-inline">
                                              <input id="_dm-inlineRadio2" checked class="form-check-input" type="radio" name="caractere" value="Formel">
                                              <label for="_dm-inlineRadio2" class="form-check-label">Formel</label>
                                           </div>
                                           <div class="form-check form-check-inline">
                                              <input id="_dm-inlineRadio3" class="form-check-input" type="radio" name="caractere" value="Informel">
                                              <label for="_dm-inlineRadio3" class="form-check-label">Informel</label>
                                           </div>
                                        </div>
                                     </div>
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">Forme juridique  </label>
                                    <select required name="forme_id" id="forme_id" class="form-control">
                                        <option value="">Choisir ...</option>
                                        @foreach ($formes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">Système comptable : </label>
                                    <div class="">
                                        <div class="form-check form-check-inline">
                                           <input id="normal" checked class="form-check-input" type="radio" name="systeme" value="Normal">
                                           <label for="normal"  class="form-check-label">Normal</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                           <input id="simple" class="form-check-input" type="radio" name="systeme" value="Minimal">
                                           <label for="simple" class="form-check-label">Minimal</label>
                                        </div>
                                     </div>
                                </div>

                            </div>

                            <div class="d-flex gap-1">
                                <div class="form-group w-300px">
                                    <label for="">Numéro de telephone</label>
                                    <input required type="text" id="phone" placeholder="Numéro de telephone" name="phone" class="form-control">
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">Email</label>
                                    <input required type="email" id="email" placeholder="Email" name="email" class="form-control">
                                </div>
                                <div class="flex-fill">
                                    <label class="col-form-label">Nature de la relation</label>
                                    <div class="">
                                       <div class="form-check form-check-inline">
                                          <input id="_dm-inlineRadio2" checked class="form-check-input" type="radio" name="lien" value="Client">
                                          <label for="_dm-inlineRadio2" class="form-check-label">Client</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                          <input id="_dm-inlineRadio3" class="form-check-input" type="radio" name="lien" value="Fournisseur">
                                          <label for="_dm-inlineRadio3" class="form-check-label">Fournisseur</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                            <input id="_dm-inlineRadio4" class="form-check-input" type="radio" name="lien" value="Banque">
                                            <label for="_dm-inlineRadio4" class="form-check-label">Banque</label>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                            <fieldset>
                                <legend>Infos du dirigeant</legend>
                                <div class="d-flex gap-1">
                                    <div class="form-group w-50">
                                        <label for="">Nom</label>
                                        <input required type="text" id="manager" placeholder="Nom et prenom du dirigeant" name="manager" class="form-control">
                                    </div>
                                    <div class="form-group w-25 mt-4">
                                        <label for="">Sexe: </label>
                                        <div class="">
                                            <div class="form-check form-check-inline">
                                               <input id="male" checked class="form-check-input" type="radio" name="manager_sexe" value="Homme">
                                               <label for="male"  class="form-check-label">Homme</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                               <input id="simple" class="form-check-input" type="radio" name="manager_sexe" value="Femme">
                                               <label for="simple" class="form-check-label">Femme</label>
                                            </div>
                                         </div>
                                    </div>
                                    <div class="form-group w-30">
                                        <label for="">Contact</label>
                                        <input required type="text" id="manager_contact" placeholder="Contact du dirigeant" name="manager_contact" class="form-control">
                                    </div>
                                </div>
                            </fieldset>
                        </section>

                        <div>
                            <button class="btn btn-primary mt-2">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection
