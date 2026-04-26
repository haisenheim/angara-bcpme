{{--
  Pièces jointes au dossier : tableau ; optionnellement bouton + modal d’upload.
  @param \App\Models\Dossier $dossier
  @param bool $showUpload  Afficher dépôt (défaut : true)
  @param string|null $routePiecesStore  Nom de route POST si showUpload
  @param string $modalId  ID unique du modal
  @param \Illuminate\Support\Collection|null $fichierTypes  Types pour le formulaire
--}}
@php
    $dossier = $dossier ?? null;
    $showUpload = (bool) ($showUpload ?? true);
    $modalId = $modalId ?? 'dossierPieceUploadModal';
    $fichierTypes = $fichierTypes ?? collect();
@endphp
@if($dossier)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-transparent border-0 py-3 @if($showUpload && isset($routePiecesStore)) d-flex align-items-center justify-content-between flex-wrap gap-2 @endif">
        <h6 class="mb-0 fw-semibold"><i class="demo-psi-folder me-2 text-primary"></i>Pièces jointes au dossier</h6>
        @if($showUpload && isset($routePiecesStore))
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                <i class="demo-psi-upload me-1"></i> Ajouter une pièce
            </button>
        @endif
    </div>
    <div class="card-body pt-0">
        @if($dossier->fichiersDossier->isEmpty())
            <p class="text-muted small mb-0">Aucune pièce n’a encore été déposée sur ce dossier.</p>
        @else
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Fichier</th>
                            <th>Date et heure</th>
                            <th>Déposé par</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dossier->fichiersDossier as $f)
                            <tr>
                                <td>{{ $f->type?->name ?? '—' }}</td>
                                <td>
                                    @if($f->name)
                                        <a href="{{ asset('files/'.$f->name) }}" target="_blank" rel="noopener">{{ $f->original_name ?: basename($f->name) }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $f->uploaded_at ? $f->uploaded_at->format('d/m/Y H:i') : '—' }}</td>
                                <td>{{ $f->uploadedBy?->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@if($showUpload && isset($routePiecesStore))
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Label">Ajouter une pièce au dossier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="{{ route($routePiecesStore, $dossier->token) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="{{ $modalId }}_type" class="form-label">Type de pièce</label>
                        <select name="type_id" id="{{ $modalId }}_type" class="form-select @error('type_id') is-invalid @enderror" required>
                            <option value="" disabled @selected(! old('type_id'))>Choisir…</option>
                            @foreach($fichierTypes as $ft)
                                <option value="{{ $ft->id }}" @selected((string) old('type_id') === (string) $ft->id)>{{ $ft->name }}</option>
                            @endforeach
                        </select>
                        @error('type_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-0">
                        <label for="{{ $modalId }}_file" class="form-label">Fichier (PDF, JPG ou PNG, max. 10 Mo)</label>
                        <input type="file" name="fichier" id="{{ $modalId }}_file" class="form-control @error('fichier') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png" required>
                        @error('fichier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@if($errors->has('fichier') || $errors->has('type_id'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById(@json($modalId));
    if (el && window.bootstrap) {
        bootstrap.Modal.getOrCreateInstance(el).show();
    }
});
</script>
@endif
@endif
@endif
