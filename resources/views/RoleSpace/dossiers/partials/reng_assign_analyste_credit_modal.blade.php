{{-- Modale : affectation à un analyste crédit (resp. engagements) --}}
@php
    /** @var \Illuminate\Support\Collection $analystesCredit */
    /** @var \App\Models\Dossier $dossier */
@endphp
<div class="modal fade" id="modalAffecterAnalysteCredit" tabindex="-1" aria-labelledby="modalAffecterAnalysteCreditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{ route('reng.dossiers.assign-analyste-credit', $dossier->token) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAffecterAnalysteCreditLabel">{{ $dossier->reng_analyste_credit_user_id ? 'Réaffecter un analyste crédit' : 'Affecter un analyste crédit' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Sélectionnez l’analyste crédit en charge de l’étude de ce dossier.
                        L’affectation (ou la réaffectation) est tracée avec la date, l’heure et l’auteur.
                    </p>
                    <div class="mb-0">
                        <label class="form-label fw-semibold" for="modal_reng_analyste_credit_user_id">Analyste crédit</label>
                        <select name="reng_analyste_credit_user_id" id="modal_reng_analyste_credit_user_id" class="form-select" required>
                            <option value="">— Choisir —</option>
                            @foreach($analystesCredit as $u)
                                <option value="{{ $u->id }}"{{ (int) $u->id === (int) $dossier->reng_analyste_credit_user_id ? ' selected' : '' }}>
                                    {{ $u->name }}
                                    @if($u->agence)
                                        — {{ $u->agence->name }}
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
                    <button type="submit" class="btn btn-primary">{{ $dossier->reng_analyste_credit_user_id ? 'Enregistrer la réaffectation' : 'Affecter' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
