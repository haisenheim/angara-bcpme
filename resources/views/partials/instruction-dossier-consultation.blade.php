{{-- Consultation harmonisée : budgets multi-programmes (par type d’appui) + chronologie des actions et contenus --}}
@php
    /** @var \App\Models\Dossier $dossier */
    $c = $instructionConsultation ?? null;
    $showConsultationTimeline = $showConsultationTimeline ?? true;
@endphp
@if($c)
    <div class="instruction-dossier-consultation">
        <div class="card mb-3 border-start border-4 border-brand">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-money me-2 text-brand"></i>Programmes et budgets d’appui (XAF)</h6>
                <p class="small text-muted mb-0 mt-1">Montants par programme : appui financier, non financier, et total par ligne.</p>
            </div>
            <div class="card-body pt-0">
                @if($c['has_budget_rows'] ?? false)
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <div class="rounded border bg-body-tertiary p-2 small">
                                <span class="text-muted d-block">Total appui financier</span>
                                <strong class="fs-6">{{ number_format((float) ($c['totaux']['financier'] ?? 0), 0, ',', ' ') }} XAF</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded border bg-body-tertiary p-2 small">
                                <span class="text-muted d-block">Total appui non financier</span>
                                <strong class="fs-6">{{ number_format((float) ($c['totaux']['non_financier'] ?? 0), 0, ',', ' ') }} XAF</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded border bg-body-tertiary p-2 small">
                                <span class="text-muted d-block">Total général</span>
                                <strong class="fs-6">{{ number_format((float) ($c['totaux']['general'] ?? 0), 0, ',', ' ') }} XAF</strong>
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
                                @foreach($c['lignes'] as $ligne)
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

        @if($showConsultationTimeline)
        <div class="card mb-3 border-start border-4 border-secondary">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-clock me-2 text-brand"></i>Historique du dossier</h6>
                <p class="small text-muted mb-0 mt-1">Événements de workflow et avis liés au dossier — du plus récent au plus ancien. Contenu produit lorsqu’il est disponible.</p>
            </div>
            <div class="card-body pt-0">
                @forelse($c['timeline'] as $row)
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
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                            <span class="badge {{ $badgeClass }}">{{ $kind === 'analyse_critique' ? 'Avis / analyse critique' : ucfirst((string) $kind) }}</span>
                            @if(!empty($row['avis_source']))
                                <span class="badge bg-light text-dark border">{{ $row['avis_source'] }}</span>
                            @endif
                            @if(!empty($row['avis_etat']))
                                <span class="badge bg-light text-secondary border small">{{ $row['avis_etat'] }}</span>
                            @endif
                            @if($at)
                                <span class="small text-muted">
                                    {{ $at->format('d/m/Y') }} à {{ $at->format('H:i') }}
                                </span>
                            @endif
                        </div>
                        <p class="fw-semibold mb-1">{{ $row['label'] ?? '—' }}</p>
                        @php $actor = $row['actor'] ?? null; @endphp
                        @if($actor)
                            <p class="small text-muted mb-2 mb-md-1">
                                <span class="text-uppercase fw-semibold">Auteur :</span>
                                <strong>{{ $actor->name }}</strong>
                                @if(!empty($row['actor_role']))
                                    <span class="text-muted">— {{ $row['actor_role'] }}</span>
                                @endif
                            </p>
                        @else
                            <p class="small text-muted mb-2 mb-md-1">Auteur non renseigné ou non applicable.</p>
                        @endif
                        @if(!empty($row['body_html']))
                            <div class="mt-2 small rich-text-rendered border rounded p-3 bg-body-tertiary">{!! $row['body_html'] !!}</div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted small mb-0">Aucun événement tracé pour ce dossier.</p>
                @endforelse
            </div>
        </div>
        @endif
    </div>
@endif
