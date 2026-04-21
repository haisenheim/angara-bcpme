{{-- Modale : affectation à un analyste juridique (resp. juridique) --}}
@php
    /** @var \Illuminate\Support\Collection $analystesJuridique */
    /** @var \App\Models\Dossier $dossier */
@endphp
<div class="modal fade" id="modalAffecterAnalysteJuridique" tabindex="-1" aria-labelledby="modalAffecterAnalysteJuridiqueLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{ route('juridique.dossiers.assign-juridique-analyste', $dossier->token) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAffecterAnalysteJuridiqueLabel">{{ $dossier->juridique_analyste_user_id ? 'Réaffecter à un autre analyste juridique' : 'Affecter à un analyste juridique' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Profil analyste juridique (config. <code>role_analyste_juridique</code>, id {{ (int) config('angara.role_analyste_juridique', 19) }}).
                        L’affectation est tracée (date, heure et auteur).
                    </p>
                    <div class="mb-0">
                        <label class="form-label fw-semibold" for="modal_juridique_analyste_user_id">Analyste juridique</label>
                        <select name="juridique_analyste_user_id" id="modal_juridique_analyste_user_id" class="form-select" required>
                            <option value="">— Choisir —</option>
                            @foreach($analystesJuridique as $u)
                                <option value="{{ $u->id }}"{{ (int) $u->id === (int) $dossier->juridique_analyste_user_id ? ' selected' : '' }}>
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
                    <button type="submit" class="btn btn-primary">{{ $dossier->juridique_analyste_user_id ? 'Enregistrer la réaffectation' : 'Affecter' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
