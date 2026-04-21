@extends('Layouts.chef_filiere')

@push('styles')
@include('partials.summernote-fr-styles')
@endpush

@section('title', 'Qualification — '.$item->name)

@section('content')
    <div class="qualification-page cf-page container-fluid py-3 py-md-4">
        @php
            $dtCa = static fn ($v) => $v ? \Carbon\Carbon::parse($v)->format('d/m/Y à H:i') : '—';
            $fmtShort = static fn ($v) => $v ? \Carbon\Carbon::parse($v)->format('d/m/Y H:i') : '—';
        @endphp

        <div class="qualification-hero p-3 p-md-4 mb-3 mb-md-4">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('chef-filiere.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('chef-filiere.qualifications.index') }}">Qualifications</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 40) }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-2">Qualification — {{ $item->name }}</h1>
            <p class="text-muted small mb-0 mb-md-2" style="max-width: 42rem;">
                Une seule qualification par client : analyses et besoins, puis soumission au chef d’agence.
                Les <strong>inscriptions aux programmes</strong> (une à la fois, dossier d’instruction créé automatiquement) se font sur la <a href="{{ route('chef-filiere.clients.show', $item->token) }}">fiche client</a> après validation du chef d’agence.
            </p>
            <a href="{{ route('chef-filiere.clients.show', $item->token) }}" class="btn btn-outline-secondary btn-sm mt-2 mt-md-3">
                <i class="demo-pli-arrow-left me-1"></i> Retour au dossier client
            </a>
            @if($item->promu_client_at || $item->prospect_rejected_at || $eer->programmes_submitted_at || $eer->qualification_validated_by_agence_at)
                <div class="qualification-hero-badges d-flex flex-wrap align-items-center gap-2 mt-3 pt-3 border-top">
                    @if($item->promu_client_at)
                        <span class="badge rounded-pill text-bg-success">Client validé (chef d'agence)</span>
                        <span class="badge rounded-pill bg-light text-dark border">{{ $dtCa($item->promu_client_at) }}</span>
                        <span class="badge rounded-pill bg-light text-dark border">{{ $item->promuClientUser?->name ?? '—' }}</span>
                    @elseif($item->prospect_rejected_at)
                        <span class="badge rounded-pill text-bg-warning text-dark">Refus chef d'agence</span>
                        <span class="badge rounded-pill bg-light text-dark border">{{ $dtCa($item->prospect_rejected_at) }}</span>
                        <span class="badge rounded-pill bg-light text-dark border">{{ $item->prospectRejectedUser?->name ?? '—' }}</span>
                    @endif
                    @if($eer->programmes_submitted_at)
                        <span class="badge rounded-pill text-bg-info text-dark">Qualification soumise au chef d'agence</span>
                        <span class="badge rounded-pill bg-light text-dark border">{{ $fmtShort($eer->programmes_submitted_at) }}</span>
                    @endif
                    @if($eer->qualification_validated_by_agence_at)
                        <span class="badge rounded-pill text-bg-success">Qualification validée (chef d'agence)</span>
                        <span class="badge rounded-pill bg-light text-dark border">{{ $fmtShort($eer->qualification_validated_by_agence_at) }}</span>
                    @endif
                </div>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button></div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show">{{ session('info') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button></div>
        @endif

        <div class="qf-max">
            <ol class="qf-stepper" aria-label="Étapes">
                <li class="qf-stepper__item qf-stepper__item--on">
                    <span class="qf-stepper__num" aria-hidden="true">1</span>
                    <span>Analyses</span>
                </li>
                <li class="qf-stepper__item qf-stepper__item--on">
                    <span class="qf-stepper__num" aria-hidden="true">2</span>
                    <span>Besoins</span>
                </li>
                <li class="qf-stepper__item qf-stepper__item--on">
                    <span class="qf-stepper__num" aria-hidden="true">3</span>
                    <span>Soumission CA</span>
                </li>
            </ol>

            @if(!$qualificationEditable)
                @if($lockedPendingCa)
                    <div class="alert alert-warning border-0 shadow-sm">
                        <strong>En attente du chef d'agence.</strong> La qualification a été transmise ; vous ne pouvez plus la modifier tant que le chef d'agence n'a pas statué.
                    </div>
                @elseif($lockedAfterValidation)
                    <div class="alert alert-success border-0 shadow-sm">
                        <strong>Qualification validée.</strong> Inscrivez le client aux programmes depuis la
                        <a href="{{ route('chef-filiere.clients.show', $item->token) }}" class="alert-link">fiche client</a> (un programme à la fois).
                    </div>
                @endif
                @include('partials.entreprise-qualification-chef-filiere', [
                    'item' => $item,
                    'qualificationContext' => 'chef-filiere',
                    'stripOuterCard' => true,
                ])
            @else
            <form id="qualification-main-form" method="post" action="{{ route('chef-filiere.qualifications.update', $item->token) }}">
                @csrf

                <div class="qf-section">
                    <div class="qf-section__head">
                        <h2 class="qf-section__title">
                            <span class="qf-section__badge">1</span>
                            Analyses
                        </h2>
                        <p class="qf-section__lead">Positionnement stratégique, fonctionnement opérationnel et analyse d’éligibilité aux dispositifs.</p>
                    </div>
                    <div class="qf-section__body">
                        <div class="qf-field">
                            <label class="qf-field__label" for="analyse_strategique">Analyse stratégique</label>
                            <p class="qf-field__hint">Vision, marché, avantages concurrentiels, risques majeurs.</p>
                            <div class="summernote-wrapper">
                                <textarea name="analyse_strategique" id="analyse_strategique" class="form-control js-summernote-fr @error('analyse_strategique') is-invalid @enderror" rows="3">{!! old('analyse_strategique', $eer->analyse_strategique) !!}</textarea>
                            </div>
                            @error('analyse_strategique')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="qf-field">
                            <label class="qf-field__label" for="analyse_operationnelle">Analyse opérationnelle</label>
                            <p class="qf-field__hint">Organisation, processus, ressources, chaîne de valeur.</p>
                            <div class="summernote-wrapper">
                                <textarea name="analyse_operationnelle" id="analyse_operationnelle" class="form-control js-summernote-fr @error('analyse_operationnelle') is-invalid @enderror" rows="3">{!! old('analyse_operationnelle', $eer->analyse_operationnelle) !!}</textarea>
                            </div>
                            @error('analyse_operationnelle')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="qf-field mb-0">
                            <label class="qf-field__label" for="analyse_eligibilite">Éligibilité</label>
                            <p class="qf-field__hint">Adéquation aux critères des programmes et freins éventuels.</p>
                            <div class="summernote-wrapper">
                                <textarea name="analyse_eligibilite" id="analyse_eligibilite" class="form-control js-summernote-fr @error('analyse_eligibilite') is-invalid @enderror" rows="3">{!! old('analyse_eligibilite', $eer->analyse_eligibilite) !!}</textarea>
                            </div>
                            @error('analyse_eligibilite')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="qf-section">
                    <div class="qf-section__head">
                        <h2 class="qf-section__title">
                            <span class="qf-section__badge">2</span>
                            Identification &amp; notes
                        </h2>
                        <p class="qf-section__lead">Synthèse des besoins exprimés et notes internes pour la suite du dossier.</p>
                    </div>
                    <div class="qf-section__body">
                        <div class="qf-field">
                            <label class="qf-field__label" for="identification_besoins">Identification des besoins</label>
                            <p class="qf-field__hint">Besoins prioritaires tels qu’identifiés avec l’entreprise.</p>
                            <div class="summernote-wrapper">
                                <textarea name="identification_besoins" id="identification_besoins" class="form-control js-summernote-fr @error('identification_besoins') is-invalid @enderror" rows="3">{!! old('identification_besoins', $eer->identification_besoins) !!}</textarea>
                            </div>
                            @error('identification_besoins')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="qf-field mb-0">
                            <label class="qf-field__label" for="qualification_notes">Notes de qualification</label>
                            <p class="qf-field__hint">Remarques complémentaires (non visibles côté entreprise si applicable).</p>
                            <div class="summernote-wrapper">
                                <textarea name="qualification_notes" id="qualification_notes" class="form-control js-summernote-fr @error('qualification_notes') is-invalid @enderror" rows="3">{!! old('qualification_notes', $eer->qualification_notes) !!}</textarea>
                            </div>
                            @error('qualification_notes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="qf-section">
                    <div class="qf-section__head">
                        <h2 class="qf-section__title">
                            <span class="qf-section__badge">3</span>
                            Besoins identifiés
                        </h2>
                        <p class="qf-section__lead">Cochez les familles de besoins correspondant au profil de l’entreprise.</p>
                    </div>
                    <div class="qf-section__body">
                        <div class="qf-besoins" role="group" aria-label="Types de besoins">
                            <div class="qf-besoin">
                                <input type="checkbox" id="besoin_financement" name="besoin_financement" value="1" {{ old('besoin_financement', $eer->besoin_financement) ? 'checked' : '' }}>
                                <label for="besoin_financement">
                                    <span class="qf-besoin__icon" aria-hidden="true">€</span>
                                    Financement
                                </label>
                            </div>
                            <div class="qf-besoin">
                                <input type="checkbox" id="besoin_accompagnement" name="besoin_accompagnement" value="1" {{ old('besoin_accompagnement', $eer->besoin_accompagnement) ? 'checked' : '' }}>
                                <label for="besoin_accompagnement">
                                    <span class="qf-besoin__icon" aria-hidden="true">◎</span>
                                    Accompagnement
                                </label>
                            </div>
                            <div class="qf-besoin">
                                <input type="checkbox" id="besoin_structuration" name="besoin_structuration" value="1" {{ old('besoin_structuration', $eer->besoin_structuration) ? 'checked' : '' }}>
                                <label for="besoin_structuration">
                                    <span class="qf-besoin__icon" aria-hidden="true">▤</span>
                                    Structuration
                                </label>
                            </div>
                        </div>
                        <div class="qf-actions-bar mb-0 mt-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="demo-psi-check-mark me-1"></i> Enregistrer la qualification
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            @if($eer->qualification_completed_at && !$eer->programmes_submitted_at)
                <div class="qf-section mt-4">
                    <div class="qf-section__head">
                        <h2 class="qf-section__title">
                            <span class="qf-section__badge">4</span>
                            Transmission au chef d'agence
                        </h2>
                        <p class="qf-section__lead mb-0">Soumettez la qualification pour validation. Les programmes seront choisis ensuite sur la fiche client.</p>
                    </div>
                    <div class="qf-section__body pt-3">
                        <form method="post" action="{{ route('chef-filiere.qualifications.submit', $item->token) }}" onsubmit="return confirm('Soumettre cette qualification au chef d\'agence pour validation ?');">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="demo-psi-upload me-1"></i> Soumettre au chef d'agence pour validation
                            </button>
                        </form>
                    </div>
                </div>
            @endif
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-fr-FR.min.js"></script>
<script>
    (function ($) {
        var toolbarFull = [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'hr']],
            ['view', ['codeview']]
        ];
        function baseOptions(height, toolbar) {
            return {
                lang: 'fr-FR',
                height: height,
                dialogsInBody: true,
                toolbar: toolbar,
                callbacks: {
                    onChange: function (contents) {
                        $(this).val(contents);
                    }
                }
            };
        }
        function syncSummernoteEditors($root) {
            $root.find('textarea.js-summernote-fr').each(function () {
                var $t = $(this);
                if ($t.next('.note-editor').length) {
                    $t.val($t.summernote('code'));
                }
            });
        }
        $(document).ready(function () {
            @if($qualificationEditable)
            $('.js-summernote-fr').summernote($.extend({}, baseOptions(220, toolbarFull), {
                placeholder: 'Saisissez le texte…'
            }));
            $('#qualification-main-form').on('submit', function () {
                syncSummernoteEditors($(this));
            });
            @endif
        });
    })(jQuery);
</script>
@endsection
