@extends('Layouts.analyste')

@push('styles')
@include('partials.summernote-fr-styles')
@endpush

@section('title', 'Dossier - ' . ($item->entreprise?->name ?? 'Instruction'))
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('analyste.dossiers.index') }}">Dossiers d'instruction</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $item->entreprise?->name ?? $item->name ?? 'Dossier' }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown button-id="analysteDossierShowActions">
        @if($item->entreprise)
            <li><a class="dropdown-item" href="{{ route('analyste.entreprise.get.engagements', $item->entreprise->token) }}"><i class="demo-psi-file-text-image me-2"></i> État des engagements</a></li>
            <li><hr class="dropdown-divider"></li>
        @endif
        @if($item->analyste_id && ! $item->isInstructionSubmittedToExploitation())
            <li><h6 class="dropdown-header text-uppercase small text-muted px-3 mb-0">Saisie analyse financière</h6></li>
            @foreach(\App\Models\Dossier::EXPLOITATION_AF_INSTRUCTION_SECTIONS as $column => $label)
                <li>
                    <a class="dropdown-item" href="#af-saisie-{{ $loop->iteration }}">
                        <span class="text-body-secondary me-1">{{ $loop->iteration }}.</span>{{ $label }}
                    </a>
                </li>
            @endforeach
            <li><hr class="dropdown-divider"></li>
        @endif
        <li>
            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#dossierPieceUploadModal_analyste">
                <i class="demo-psi-upload me-2"></i> Ajouter une pièce au dossier
            </button>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Dossier d'instruction</h5>
        <p class="lead mb-0">{{ $item->entreprise?->name }} — {{ $item->programmesLabel() }}</p>
        @if($item->exploitation_analyste_assigned_at)
            <p class="small text-muted mb-0 mt-2">
                <span class="text-uppercase fw-semibold">Cotation du dossier</span> —
                le {{ $item->exploitation_analyste_assigned_at->format('d/m/Y') }} à {{ $item->exploitation_analyste_assigned_at->format('H:i') }}
                @if($item->exploitationAnalysteAssignedBy)
                    par <strong>{{ $item->exploitationAnalysteAssignedBy->name }}</strong>
                @endif
            </p>
        @endif
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
    @error('submission')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    {{-- Résumé du dossier --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase d-block mb-1">Entreprise</small>
                    <p class="mb-0 fw-semibold">{{ $item->entreprise?->name ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase d-block mb-1">Programme(s)</small>
                    <p class="mb-0 fw-semibold small">{{ $item->programmesLabel() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 text-center">
                    <small class="text-muted text-uppercase d-block mb-1">Note</small>
                    <p class="mb-0 fw-bold fs-5">{{ $item->note ?? '—' }}</p>
                </div>
            </div>
        </div>
        @if($sme)
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm border-start border-3 border-primary h-100">
                    <div class="card-body py-3">
                        <small class="text-muted text-uppercase d-block mb-1">Notation PME</small>
                        <p class="mb-0 fw-semibold">{{ $sme->name ?? $sme['name'] ?? '—' }}</p>
                        @if(isset($sme->mention) || isset($sme['mention']))
                            <small class="text-muted">{{ $sme->mention ?? $sme['mention'] }}</small>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    @include('partials.dossier-engagements-totaux-cards', ['dossier' => $item])

    @include('partials.instruction-dossier-consultation', ['dossier' => $item, 'instructionConsultation' => $instructionConsultation ?? null])

    @include('partials.dossier-pieces-jointes', [
        'dossier' => $item,
        'routePiecesStore' => 'analyste.dossier.pieces.store',
        'modalId' => 'dossierPieceUploadModal_analyste',
        'fichierTypes' => $fichierTypes ?? collect(),
    ])

    <div class="row g-3">
        {{-- Colonne gauche : DSF et notation PME --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-upload me-2 text-primary"></i>Import DSF</h6>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data" action="{{ route('analyste.dossier.dsf') }}" method="post">
                        @csrf
                        <input type="hidden" name="dossier_id" value="{{ $item->id }}">
                        <div class="mb-3">
                            <label class="form-label">Année N</label>
                            <input type="number" required name="annee" class="form-control" placeholder="Ex: 2024" min="2000" max="2100">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fichier DSF</label>
                            <input type="file" name="upload" class="form-control" accept=".xlsx,.xls,.csv">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="demo-psi-check me-2"></i> Enregistrer
                        </button>
                    </form>
                </div>
            </div>

            @if($sme)
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="mb-0 fw-semibold"><i class="demo-psi-information me-2 text-primary"></i>Notation PME</h6>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-semibold">{{ $sme->name ?? $sme['name'] }}</h6>
                        @if(isset($sme->mention) || isset($sme['mention']))
                            <p class="text-muted small mb-2">{{ $sme->mention ?? $sme['mention'] }}</p>
                        @endif
                        @if(isset($sme->description) || isset($sme['description']))
                            <p class="mb-0 small">{{ $sme->description ?? $sme['description'] }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Colonne droite : Grille de notation --}}
        <div class="col-lg-8">
            @include('RoleSpace.dossiers.partials.instruction_grille_notation', [
                'item' => $item,
                'criteres' => $criteres,
                'indicateurs' => $indicateurs,
                'sme' => $sme,
                'readOnly' => false,
            ])
        </div>
    </div>

    @if($item->analyste_id)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-file-edit me-2 text-primary"></i>Saisie analyste financier</h6>
                <p class="small text-muted mb-0 mt-1">Sept rubriques à renseigner (Summernote), en complément de la grille de notation et de l’import DSF. Utilisez le menu <strong>Actions</strong> pour accéder rapidement à chaque zone. <strong>Enregistrer le brouillon</strong> permet de reprendre plus tard. La soumission au responsable exploitation exige les <strong>sept</strong> rubriques complètes.</p>
            </div>
            <div class="card-body pt-0">
                @if($item->isInstructionSubmittedToExploitation())
                    <p class="small text-success mb-2">
                        <strong>Transmission au responsable exploitation (analyste financier)</strong> — le {{ $item->exploitation_analyste_transmitted_to_exploitation_at->format('d/m/Y à H:i') }}
                        @if($item->exploitationAnalysteTransmittedToExploitationBy)
                            — {{ $item->exploitationAnalysteTransmittedToExploitationBy->name }}
                        @endif
                    </p>
                    <p class="small text-muted mb-3">Les rubriques ci-dessous ne sont plus modifiables après soumission.</p>
                    @foreach(\App\Models\Dossier::EXPLOITATION_AF_INSTRUCTION_SECTIONS as $column => $label)
                        <div class="mb-4 pb-3 border-bottom border-light-subtle">
                            <h6 class="form-label fw-semibold mb-2 text-body-secondary">{{ $loop->iteration }}. {{ $label }}</h6>
                            @if($item->afInstructionSectionHasSubstance($column))
                                <div class="rich-text-rendered small border rounded p-3 bg-light">{!! $item->{$column} !!}</div>
                            @else
                                <p class="text-muted small mb-0">—</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <form id="form-analyste-af-sections" method="post" class="mb-0">
                        @csrf
                        @foreach(\App\Models\Dossier::EXPLOITATION_AF_INSTRUCTION_SECTIONS as $column => $label)
                            <div id="af-saisie-{{ $loop->iteration }}" class="mb-4 pb-2 border-bottom border-light-subtle">
                                <label class="form-label fw-semibold" for="af_editor_{{ $column }}">{{ $loop->iteration }}. {{ $label }}</label>
                                <div class="summernote-wrapper">
                                    <textarea
                                        name="{{ $column }}"
                                        id="af_editor_{{ $column }}"
                                        class="form-control js-af-instruction-summernote"
                                        rows="6"
                                        data-placeholder="{{ e($label) }}…"
                                    >{!! old($column, $item->{$column} ?? '') !!}</textarea>
                                </div>
                            </div>
                        @endforeach
                        @if($item->exploitation_analyste_instruction_avis_saved_at)
                            <p class="small text-muted mb-3 mb-md-2">Dernière sauvegarde du brouillon : le {{ $item->exploitation_analyste_instruction_avis_saved_at->format('d/m/Y à H:i') }}.</p>
                        @endif
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <button type="submit" formaction="{{ route('analyste.dossiers.instruction-avis-brouillon', $item) }}" class="btn btn-outline-primary">
                                Enregistrer le brouillon
                            </button>
                            <button type="submit" formaction="{{ route('analyste.dossiers.soumettre-exploitation', $item) }}" class="btn btn-success" id="btn-soumettre-rexp-analyste">
                                Soumettre au responsable exploitation pour validation
                            </button>
                        </div>
                        <p class="small text-muted mt-2 mb-0">Les sept rubriques doivent contenir du texte significatif avant soumission au responsable exploitation.</p>
                    </form>
                @endif
            </div>
        </div>
    @endif

@endsection

@section('modal')
<div class="modal fade" id="critereModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choix de la valeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('analyste.instruction.critere.reponse') }}" method="post">
                    @csrf
                    <input type="hidden" id="_dossier_id" name="dossier_id">
                    <input type="hidden" id="_programme_id" name="programme_id">
                    <input type="hidden" id="critere_id_input" name="critere_id">
                    <div class="mb-3">
                        <label id="critere_name" class="form-label"></label>
                        <select required name="choice_id" id="critere_choices" class="form-select"></select>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-critere').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = "{{ route('analyste.instruction.critere.choices') }}";
            var name = this.dataset.name;
            var dossierId = this.dataset.dossier_id;
            var programmeId = this.dataset.programme_id;
            var id = this.dataset.id;

            document.getElementById('critere_name').textContent = name;
            document.getElementById('_dossier_id').value = dossierId;
            document.getElementById('_programme_id').value = programmeId;
            document.getElementById('critere_id_input').value = id;

            fetch(url + '?id=' + id)
                .then(r => r.json())
                .then(function(data) {
                    var select = document.getElementById('critere_choices');
                    select.innerHTML = '<option value="">Choisir...</option>';
                    data.forEach(function(choice) {
                        var opt = document.createElement('option');
                        opt.value = choice.id;
                        opt.textContent = choice.valeur;
                        select.appendChild(opt);
                    });
                });
        });
    });
});
</script>
@endsection

@section('script')
<style>
.table-notation th { font-weight: 600; }
.table-notation .vertical-align { vertical-align: middle !important; text-align: center; }
</style>
@if($item->analyste_id && ! $item->isInstructionSubmittedToExploitation())
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-fr-FR.min.js"></script>
<script>
(function ($) {
    function baseOptions(height, placeholder) {
        return {
            lang: 'fr-FR',
            height: height,
            dialogsInBody: true,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'hr']],
                ['view', ['codeview']]
            ],
            placeholder: placeholder || '…',
            callbacks: {
                onChange: function (contents) {
                    $(this).val(contents);
                }
            }
        };
    }
    function syncSummernoteToTextarea($ta) {
        if ($ta.length && $ta.next('.note-editor').length) {
            $ta.val($ta.summernote('code'));
        }
    }
    function avisHasSubstance(html) {
        var t = $('<div>').html(html || '').text().replace(/\u00a0/g, ' ').trim();
        return t.length > 0;
    }
    jQuery(window).on('load', function () {
        var $editors = $('.js-af-instruction-summernote');
        if (!$editors.length) return;
        $editors.each(function () {
            var $ta = $(this);
            var ph = $ta.data('placeholder') || '';
            $ta.summernote($.extend({}, baseOptions(220, ph)));
        });
        var $form = $('#form-analyste-af-sections');
        var $submit = $('#btn-soumettre-rexp-analyste');
        function refreshSubmitState() {
            $editors.each(function () { syncSummernoteToTextarea($(this)); });
            var allSeven = true;
            $editors.each(function () {
                if (!avisHasSubstance($(this).val())) {
                    allSeven = false;
                }
            });
            $submit.prop('disabled', !allSeven);
        }
        $editors.on('summernote.change', refreshSubmitState);
        refreshSubmitState();
        $form.find('button[type="submit"]').on('mousedown', function () {
            $editors.each(function () { syncSummernoteToTextarea($(this)); });
        });
        $form.on('submit', function (e) {
            // Toujours synchroniser Summernote → textarea avant envoi (brouillon ou soumission).
            $editors.each(function () { syncSummernoteToTextarea($(this)); });
            var target = e.originalEvent && e.originalEvent.submitter;
            var action = target && (target.getAttribute('formaction') || '');
            if (action.indexOf('soumettre-exploitation') === -1) {
                return true;
            }
            var allSeven = true;
            $editors.each(function () {
                if (!avisHasSubstance($(this).val())) {
                    allSeven = false;
                }
            });
            if (!allSeven) {
                e.preventDefault();
                return false;
            }
        });
    });
})(window.jQuery);
</script>
@endif
@endsection
