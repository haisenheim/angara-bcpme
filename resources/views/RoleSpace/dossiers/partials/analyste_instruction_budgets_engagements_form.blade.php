@php
    /** @var \App\Models\Dossier $dossier */
    $canEditBudgets = $dossier->analysteFinancierPeutMettreAJourBudgetsEtEngagements();
    $dips = $dossier->relationLoaded('instructionProgrammes')
        ? $dossier->instructionProgrammes->sortBy(['sort_order', 'id'])->values()
        : $dossier->instructionProgrammes()->orderBy('sort_order')->orderBy('id')->get();
@endphp

@if($canEditBudgets)
    @php
        $oldProgRows = collect(old('programme_budgets', []))->keyBy(fn ($r) => (int) ($r['id'] ?? 0));
    @endphp
    <div id="consultation-af-engagements-budgets" class="card border-0 shadow-sm mb-4 border-start border-4 border-info">
        <div class="card-header bg-transparent border-0 py-3">
            <h6 class="mb-0 fw-semibold"><i class="demo-psi-credit-card-2 me-2 text-info"></i>Engagements et budgets programme (XAF)</h6>
            <p class="small text-muted mb-0 mt-1">
                Dans ce bloc de consultation du dossier, vous pouvez ajuster les totaux saisis en structuration et les budgets d’appui par ligne programme (XAF).
                Au moins un montant (financier ou non financier) par programme.
                Modifiable tant que le dossier d’instruction n’est pas <strong>clos</strong> (peu importe l’étape du circuit).
            </p>
        </div>
        <div class="card-body pt-0">
            @error('programme_budgets')
                <div class="alert alert-danger py-2 small">{{ $message }}</div>
            @enderror

            <form method="post" action="{{ route('analyste.dossiers.instruction-budgets-engagements', $dossier) }}" class="mb-0">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="engagements_sollicites_total_af">Total engagements sollicités</label>
                        <input
                            type="number"
                            name="engagements_sollicites_total"
                            id="engagements_sollicites_total_af"
                            class="form-control @error('engagements_sollicites_total') is-invalid @enderror"
                            min="0"
                            step="1"
                            value="{{ old('engagements_sollicites_total', $dossier->engagements_sollicites_total ?? '') }}"
                            required
                        >
                        @error('engagements_sollicites_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="engagements_en_cours_total_af">Total engagements en cours</label>
                        <input
                            type="number"
                            name="engagements_en_cours_total"
                            id="engagements_en_cours_total_af"
                            class="form-control @error('engagements_en_cours_total') is-invalid @enderror"
                            min="0"
                            step="1"
                            value="{{ old('engagements_en_cours_total', $dossier->engagements_en_cours_total ?? '') }}"
                            required
                        >
                        @error('engagements_en_cours_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                @if($dips->isEmpty())
                    <p class="text-muted small mb-3 mb-md-0">Aucune ligne programme sur ce dossier : seuls les totaux d’engagements peuvent être mis à jour.</p>
                @else
                    <p class="small fw-semibold text-body-secondary mb-2">Budgets par programme</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Programme</th>
                                    <th class="text-end" style="min-width: 9rem;">Appui financier</th>
                                    <th class="text-end" style="min-width: 9rem;">Appui non financier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dips as $idx => $dip)
                                    @php
                                        $oRow = $oldProgRows->get((int) $dip->id);
                                        $vFin = $oRow['budget_appui_financier'] ?? $dip->budget_appui_financier;
                                        $vNf = $oRow['budget_appui_non_financier'] ?? $dip->budget_appui_non_financier;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $dip->programme?->name ?? '—' }}
                                            <input type="hidden" name="programme_budgets[{{ $idx }}][id]" value="{{ $dip->id }}">
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                name="programme_budgets[{{ $idx }}][budget_appui_financier]"
                                                class="form-control form-control-sm text-end @error('programme_budgets.'.$idx.'.budget_appui_financier') is-invalid @enderror"
                                                min="0"
                                                step="1"
                                                value="{{ $vFin }}"
                                            >
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                name="programme_budgets[{{ $idx }}][budget_appui_non_financier]"
                                                class="form-control form-control-sm text-end @error('programme_budgets.'.$idx.'.budget_appui_non_financier') is-invalid @enderror"
                                                min="0"
                                                step="1"
                                                value="{{ $vNf }}"
                                            >
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">
                    <i class="demo-psi-check me-1"></i> Enregistrer les montants
                </button>
            </form>
        </div>
    </div>
@endif
