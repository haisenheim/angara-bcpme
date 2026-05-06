{{--
  Dossier d’analyse critique : rubriques AF (analyse critique par l’analyste financier).
  Variables : $doc (InstructionAnalyseCritiqueDossierDocumentService::build).
--}}
@php
    /** @var array $doc */
    $item = $doc['item'];
    $item->loadMissing('analyste');
    $titreAnalyseCritique = 'Analyse critique faite par '.($item->analyste?->name ?? 'l’analyste financier (non renseigné)');
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-semibold text-uppercase text-muted small mb-2">Chargé d’instruction</h6>
                @if($doc['analyste'])
                    <p class="mb-0"><strong>{{ $doc['analyste']->name }}</strong></p>
                    <p class="small text-muted mb-0">{{ $doc['analyste']->role?->name ?? 'Profil non renseigné' }}</p>
                @else
                    <p class="text-muted small mb-0">Non affecté ou non renseigné.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-semibold text-uppercase text-muted small mb-2">Dernière sauvegarde (brouillon / avis)</h6>
                @if($doc['saisies_meta']['edited_at'] ?? null)
                    <p class="mb-0 small">{{ $doc['saisies_meta']['edited_at']->format('d/m/Y à H:i') }}</p>
                @else
                    <p class="text-muted small mb-0">Non renseignée.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if(! $doc['has_analyste_document'])
    <div class="alert alert-warning border-0 mb-4">
        <p class="mb-0 small">Aucun contenu d’avis chargé d’instruction n’est encore disponible pour ce dossier (rubriques vides et avis non compilé).</p>
    </div>
@endif

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-body-tertiary border-0 py-3">
        <h6 class="fw-semibold mb-0 small text-muted">{{ $titreAnalyseCritique }}</h6>
        <p class="small text-muted mb-0 mt-2">Rubriques affichées séparément ; si aucune rubrique n’est en base mais un avis consolidé existe, il apparaît en complément.</p>
    </div>
    <div class="card-body pt-3">
        @include('partials.exploitation-analyste-instruction-zones', [
            'dossier' => $item,
            'showSectionTitle' => false,
            'showEmptyZones' => true,
        ])
    </div>
</div>
