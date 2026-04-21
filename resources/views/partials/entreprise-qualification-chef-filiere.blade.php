{{--
    Qualification chef de filière (EER) — affiché sur les fiches client lorsque la qualification a été engagée.
    Variables attendues : $item (Entreprise), $qualificationContext (chef-filiere|gestionnaire|ca|regional|juridique|analyste|admin|...)
    stripOuterCard (optionnel) : si true, n’affiche que le corps (sans carte ni en-tête), pour l’intégrer dans une section personnalisée.
    hideMetaRow (optionnel) : si true, masque la ligne des 4 colonnes (statut / dates) — utile quand le bandeau parent affiche déjà le résumé.
--}}
@php
    $eer = $item->dossierEntreeRelation;
    $ctx = $qualificationContext ?? 'gestionnaire';
    $stripOuterCard = $stripOuterCard ?? false;
    $hideMetaRow = $hideMetaRow ?? false;
    $fmtDt = static fn ($v) => $v ? \Carbon\Carbon::parse($v)->format('d/m/Y H:i') : '—';
    $showBloc = $eer && (
        $eer->qualification_completed_at
        || $eer->programmes_submitted_at
        || $eer->qualification_validated_by_agence_at
        || in_array($eer->statut, [
            \App\Models\DossierEntreeRelation::STATUT_QUALIFIE,
            \App\Models\DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION,
            \App\Models\DossierEntreeRelation::STATUT_QUALIFICATION_AGENCE_VALIDEE,
            \App\Models\DossierEntreeRelation::STATUT_INSTRUCTION_VALIDEE,
        ], true)
    );
@endphp
@if($showBloc)
    @if(!$stripOuterCard)
    <div class="card border-0 shadow-sm mb-4 border-start border-primary border-4">
        <div class="card-header bg-white py-3">
            <h2 class="h5 mb-0">Qualification (chef de filière)</h2>
            <p class="text-muted small mb-0 mt-1">Synthèse de la qualification (une par client). Les inscriptions programme et dossiers d’instruction suivent la validation du chef d’agence.</p>
        </div>
        <div class="card-body">
    @endif
            @if(! $hideMetaRow)
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <small class="text-muted text-uppercase">Statut EER</small>
                    <p class="mb-0 fw-medium"><span class="badge bg-secondary">{{ $eer->statut }}</span></p>
                </div>
                <div class="col-md-3">
                    <small class="text-muted text-uppercase">Qualification enregistrée</small>
                    <p class="mb-0">{{ $fmtDt($eer->qualification_completed_at) }}</p>
                    <p class="small text-muted mb-0">{{ $eer->qualificationUser?->name ?? '—' }}</p>
                </div>
                <div class="col-md-3">
                    <small class="text-muted text-uppercase">Soumission au chef d’agence</small>
                    <p class="mb-0">{{ $fmtDt($eer->programmes_submitted_at) }}</p>
                    <p class="small text-muted mb-0">{{ $eer->programmesSubmittedBy?->name ?? '—' }}</p>
                </div>
                <div class="col-md-3">
                    <small class="text-muted text-uppercase">Validation qualification (CA)</small>
                    <p class="mb-0">{{ $fmtDt($eer->qualification_validated_by_agence_at) }}</p>
                    <p class="small text-muted mb-0">{{ $eer->qualificationValidatedByAgenceUser?->name ?? '—' }}</p>
                </div>
            </div>
            @endif

            @if($eer->statut === \App\Models\DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION && ! $eer->qualification_validated_by_agence_at)
                <div class="alert alert-warning border-0 mb-4">
                    <strong>En attente du chef d’agence.</strong>
                    @if($eer->programmeSelections->isNotEmpty())
                        Les dossiers d’instruction seront créés après validation (programmes listés ci‑dessous — ancien flux).
                    @else
                        Après validation, le chef de filière inscrit le client aux programmes depuis la fiche client (un programme à la fois, dossier créé automatiquement).
                    @endif
                </div>
            @endif

            @if($eer->qualification_validated_by_agence_at && $eer->programmeSelections->isEmpty())
                <div class="alert alert-success border-0 mb-4">
                    <strong>Qualification validée par le chef d’agence.</strong> Inscrivez le client aux programmes depuis la fiche client (chef de filière).
                </div>
            @endif

            @if($eer->statut === \App\Models\DossierEntreeRelation::STATUT_INSTRUCTION_VALIDEE && $eer->instruction_validated_at)
                <div class="alert alert-success border-0 mb-4">
                    Dossiers d’instruction validés par le chef d’agence le {{ $fmtDt($eer->instruction_validated_at) }}
                    @if($eer->instructionValidatedBy)
                        — {{ $eer->instructionValidatedBy->name }}
                    @endif
                </div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h6 class="text-uppercase text-muted small">Besoins identifiés</h6>
                    <p class="mb-0">
                        @if($eer->besoin_financement)<span class="badge bg-light text-dark border me-1">Financement</span>@endif
                        @if($eer->besoin_accompagnement)<span class="badge bg-light text-dark border me-1">Accompagnement</span>@endif
                        @if($eer->besoin_structuration)<span class="badge bg-light text-dark border me-1">Structuration</span>@endif
                        @if(!$eer->besoin_financement && !$eer->besoin_accompagnement && !$eer->besoin_structuration)
                            <span class="text-muted">—</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($eer->analyse_strategique || $eer->analyse_operationnelle || $eer->analyse_eligibilite || $eer->identification_besoins || $eer->qualification_notes)
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted small mb-2">Contenu de la qualification</h6>
                    @if($eer->analyse_strategique)
                        <p class="small text-muted mb-1">Analyse stratégique</p>
                        <div class="border rounded p-3 bg-light mb-3 rich-text-rendered">{!! $eer->analyse_strategique !!}</div>
                    @endif
                    @if($eer->analyse_operationnelle)
                        <p class="small text-muted mb-1">Analyse opérationnelle</p>
                        <div class="border rounded p-3 bg-light mb-3 rich-text-rendered">{!! $eer->analyse_operationnelle !!}</div>
                    @endif
                    @if($eer->analyse_eligibilite)
                        <p class="small text-muted mb-1">Éligibilité</p>
                        <div class="border rounded p-3 bg-light mb-3 rich-text-rendered">{!! $eer->analyse_eligibilite !!}</div>
                    @endif
                    @if($eer->identification_besoins)
                        <p class="small text-muted mb-1">Identification des besoins</p>
                        <div class="border rounded p-3 bg-light mb-3 rich-text-rendered">{!! $eer->identification_besoins !!}</div>
                    @endif
                    @if($eer->qualification_notes)
                        <p class="small text-muted mb-1">Notes</p>
                        <div class="border rounded p-3 bg-light mb-3 rich-text-rendered">{!! $eer->qualification_notes !!}</div>
                    @endif
                </div>
            @endif

            @if($eer->programmeSelections->isNotEmpty())
                <h6 class="text-uppercase text-muted small mb-2">Programmes retenus</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Programme</th>
                                <th>Type d’appui</th>
                                <th>Notes</th>
                                <th>Dossier d’instruction</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eer->programmeSelections as $sel)
                                <tr>
                                    <td>{{ $sel->programme?->name ?? '—' }}</td>
                                    <td>{{ $sel->type_appui ?? '—' }}</td>
                                    <td class="small rich-text-rendered">{!! $sel->notes ?: '—' !!}</td>
                                    <td>
                                        @if($sel->instruction_dossier_id && $sel->instructionDossier)
                                            @php
                                                $d = $sel->instructionDossier;
                                                $dossierUrl = match ($ctx) {
                                                    'chef-filiere' => route('chef-filiere.instructions.dossier.show', $d->token),
                                                    'ca' => route('ca.dossiers.show', $d->token),
                                                    'respexp' => route('respexp.dossiers.show', $d->token),
                                                    'regional' => route('regional.dossiers.show', $d->token),
                                                    'gestionnaire' => route('gestionnaire.dossiers.show', $d->token),
                                                    'analyste' => route('analyste.dossiers.show', $d->token),
                                                    'admin' => route('admin.dossiers.show', $d->token),
                                                    default => null,
                                                };
                                            @endphp
                                            @if($dossierUrl)
                                                <a href="{{ $dossierUrl }}" class="btn btn-sm btn-outline-primary">Voir le dossier</a>
                                            @else
                                                <span class="text-muted small">Dossier créé (réf. {{ $d->token }})</span>
                                            @endif
                                        @elseif($eer->statut === \App\Models\DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
                                            <span class="badge bg-warning text-dark">À créer après validation chef d’agence</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
    @if(!$stripOuterCard)
        </div>
    </div>
    @endif
@endif
