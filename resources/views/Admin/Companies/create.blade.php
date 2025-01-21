@extends('Layouts.admin')

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
       <li class="breadcrumb-item active" aria-current="page">Nouvelle Entreprise</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouvelle Entreprise</h5>
    </div>
@endsection

@section('content')
    <div class="card mb-3">
        
        <div class="card-body px-md-5 pt-md-5">
            <form action="{{ route('admin.entreprises.store') }}" method="post" id="my-form">
                @csrf
                <input type="hidden" id="appuisnf" name="appuisnf">
                <input type="hidden" id="appuisf" name="appuisf">
                <input type="hidden" id="autres" name="autres">
                <input type="hidden" id="arr_id" name="arrondissement_id">
                <section data-step="step-1">
                    <h3 class="sub-title text-center mb-3">Étape 1: Identification</h3>
                    <div class="row g-3 mb-5">
                        <div class="col-md-6 col-xl-4 mt-4">
                            <label for="">&numero; Registre de commerce</label>
                            <input required type="text" id="rccm" name="rccm" placeholder="Numero de Registre de commerce de l'entreprise" class="form-control">
                        </div>
                        <div class="col-md-6 col-xl-4 mt-4">
                            <label for="">&numero; d’identifiant unique</label>
                            <input required type="text" id="niu" name="niu" placeholder="Numéro d’identifiant unique (NIU/Impôt)" class="form-control">
                        </div>
                        <div class="col-md-6 col-xl-4 mt-4">
                            <label for="">&numero; Employeur ou Assurance volontaire</label>
                            <input required type="text" id="cnps" name="cnps" placeholder="Numero Employeur ou Assurance volontaire (CNPS)" class="form-control">
                        </div>
                        <div class="col-md-6 mt-4">
                            <label for="">Dénomination</label>
                            <input required type="text" id="name" placeholder="Dénomination de l'entreprise" name="name" class="form-control">
                        </div>
                        <div class="col-md-6 mt-4">
                            <label for="">Numéro principal Mobile Money</label>
                            <input required type="text" id="mm_phone" placeholder="Numéro principal Mobile Money" name="mm_phone" class="form-control">
                        </div>
                        <div class="col-md-6 col-xl-4 mt-4">
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
                        <div class="col-md-6 col-xl-4 mt-4">
                            
                                <label class="">Caractère : </label>
                                <div class="d-flex gap-3">
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
                        <div class="col-md-6 col-xl-4 mt-4">
                            <label for="">Forme juridique  </label>
                            <select required name="forme_id" id="forme_id" class="form-control">
                                <option value="">Choisir ...</option>
                                @foreach ($formes as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-lg-3 mt-4">
                            <label for="">Système comptable : </label>
                            <div class="d-flex gap-3">
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
                        <div class="col-md-6 col-lg-3 mt-4">
                            <label for="">Capital social de l'entreprise</label>
                            <input required type="number" id="capital" placeholder="Capital social de l'entreprise" name="capital" class="form-control">
                        </div>
                        <div class="col-md-6 col-lg-3 mt-4">
                            <label for="">Date de création formelle</label>
                            <input required type="date" id="dt_creation"  name="dt_creation" class="form-control">
                        </div>
                        <div class="col-md-6 col-lg-3 mt-4">
                            <label for="">Date début des activités</label>
                            <input required type="date" id="dt_start"  name="dt_start" class="form-control">
                        </div>
                        <div class="col-md-6 col-xl-4 mt-4">
                            <label for="">Ressources propres </label>
                            <input required type="number" id="ressources_propres"  name="ressources_propres" placeholder="Ressources propres de l'entreprise " class="form-control">
                        </div>
                        <div class="col-md-6 col-xl-4 mt-4">
                            <label for="">Total actif (Voir bilan)</label>
                            <input required type="number" id="total_actif"  name="total_actif" placeholder="Total actif de l'entreprise" class="form-control">
                        </div>
                        <div class="col-md-6 col-xl-4 mt-4">
                            <label for="">Type de personnel : </label>
                            <div class="d-flex gap-3">
                                <div class="form-check form-check-inline">
                                    <input id="s1" checked class="form-check-input" type="radio" name="type_personnel" value="permanent">
                                    <label for="normal"  class="form-check-label">Permanent</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input id="s2" class="form-check-input" type="radio" name="type_personnel" value="saisonier">
                                    <label for="simple" class="form-check-label">Saisonier</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input id="s3" class="form-check-input" type="radio" name="type_personnel" value="mixte">
                                    <label for="simple" class="form-check-label">Mixte</label>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-6 col-xl-4 mt-4">
                            <label for="">Nombre d'employés</label>
                            <input required  type="number" id="nb_personnel"  name="nb_personnel" placeholder="Nombre d'employés" class="form-control">
                        </div>
                    </div>

                    


                    <fieldset>
                        <legend class="font-bold bold mb-3 text-md-start">Infos du dirigeant</legend>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6 col-xl-4 mt-4">
                                <label for="">Nom</label>
                                <input required type="text" id="manager" placeholder="Nom et prenom du dirigeant" name="manager" class="form-control">
                            </div>
                            <div class="col-md-6 col-xl-4 mt-4">
                                <label for="">Sexe: </label>
                                <div class="d-flex gap-3">
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
                            <div class="col-md-6 col-xl-4 mt-4">
                                <label for="">Contact</label>
                                <input required type="text" id="manager_contact" placeholder="Contact du dirigeant" name="manager_contact" class="form-control">
                            </div>
                            <div class="col-md-6 col-xl-4 mt-4">
                                <label for="">Niveau d'instruction du dirigeant</label>
                                <select required name="manager_niveau" id="manager_niveau" class="form-control">
                                    <option value="">Choisir ...</option>
                                    <option value="Supérieur">Supérieur</option>
                                    <option value="Secondaire">Secondaire</option>
                                    <option value="Primaire">Primaire</option>
                                    <option value="Sans niveau">Sans niveau</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-xl-4 mt-4">
                                <label for="">Date de naissance</label>
                                <input required type="date" id="manager_dtn"  name="manager_dtn" class="form-control">
                            </div>
                            <div class="col-md-6 col-xl-4 mt-4">
                                <label for="">Le dirigeant est-il le promoteur ? </label>
                                <div class="d-flex gap-3">
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
                    <div class="row border-top">
                        <div class="step-footer col-12 col-md-8 offset-md-2 d-flex justify-content-end">
                            <button class="btn btn-primary mt-2" data-next>Suivant</button>
                        </div>
                    </div>
                    
                    
                </section>

                <section data-step="step-2">
                    <h3 class="text-center mb-3">Étape 2: Objet Social   </h3>
                    <div class="d-flex justify-content-center">
                        <div>
                            <fieldset>
                                <legend>Produit principal</legend>
                                <div>
                                    <div style="width: 600px" class="form-group mt-2 mb-3">
                                        <select id="produit_id" name="produit_id" class="easyui-combotree form-control" style="width:600px;"
                                            data-options="url:'{{ route('util.produits.list') }}',method:'get',label:'Produit principal:',labelPosition:'top'">
                                        </select>
                                    </div>
                                    <div style="width: 600px" class="form-group mt-3">
                                        <label for="">Ancienneté dans le service/produit principal (Année )</label>
                                        <input name="produit_year_start" id="produit_year_start" class="form-control"  type="number">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="mt-3">
                                <legend>Produits secondaires</legend>
                                <div style="width: 600px" class="form-group mt-3">
                                    <label for="">Choix des autres produits</label>
                                    <input id="ct" class="form-control"  type="text">
                                </div>

                                <h5 class="text-center mt-4 mb-3">Liste des produits/services secondaires</h5>
                                <div>
                                    <ul id="produits" class="list-group mt-3 fw-bold fs-6">
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <button id="btn-test" class="btn btn-light mt-2" data-prev>Precdent</button>
                    <button class="btn btn-primary mt-2" data-next>Suivant</button>
                </section>

                {{-- STEP 3 --}}
                <section data-step="step-3">
                    <div class="d-flex justify-content-center">
                        <div>
                            <h3 class="text-center">Étape 3: Appuis sollicités</h3>
                        <fieldset class="mt-3">
                            <legend>Appuis Financiers</legend>
                            <div style="width: 600px" class="form-group mt-3">
                                <label for="">Choix des appuis financiers</label>
                                <input id="af" class="form-control"  type="text">
                            </div>

                            <h5 class="text-center mt-4 mb-3">Liste des appuis financiers</h5>
                            <div>
                                <ul id="afs" class="list-group mt-3 fw-bold fs-6">
                                </ul>
                            </div>
                        </fieldset>

                        <fieldset class="mt-3">
                            <legend>Appuis non Financiers</legend>
                            <div style="width: 600px" class="form-group mt-3">
                                <label for="">Choix des appuis non financiers</label>
                                <input  id="anf" class="form-control"  type="text">
                            </div>

                            <h5 class="text-center mt-4 mb-3">Liste des  appuis non financiers</h5>
                            <div>
                                <ul id="anfs" class="list-group mt-3 fw-bold fs-6">
                                </ul>
                            </div>
                        </fieldset>
                        </div>

                    </div>
                    <div>
                        <button id="btn-test" class="btn btn-light mt-2" data-prev>Precdent</button>
                        <button class="btn btn-primary mt-2" data-next>Suivant</button>
                    </div>
                </section>
                <section data-step="step-4">
                    <div class="d-flex justify-content-center">
                        <div>
                            <h3 class="text-center">Étape 4: Localisation / Contact</h3>
                        <fieldset class="mt-3">
                            <legend>Localisation</legend>
                            <div style="width: 600px" class="form-group mt-3">
                                <label for="">Commune</label>
                                <input  id="arrondissement_id" class="form-control"  type="text">
                            </div>
                        </fieldset>

                        <fieldset class="mt-3">
                            <legend>Contact</legend>
                            <div style="width: 600px" class="form-group mt-3">
                                <label for="">Telephone</label>
                                <input name="phone" id="phone" class="form-control"  type="text">
                            </div>
                            <div style="width: 600px" class="form-group mt-3">
                                <label for="">Email</label>
                                <input name="email" id="email" class="form-control"  type="email">
                            </div>

                        </fieldset>
                        </div>

                    </div>
                    <div>
                        <button id="btn-test" class="btn btn-light mt-2" data-prev>Precdent</button>
                        <button class="btn btn-success mt-2" data-next>Enregistrer</button>

                    </div>
                </section>
            </form>
        </div>
            
    </div>

 <script>
    document.addEventListener('DOMContentLoaded', () => {
            const wizard = new Zangdar('#my-form')
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
    $(document).ready(function($){
        $.ajax({
             'url':"{{ route('util.entreprise.create.data') }}",
             'type':'get',
             'dataType':'json',
             success:function(arr){
                 console.log(arr);
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
        $('#autres').val(autres)
     }

     function buildAnf(){
        var apnfs = []
        $('#anfs').html('')
        anfs.forEach(element => {
            apnfs.push(element.id)
            var li = `<li class="list-group-item">${element.title}</li>`
            $('#anfs').append(li)
        });
        $('#appuisnf').val(apnfs)
     }

     function buildAf(){
        var apfs = []
        $('#afs').html('')
        afs.forEach(element => {
            apfs.push(element.id)
            var li = `<li class="list-group-item">${element.title}</li>`
            $('#afs').append(li)
        });
        $('#appuisf').val(apfs)
     }
 </script>


@endsection
