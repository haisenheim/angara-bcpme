{{-- Parcours d’instruction + historique détaillé (chronologie par étape, ordre croissant, fonds subtle) --}}
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
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-shuffle me-2 text-primary"></i>Parcours d’instruction et historique du dossier</h6>
                    <p class="text-muted small mb-0 mt-1">Chaque étape regroupe, en <strong>ordre chronologique croissant</strong>, les événements et contenus associés, puis le détail métier de l’étape.</p>
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
                    $rows = collect($c['timeline'] ?? [])
                        ->filter(fn ($r) => (int) ($r['workflow_step'] ?? 0) === $stepNum)
                        ->sortBy(function ($r) {
                            $t = $r['at'] ?? null;

                            return $t instanceof \Carbon\Carbon ? $t->getTimestamp() : 0;
                        })
                        ->values();
                @endphp
                <section class="rounded-3 border p-3 p-lg-4 bg-{{ $bs }}-subtle border-{{ $bs }}-subtle text-break" style="overflow-wrap:anywhere; word-break:break-word;">
                    <h6 class="fw-semibold mb-3 pb-2 border-bottom border-secondary border-opacity-25">{{ $stepTitle }}</h6>

                    @if($rows->isNotEmpty())
                        <p class="small text-muted fw-semibold mb-2">Historique associé à cette étape</p>
                        <div class="vstack gap-3 mb-4">
                            @foreach($rows as $row)
                                @php
                                    $kind = $row['kind'] ?? 'event';
                                    $badgeClass = match ($kind) {
                                        'analyse_critique' => 'bg-primary',
                                        'avis' => 'bg-info',
                                        'validation' => 'bg-dark',
                                        'affectation' => 'bg-secondary',
                                        'transmission' => 'bg-success',
                                        default => 'bg-secondary',
                                    };
                                    $at = $row['at'] ?? null;
                                    if ($at !== null && $at !== '' && ! ($at instanceof \Carbon\Carbon)) {
                                        try {
                                            $at = \Carbon\Carbon::parse($at);
                                        } catch (\Throwable) {
                                            $at = null;
                                        }
                                    }
                                @endphp
                                <div class="border rounded p-3 bg-white bg-opacity-75">
                                    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                        <span class="badge {{ $badgeClass }}">{{ $kind === 'analyse_critique' ? 'Avis / analyse critique' : ucfirst((string) $kind) }}</span>
                                        @if(! empty($row['avis_source']))
                                            <span class="badge bg-light text-dark border">{{ $row['avis_source'] }}</span>
                                        @endif
                                        @if(! empty($row['avis_etat']))
                                            <span class="badge bg-light text-secondary border small">{{ $row['avis_etat'] }}</span>
                                        @endif
                                        @if($at)
                                            <span class="small text-muted">{{ $at->format('d/m/Y') }} à {{ $at->format('H:i') }}</span>
                                        @endif
                                    </div>
                                    <p class="fw-semibold mb-1 small">{{ $row['label'] ?? '—' }}</p>
                                    @php $actor = $row['actor'] ?? null; @endphp
                                    @if($actor)
                                        <p class="small text-muted mb-2 mb-md-1">
                                            <span class="text-uppercase fw-semibold">Auteur :</span>
                                            <strong>{{ $actor->name }}</strong>
                                            @if(! empty($row['actor_role']))
                                                <span class="text-muted">— {{ $row['actor_role'] }}</span>
                                            @elseif($actor->role?->name)
                                                <span class="text-muted">— {{ $actor->role->name }}</span>
                                            @endif
                                        </p>
                                    @else
                                        <p class="small text-muted mb-2 mb-md-1">Auteur non renseigné ou non applicable.</p>
                                    @endif
                                    @if(! empty($row['body_html']))
                                        <div class="mt-2 small rich-text-rendered border rounded p-3 bg-body-tertiary">{!! $row['body_html'] !!}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="pt-3 mt-1 border-top border-secondary border-opacity-25">
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
