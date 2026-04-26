@extends('Layouts.gestionnaire')

@push('styles')
<link rel="stylesheet" href="{{ asset('dropdowncombotree/comboTreeStyle.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/default/easyui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/icon.css') }}">
@endpush

@section('title', 'Modifier - ' . Str::limit($item->name, 30))
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.show', $item->token) }}">{{ Str::limit($item->name, 30) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Modifier</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="{{ route('gestionnaire.entreprises.show', $item->token) }}" class="dropdown-item">
                <i class="demo-psi-arrow-left me-2"></i>Annuler
            </a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Modifier l'entreprise</h5>
        <p class="text-body-secondary mb-0 mt-1">{{ $item->name }}</p>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <form action="{{ route('gestionnaire.entreprises.save') }}" method="post" id="my-form">
                        @csrf
                        <input type="hidden" id="arr_id" name="arrondissement_id" value="{{ $item->arrondissement_id }}">
                        <input type="hidden" name="token" value="{{ $item->token }}">

                        <section data-step="step-1">
                            <h5 class="text-primary fw-semibold mb-4">
                                <i class="demo-psi-id me-2"></i>Étape 1: Identification
                            </h5>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label for="rccm" class="form-label">N° Registre de commerce</label>
                                    <input required type="text" id="rccm" name="rccm" value="{{ $item->rccm }}" placeholder="Numéro de registre de commerce" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="niu" class="form-label">N° d'identifiant unique</label>
                                    <input required type="text" id="niu" name="niu" value="{{ $item->niu }}" placeholder="NIU / Impôt" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="cnps" class="form-label">N° Employeur ou Assurance volontaire</label>
                                    <input required type="text" id="cnps" name="cnps" value="{{ $item->cnps }}" placeholder="CNPS" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-8">
                                    <label for="name" class="form-label">Dénomination</label>
                                    <input required type="text" id="name" value="{{ $item->name }}" placeholder="Dénomination de l'entreprise" name="name" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="mm_phone" class="form-label">Numéro Mobile Money</label>
                                    <input required type="text" id="mm_phone" value="{{ $item->mm_phone }}" placeholder="Numéro Mobile Money" name="mm_phone" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label for="taille" class="form-label">Type d'entreprise</label>
                                    <select required name="taille" id="taille" class="form-select">
                                        <option value="">Choisir...</option>
                                        <option {{ $item->taille=='GRANDE'?'selected':'' }} value="GRANDE">GRANDE</option>
                                        <option {{ $item->taille=='MOYENNE'?'selected':'' }} value="MOYENNE">MOYENNE</option>
                                        <option {{ $item->taille=='PETITE'?'selected':'' }} value="PETITE">PETITE</option>
                                        <option {{ $item->taille=='TRES PETITE'?'selected':'' }} value="TRES PETITE">TRES PETITE</option>
                                        <option {{ $item->taille=='COOPERATIVE'?'selected':'' }} value="COOPERATIVE">COOPERATIVE</option>
                                        <option {{ $item->taille=='ASSOCIATION'?'selected':'' }} value="ASSOCIATION">ASSOCIATION</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label d-block">Caractère</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input id="caractere-formel" class="form-check-input" type="radio" name="caractere" value="Formel" {{ $item->caractere=='Formel'?'checked':'' }}>
                                            <label for="caractere-formel" class="form-check-label">Formel</label>
                                        </div>
                                        <div class="form-check">
                                            <input id="caractere-informel" class="form-check-input" type="radio" name="caractere" value="Informel" {{ $item->caractere=='Informel'?'checked':'' }}>
                                            <label for="caractere-informel" class="form-check-label">Informel</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="forme_id" class="form-label">Forme juridique</label>
                                    <select required name="forme_id" id="forme_id" class="form-select">
                                        <option value="">Choisir...</option>
                                        @foreach ($formes as $it)
                                            <option {{ $item->forme_id==$it->id?'selected':'' }} value="{{ $it->id }}">{{ $it->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">Système comptable</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input id="systeme-normal" class="form-check-input" type="radio" name="systeme" value="Normal" {{ ($item->systeme ?? 'Normal')=='Normal'?'checked':'' }}>
                                            <label for="systeme-normal" class="form-check-label">Normal</label>
                                        </div>
                                        <div class="form-check">
                                            <input id="systeme-minimal" class="form-check-input" type="radio" name="systeme" value="Minimal" {{ ($item->systeme ?? '')=='Minimal'?'checked':'' }}>
                                            <label for="systeme-minimal" class="form-check-label">Minimal</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="capital" class="form-label">Capital social</label>
                                    <input required type="number" id="capital" value="{{ $item->capital }}" placeholder="Capital social" name="capital" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="dt_creation" class="form-label">Date de création formelle</label>
                                    <input required type="date" id="dt_creation" value="{{ $item->dt_creation }}" name="dt_creation" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="dt_start" class="form-label">Date début des activités</label>
                                    <input required type="date" id="dt_start" value="{{ $item->dt_start }}" name="dt_start" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label for="ressources_propres" class="form-label">Ressources propres</label>
                                    <input required type="number" id="ressources_propres" value="{{ $item->ressources_propres }}" name="ressources_propres" placeholder="Ressources propres" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="total_actif" class="form-label">Total actif</label>
                                    <input required type="number" id="total_actif" name="total_actif" value="{{ $item->total_actif }}" placeholder="Total actif (bilan)" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Type de personnel</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <div class="form-check">
                                            <input id="type-permanent" class="form-check-input" type="radio" name="type_personnel" value="permanent" {{ ($item->personnel_permanent ?? 1)?'checked':'' }}>
                                            <label for="type-permanent" class="form-check-label">Permanent</label>
                                        </div>
                                        <div class="form-check">
                                            <input id="type-saisonier" class="form-check-input" type="radio" name="type_personnel" value="saisonier" {{ ($item->personnel_saisonier ?? 0)?'checked':'' }}>
                                            <label for="type-saisonier" class="form-check-label">Saisonnier</label>
                                        </div>
                                        <div class="form-check">
                                            <input id="type-mixte" class="form-check-input" type="radio" name="type_personnel" value="mixte" {{ ($item->personnel_mixte ?? 0)?'checked':'' }}>
                                            <label for="type-mixte" class="form-check-label">Mixte</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="nb_personnel" class="form-label">Nombre d'employés</label>
                                    <input required type="number" id="nb_personnel" value="{{ $item->nb_personnel }}" name="nb_personnel" placeholder="Nombre d'employés" class="form-control">
                                </div>
                            </div>

                            <fieldset class="mt-4 pt-3 border-top">
                                <legend class="fs-6 fw-semibold text-muted">Infos du dirigeant</legend>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-8">
                                        <label for="manager" class="form-label">Nom</label>
                                        <input required type="text" id="manager" value="{{ $item->manager }}" placeholder="Nom et prénom du dirigeant" name="manager" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label d-block">Sexe</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input id="manager-male" class="form-check-input" type="radio" name="manager_sexe" value="Homme" {{ ($item->manager_sexe ?? 'Homme')=='Homme'?'checked':'' }}>
                                                <label for="manager-male" class="form-check-label">Homme</label>
                                            </div>
                                            <div class="form-check">
                                                <input id="manager-femme" class="form-check-input" type="radio" name="manager_sexe" value="Femme" {{ ($item->manager_sexe ?? '')=='Femme'?'checked':'' }}>
                                                <label for="manager-femme" class="form-check-label">Femme</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-4">
                                        <label for="manager_contact" class="form-label">Contact</label>
                                        <input required type="text" id="manager_contact" value="{{ $item->manager_contact }}" placeholder="Contact du dirigeant" name="manager_contact" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="manager_niveau" class="form-label">Niveau d'instruction</label>
                                        <select required name="manager_niveau" id="manager_niveau" class="form-select">
                                            <option value="">Choisir...</option>
                                            <option {{ $item->manager_niveau=="Supérieur"?'selected':'' }} value="Supérieur">Supérieur</option>
                                            <option {{ $item->manager_niveau=="Secondaire"?'selected':'' }} value="Secondaire">Secondaire</option>
                                            <option {{ $item->manager_niveau=="Primaire"?'selected':'' }} value="Primaire">Primaire</option>
                                            <option {{ $item->manager_niveau=="Sans niveau"?'selected':'' }} value="Sans niveau">Sans niveau</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="manager_dtn" class="form-label">Date de naissance</label>
                                        <input required type="date" id="manager_dtn" name="manager_dtn" value="{{ $item->manager_dtn }}" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label d-block">Promoteur ?</label>
                                        <div class="d-flex gap-2">
                                            <div class="form-check">
                                                <input id="mp-1" class="form-check-input" type="radio" name="manager_promoteur" value="1" {{ ($item->manager_promoteur ?? 1)?'checked':'' }}>
                                                <label for="mp-1" class="form-check-label">Oui</label>
                                            </div>
                                            <div class="form-check">
                                                <input id="mp-2" class="form-check-input" type="radio" name="manager_promoteur" value="0" {{ ($item->manager_promoteur ?? 1)==0?'checked':'' }}>
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
                                                <input name="produit_year_start" id="produit_year_start" value="{{ $item->produit_year_start }}" class="form-control" type="number" placeholder="Nombre d'années">
                                            </div>
                                        </div>
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
                                <i class="demo-psi-map me-2"></i>Étape 3: Localisation / Contact
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
                                                <input name="phone" id="phone" value="{{ $item->phone }}" class="form-control" type="text" placeholder="Numéro de téléphone">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Email</label>
                                                <input name="email" id="email" value="{{ $item->email }}" class="form-control" type="email" placeholder="Adresse email">
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
    });
</script>
<script src="{{ asset('dropdowncombotree/comboTreePlugin.js') }}"></script>
<script>
    $.expr[':'].icontains = function (obj, index, meta, stack) { return (obj.textContent || obj.innerText || jQuery(obj).text() || '').toLowerCase().indexOf(meta[3].toLowerCase()) >= 0; };
</script>
<script>
    var _url = "{{ route('util.produits.list') }}";
    var combo;
    var items = [];
    var anfs = [];
    var afs = [];

    $(document).ready(function($){
        $.ajax({
            'url': "{{ route('util.entreprise.create.data') }}",
            'type': 'get',
            'dataType': 'json',
            success: function(arr){
                var arrond = $('#arrondissement_id').comboTree({
                    source: arr.localites,
                    collapse: true,
                    isMultiple: false,
                    editable: true,
                });
                $('.ct-arrow-btn').html('<i class="pli-arrow-down"></i>');
                arrond.onChange(function(){
                    var elt = arrond._selectedItem;
                    if (elt) $('#arr_id').val(elt.id);
                });
            }
        });
    });
</script>
@endsection
