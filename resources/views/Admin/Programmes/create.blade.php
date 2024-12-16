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
       <li class="breadcrumb-item"><a href="#">Programmes</a></li>
       <li class="breadcrumb-item active" aria-current="page">Nouveau Programme</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouveau Programme</h5>
    </div>
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="d-flex gap-0" style="width: 900px">
            <div style="width: 20px;" class="bg-blue">

            </div>
            <div class="card flex-fill">
                <div class="card-body">
                    <form action="{{ route('admin.programmes.store') }}" method="post" id="my-form">
                        @csrf
                        <input type="hidden" id="appuisnf" name="appuisnf">
                        <input type="hidden" id="appuisf" name="appuisf">
                        <input type="hidden" id="prods" name="produits">
                        <input type="hidden" id="organismes" name="bailleurs">
                        <section data-step="step-1">
                            <h3 class="text-center mb-3">Étape 1: Identification</h3>
                            <div class="d-flex gap-2">
                                <div class="form-group flex-fill">
                                    <label for="">Dénomination</label>
                                    <input required type="text" id="name" placeholder="Dénomination du programme" name="name" class="form-control">
                                </div>
                                <div class="form-group w-300px">
                                    <label for="">N° Référence de la Convention cadre du programme</label>
                                    <input required type="text" id="convention" name="convention" placeholder="N° Référence de la Convention cadre du programme" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="form-group w-30">
                                    <label for="">Date de signature de la convention cadre</label>
                                    <input required type="date" id="dt_sig_conv"  name="dt_sig_conv" class="form-control">
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">Institution signataire</label>
                                    <input required type="text" id="signataire" name="signataire" placeholder="Institution nationale signataire" class="form-control">
                                </div>
                            </div>
                            <fieldset>
                                <legend>Budgets du programme</legend>
                                <div class="d-flex gap-2">
                                    <div class="form-group flex-fill">
                                        <label for="">Pour appuis financiers</label>
                                        <input required type="number" value="0" id="budget_af" name="budget_af" placeholder="Budget pour les appuis financiers" class="form-control">
                                    </div>
                                    <div class="form-group flex-fill">
                                        <label for="">Pour appuis non financiers</label>
                                        <input required type="number" value="0" id="budget_anf" name="budget_anf" placeholder="Budget pour les appuis non financiers" class="form-control">
                                    </div>
                                    <div class="form-group flex-fill">
                                        <label for="">Pour la coordination</label>
                                        <input required type="number" value="0" id="budget_coord" name="budget_coord" placeholder="Budget pour lea coordination" class="form-control">
                                    </div>
                                </div>
                            </fieldset>

                            <div>
                                <div class="form-group">
                                    <div class="">
                                        <label class="col-form-label">Bénéficiaires cibles personnes morales</label>
                                        <div class="">
                                            <div class="form-check form-check-inline">
                                                <input id="" class="form-check-input" type="checkbox" name="type_entreprise[]" value="GRANDE">
                                                <label for="" class="form-check-label">Grande</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                    <input id="" class="form-check-input" type="checkbox" name="type_entreprise[]" value="MOYENNE">
                                                    <label for="" class="form-check-label">Moyenne</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input id="" class="form-check-input" type="checkbox" name="type_entreprise[]" value="PETITE">
                                                <label for="" class="form-check-label">Petite</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input id="" class="form-check-input" type="checkbox" name="type_entreprise[]" value="TRES PETITE">
                                                <label for="" class="form-check-label">Très Petite</label>
                                             </div>
                                             <div class="form-check form-check-inline">
                                                <input id="" class="form-check-input" type="checkbox" name="type_entreprise[]" value="COOPERATIVE">
                                                <label for="" class="form-check-label">Coopérative</label>
                                             </div>
                                             <div class="form-check form-check-inline">
                                                <input id="" class="form-check-input" type="checkbox" name="type_entreprise[]" value="ASSOCIATION">
                                                <label for="" class="form-check-label">Association</label>
                                             </div>

                                        </div>
                                     </div>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <div class="">
                                        <label class="col-form-label">Bénéficiaires cibles personnes physiques</label>
                                        <div class="">
                                            <div class="form-check form-check-inline">
                                                <input id="" class="form-check-input" type="checkbox" name="type_personne[]" value="Homme">
                                                <label for="" class="form-check-label">Hommes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                    <input id="" class="form-check-input" type="checkbox" name="type_personne[]" value="Femme">
                                                    <label for="" class="form-check-label">Femmes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input id="" class="form-check-input" type="checkbox" name="type_personne[]" value="Jeune">
                                                <label for="" class="form-check-label">Jeunes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input id="" class="form-check-input" type="checkbox" name="type_personne[]" value="Personnes vulnérables">
                                                <label for="" class="form-check-label">Personnes vulnérables</label>
                                             </div>
                                        </div>
                                     </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="form-group w-30">
                                    <label for="">Date début des activités du programme</label>
                                    <input required type="date" id="dt_start"  name="dt_start" class="form-control">
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">Personnes ressources et contact du programme</label>
                                    <input required type="text" id="contact" name="contact" placeholder="Personnes ressources et contact du programme" class="form-control">
                                </div>
                            </div>
                            <button class="btn btn-primary mt-2" data-next>Suivant</button>
                        </section>
                        <section data-step="step-2">
                            <div class="d-flex justify-content-center">
                                <div>
                                    <h3 class="text-center">Étape 2: Secteurs et Bailleurs de fonds</h3>
                                    <div class="">
                                        <fieldset class="">
                                            <legend>Bailleurs de fonds signataires</legend>
                                            <div style="width: 600px;" class="form-group mt-3">
                                                <label for="">Choix des bailleurs de fonds</label>
                                                <input id="bf" class="form-control"  type="text">
                                            </div>

                                            <h5 class="text-center mt-4 mb-3">Liste des bailleurs choisis</h5>
                                            <div>
                                                <ul id="bailleurs" class="list-group mt-3 fw-bold fs-6">
                                                </ul>
                                            </div>
                                        </fieldset>
                                        <fieldset class="mt-3">
                                            <legend>Secteurs cibles </legend>
                                            <div style="width: 600px" class="form-group mt-3">
                                                <label for="">Choix des secteurs</label>
                                                <input id="ct" class="form-control"  type="text">
                                            </div>

                                            <h5 class="text-center mt-4 mb-3">Liste des produits/services ou des secteurs</h5>
                                            <div>
                                                <ul id="produits" class="list-group mt-3 fw-bold fs-6">
                                                </ul>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>

                            </div>
                            <div>
                                <button class="btn btn-light mt-2" data-prev>Precdent</button>
                                <button  class="btn btn-primary mt-2" data-next>Suivant</button>
                            </div>
                        </section>

                        <section data-step="step-3">
                            <div class="d-flex justify-content-center">
                                <div>
                                    <h3 class="text-center">Étape 3: Appuis proposés</h3>
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
                                <button  class="btn btn-success mt-2" >Enregistrer</button>
                            </div>
                        </section>
                    </form>
                </div>
            </div>
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
    //var _url = "{{ route('util.produits.list') }}"
    var combo;
    var items = [];
    var anfs = [];
    var afs = [];
    var organismes = [];
    $(document).ready(function($){
        $.ajax({
             'url':"{{ route('util.programme.create.data') }}",
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
                     bf = $('#bf').comboTree({
                         source : arr.organismes,
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
                     $('.ct-arrow-btn').html('<i class="pli-arrow-down"></i>');

                     combo.onChange(function(){
                         var elts = combo._selectedItems;
                         items = elts;
                         $('#prods').val(combo.getSelectedIds())
                         build()
                     });

                     bf.onChange(function(){
                         var elts = bf._selectedItems;
                         organismes = elts;
                         $('#organismes').val(bf.getSelectedIds())
                         buildBf()
                     });


                     af.onChange(function(){
                         afs = af._selectedItems;
                         $('#appuisf').val(af.getSelectedIds())
                         buildAf()
                     });

                     anf.onChange(function(){
                         anfs = anf._selectedItems;
                         $('#appuisnf').val(anf.getSelectedIds())
                         buildAnf()
                     });
             }
         });

    })

    function build(){
        $('#produits').html('')
        var autres = [];

        items.forEach(element => {
            var li = `<li class="list-group-item">${element.title}</li>`
            $('#produits').append(li)
        });
     }

     function buildBf(){
        $('#bailleurs').html('')
        var bailleurs = [];

        organismes.forEach(element => {
            bailleurs.push(element.id)
            var li = `<li class="list-group-item">${element.title}</li>`
            $('#bailleurs').append(li)
        });
     }

     function buildAnf(){
        var apnfs = []
        $('#anfs').html('')
        anfs.forEach(element => {
            apnfs.push(element.id)
            var li = `<li class="list-group-item">${element.title}</li>`
            $('#anfs').append(li)
        });
     }

     function buildAf(){
        var apfs = []
        $('#afs').html('')
        afs.forEach(element => {
            apfs.push(element.id)
            var li = `<li class="list-group-item">${element.title}</li>`
            $('#afs').append(li)
        });
     }
 </script>


@endsection
