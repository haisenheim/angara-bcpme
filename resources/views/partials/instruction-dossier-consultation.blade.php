{{-- Consultation harmonisée : budgets multi-programmes (par type d’appui) + chronologie des actions et contenus --}}
@php
    /** @var \App\Models\Dossier $dossier */
    $c = $instructionConsultation ?? null;
    $consultationPayload = is_array($c) ? $c : null;
    $showConsultationTimeline = $showConsultationTimeline ?? true;
    $allowAnalysteInstructionMontantsEdit = $allowAnalysteInstructionMontantsEdit ?? false;
    $uConsult = auth()->user();
    $isNationalAnalysteFin = $uConsult && method_exists($uConsult, 'isAnalysteFinancierNational') && $uConsult->isAnalysteFinancierNational();
    $canEditMontantsAnalyste = $allowAnalysteInstructionMontantsEdit
        && isset($dossier)
        && auth()->check()
        && $dossier->analysteFinancierPeutMettreAJourBudgetsEtEngagements()
        && (
            (int) ($dossier->analyste_id ?? 0) === (int) auth()->id()
            || $isNationalAnalysteFin
        );
    $fmtEngagementTotalConsult = function ($v) {
        if ($v === null || $v === '') {
            return '—';
        }

        return number_format((float) $v, 0, ',', ' ').' XAF';
    };
@endphp
@if($consultationPayload !== null || $canEditMontantsAnalyste)
    <div class="instruction-dossier-consultation">
        @if($canEditMontantsAnalyste)
            @include('RoleSpace.dossiers.partials.analyste_instruction_budgets_engagements_form', ['dossier' => $dossier])
        @elseif($consultationPayload !== null)
            <div class="card mb-3 border-start border-4 border-info">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-credit-card-2 me-2 text-info"></i>Totaux engagements (structuration)</h6>
                    <p class="small text-muted mb-0 mt-1">Montants saisis par le chef de filière sur le dossier : sollicités vs encours existants.</p>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="rounded border bg-body-tertiary p-2 small h-100">
                                <span class="text-muted d-block">Total engagements sollicités</span>
                                <strong class="fs-6">{{ $fmtEngagementTotalConsult($dossier->engagements_sollicites_total ?? null) }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rounded border bg-body-tertiary p-2 small h-100">
                                <span class="text-muted d-block">Total engagements en cours</span>
                                <strong class="fs-6">{{ $fmtEngagementTotalConsult($dossier->engagements_en_cours_total ?? null) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3 border-start border-4 border-brand">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-money me-2 text-brand"></i>Programmes et budgets d’appui (XAF)</h6>
                    <p class="small text-muted mb-0 mt-1">Montants par programme : appui financier, non financier, et total par ligne.</p>
                </div>
                <div class="card-body pt-0">
                    @if($consultationPayload['has_budget_rows'] ?? false)
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <div class="rounded border bg-body-tertiary p-2 small">
                                    <span class="text-muted d-block">Total appui financier</span>
                                    <strong class="fs-6">{{ number_format((float) ($consultationPayload['totaux']['financier'] ?? 0), 0, ',', ' ') }} XAF</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="rounded border bg-body-tertiary p-2 small">
                                    <span class="text-muted d-block">Total appui non financier</span>
                                    <strong class="fs-6">{{ number_format((float) ($consultationPayload['totaux']['non_financier'] ?? 0), 0, ',', ' ') }} XAF</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="rounded border bg-body-tertiary p-2 small">
                                    <span class="text-muted d-block">Total général</span>
                                    <strong class="fs-6">{{ number_format((float) ($consultationPayload['totaux']['general'] ?? 0), 0, ',', ' ') }} XAF</strong>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Programme</th>
                                        <th class="text-end">Appui financier</th>
                                        <th class="text-end">Appui non financier</th>
                                        <th class="text-end">Total programme</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($consultationPayload['lignes'] as $ligne)
                                        <tr>
                                            <td>{{ $ligne['programme_name'] }}</td>
                                            <td class="text-end">{{ number_format((float) $ligne['financier'], 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format((float) $ligne['non_financier'], 0, ',', ' ') }}</td>
                                            <td class="text-end fw-semibold">{{ number_format((float) $ligne['total_ligne'], 0, ',', ' ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted small mb-0">Aucune ligne programme budgétisée n’est encore enregistrée pour ce dossier.</p>
                    @endif
                </div>
            </div>
        @endif

        @if($allowAnalysteInstructionMontantsEdit && $dossier->isInstructionClosed())
            <p class="small text-muted mb-3">Les totaux d’engagements et les budgets par programme ne sont plus modifiables : le dossier d’instruction est clos.</p>
        @endif

        @if($showConsultationTimeline && $consultationPayload !== null)
        <div class="card mb-3 border-start border-4 border-secondary">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-clock me-2 text-brand"></i>Historique du dossier</h6>
                <p class="small text-muted mb-0 mt-1">Événements de workflow et avis liés au dossier — du plus récent au plus ancien. Contenu produit lorsqu’il est disponible.</p>
            </div>
            <div class="card-body pt-0">
                @forelse($consultationPayload['timeline'] as $row)
                    @include('partials.instruction-dossier-timeline-entry', ['row' => $row])
                @empty
                    <p class="text-muted small mb-0">Aucun événement tracé pour ce dossier.</p>
                @endforelse
            </div>
        </div>
        @endif
    </div>
@endif
