{{-- Parcours d’instruction par étapes : fond coloré subtle uniquement sur l’en-tête de chaque étape. --}}
@php
    /** @var \App\Models\Dossier $dossier */
    /** @var array|null $instructionConsultation */
    /** @var \App\Services\StructurationClosureService $structurationSvc */
    $c = $instructionConsultation ?? null;
@endphp
@if($c)
    <div class="card border-0 shadow-sm mt-3" id="instruction-parcours-historique-unifie">
        <div class="card-header bg-transparent border-0 py-3">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                <div>
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-shuffle me-2 text-primary"></i>Parcours d’instruction (par étapes)</h6>
                    <p class="text-muted small mb-0 mt-1">Détail métier par étape du parcours. La liste des événements (dates, auteurs, profils) figure dans la section <strong>Historique (chronologie)</strong> ci-dessus ; le détail des contenus saisis reste dans chaque étape ci-dessous.</p>
                </div>
                @if($dossier->isInstructionClosed())
                    <span class="badge bg-dark">Dossier d’instruction clos</span>
                @endif
            </div>
        </div>
        <div class="card-body pt-0 vstack gap-4">
            @for($stepNum = 1; $stepNum <= 6; $stepNum++)
                @php
                    [$stepTitle, $bs] = match ($stepNum) {
                        1 => ['1) Dossier d’instruction (chef de filière → chef d’agence)', 'primary'],
                        2 => ['2) Exploitation (analyste financier → REXP)', 'success'],
                        3 => ['3) Juridique (analyste juridique → responsable juridique)', 'info'],
                        4 => ['4) Engagements (analyste crédit → RENG)', 'warning'],
                        5 => ['5) Risques (analyste risques → RERX / direction)', 'secondary'],
                        6 => ['6) Clôture du dossier d’instruction (délégation de pouvoir)', 'dark'],
                        default => ['', 'light'],
                    };
                @endphp
                <section class="rounded-3 border bg-white text-break overflow-hidden" style="overflow-wrap:anywhere; word-break:break-word;">
                    <div class="px-3 py-2 bg-{{ $bs }}-subtle border-bottom border-{{ $bs }} border-opacity-25">
                        <h6 class="fw-semibold mb-0 text-{{ $bs }}">{{ $stepTitle }}</h6>
                    </div>
                    <div class="p-3 p-lg-4">
                        @if($stepNum === 1)
                            <div class="d-flex gap-2 flex-wrap align-items-center mb-2">
                                <span class="badge bg-light text-dark border">{{ $structurationSvc->closureStatutLabel($dossier) }}</span>
                                @if($dossier->chef_filiere_submitted_to_agence_at)
                                    <span class="text-muted small">Soumis le {{ $dossier->chef_filiere_submitted_to_agence_at->format('d/m/Y H:i') }}</span>
                                @endif
                                @if($dossier->instruction_agence_validated_at)
                                    <span class="text-success small">Validé le {{ $dossier->instruction_agence_validated_at->format('d/m/Y H:i') }}</span>
                                @elseif($dossier->instruction_agence_rejected_at)
                                    <span class="text-danger small">Rejeté le {{ $dossier->instruction_agence_rejected_at->format('d/m/Y H:i') }}</span>
                                @endif
                            </div>
                            @if($dossier->instruction_agence_reject_motif)
                                <div class="alert alert-danger py-2 small mb-0 text-break" style="white-space: pre-wrap; overflow-wrap:anywhere; word-break:break-word;">{{ $dossier->instruction_agence_reject_motif }}</div>
                            @endif
                            @if($dossier->instruction_agence_closing_note)
                                <div class="alert alert-secondary py-2 small mb-0 mt-2 text-break" style="white-space: pre-wrap; overflow-wrap:anywhere; word-break:break-word;"><strong>Note :</strong> {{ $dossier->instruction_agence_closing_note }}</div>
                            @endif
                        @elseif($stepNum === 2)
                            @include('RoleSpace.dossiers.partials.respexp_dossier_hub')
                        @elseif($stepNum === 3)
                            @include('RoleSpace.dossiers.partials.juridique_instruction_workflow')
                        @elseif($stepNum === 4)
                            @include('RoleSpace.dossiers.partials.reng_instruction_workflow')
                        @elseif($stepNum === 5)
                            @include('RoleSpace.dossiers.partials.rerx_risques_workflow')
                        @elseif($stepNum === 6)
                            <div class="d-flex gap-2 flex-wrap align-items-center mb-2">
                                <span class="badge bg-light text-dark border">{{ $instructionClosureStatutLabel ?? '—' }}</span>
                                @if($dossier->instruction_closure_validated_at)
                                    <span class="text-success small">Clos le {{ $dossier->instruction_closure_validated_at->format('d/m/Y H:i') }}</span>
                                @elseif($dossier->instruction_closure_rejected_at)
                                    <span class="text-danger small">Rejet le {{ $dossier->instruction_closure_rejected_at->format('d/m/Y H:i') }}</span>
                                @endif
                            </div>
                            @if(! empty($instructionClosureRuleDescription))
                                <p class="text-muted small mb-2 text-break">{{ $instructionClosureRuleDescription }}</p>
                            @endif
                            @if($dossier->instruction_closure_reject_motif)
                                <div class="alert alert-danger py-2 small mb-0 text-break" style="white-space: pre-wrap; overflow-wrap:anywhere; word-break:break-word;">{{ $dossier->instruction_closure_reject_motif }}</div>
                            @endif
                            @if($dossier->instruction_closure_note)
                                <div class="alert alert-secondary py-2 small mb-0 mt-2 text-break" style="white-space: pre-wrap; overflow-wrap:anywhere; word-break:break-word;"><strong>Note :</strong> {{ $dossier->instruction_closure_note }}</div>
                            @endif
                        @endif
                    </div>
                </section>
            @endfor
        </div>
    </div>
@endif
