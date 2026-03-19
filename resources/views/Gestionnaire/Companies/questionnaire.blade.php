@extends('Layouts.gestionnaire')

@push('styles')
<link rel="stylesheet" href="{{ asset('dropdowncombotree/comboTreeStyle.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/default/easyui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/icon.css') }}">
@endpush

@section('title', 'Mise en relation - ' . $item->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.show', $item->token) }}">{{ Str::limit($item->name, 30) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Mise en relation</li>
    </ol>
</nav>
@endsection

@section('actions')
    <a href="{{ route('gestionnaire.entreprises.show', $item->token) }}" class="btn btn-outline-secondary btn-sm">
        <i class="demo-psi-arrow-left me-2"></i>Annuler
    </a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Formulaire de mise en relation</h5>
        <p class="text-body-secondary mb-0 mt-1">{{ $item->name }} — Répondez aux critères de mise en relation</p>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div id="my-form">
                        @csrf
                        <input type="hidden" id="id" name="id" value="{{ $item->id }}">
                        <input type="hidden" id="token" name="token" value="{{ $item->token }}">
                        <div class="tab-base tab-vertical">
                            <div class="row g-0">
                                {{-- Navigation des critères --}}
                                <div class="col-md-4 col-lg-3">
                                    <div class="border-end bg-light">
                                        <div class="p-3">
                                            <h6 class="text-muted text-uppercase small mb-3">Critères</h6>
                                            <ul class="nav nav-tabs flex-column border-0" role="tablist">
                                                @foreach ($criteres as $sc)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link text-start rounded {{ $sc->id==1?'active':'' }}" data-bs-toggle="tab" data-bs-target="#_vtab_{{ $sc->id }}" type="button" role="tab">
                                                        <i class="demo-psi-file-edit me-2"></i>{{ $sc->name }}
                                                    </button>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                {{-- Contenu des onglets --}}
                                <div class="col-md-8 col-lg-9">
                                    <div class="tab-content p-4" style="min-height: 50vh;">
                                        @foreach ($criteres as $sc)
                                        <div id="_vtab_{{ $sc->id }}" class="tab-pane fade {{ $sc->id==1?'show active':'' }}" role="tabpanel">
                                            <h5 class="fw-semibold mb-4 text-primary">{{ $sc->name }}</h5>
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width: 55%;">Question</th>
                                                            <th>Réponse</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($sc->questions as $question)
                                                        <tr>
                                                            <td class="py-3">
                                                                <label for="choice_{{ $question->id }}" class="form-label mb-0 fw-normal">{{ $question->name }}</label>
                                                            </td>
                                                            <td class="py-3">
                                                                <select data-critere_id="{{ $sc->critere_id }}" data-sc_id="{{ $sc->id }}" data-question_id="{{ $question->id }}" 
                                                                    name="choice_{{ $question->id }}" id="choice_{{ $question->id }}" 
                                                                    class="form-select form-select-sm choice" style="min-width: 200px;">
                                                                    <option value="0">Choisir...</option>
                                                                    @foreach ($question->choices as $choice)
                                                                    <option data-value="{{ $choice->value }}" value="{{ $choice->id }}" {{ ($reponses[$question->id] ?? 0) == $choice->id ? 'selected' : '' }}>
                                                                        {{ $choice->name }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                    <a href="{{ route('gestionnaire.entreprises.show', $item->token) }}" class="btn btn-link text-muted">Retour au dossier</a>
                    <button id="btn-save" type="button" class="btn btn-primary">
                        <i class="demo-psi-check me-2"></i>Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('jquery-easyui/jquery.easyui.min.js') }}"></script>
<script>
    var _url = "{{ route('gestionnaire.entreprise.questionnaire.save') }}";
    var token = document.getElementById('token').value;

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('btn-save').addEventListener('click', function() {
            var resps = [];
            var id = document.getElementById('id').value;
            var _token = document.querySelector('input[name="_token"]').value;

            document.querySelectorAll('.choice').forEach(function(sel) {
                var opt = sel.options[sel.selectedIndex];
                var val = opt ? parseFloat(opt.dataset.value) || 0 : 0;
                var choiceId = parseInt(sel.value) || 0;
                if (choiceId !== 0) {
                    resps.push({
                        critere_id: sel.dataset.critere_id,
                        sous_critere_id: sel.dataset.sc_id,
                        question_id: sel.dataset.question_id,
                        choice_id: choiceId,
                        entreprise_id: id,
                        value: val
                    });
                }
            });

            fetch(_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': _token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ choices: resps, _token: _token })
            })
            .then(function(r) { return r.json(); })
            .then(function() {
                window.location.replace('/gestionnaire/entreprises/' + token);
            })
            .catch(function(err) {
                console.error(err);
                alert('Une erreur est survenue lors de l\'enregistrement.');
            });
        });
    });
</script>
@endsection
