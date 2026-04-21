{{-- Modale : affectation à un analyste risques (resp. risques) --}}
@php
    /** @var \Illuminate\Support\Collection $analystesRisques */
    /** @var \App\Models\Dossier $dossier */
@endphp
<div class="modal fade" id="modalAffecterAnalysteRisques" tabindex="-1" aria-labelledby="modalAffecterAnalysteRisquesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{ route('rerx.dossiers.assign-analyste-risques', $dossier->token) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAffecterAnalysteRisquesLabel">{{ $dossier->rerx_analyste_risques_user_id ? 'Réaffecter un analyste risques' : 'Affecter un analyste risques' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Profil analyste risques (config. <code>role_analyste_risques</code>, id {{ (int) config('angara.role_analyste_risques', 18) }}).
                        L’affectation est tracée (date, heure et auteur).
                    </p>
                    <div class="mb-0">
                        <label class="form-label fw-semibold" for="modal_rerx_analyste_risques_user_id">Analyste risques</label>
                        <select name="rerx_analyste_risques_user_id" id="modal_rerx_analyste_risques_user_id" class="form-select" required>
                            <option value="">— Choisir —</option>
                            @foreach($analystesRisques as $u)
                                <option value="{{ $u->id }}"{{ (int) $u->id === (int) $dossier->rerx_analyste_risques_user_id ? ' selected' : '' }}>
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
                    <button type="submit" class="btn btn-primary">{{ $dossier->rerx_analyste_risques_user_id ? 'Enregistrer la réaffectation' : 'Affecter' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
