{{-- Modales — formulaires inchangés (routes admin.programme.*) --}}

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addModalLabel">Nouvelle composante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body pt-2">
                <form enctype="multipart/form-data" action="{{ route('admin.programme.composante.save') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $item->token }}">
                    <input type="hidden" name="programme_id" value="{{ $item->id }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="comp_name">Nom</label>
                        <input type="text" name="name" id="comp_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="organisme_id">Organisme</label>
                        <select name="organisme_id" id="organisme_id" class="form-select cmp">
                            <option value="0">Sélectionner un bailleur de fonds…</option>
                            @foreach($organismes as $it)
                                <option value="{{ $it->id }}">{{ $it->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="banque_id">Banque</label>
                        <select name="banque_id" id="banque_id" class="form-select cmp">
                            <option value="0">Sélectionner une banque…</option>
                            @foreach($banques as $it)
                                <option value="{{ $it->id }}">{{ $it->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <span class="form-label fw-semibold d-block mb-2">Axe d'intervention</span>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input id="ax_coord" checked class="form-check-input" type="radio" name="type" value="Coordination">
                                <label for="ax_coord" class="form-check-label">Coordination</label>
                            </div>
                            <div class="form-check">
                                <input id="ax_af" class="form-check-input" type="radio" name="type" value="Appuis financiers">
                                <label for="ax_af" class="form-check-label">Appuis financiers</label>
                            </div>
                            <div class="form-check">
                                <input id="ax_anf" class="form-check-input" type="radio" name="type" value="Appuis non financiers">
                                <label for="ax_anf" class="form-check-label">Appuis non financiers</label>
                            </div>
                            <div class="form-check">
                                <input id="ax_form" class="form-check-input" type="radio" name="type" value="Formation technique et professionnelle">
                                <label for="ax_form" class="form-check-label">Formation technique et professionnelle</label>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addIndModal" tabindex="-1" aria-labelledby="addIndModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addIndModalLabel">Nouvel objectif / résultat attendu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body pt-2">
                <form enctype="multipart/form-data" action="{{ route('admin.programme.resultat.save') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $item->token }}">
                    <input type="hidden" name="programme_id" value="{{ $item->id }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="indicateur_id">Indicateur</label>
                        <select required name="indicateur_id" id="indicateur_id" class="form-select cmp">
                            <option value="0">Sélectionner un indicateur…</option>
                            @foreach($indicateurs as $it)
                                <option value="{{ $it->id }}">{{ $it->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="attente">Objectif attendu</label>
                        <input required type="text" name="attente" id="attente" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addAppuiModal" tabindex="-1" aria-labelledby="addAppuiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addAppuiModalLabel">Ajouter un appui au programme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body pt-2">
                <form enctype="multipart/form-data" action="{{ route('admin.programme.appui.save') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $item->token }}">
                    <input type="hidden" name="programme_id" value="{{ $item->id }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="service_id">Service</label>
                        <select required name="service_id" id="service_id" class="form-select cmp">
                            <option value="0">Sélectionner un service…</option>
                            @foreach($services as $it)
                                <option value="{{ $it->id }}">{{ $it->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addProdModal" tabindex="-1" aria-labelledby="addProdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addProdModalLabel">Ajouter un secteur / produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body pt-2">
                <form enctype="multipart/form-data" action="{{ route('admin.programme.produit.save') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $item->token }}">
                    <input type="hidden" name="programme_id" value="{{ $item->id }}">
                    <div class="mb-3 programme-fiche-combotree-wrap">
                        <select id="produit_id" name="produit_id" class="easyui-combotree form-control"
                            data-options="url:'{{ route('util.produits.list') }}',method:'get',label:'Produit :',labelPosition:'top'">
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
