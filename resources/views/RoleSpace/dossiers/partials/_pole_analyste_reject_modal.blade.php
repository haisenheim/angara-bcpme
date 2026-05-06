{{--
    Modale de rejet d'une soumission analyste par le responsable de pôle (workflow 2).
    Réutilisable pour les 4 pôles (Exploitation, Juridique, Engagements, Risques).

    Variables attendues :
    - $modalId       : identifiant unique du modal (ex. "modalRejectAnalysteJuridique")
    - $action        : URL de soumission (route POST de rejet)
    - $titre         : intitulé affiché (ex. "Rejeter l'avis de l'analyste juridique")
    - $description   : phrase explicative courte (optionnel)
--}}
@php
    $modalId = $modalId ?? 'modalRejectAnalystePole';
    $titre = $titre ?? 'Rejeter la soumission de l’analyste';
    $description = $description ?? 'Le rejet rend la soumission de l’analyste à nouveau modifiable. Le motif est obligatoire et tracé (date, heure, identité).';
@endphp
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Label">{{ $titre }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="post" action="{{ $action }}" onsubmit="return confirm('Confirmer le rejet ? L’analyste sera notifié et pourra modifier puis retransmettre.');">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">{{ $description }}</p>
                    <label class="form-label small" for="{{ $modalId }}_motif">Motif du rejet <span class="text-danger" aria-hidden="true">*</span></label>
                    <textarea class="form-control @error('rejet_motif') is-invalid @enderror" id="{{ $modalId }}_motif" name="rejet_motif" rows="4" maxlength="5000" required minlength="1" placeholder="Indiquez ce qui doit être corrigé (obligatoire)">{{ old('rejet_motif') }}</textarea>
                    @error('rejet_motif')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">L’analyste reçoit le motif par e-mail et dans son espace ; il peut alors corriger sa saisie et retransmettre.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter la soumission</button>
                </div>
            </form>
        </div>
    </div>
</div>
