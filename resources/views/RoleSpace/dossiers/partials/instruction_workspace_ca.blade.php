@php
    /** @var \App\Models\Dossier $dossier */
@endphp


<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-information me-2 text-primary"></i>Résumé du dossier</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0 g-2">
                    <dt class="col-sm-5 text-muted small">Entreprise</dt>
                    <dd class="col-sm-7">{{ $dossier->entreprise?->name ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Programme(s)</dt>
                    <dd class="col-sm-7">{{ $dossier->programmesLabel() }}</dd>
                    @if($dossier->chef_filiere_submitted_to_agence_at)
                        <dt class="col-sm-5 text-muted small">Transmis à l’agence</dt>
                        <dd class="col-sm-7">
                            {{ $dossier->chef_filiere_submitted_to_agence_at->format('d/m/Y H:i') }}
                            @if($dossier->chefFiliereSubmittedToAgenceBy)
                                — {{ $dossier->chefFiliereSubmittedToAgenceBy->name }}
                            @endif
                        </dd>
                    @endif
                    @if($dossier->instruction_agence_validated_at)
                        <dt class="col-sm-5 text-muted small">Validé agence</dt>
                        <dd class="col-sm-7">
                            {{ $dossier->instruction_agence_validated_at->format('d/m/Y H:i') }}
                            @if($dossier->instructionAgenceValidatedBy) — {{ $dossier->instructionAgenceValidatedBy->name }} @endif
                        </dd>
                    @elseif($dossier->instruction_agence_rejected_at)
                        <dt class="col-sm-5 text-muted small">Rejet agence</dt>
                        <dd class="col-sm-7 text-danger">
                            {{ $dossier->instruction_agence_rejected_at->format('d/m/Y H:i') }}
                            @if($dossier->instructionAgenceRejectedBy) — {{ $dossier->instructionAgenceRejectedBy->name }} @endif
                        </dd>
                    @endif
                    @if($dossier->instruction_ca_transmitted_to_exploitation_at)
                        <dt class="col-sm-5 text-muted small">Transmission REXP</dt>
                        <dd class="col-sm-7">
                            {{ $dossier->instruction_ca_transmitted_to_exploitation_at->format('d/m/Y H:i') }}
                            @if($dossier->instructionCaTransmittedToExploitationBy) — {{ $dossier->instructionCaTransmittedToExploitationBy->name }} @endif
                        </dd>
                    @endif
                    <dt class="col-sm-5 text-muted small">Engagements sollicités</dt>
                    <dd class="col-sm-7">{{ $dossier->engagements_sollicites_total !== null ? number_format((float) $dossier->engagements_sollicites_total, 0, ',', ' ').' XAF' : '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Engagements en cours</dt>
                    <dd class="col-sm-7">{{ $dossier->engagements_en_cours_total !== null ? number_format((float) $dossier->engagements_en_cours_total, 0, ',', ' ').' XAF' : '—' }}</dd>
                </dl>
            </div>
        </div>

        @if(($noteFinale ?? null) !== null || ($sme ?? null))
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-information me-2 text-primary"></i>Notation PME</h6>
                </div>
                <div class="card-body">
                    @if(($noteFinale ?? null) !== null)
                        <p class="small text-muted mb-2">Note pondérée finale : <strong>{{ $noteFinale }}</strong></p>
                    @endif
                    @if($sme ?? null)
                        <h6 class="fw-semibold">{{ $sme->name ?? $sme['name'] ?? '—' }}</h6>
                        @if(!empty($smeMention ?? null) || !empty($sme->mention ?? $sme['mention'] ?? null))
                            <div class="mb-2">
                                <span class="text-muted small d-block mb-1">Mention</span>
                                @include('RoleSpace.dossiers.partials._sme_mention_badge', [
                                    'mention' => $smeMention ?? $sme->mention ?? $sme['mention'] ?? null,
                                ])
                            </div>
                        @endif
                        @if(!empty($smeDescription ?? null) || !empty($sme->description ?? $sme['description'] ?? null))
                            <p class="small text-muted mb-1 fw-semibold">Avis SME</p>
                            <p class="mb-0 small">{{ $smeDescription ?? $sme->description ?? $sme['description'] }}</p>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-8">
        <div class="ca-dossier-show__notation-wrap">
            @include('RoleSpace.dossiers.partials.instruction_grille_notation', [
                'item' => $dossier,
                'criteres' => $criteres ?? [],
                'indicateurs' => $indicateurs ?? [],
                'indicateurReference' => $indicateurReference ?? null,
                'noteFinale' => $noteFinale ?? null,
                'sme' => $sme ?? null,
                'smeMention' => $smeMention ?? null,
                'smeDescription' => $smeDescription ?? null,
                'readOnly' => false,
            ])
        </div>
    </div>
</div>

@include('partials.ca-instruction-agence-avis-saisie', ['dossier' => $dossier])
