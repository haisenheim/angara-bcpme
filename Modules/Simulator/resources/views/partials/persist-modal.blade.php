<div class="modal fade" id="simulator-persist-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="simulator-persist-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('simulator::simulator.actions.persist') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('simulator::simulator.fields.name') }}</label>
                        <input type="text" name="name" class="form-control" maxlength="255" placeholder="Ex. Scenario constant 5 ans 8%">
                    </div>

                    <div class="mb-2 fw-semibold">{{ __('simulator::simulator.attachment.choose') }}</div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="attach_target" id="attach-none" value="none" checked>
                        <label class="form-check-label" for="attach-none">{{ __('simulator::simulator.attachment.none') }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="attach_target" id="attach-dossier" value="dossier">
                        <label class="form-check-label" for="attach-dossier">{{ __('simulator::simulator.attachment.choose_dossier') }}</label>
                    </div>
                    <div class="ms-4 mb-2">
                        <input type="number" min="1" name="dossier_id" class="form-control form-control-sm" placeholder="ID du dossier">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="attach_target" id="attach-programme" value="programme">
                        <label class="form-check-label" for="attach-programme">{{ __('simulator::simulator.attachment.choose_programme') }}</label>
                    </div>
                    <div class="ms-4">
                        <input type="number" min="1" name="dossier_instruction_programme_id" class="form-control form-control-sm" placeholder="ID de la ligne dossier-programme">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">{{ __('simulator::simulator.actions.persist') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
