@extends('Layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('dropdowncombotree/comboTreeStyle.css') }}">
<link rel="stylesheet" href="{{ asset('jquery-easyui/themes/default/easyui.css') }}">
<link rel="stylesheet" href="{{ asset('jquery-easyui/themes/icon.css') }}">
<style>
    .prog-create-wrap { max-width: 56rem; margin-left: auto; margin-right: auto; }
    .prog-create-steps {
        display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center;
        list-style: none; padding: 0; margin: 0 0 1.5rem;
    }
    .prog-create-steps li {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-size: 0.875rem; font-weight: 600;
        color: #64748b;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
    }
    .prog-create-steps .step-num {
        display: inline-flex; align-items: center; justify-content: center;
        width: 1.5rem; height: 1.5rem; border-radius: 50%;
        font-size: 0.75rem; background: #cbd5e1; color: #334155;
    }
    .prog-create-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        background: #fff;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
    }
    .prog-create-card__head {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
        font-weight: 700;
        font-size: 0.95rem;
        color: #0f172a;
    }
    .prog-create-card__body { padding: 1.25rem 1.25rem 1.5rem; }
    .prog-create-label { font-weight: 600; font-size: 0.875rem; color: #334155; margin-bottom: 0.35rem; }
    .prog-create-hint { font-size: 0.75rem; color: #64748b; margin-bottom: 0.35rem; }
    .prog-create-check-grid {
        display: flex; flex-wrap: wrap; gap: 0.5rem 1.25rem;
    }
    .prog-create-check-grid .form-check { margin-bottom: 0; min-width: 8rem; }
    .prog-create-tree-box { max-width: 100%; }
    @media (min-width: 768px) {
        .prog-create-tree-box { max-width: 36rem; }
    }
    .prog-create-selected-title {
        font-size: 0.8125rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;
        color: #64748b; margin: 1.25rem 0 0.5rem;
    }
    .prog-create-selected-list { min-height: 2.5rem; }
    .prog-create-selected-list .list-group-item {
        border-radius: 0.5rem !important; margin-bottom: 0.35rem; border: 1px solid #e2e8f0;
    }
    .prog-create-footer {
        display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: flex-end;
        padding-top: 1.25rem; margin-top: 1.25rem; border-top: 1px solid #e2e8f0;
    }
</style>
@endpush

@php
    $fmtDate = fn ($v) => $v ? \Carbon\Carbon::parse($v)->format('Y-m-d') : '';
    $pmSel = array_filter(explode('-', (string) ($item->type_pm ?? '')));
    $ppSel = array_filter(explode('-', (string) ($item->type_pp ?? '')));
@endphp

@section('title', 'Modifier le programme')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.programmes.index') }}">Programmes</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.programmes.show', $item->token) }}">{{ Str::limit($item->name, 40) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Modifier</li>
    </ol>
</nav>
@endsection

@section('actions')
    <a href="{{ route('admin.programmes.show', $item->token) }}" class="btn btn-outline-secondary btn-sm">Fiche programme</a>
    <a href="{{ route('admin.programmes.index') }}" class="btn btn-outline-secondary btn-sm">Liste</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Modifier le programme</h5>
        <p class="text-body-secondary mb-0 mt-1 small">Même parcours que la création : identification, secteurs et bailleurs, appuis proposés.</p>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 p-md-4 p-lg-5">
            <ol class="prog-create-steps" aria-label="Étapes">
                <li><span class="step-num" aria-hidden="true">1</span> Identification & budgets</li>
                <li><span class="step-num" aria-hidden="true">2</span> Secteurs & bailleurs</li>
                <li><span class="step-num" aria-hidden="true">3</span> Appuis proposés</li>
            </ol>

            <form action="{{ route('admin.programmes.save') }}" method="post" id="programme-edit-form" class="prog-create-wrap" novalidate>
                @csrf
                <input type="hidden" name="id" value="{{ $item->id }}">
                <input type="hidden" id="appuisnf" name="appuisnf" value="{{ implode(',', $anfIds) }}">
                <input type="hidden" id="appuisf" name="appuisf" value="{{ implode(',', $afsIds) }}">
                <input type="hidden" id="prods" name="produits" value="{{ implode(',', $produitIds) }}">
                <input type="hidden" id="organismes" name="bailleurs" value="{{ implode(',', $organismeIds) }}">

                <section data-step="step-1" class="mb-0">
                    <div class="prog-create-card mb-4">
                        <div class="prog-create-card__head">Identification de la convention</div>
                        <div class="prog-create-card__body">
                            <div class="row g-3">
                                <div class="col-12 col-xl-6">
                                    <label class="prog-create-label" for="name">Dénomination <span class="text-danger">*</span></label>
                                    <input required type="text" id="name" name="name" class="form-control" value="{{ old('name', $item->name) }}" placeholder="Nom du programme" autocomplete="off">
                                </div>
                                <div class="col-12 col-xl-6">
                                    <label class="prog-create-label" for="convention">N° référence convention cadre <span class="text-danger">*</span></label>
                                    <input required type="text" id="convention" name="convention" class="form-control" value="{{ old('convention', $item->convention) }}" placeholder="Référence officielle" autocomplete="off">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="prog-create-label" for="dt_sig_conv">Date de signature <span class="text-danger">*</span></label>
                                    <input required type="date" id="dt_sig_conv" name="dt_sig_conv" class="form-control" value="{{ old('dt_sig_conv', $fmtDate($item->dt_sig_conv)) }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="prog-create-label" for="signataire">Institution signataire <span class="text-danger">*</span></label>
                                    <input required type="text" id="signataire" name="signataire" class="form-control" value="{{ old('signataire', $item->signataire) }}" placeholder="Institution nationale signataire">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="prog-create-card mb-4">
                        <div class="prog-create-card__head">Budgets (XAF)</div>
                        <div class="prog-create-card__body">
                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label class="prog-create-label" for="budget_af">Appuis financiers <span class="text-danger">*</span></label>
                                    <input required type="number" min="0" step="1" id="budget_af" name="budget_af" class="form-control" value="{{ old('budget_af', $item->budget_af ?? 0) }}">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="prog-create-label" for="budget_anf">Appuis non financiers <span class="text-danger">*</span></label>
                                    <input required type="number" min="0" step="1" id="budget_anf" name="budget_anf" class="form-control" value="{{ old('budget_anf', $item->budget_anf ?? 0) }}">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="prog-create-label" for="budget_coord">Coordination <span class="text-danger">*</span></label>
                                    <input required type="number" min="0" step="1" id="budget_coord" name="budget_coord" class="form-control" value="{{ old('budget_coord', $item->budget_coord ?? 0) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="prog-create-card mb-4">
                        <div class="prog-create-card__head">Bénéficiaires cibles — personnes morales</div>
                        <div class="prog-create-card__body">
                            <p class="prog-create-hint mb-2">Cochez les catégories éligibles (au moins une si applicable).</p>
                            <div class="prog-create-check-grid">
                                @foreach (['GRANDE' => 'Grande', 'MOYENNE' => 'Moyenne', 'PETITE' => 'Petite', 'TRES PETITE' => 'Très petite', 'COOPERATIVE' => 'Coopérative', 'ASSOCIATION' => 'Association'] as $val => $lib)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="type_entreprise[]" id="te_edit_{{ $loop->index }}" value="{{ $val }}" @checked(in_array($val, $pmSel, true))>
                                        <label class="form-check-label" for="te_edit_{{ $loop->index }}">{{ $lib }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="prog-create-card mb-4">
                        <div class="prog-create-card__head">Bénéficiaires cibles — personnes physiques</div>
                        <div class="prog-create-card__body">
                            <div class="prog-create-check-grid">
                                @foreach (['Homme' => 'Hommes', 'Femme' => 'Femmes', 'Jeune' => 'Jeunes', 'Personnes vulnérables' => 'Personnes vulnérables'] as $val => $lib)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="type_personne[]" id="tp_edit_{{ $loop->index }}" value="{{ $val }}" @checked(in_array($val, $ppSel, true))>
                                        <label class="form-check-label" for="tp_edit_{{ $loop->index }}">{{ $lib }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="prog-create-card mb-4">
                        <div class="prog-create-card__head">Calendrier & contact</div>
                        <div class="prog-create-card__body">
                            <div class="row g-3">
                                <div class="col-12 col-md-5">
                                    <label class="prog-create-label" for="dt_start">Début des activités <span class="text-danger">*</span></label>
                                    <input required type="date" id="dt_start" name="dt_start" class="form-control" value="{{ old('dt_start', $fmtDate($item->dt_start)) }}">
                                </div>
                                <div class="col-12 col-md-7">
                                    <label class="prog-create-label" for="contact">Personnes ressources & contact <span class="text-danger">*</span></label>
                                    <input required type="text" id="contact" name="contact" class="form-control" value="{{ old('contact', $item->contact) }}" placeholder="Coordonnées du point de contact programme">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="prog-create-footer">
                        <button type="button" class="btn btn-primary px-4" data-next>Suivant <i class="demo-psi-arrow-right ms-1"></i></button>
                    </div>
                </section>

                <section data-step="step-2" class="mb-0">
                    <div class="prog-create-card mb-4">
                        <div class="prog-create-card__head">Bailleurs de fonds signataires</div>
                        <div class="prog-create-card__body">
                            <label class="prog-create-label" for="bf">Rechercher et sélectionner</label>
                            <div class="prog-create-tree-box mb-2">
                                <input id="bf" class="form-control" type="text" autocomplete="off" aria-describedby="bf-help-edit">
                            </div>
                            <p id="bf-help-edit" class="prog-create-hint mb-0">Sélection multiple possible. Les choix apparaissent ci-dessous.</p>
                            <p class="prog-create-selected-title mb-0">Bailleurs sélectionnés</p>
                            <ul id="bailleurs" class="list-group prog-create-selected-list mt-2"></ul>
                        </div>
                    </div>

                    <div class="prog-create-card mb-4">
                        <div class="prog-create-card__head">Secteurs cibles (produits / services)</div>
                        <div class="prog-create-card__body">
                            <label class="prog-create-label" for="ct">Arborescence sectorielle</label>
                            <div class="prog-create-tree-box mb-2">
                                <input id="ct" class="form-control" type="text" autocomplete="off" aria-describedby="ct-help-edit">
                            </div>
                            <p id="ct-help-edit" class="prog-create-hint mb-0">Choisissez les secteurs ou produits concernés.</p>
                            <p class="prog-create-selected-title mb-0">Secteurs sélectionnés</p>
                            <ul id="produits" class="list-group prog-create-selected-list mt-2"></ul>
                        </div>
                    </div>

                    <div class="prog-create-footer">
                        <button type="button" class="btn btn-light border" data-prev><i class="demo-psi-arrow-left me-1"></i> Précédent</button>
                        <button type="button" class="btn btn-primary px-4" data-next>Suivant <i class="demo-psi-arrow-right ms-1"></i></button>
                    </div>
                </section>

                <section data-step="step-3" class="mb-0">
                    <div class="prog-create-card mb-4">
                        <div class="prog-create-card__head">Appuis financiers</div>
                        <div class="prog-create-card__body">
                            <label class="prog-create-label" for="af">Types d'appui</label>
                            <div class="prog-create-tree-box mb-2">
                                <input id="af" class="form-control" type="text" autocomplete="off">
                            </div>
                            <p class="prog-create-selected-title mb-0">Liste des appuis financiers retenus</p>
                            <ul id="afs" class="list-group prog-create-selected-list mt-2"></ul>
                        </div>
                    </div>

                    <div class="prog-create-card mb-4">
                        <div class="prog-create-card__head">Appuis non financiers</div>
                        <div class="prog-create-card__body">
                            <label class="prog-create-label" for="anf">Types d'appui</label>
                            <div class="prog-create-tree-box mb-2">
                                <input id="anf" class="form-control" type="text" autocomplete="off">
                            </div>
                            <p class="prog-create-selected-title mb-0">Liste des appuis non financiers retenus</p>
                            <ul id="anfs" class="list-group prog-create-selected-list mt-2"></ul>
                        </div>
                    </div>

                    <div class="prog-create-footer">
                        <button type="button" class="btn btn-light border" data-prev><i class="demo-psi-arrow-left me-1"></i> Précédent</button>
                        <button type="submit" class="btn btn-success px-4"><i class="demo-psi-check me-1"></i> Enregistrer les modifications</button>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('jquery-easyui/jquery.easyui.min.js') }}"></script>
<script src="{{ asset('dropdowncombotree/comboTreePlugin.js') }}"></script>
<script>
(function () {
    var initialProduitIds = @json($produitIds);
    var initialOrganismeIds = @json($organismeIds);
    var initialAfsIds = @json($afsIds);
    var initialAnfIds = @json($anfIds);

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Zangdar !== 'undefined') {
            new Zangdar('#programme-edit-form');
        }
    });

    $.expr[':'].icontains = function (obj, index, meta) {
        return (obj.textContent || obj.innerText || jQuery(obj).text() || '').toLowerCase().indexOf(meta[3].toLowerCase()) >= 0;
    };

    var combo;
    var bf;
    var items = [];
    var anfs = [];
    var afs = [];
    var organismes = [];

    function syncHiddenFromCombo(tree, hiddenSelector) {
        var ids = tree.getSelectedIds();
        $(hiddenSelector).val(ids && ids.length ? ids.join(',') : '');
    }

    $(document).ready(function () {
        $.ajax({
            url: "{{ route('util.programme.create.data') }}",
            type: 'get',
            dataType: 'json',
            success: function (arr) {
                combo = $('#ct').comboTree({
                    source: arr.produits,
                    collapse: true,
                    isMultiple: true,
                    editable: true
                });
                bf = $('#bf').comboTree({
                    source: arr.organismes,
                    collapse: true,
                    isMultiple: true,
                    editable: true
                });
                var af = $('#af').comboTree({
                    source: arr.afs,
                    collapse: true,
                    isMultiple: true,
                    editable: true
                });
                var anf = $('#anf').comboTree({
                    source: arr.anfs,
                    collapse: true,
                    isMultiple: true,
                    editable: true
                });
                $('.ct-arrow-btn').html('<i class="pli-arrow-down"></i>');

                combo.onChange(function () {
                    items = combo._selectedItems;
                    syncHiddenFromCombo(combo, '#prods');
                    build();
                });

                bf.onChange(function () {
                    organismes = bf._selectedItems;
                    syncHiddenFromCombo(bf, '#organismes');
                    buildBf();
                });

                af.onChange(function () {
                    afs = af._selectedItems;
                    syncHiddenFromCombo(af, '#appuisf');
                    buildAf();
                });

                anf.onChange(function () {
                    anfs = anf._selectedItems;
                    syncHiddenFromCombo(anf, '#appuisnf');
                    buildAnf();
                });

                if (initialProduitIds.length) {
                    combo.setSelection(initialProduitIds);
                    items = combo._selectedItems;
                    syncHiddenFromCombo(combo, '#prods');
                    build();
                }
                if (initialOrganismeIds.length) {
                    bf.setSelection(initialOrganismeIds);
                    organismes = bf._selectedItems;
                    syncHiddenFromCombo(bf, '#organismes');
                    buildBf();
                }
                if (initialAfsIds.length) {
                    af.setSelection(initialAfsIds);
                    afs = af._selectedItems;
                    syncHiddenFromCombo(af, '#appuisf');
                    buildAf();
                }
                if (initialAnfIds.length) {
                    anf.setSelection(initialAnfIds);
                    anfs = anf._selectedItems;
                    syncHiddenFromCombo(anf, '#appuisnf');
                    buildAnf();
                }
            }
        });
    });

    function build() {
        $('#produits').html('');
        items.forEach(function (element) {
            $('#produits').append('<li class="list-group-item">' + element.title + '</li>');
        });
    }

    function buildBf() {
        $('#bailleurs').html('');
        organismes.forEach(function (element) {
            $('#bailleurs').append('<li class="list-group-item">' + element.title + '</li>');
        });
    }

    function buildAnf() {
        $('#anfs').html('');
        anfs.forEach(function (element) {
            $('#anfs').append('<li class="list-group-item">' + element.title + '</li>');
        });
    }

    function buildAf() {
        $('#afs').html('');
        afs.forEach(function (element) {
            $('#afs').append('<li class="list-group-item">' + element.title + '</li>');
        });
    }
})();
</script>
@endsection
