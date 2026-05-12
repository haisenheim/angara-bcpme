<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><i class="bi bi-calculator me-1"></i> Simulations de credit</strong>
        <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-plus-lg me-1"></i> Nouvelle simulation
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('simulator.index', ['dossier_id' => $dossier->id]) }}">
                        Pour le dossier complet
                    </a>
                </li>
                @if ($programmes->isNotEmpty())
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">Pour une ligne programme</h6></li>
                    @foreach ($programmes as $line)
                        <li>
                            <a class="dropdown-item" href="{{ route('simulator.index', ['dossier_instruction_programme_id' => $line->id]) }}">
                                {{ optional($line->programme)->name ?? 'Programme #'.$line->programme_id }}
                                <span class="text-muted small">
                                    ({{ number_format((float) $line->budget_appui_financier, 0, ',', ' ') }})
                                </span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    <div class="card-body p-0">
        @if ($scenarios->isEmpty())
            <div class="p-4 text-center text-muted">
                Aucune simulation enregistree pour ce dossier.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Cible</th>
                            <th class="text-end">Capital</th>
                            <th class="text-end">Taux</th>
                            <th class="text-end">Echeance max</th>
                            <th class="text-end">TEG</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($scenarios as $s)
                            <tr>
                                <td>
                                    <a href="{{ route('simulator.scenarios.show', $s) }}" class="fw-semibold">{{ $s->name }}</a>
                                </td>
                                <td>
                                    @if ($s->dossier_id)
                                        <span class="badge text-bg-primary-subtle">Dossier</span>
                                    @else
                                        <span class="badge text-bg-info-subtle">Ligne #{{ $s->dossier_instruction_programme_id }}</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format((float) $s->principal, 0, ',', ' ') }} {{ $s->currency }}</td>
                                <td class="text-end">{{ number_format((float) $s->annual_rate, 2, ',', ' ') }} %</td>
                                <td class="text-end">{{ number_format((float) $s->max_payment, 0, ',', ' ') }}</td>
                                <td class="text-end">{{ $s->computed_teg !== null ? number_format((float) $s->computed_teg, 2, ',', ' ').' %' : '-' }}</td>
                                <td>
                                    @php
                                        $statusClass = match ($s->status) {
                                            'draft' => 'text-bg-secondary-subtle',
                                            'submitted' => 'text-bg-info',
                                            'validated' => 'text-bg-success',
                                            'rejected' => 'text-bg-danger',
                                            default => 'text-bg-secondary-subtle',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ __('simulator::simulator.workflow.status.'.$s->status) }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('simulator.scenarios.show', $s) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
