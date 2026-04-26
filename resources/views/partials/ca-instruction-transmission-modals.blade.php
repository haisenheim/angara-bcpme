{{-- Formulaires POST : validation / rejet de la structuration soumise (chef de filière → chef d’agence). --}}
@php
    /** @var \App\Models\Dossier $dossier */
@endphp
<div class="modal fade" id="caInstructionTransmissionApproveModal" tabindex="-1" aria-labelledby="caInstructionTransmissionApproveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="caInstructionTransmissionApproveModalLabel">Valider la structuration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="post" action="{{ route('ca.workflow.instruction-dossiers.approve', $dossier->token) }}">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">Confirmez la validation de la structuration soumise par le chef de filière (décision chef d’agence).</p>
                    <label class="form-label small" for="ca_instruction_approve_note">Commentaire interne (optionnel)</label>
                    <textarea class="form-control" id="ca_instruction_approve_note" name="closing_note" rows="3" maxlength="5000" placeholder="Note éventuelle pour le suivi interne"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Valider la structuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="caInstructionTransmissionRejectModal" tabindex="-1" aria-labelledby="caInstructionTransmissionRejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="caInstructionTransmissionRejectModalLabel">Rejeter la structuration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="post" action="{{ route('ca.workflow.instruction-dossiers.reject', $dossier->token) }}">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">Le chef de filière pourra corriger la structuration et la soumettre à nouveau.</p>
                    <div class="mb-3">
                        <label class="form-label" for="ca_instruction_reject_motif">Motif du rejet</label>
                        <textarea class="form-control" id="ca_instruction_reject_motif" name="reject_motif" rows="4" maxlength="5000" placeholder="Expliquez le motif du rejet…"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small" for="ca_instruction_reject_note">Commentaire interne (optionnel)</label>
                        <textarea class="form-control" id="ca_instruction_reject_note" name="closing_note" rows="2" maxlength="5000"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter la structuration</button>
                </div>
            </form>
        </div>
    </div>
</div>
