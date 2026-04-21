{{-- Modale : affectation à un analyste financier (REXP) --}}
@php
    /** @var \Illuminate\Support\Collection $analystesExploitation */
    /** @var \App\Models\Dossier $dossier */
@endphp
<div class="modal fade" id="modalAffecterAnalyste" tabindex="-1" aria-labelledby="modalAffecterAnalysteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{ route('respexp.dossiers.assign-analyste', $dossier->token) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAffecterAnalysteLabel">{{ $dossier->analyste_id ? 'Réaffecter le dossier' : 'Affecter à un analyste financier' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Choisissez l’analyste financier chargé d’<strong>instruire ce dossier</strong> : notation, état des engagements, analyses et suites jusqu’à la préparation de la décision.
                        La personne sélectionnée retrouvera le dossier dans <strong>son espace dédié</strong> pour mener l’instruction.
                    </p>
                    <div class="mb-0">
                        <label class="form-label fw-semibold" for="modal_analyste_user_id">Analyste</label>
                        <select name="analyste_user_id" id="modal_analyste_user_id" class="form-select" required>
                            <option value="">— Choisir —</option>
                            @foreach($analystesExploitation as $u)
                                <option value="{{ $u->id }}"{{ (int) $u->id === (int) $dossier->analyste_id ? ' selected' : '' }}>
                                    {{ $u->name }}
                                    @if($u->agence)
                                        — {{ $u->agence->name }}
                                    @else
                                        — Sans agence
                                    @endif
                                    @if($u->email)
                                        ({{ $u->email }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">{{ $dossier->analyste_id ? 'Enregistrer la réaffectation' : 'Affecter' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
