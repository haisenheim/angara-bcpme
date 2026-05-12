@extends('Layouts.gestionnaire')

@push('styles')
<link rel="stylesheet" href="{{ asset('dropdowncombotree/comboTreeStyle.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/default/easyui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/icon.css') }}">
@endpush

@section('title', 'Nouvelle entreprise')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.index') }}">Entreprises</a></li>
       <li class="breadcrumb-item active" aria-current="page">Nouvelle entreprise</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="{{ route('gestionnaire.entreprises.index') }}" class="dropdown-item"><i class="demo-pli-arrow-left me-2"></i>Annuler</a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouvelle entreprise</h5>
        <p class="text-body-secondary mb-0">Remplissez les informations en 4 étapes</p>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <form action="{{ route('gestionnaire.entreprises.store') }}" method="post" id="my-form">
                        @csrf
                        <input type="hidden" id="appuisnf" name="appuisnf">
                        <input type="hidden" id="appuisf" name="appuisf">
                        <input type="hidden" id="autres" name="autres">
                        <input type="hidden" id="arr_id" name="arrondissement_id">
                        <section data-step="step-1">
                            <h5 class="text-primary fw-semibold mb-4">
                                <i class="demo-psi-id me-2"></i>Étape 1: Identification
                            </h5>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label for="rccm" class="form-label">N° Registre de commerce</label>
                                    <input type="text" id="rccm" name="rccm" placeholder="Numéro de registre de commerce" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="niu" class="form-label">N° d’identifiant unique</label>
                                    <input required type="text" id="niu" name="niu" placeholder="Numéro d’identifiant unique (NIU/Impôt)" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="cnps" class="form-label">N° Employeur ou Assurance volontaire</label>
                                    <input required type="text" id="cnps" name="cnps" placeholder="CNPS" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-8">
                                    <label for="name" class="form-label">Dénomination</label>
                                    <input required type="text" id="name" placeholder="Dénomination de l'entreprise" name="name" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="mm_phone" class="form-label">Numéro Mobile Money</label>
                                    <input type="text" id="mm_phone" placeholder="Numéro Mobile Money" name="mm_phone" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label for="taille" class="form-label">Type d'entreprise</label>
                                    <select required name="taille" id="taille" class="form-select">
                                        <option value="">Choisir ...</option>
                                        <option value="GRANDE">GRANDE</option>
                                        <option value="MOYENNE">MOYENNE</option>
                                        <option value="PETITE">PETITE</option>
                                        <option value="TRES PETITE">TRES PETITE</option>
                                        <option value="COOPERATIVE">COOPERATIVE</option>
                                        <option value="ASSOCIATION">ASSOCIATION</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label d-block">Caractère</label>
                                    <div class="form-check form-check-inline">
                                        <input data-formal="1" id="caractere-formel" checked class="form-check-input caractere" type="radio" name="caractere" value="Formel">
                                        <label for="caractere-formel" class="form-check-label">Formel</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input data-formal="0" id="caractere-informel" class="form-check-input caractere" type="radio" name="caractere" value="Informel">
                                        <label for="caractere-informel" class="form-check-label">Informel</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="forme_id" class="form-label">Forme juridique</label>
                                    <select required name="forme_id" id="forme_id" class="form-select">
                                        <option value="">Choisir ...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">Système comptable</label>
                                    <div class="">
                                        <div class="form-check form-check-inline">
                                           <input id="systeme-normal" checked class="form-check-input" type="radio" name="systeme" value="Normal">
                                           <label for="systeme-normal" class="form-check-label">Normal</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                           <input id="systeme-minimal" class="form-check-input" type="radio" name="systeme" value="Minimal">
                                           <label for="systeme-minimal" class="form-check-label">Minimal</label>
                                        </div>
                                     </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="capital" class="form-label">Capital social</label>
                                    <input required type="number" id="capital" placeholder="Capital social" name="capital" class="form-control formal-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="dt_creation" class="form-label">Date de création formelle</label>
                                    <input required type="date" id="dt_creation" name="dt_creation" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="dt_start" class="form-label">Date début des activités</label>
                                    <input required type="date" id="dt_start" name="dt_start" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label for="ressources_propres" class="form-label">Ressources propres</label>
                                    <input required type="number" id="ressources_propres" name="ressources_propres" placeholder="Ressources propres" class="form-control formal-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="total_actif" class="form-label">Total actif</label>
                                    <input required type="number" id="total_actif" name="total_actif" placeholder="Total actif (bilan)" class="form-control formal-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Type de personnel</label>
                                    <div class="">
                                        <div class="form-check form-check-inline">
                                           <input id="type-permanent" checked class="form-check-input" type="radio" name="type_personnel" value="permanent">
                                           <label for="type-permanent" class="form-check-label">Permanent</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                           <input id="type-saisonier" class="form-check-input" type="radio" name="type_personnel" value="saisonier">
                                           <label for="type-saisonier" class="form-check-label">Saisonnier</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input id="type-mixte" class="form-check-input" type="radio" name="type_personnel" value="mixte">
                                            <label for="type-mixte" class="form-check-label">Mixte</label>
                                         </div>
                                     </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="nb_personnel" class="form-label">Nombre d'employés</label>
                                    <input required type="number" id="nb_personnel" name="nb_personnel" placeholder="Nombre d'employés" class="form-control">
                                </div>
                            </div>


                            <fieldset class="mt-4 pt-3 border-top">
                                <legend class="fs-6 fw-semibold text-muted">Infos du dirigeant</legend>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-8">
                                        <label for="manager" class="form-label">Nom</label>
                                        <input required type="text" id="manager" placeholder="Nom et prénom du dirigeant" name="manager" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label d-block">Sexe</label>
                                        <div class="">
                                            <div class="form-check form-check-inline">
                                               <input id="manager-male" checked class="form-check-input" type="radio" name="manager_sexe" value="Homme">
                                               <label for="manager-male" class="form-check-label">Homme</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                               <input id="manager-femme" class="form-check-input" type="radio" name="manager_sexe" value="Femme">
                                               <label for="manager-femme" class="form-check-label">Femme</label>
                                            </div>
                                         </div>
                                    </div>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-4">
                                        <label for="manager_contact" class="form-label">Contact</label>
                                        <input type="text" id="manager_contact" placeholder="Contact du dirigeant" name="manager_contact" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="manager_niveau" class="form-label">Niveau d'instruction</label>
                                        <select required name="manager_niveau" id="manager_niveau" class="form-select">
                                            <option value="">Choisir ...</option>
                                            <option value="Supérieur">Supérieur</option>
                                            <option value="Secondaire">Secondaire</option>
                                            <option value="Primaire">Primaire</option>
                                            <option value="Sans niveau">Sans niveau</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="manager_dtn" class="form-label">Date de naissance</label>
                                        <input required type="date" id="manager_dtn" name="manager_dtn" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label d-block">Promoteur ?</label>
                                        <div class="">
                                            <div class="form-check form-check-inline">
                                               <input id="mp-1" checked class="form-check-input" type="radio" name="manager_promoteur" value="1">
                                               <label for="mp-1"  class="form-check-label">Oui</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                               <input id="mp-2" class="form-check-input" type="radio" name="manager_promoteur" value="0">
                                               <label for="mp-2" class="form-check-label">Non</label>
                                            </div>
                                         </div>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-primary" data-next>Suivant <i class="demo-pli-arrow-right ms-1"></i></button>
                            </div>
                        </section>

                        <section data-step="step-2">
                            <h5 class="text-primary fw-semibold mb-4">
                                <i class="demo-psi-box me-2"></i>Étape 2: Objet social
                            </h5>
                            <div class="row justify-content-center">
                                <div class="col-12 col-lg-10">
                                    <fieldset class="mb-4">
                                        <legend class="fs-6 fw-semibold text-muted">Produit principal</legend>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="produit_id" class="form-label">Produit principal</label>
                                                <select id="produit_id" name="produit_id" class="easyui-combotree form-control" style="width:100%;max-width:100%;"
                                                    data-options="url:'{{ route('util.produits.list') }}',method:'get',label:'Produit principal:',labelPosition:'top'">
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="produit_year_start" class="form-label">Ancienneté dans le service/produit principal (années)</label>
                                                <input name="produit_year_start" id="produit_year_start" class="form-control" type="number" placeholder="Nombre d'années">
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="mb-4">
                                        <legend class="fs-6 fw-semibold text-muted">Produits secondaires</legend>
                                        <div class="mb-3">
                                            <label for="ct" class="form-label">Choix des autres produits</label>
                                            <input id="ct" class="form-control" type="text" placeholder="Rechercher et sélectionner...">
                                        </div>
                                        <p class="text-muted small mb-2">Produits/services secondaires sélectionnés</p>
                                        <ul id="produits" class="list-group list-group-flush">
                                        </ul>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" data-prev><i class="demo-pli-arrow-left me-1"></i>Précédent</button>
                                <button type="button" class="btn btn-primary" data-next>Suivant <i class="demo-pli-arrow-right ms-1"></i></button>
                            </div>
                        </section>

                        <section data-step="step-3">
                            <h5 class="text-primary fw-semibold mb-4">
                                <i class="demo-psi-handshake me-2"></i>Étape 3: Appuis sollicités
                            </h5>
                            <div class="row justify-content-center">
                                <div class="col-12 col-lg-10">
                                    <fieldset class="mb-4">
                                        <legend class="fs-6 fw-semibold text-muted">Appuis financiers</legend>
                                        <div class="mb-3">
                                            <label for="af" class="form-label">Choix des appuis financiers</label>
                                            <input id="af" class="form-control" type="text" placeholder="Rechercher et sélectionner...">
                                        </div>
                                        <p class="text-muted small mb-2">Appuis financiers sélectionnés</p>
                                        <ul id="afs" class="list-group list-group-flush">
                                        </ul>
                                    </fieldset>

                                    <fieldset class="mb-4">
                                        <legend class="fs-6 fw-semibold text-muted">Appuis non financiers</legend>
                                        <div class="mb-3">
                                            <label for="anf" class="form-label">Choix des appuis non financiers</label>
                                            <input id="anf" class="form-control" type="text" placeholder="Rechercher et sélectionner...">
                                        </div>
                                        <p class="text-muted small mb-2">Appuis non financiers sélectionnés</p>
                                        <ul id="anfs" class="list-group list-group-flush">
                                        </ul>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" data-prev><i class="demo-pli-arrow-left me-1"></i>Précédent</button>
                                <button type="button" class="btn btn-primary" data-next>Suivant <i class="demo-pli-arrow-right ms-1"></i></button>
                            </div>
                        </section>
                        <section data-step="step-4">
                            <h5 class="text-primary fw-semibold mb-4">
                                <i class="demo-psi-map me-2"></i>Étape 4: Localisation / Contact
                            </h5>
                            <div class="row justify-content-center">
                                <div class="col-12 col-lg-10">
                                    <fieldset class="mb-4">
                                        <legend class="fs-6 fw-semibold text-muted">Localisation</legend>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="arrondissement_id" class="form-label">Commune</label>
                                                <input id="arrondissement_id" class="form-control" type="text" placeholder="Rechercher la commune...">
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="mb-4">
                                        <legend class="fs-6 fw-semibold text-muted">Contact</legend>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="phone" class="form-label">Téléphone</label>
                                                <input name="phone" id="phone" class="form-control" type="text" placeholder="Numéro de téléphone">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Email</label>
                                                <input name="email" id="email" class="form-control" type="email" placeholder="Adresse email">
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" data-prev><i class="demo-pli-arrow-left me-1"></i>Précédent</button>
                                <button type="submit" class="btn btn-success"><i class="demo-pli-check me-1"></i>Enregistrer</button>
                            </div>
                        </section>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('jquery-easyui/jquery.easyui.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
            const wizard = new Zangdar('#my-form');
            document.getElementById('my-form').addEventListener('submit', function() {
                // Réactiver les champs désactivés pour qu'ils soient envoyés (Informel)
                $('.formal-control').prop('disabled', false);
            });
    })
 </script>
 <script src="{{ asset('dropdowncombotree/comboTreePlugin.js') }}"></script>
 <script>
     $.expr[':'].icontains = function (obj, index, meta, stack) { return (obj.textContent || obj.innerText || jQuery(obj).text() || '').toLowerCase().indexOf(meta[3].toLowerCase()) >= 0; };
 </script>
 <script>
    var _url = "{{ route('util.produits.list') }}"

    var combo;
    var items = [];
    var anfs = [];
    var afs = [];
    var formes = [];
    $(document).ready(function($){
        $.ajax({
             'url':"{{ route('util.entreprise.create.data') }}",
             'type':'get',
             'dataType':'json',
             success:function(arr){
                 console.log(arr);
                    formes = arr.formes;
                    console.log(formes);
                    formes.forEach(elt => {
                        if(elt.formel==1){
                            $('#forme_id').append(`<option value="${elt.id}">${elt.name}</option>`)
                        }
                    });
                    combo = $('#ct').comboTree({
                         source : arr.produits,
                         collapse: true,
                         isMultiple:true,
                         editable:true,
                     });
                     var af = $('#af').comboTree({
                         source : arr.afs,
                         collapse: true,
                         isMultiple:true,
                         editable:true,
                     });
                     var anf = $('#anf').comboTree({
                         source : arr.anfs,
                         collapse: true,
                         isMultiple:true,
                         editable:true,
                     });
                     var arrond = $('#arrondissement_id').comboTree({
                         source : arr.localites,
                         collapse: true,
                         isMultiple:false,
                         editable:true,
                     });
                     $('.ct-arrow-btn').html('<i class="pli-arrow-down"></i>');
                     arrond.onChange(function(){
                         var elt = arrond._selectedItem;
                         $('#arr_id').val(elt.id);
                     });
                     combo.onChange(function(){
                         var elts = combo._selectedItems;
                         items = elts;
                         build()
                     });


                     af.onChange(function(){
                         afs = af._selectedItems;
                         buildAf()
                     });

                     anf.onChange(function(){
                         anfs = anf._selectedItems;
                         buildAnf()
                     });

                     $('.caractere').change(function(){
                        var c = $(this).data('formal');
                        if(c==0){
                            $('.formal-control').prop('disabled', true).val(0);
                        }else{
                            $('.formal-control').prop('disabled', false).val('');
                        }
                        $('#forme_id').html(`<option value="">Choisir une forme ...</option>`)
                        formes.forEach(elt => {
                            if(elt.formel==c){
                                $('#forme_id').append(`<option value="${elt.id}">${elt.name}</option>`)
                            }
                        });
                    });
             }
         });



    })

    function build(){
        $('#produits').html('')
        var autres = [];

        items.forEach(element => {
            //console.log(element)
            autres.push(element.id)
            var li = `<li class="list-group-item">${element.title}</li>`
            $('#produits').append(li)
        });
        $('#autres').val(autres.join(','))
     }

     function buildAnf(){
        var apnfs = []
        $('#anfs').html('')
        anfs.forEach(element => {
            apnfs.push(element.id)
            var li = `<li class="list-group-item">${element.title}</li>`
            $('#anfs').append(li)
        });
        $('#appuisnf').val(apnfs.join(','))
     }

     function buildAf(){
        var apfs = []
        $('#afs').html('')
        afs.forEach(element => {
            apfs.push(element.id)
            var li = `<li class="list-group-item">${element.title}</li>`
            $('#afs').append(li)
        });
        $('#appuisf').val(apfs.join(','))
     }
 </script>


@endsection
