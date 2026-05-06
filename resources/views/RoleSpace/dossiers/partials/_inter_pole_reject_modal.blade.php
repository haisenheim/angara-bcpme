{{--
    Modale de rejet inter-pôle (workflow 2 — prompt l. 220).

    Permet à un responsable de pôle de renvoyer le dossier au pôle précédent (réouverture)
    avec un motif obligatoire, sans clôturer.

    Variables attendues :
    - $modalId       : identifiant unique du modal (ex. "modalRejectVersExploitation")
    - $action        : URL de soumission (route POST de rejet inter-pôle)
    - $titre         : intitulé affiché
    - $description   : phrase explicative courte (optionnel)
    - $ctaLabel      : libellé du bouton de confirmation (ex. "Renvoyer au pôle exploitation")
--}}
@php
    $modalId = $modalId ?? 'modalRejectInterPole';
    $titre = $titre ?? 'Renvoyer le dossier au pôle précédent';
    $description = $description ?? 'Le rejet inter-pôle renvoie le dossier au responsable du pôle précédent qui pourra modifier son avis et retransmettre. Le motif est obligatoire et tracé (date, heure, identité).';
    $ctaLabel = $ctaLabel ?? 'Renvoyer au pôle précédent';
@endphp
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Label">{{ $titre }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="post" action="{{ $action }}" onsubmit="return confirm('Confirmer le renvoi au pôle précédent ? Le responsable concerné sera notifié et pourra modifier puis retransmettre.');">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">{{ $description }}</p>
                    <label class="form-label small" for="{{ $modalId }}_motif">Motif du rejet inter-pôle <span class="text-danger" aria-hidden="true">*</span></label>
                    <textarea class="form-control @error('rejet_motif') is-invalid @enderror" id="{{ $modalId }}_motif" name="rejet_motif" rows="4" maxlength="5000" required minlength="1" placeholder="Indiquez ce qui doit être corrigé par le pôle précédent (obligatoire)">{{ old('rejet_motif') }}</textarea>
                    @error('rejet_motif')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Le responsable du pôle précédent reçoit le motif par e-mail et dans son espace ; il peut alors corriger et retransmettre.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">{{ $ctaLabel }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
