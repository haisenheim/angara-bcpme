{{-- Consultation détail ligne — tous les profils (lecture seule) --}}
<div class="modal fade" id="engagementLigneDetailModal" tabindex="-1" aria-labelledby="engagementLigneDetailModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border border-secondary border-2 rounded-3 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="engagementLigneDetailModalLabel">
                    <i class="demo-psi-eye me-2"></i>Détail de la ligne d’engagement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="border rounded-2 p-2 mb-3 small text-muted bg-light">
                    Montants en <strong>XAF</strong>.
                </div>

                <div class="border rounded-2 p-3 mb-3 engagement-detail-section">
                    <h6 class="text-primary text-uppercase small fw-semibold mb-2 mb-md-3 border-bottom pb-2">Produit / catégorie</h6>
                    <p class="mb-0 fw-medium" data-detail="categorie">—</p>
                </div>

                <div class="border rounded-2 p-3 mb-3 engagement-detail-section">
                    <h6 class="text-primary text-uppercase small fw-semibold mb-2 mb-md-3 border-bottom pb-2">Partenaire financier</h6>
                    <dl class="row mb-0 small">
                        <dt class="col-sm-4 text-muted">Nom</dt>
                        <dd class="col-sm-8" data-detail="partenaire_nom">—</dd>
                        <dt class="col-sm-4 text-muted">Type</dt>
                        <dd class="col-sm-8 mb-0" data-detail="partenaire_kind_label">—</dd>
                    </dl>
                </div>

                <div class="border rounded-2 p-3 mb-3 engagement-detail-section">
                    <h6 class="text-primary text-uppercase small fw-semibold mb-2 mb-md-3 border-bottom pb-2">Encours</h6>
                    <dl class="row mb-0 small">
                        <dt class="col-sm-4 text-muted">Initial</dt>
                        <dd class="col-sm-8 text-end" data-detail="encours_initial">—</dd>
                        <dt class="col-sm-4 text-muted">Actuel</dt>
                        <dd class="col-sm-8 text-end" data-detail="encours_actuel">—</dd>
                        <dt class="col-sm-4 text-muted">Remboursement N-1</dt>
                        <dd class="col-sm-8 text-end" data-detail="encours_remboursement_n1">—</dd>
                        <dt class="col-sm-4 text-muted">Retards</dt>
                        <dd class="col-sm-8 text-end" data-detail="encours_retards">—</dd>
                        <dt class="col-sm-4 text-muted">Impayés</dt>
                        <dd class="col-sm-8 text-end text-danger" data-detail="encours_impayes">—</dd>
                        <dt class="col-sm-4 text-muted">Statut</dt>
                        <dd class="col-sm-8" data-detail="encours_statut_label">—</dd>
                        <dt class="col-sm-4 text-muted mb-0">Date de validité</dt>
                        <dd class="col-sm-8 mb-0" data-detail="encours_date_validite">—</dd>
                    </dl>
                </div>

                <div class="border rounded-2 p-3 mb-3 engagement-detail-section">
                    <h6 class="text-primary text-uppercase small fw-semibold mb-2 mb-md-3 border-bottom pb-2">Sollicité</h6>
                    <dl class="row mb-0 small">
                        <dt class="col-sm-4 text-muted">Montant</dt>
                        <dd class="col-sm-8 text-end" data-detail="sollicite_montant">—</dd>
                        <dt class="col-sm-4 text-muted mb-0">Date de validité</dt>
                        <dd class="col-sm-8 mb-0" data-detail="sollicite_date_validite">—</dd>
                    </dl>
                </div>

                <div class="border rounded-2 p-3 mb-3 engagement-detail-section">
                    <h6 class="text-primary text-uppercase small fw-semibold mb-2 mb-md-3 border-bottom pb-2">Synthèse</h6>
                    <dl class="row mb-0 small">
                        <dt class="col-sm-4 text-muted">Total engagement</dt>
                        <dd class="col-sm-8 text-end fw-semibold" data-detail="total_montant">—</dd>
                        <dt class="col-sm-4 text-muted mb-0">Variation</dt>
                        <dd class="col-sm-8 text-end mb-0" data-detail="variation">—</dd>
                    </dl>
                </div>

                <div class="border rounded-2 p-3 mb-3 engagement-detail-section">
                    <h6 class="text-primary text-uppercase small fw-semibold mb-2 mb-md-3 border-bottom pb-2">Note / commentaire</h6>
                    <div class="border rounded p-2 small bg-light" data-detail="commentaire"
                         style="white-space: pre-wrap; word-break: break-word; min-height: 2.5rem;">—</div>
                </div>

                <p class="text-muted small mb-0" data-detail="meta"></p>
            </div>
            <div class="modal-footer py-2 border-top">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
