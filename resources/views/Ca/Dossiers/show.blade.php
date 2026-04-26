@extends('Layouts.ca')

@push('styles')
<style>
    .table-notation th { font-weight: 600; }
    .table-notation .vertical-align { vertical-align: middle !important; text-align: center; }
    .ca-dossier-show__notation-body { max-height: min(78vh, 42rem); overflow: auto; }
</style>
@if($item->isInstructionValidatedByAgence() && ! $item->isInstructionCaTransmittedToExploitation())
    @include('partials.summernote-fr-styles')
@endif
@endpush

@section('title', 'Dossier - ' . ($item->entreprise?->name ?? 'Instruction'))
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.dossiers.index') }}">Dossiers d'instruction</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $item->entreprise?->name ?? $item->name ?? 'Dossier' }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown button-id="caDossierShowActions" menu-class="dropdown-menu dropdown-menu-end border shadow-sm py-2">
        @if(($canApproveRejectInstructionTransmission ?? false) && $item->isInstructionPendingAgenceValidation())
            <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#caInstructionTransmissionApproveModal"><i class="demo-psi-check me-2 text-success"></i> Valider la transmission</button></li>
            <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#caInstructionTransmissionRejectModal"><i class="demo-psi-cross me-2 text-danger"></i> Rejeter la transmission</button></li>
            <li><hr class="dropdown-divider"></li>
        @endif
        <li><a class="dropdown-item" href="{{ route('ca.dossier.analyse-critique.synthese', $item->token) }}"><i class="demo-psi-file-text me-2"></i> Dossier d’analyse critique</a></li>
        <li><a class="dropdown-item" href="{{ route('ca.dossier.analyse-critique.synthese.pdf', $item->token) }}" target="_blank" rel="noopener"><i class="demo-psi-download me-2"></i> Exporter le dossier en PDF</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#dossierPieceUploadModal_ca">
                <i class="demo-psi-upload me-2"></i> Ajouter une pièce au dossier
            </button>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Dossier d'instruction</h5>
        <p class="text-body-secondary mb-0">{{ $item->entreprise?->name }} — {{ $item->programmesLabel() }}</p>
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

    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">

    {{-- Cartes récapitulatives --}}
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
                    <p class="mb-0 fw-bold fs-5">{{ $item->note ?? $item['note'] ?? '—' }}</p>
                </div>
            </div>
        </div>
        @if($sme ?? null)
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
        'routePiecesStore' => 'ca.dossier.pieces.store',
        'modalId' => 'dossierPieceUploadModal_ca',
        'fichierTypes' => $fichierTypes ?? collect(),
    ])

    <div class="row g-3">
        {{-- Colonne gauche : Résumé et notation PME --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-information me-2 text-primary"></i>Résumé du dossier</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 g-2">
                        <dt class="col-sm-5 text-muted small">Entreprise</dt>
                        <dd class="col-sm-7">{{ $item->entreprise?->name ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Programme(s)</dt>
                        <dd class="col-sm-7">{{ $item->programmesLabel() }}</dd>
                        @if($item->chef_filiere_submitted_to_agence_at)
                            <dt class="col-sm-5 text-muted small">Transmis à l’agence</dt>
                            <dd class="col-sm-7">
                                {{ $item->chef_filiere_submitted_to_agence_at->format('d/m/Y H:i') }}
                                @if($item->chefFiliereSubmittedToAgenceBy)
                                    — {{ $item->chefFiliereSubmittedToAgenceBy->name }}
                                @endif
                            </dd>
                        @endif
                        @if($item->instruction_agence_validated_at)
                            <dt class="col-sm-5 text-muted small">Validé agence</dt>
                            <dd class="col-sm-7">
                                {{ $item->instruction_agence_validated_at->format('d/m/Y H:i') }}
                                @if($item->instructionAgenceValidatedBy) — {{ $item->instructionAgenceValidatedBy->name }} @endif
                            </dd>
                        @elseif($item->instruction_agence_rejected_at)
                            <dt class="col-sm-5 text-muted small">Rejet agence</dt>
                            <dd class="col-sm-7 text-danger">
                                {{ $item->instruction_agence_rejected_at->format('d/m/Y H:i') }}
                                @if($item->instructionAgenceRejectedBy) — {{ $item->instructionAgenceRejectedBy->name }} @endif
                            </dd>
                        @endif
                        @if($item->instruction_ca_transmitted_to_exploitation_at)
                            <dt class="col-sm-5 text-muted small">Transmission REXP</dt>
                            <dd class="col-sm-7">
                                {{ $item->instruction_ca_transmitted_to_exploitation_at->format('d/m/Y H:i') }}
                                @if($item->instructionCaTransmittedToExploitationBy) — {{ $item->instructionCaTransmittedToExploitationBy->name }} @endif
                            </dd>
                        @endif
                        <dt class="col-sm-5 text-muted small">Engagements sollicités</dt>
                        <dd class="col-sm-7">{{ $item->engagements_sollicites_total !== null ? number_format((float) $item->engagements_sollicites_total, 0, ',', ' ').' XAF' : '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Engagements en cours</dt>
                        <dd class="col-sm-7">{{ $item->engagements_en_cours_total !== null ? number_format((float) $item->engagements_en_cours_total, 0, ',', ' ').' XAF' : '—' }}</dd>
                    </dl>
                </div>
            </div>

            @if($sme ?? null)
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
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-bar-chart me-2 text-primary"></i>Grille de notation</h6>
                </div>
                <div class="card-body ca-dossier-show__notation-body">
                    @if(count($indicateurs ?? []))
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-notation align-middle">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Critère principal</th>
                                        <th>N°</th>
                                        <th>%</th>
                                        <th>Sous-critère</th>
                                        <th>Valeur</th>
                                        <th>Note</th>
                                        <th>Pondérée</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($criteres[0]))
                                        <tr>
                                            <th class="vertical-align bg-white" rowspan="{{ count($criteres[0]['souscriteres'] ?? []) + 1 }}">{{ $criteres[0]['name'] }}</th>
                                        </tr>
                                        @foreach($criteres[0]['souscriteres'] ?? [] as $sc)
                                            <tr>
                                                <td>{{ $sc['sequence'] ?? '-' }}</td>
                                                <td>{{ $sc['default'] ?? 0 }}%</td>
                                                <td>{{ $sc['name'] ?? '-' }}</td>
                                                <td>
                                                    {{ isset($sc['reponse']['choice']) ? $sc['reponse']['choice']['valeur'] : '-' }}
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] ?? '' }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] ?? '' }}" class="btn btn-sm btn-link p-0 ms-1 btn-critere" title="Modifier"><i class="demo-psi-pen-5"></i></button>
                                                </td>
                                                <td>{{ isset($sc['reponse']['choice']) ? $sc['reponse']['note'] : '-' }}</td>
                                                <td>{{ isset($sc['reponse']['choice']) ? round($sc['reponse']['note'] * ($sc['default'] ?? 0) / 100, 2) : '-' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <td colspan="4"></td>
                                            <th colspan="2">Note critère pondérée</th>
                                            <th>{{ $criteres[0]['note'] ?? 0 }}</th>
                                        </tr>
                                    @endif

                                    @if(isset($criteres[1]))
                                        <tr>
                                            <th class="vertical-align bg-white" rowspan="{{ count($criteres[1]['souscriteres'] ?? []) + 1 }}">{{ $criteres[1]['name'] }}</th>
                                        </tr>
                                        @foreach($criteres[1]['souscriteres'] ?? [] as $sc)
                                            <tr>
                                                <td>{{ $sc['sequence'] ?? '-' }}</td>
                                                <td>{{ $sc['default'] ?? 0 }}%</td>
                                                <td>{{ $sc['name'] ?? '-' }}</td>
                                                <td>
                                                    {{ isset($sc['reponse']['choice']) ? $sc['reponse']['choice']['valeur'] : '-' }}
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] ?? '' }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] ?? '' }}" class="btn btn-sm btn-link p-0 ms-1 btn-critere" title="Modifier"><i class="demo-psi-pen-5"></i></button>
                                                </td>
                                                <td>{{ isset($sc['reponse']['choice']) ? $sc['reponse']['note'] : '-' }}</td>
                                                <td>{{ isset($sc['reponse']['choice']) ? round($sc['reponse']['note'] * ($sc['default'] ?? 0) / 100, 2) : '-' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <td colspan="4"></td>
                                            <th colspan="2">Note critère pondérée</th>
                                            <th>{{ $criteres[1]['note'] ?? 0 }}</th>
                                        </tr>
                                    @endif

                                    @if(isset($indicateurs[0]['notation']['details']))
                                        <tr>
                                            <th class="vertical-align bg-white" rowspan="{{ count($indicateurs[0]['notation']['details']) + 1 }}">Finance</th>
                                        </tr>
                                        @foreach($indicateurs[0]['notation']['details'] as $sc)
                                            <tr>
                                                <td>{{ $sc['sequence'] ?? '-' }}</td>
                                                <td>{{ $sc['pourcentage'] ?? 0 }}%</td>
                                                <td>{{ $sc['critere'] ?? '-' }}</td>
                                                <td>{{ $sc['valeur'] ?? '-' }}</td>
                                                <td>{{ $sc['note'] ?? '-' }}</td>
                                                <td>{{ $sc['pondere'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <td colspan="4"></td>
                                            <th colspan="2">Note critère pondérée</th>
                                            <th>{{ $indicateurs[0]['notation']['note'] ?? 0 }}</th>
                                        </tr>
                                    @endif

                                    @if(isset($criteres[3]))
                                        <tr>
                                            <th class="vertical-align bg-white" rowspan="{{ count($criteres[3]['souscriteres'] ?? []) + 1 }}">{{ $criteres[3]['name'] }}</th>
                                        </tr>
                                        @foreach($criteres[3]['souscriteres'] ?? [] as $sc)
                                            <tr>
                                                <td>{{ $sc['sequence'] ?? '-' }}</td>
                                                <td>{{ $sc['default'] ?? 0 }}%</td>
                                                <td>{{ $sc['name'] ?? '-' }}</td>
                                                <td>
                                                    {{ isset($sc['reponse']['choice']) ? $sc['reponse']['choice']['valeur'] : '-' }}
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] ?? '' }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] ?? '' }}" class="btn btn-sm btn-link p-0 ms-1 btn-critere" title="Modifier"><i class="demo-psi-pen-5"></i></button>
                                                </td>
                                                <td>{{ isset($sc['reponse']['choice']) ? $sc['reponse']['note'] : '-' }}</td>
                                                <td>{{ isset($sc['reponse']['choice']) ? round($sc['reponse']['note'] * ($sc['default'] ?? 0) / 100, 2) : '-' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <td colspan="4"></td>
                                            <th colspan="2">Note critère pondérée</th>
                                            <th>{{ $criteres[3]['note'] ?? 0 }}</th>
                                        </tr>
                                    @endif

                                    <tr class="table-dark">
                                        <th colspan="4"></th>
                                        <th colspan="2">Note pondérée finale</th>
                                        <th colspan="2">Notation PME</th>
                                    </tr>
                                    <tr class="table-dark">
                                        <th colspan="4"></th>
                                        <th class="fw-bold" colspan="2">{{ $item->note ?? $item['note'] ?? '—' }}</th>
                                        <th class="fw-bold" colspan="2">{{ $sme->name ?? $sme['name'] ?? '—' }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="demo-psi-file-search text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-2">Aucune donnée DSF importée</p>
                            <p class="small text-muted">Les données DSF sont importées par l'analyste financier.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

        </div>
    </div>

    @if(($canApproveRejectInstructionTransmission ?? false) && $item->isInstructionPendingAgenceValidation())
        @include('partials.ca-instruction-transmission-modals', ['dossier' => $item])
    @endif

    @include('partials.ca-instruction-agence-avis-saisie', ['dossier' => $item])
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
                <form action="{{ route('ca.instruction.critere.reponse') }}" method="post">
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
            var url = "{{ route('ca.instruction.critere.choices') }}";
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
@if($item->isInstructionValidatedByAgence() && ! $item->isInstructionCaTransmittedToExploitation())
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-fr-FR.min.js"></script>
<script>
(function ($) {
    function baseOptions(height) {
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
            placeholder: 'Rédigez l’avis du chef d’agence sur ce dossier d’instruction…',
        };
    }
    function syncSummernoteToTextarea($ta) {
        if ($ta.length && $ta.next('.note-editor').length) {
            $ta.val($ta.summernote('code'));
        }
    }
    jQuery(function () {
        var $ta = $('#instruction_agence_ca_avis');
        if (!$ta.length) {
            return;
        }
        $ta.summernote($.extend({}, baseOptions(260)));
        $('#form-ca-instruction-agence-avis').on('submit', function () {
            syncSummernoteToTextarea($ta);
        });
    });
})(window.jQuery);
</script>
@endif
@endsection
