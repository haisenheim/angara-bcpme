@php /** @var array<string,string> $kindLabels */ @endphp

<div class="modal fade" id="partenaireModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="engagementPartenaireForm" method="post" action="{{ route('engagements.partenaires.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nouveau partenaire financier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Type</label>
                            <select name="kind" class="form-select" required>
                                @foreach($kindLabels as $code => $label)
                                    <option value="{{ $code }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Raison sociale</label>
                            <input type="text" class="form-control" name="name" required maxlength="255">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Siège</label>
                            <input type="text" class="form-control" name="siege" maxlength="255">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Adresse / contact</label>
                            <input type="text" class="form-control" name="address" maxlength="1000">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
