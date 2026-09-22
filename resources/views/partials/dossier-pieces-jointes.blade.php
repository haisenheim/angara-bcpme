{{--
  Pièces jointes au dossier : tableau ; optionnellement bouton + modal d’upload.
  @param \App\Models\Dossier $dossier
  @param bool $showUpload  Afficher dépôt (défaut : true)
  @param string|null $routePiecesStore  Nom de route POST si showUpload
  @param string|null $routePiecesDestroy  Nom de route DELETE (déduit de routePiecesStore si absent)
  @param string|null $routePiecesUpdate  Nom de route PUT (déduit de routePiecesStore si absent)
  @param string $modalId  ID unique du modal
  @param \Illuminate\Support\Collection|null $fichierTypes  Types pour le formulaire
--}}
@php
    $dossier = $dossier ?? null;
    $showUpload = (bool) ($showUpload ?? true);
    $modalId = $modalId ?? 'dossierPieceUploadModal';
    $fichierTypes = $fichierTypes ?? collect();
    $routePiecesDestroy = $routePiecesDestroy ?? (isset($routePiecesStore) ? preg_replace('/\.store$/', '.destroy', $routePiecesStore) : null);
    $routePiecesUpdate = $routePiecesUpdate ?? (isset($routePiecesStore) ? preg_replace('/\.store$/', '.update', $routePiecesStore) : null);
    $canManageOwnPieces = (bool) ($routePiecesDestroy || $routePiecesUpdate);
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
                            @if($canManageOwnPieces)
                                <th class="text-end" style="width: 1%;">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dossier->fichiersDossier as $f)
                            @php
                                $isOwnPiece = $f->uploaded_by_user_id && (int) $f->uploaded_by_user_id === (int) auth()->id();
                                $editModalId = $modalId.'_edit_'.$f->id;
                            @endphp
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
                                @if($canManageOwnPieces)
                                    <td class="text-end">
                                        @if($isOwnPiece)
                                            <div class="d-inline-flex gap-1">
                                                @if($routePiecesUpdate)
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#{{ $editModalId }}" title="Modifier la pièce que vous avez déposée">
                                                        <i class="demo-psi-pencil"></i>
                                                    </button>
                                                @endif
                                                @if($routePiecesDestroy)
                                                    <form method="post" action="{{ route($routePiecesDestroy, ['token' => $dossier->token, 'piece' => $f->id]) }}" class="d-inline" onsubmit="return confirm('Supprimer cette pièce jointe ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer la pièce que vous avez déposée">
                                                            <i class="demo-psi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endif
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
                <input type="hidden" name="dossier_piece_form" value="create">
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
@endif

@if($routePiecesUpdate && $fichierTypes->isNotEmpty())
    @foreach($dossier->fichiersDossier as $f)
        @if($f->uploaded_by_user_id && (int) $f->uploaded_by_user_id === (int) auth()->id())
            @php $editModalId = $modalId.'_edit_'.$f->id; @endphp
            <div class="modal fade" id="{{ $editModalId }}" tabindex="-1" aria-labelledby="{{ $editModalId }}Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="{{ $editModalId }}Label">Modifier la pièce</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <form action="{{ route($routePiecesUpdate, ['token' => $dossier->token, 'piece' => $f->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="dossier_piece_form" value="update">
                            <input type="hidden" name="dossier_piece_id" value="{{ $f->id }}">
                            <div class="modal-body">
                                <p class="small text-muted mb-3">Fichier actuel : <strong>{{ $f->original_name ?: basename($f->name ?? '') }}</strong></p>
                                <div class="mb-3">
                                    <label for="{{ $editModalId }}_type" class="form-label">Type de pièce</label>
                                    <select name="type_id" id="{{ $editModalId }}_type" class="form-select @if(old('dossier_piece_id') == $f->id) @error('type_id') is-invalid @enderror @endif" required>
                                        @foreach($fichierTypes as $ft)
                                            <option value="{{ $ft->id }}" @selected(
                                                old('dossier_piece_id') == $f->id
                                                    ? (string) old('type_id') === (string) $ft->id
                                                    : (string) $f->type_id === (string) $ft->id
                                            )>{{ $ft->name }}</option>
                                        @endforeach
                                    </select>
                                    @if(old('dossier_piece_id') == $f->id)
                                        @error('type_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    @endif
                                </div>
                                <div class="mb-0">
                                    <label for="{{ $editModalId }}_file" class="form-label">Nouveau fichier (PDF, JPG ou PNG, max. 10 Mo)</label>
                                    <input type="file" name="fichier" id="{{ $editModalId }}_file" class="form-control @if(old('dossier_piece_id') == $f->id) @error('fichier') is-invalid @enderror @endif" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png" required>
                                    @if(old('dossier_piece_id') == $f->id)
                                        @error('fichier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @endif
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
        @endif
    @endforeach
@endif

@if($errors->has('fichier') || $errors->has('type_id'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    var formKind = @json(old('dossier_piece_form', 'create'));
    var pieceId = @json(old('dossier_piece_id'));
    var targetId = formKind === 'update' && pieceId
        ? @json($modalId) + '_edit_' + pieceId
        : @json($modalId);
    var el = document.getElementById(targetId);
    if (el && window.bootstrap) {
        bootstrap.Modal.getOrCreateInstance(el).show();
    }
});
</script>
@endif
@endif
