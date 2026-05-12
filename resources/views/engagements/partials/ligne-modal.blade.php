@php
    /** @var \App\Models\Entreprise $entreprise */
    /** @var \Illuminate\Support\Collection $produits */
    /** @var array $partenaires */
    /** @var array<string,string> $statuts */
@endphp

<div class="modal fade" id="engagementLigneModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form method="post" action="{{ route('engagements.lignes.store', $entreprise->token) }}">
                @csrf
                <input type="hidden" name="_method" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle ligne d’engagement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Catégorie / produit</label>

                            <div class="form-control bg-light border d-flex align-items-center justify-content-between"
                                 data-mode="categorie-readonly">
                                <strong>—</strong>
                                <small class="text-muted">prédéfini</small>
                            </div>

                            <select name="engagement_categorie_id" data-categorie-id-input
                                    data-mode="categorie-select" class="form-select d-none">
                                <option value="">— Sélectionner un produit —</option>
                                @foreach($produits as $p)
                                    <option value="{{ $p->id }}">{{ $p->libelle }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="engagementLigneModal_partenaire">
                                Partenaire financier
                                <small class="text-muted">(banque, EMF ou autre)</small>
                            </label>
                            <select id="engagementLigneModal_partenaire" name="partenaire_id"
                                    class="form-select engagement-partenaire-select">
                                <option value="">— Aucun partenaire spécifique —</option>
                                @foreach(['banques' => 'Banques', 'emfs' => 'EMF', 'autres' => 'Autres partenaires'] as $key => $label)
                                    @if(! empty($partenaires[$key]))
                                        <optgroup label="{{ $label }}">
                                            @foreach($partenaires[$key] as $partenaire)
                                                <option value="{{ $partenaire->id }}">{{ $partenaire->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <fieldset class="mb-3">
                        <legend class="form-label small text-uppercase fw-semibold text-primary">Encours</legend>
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label">Initial</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="encours_initial" value="0">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label">Actuel</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="encours_actuel" value="0">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label">Remboursement N-1</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="encours_remboursement_n1" value="0">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label">Retards</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="encours_retards" value="0">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label">Impayés</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="encours_impayes" value="0">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label">Statut</label>
                                <select name="encours_statut" class="form-select">
                                    <option value="">—</option>
                                    @foreach($statuts as $code => $label)
                                        <option value="{{ $code }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label">Date de validité</label>
                                <input type="date" class="form-control" name="encours_date_validite">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mb-3">
                        <legend class="form-label small text-uppercase fw-semibold text-primary">Sollicité</legend>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Montant</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="sollicite_montant" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date de validité</label>
                                <input type="date" class="form-control" name="sollicite_date_validite">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend class="form-label small text-uppercase fw-semibold text-primary">Note / commentaire</legend>
                        <textarea name="commentaire" class="form-control" rows="3"
                                  placeholder="Précisions, conditions, garanties associées, références internes…"></textarea>
                    </fieldset>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
