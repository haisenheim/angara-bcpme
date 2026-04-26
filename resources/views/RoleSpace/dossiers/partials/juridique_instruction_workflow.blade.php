{{-- Workflow pôle juridique : affectation analyste → avis analyste → avis resp. → engagements --}}
@php
    /** @var \App\Models\Dossier $dossier */
    $r = $space['route'] ?? '';
    $isReju = $r === 'juridique';
    $isAnalysteAj = $r === 'analyste-juridique';
    $profilAjId = (int) config('angara.role_analyste_juridique', 19);
    $submittedToReju = $dossier->isJuridiqueAnalysteAvisSubmittedToReju();
    $submittedToEngagements = $dossier->isSubmittedToEngagementsFromJuridique();
    $isAssignedAnalyst = $isAnalysteAj && (int) $dossier->juridique_analyste_user_id === (int) auth()->id();
    $analystCanEditAvis = $isAssignedAnalyst && ! $submittedToEngagements;
@endphp

<div class="card shadow-sm border-0 mb-4 border-start border-4 border-primary">
    <div class="card-header bg-white py-3">
        <strong>Pôle juridique — affectation et avis</strong>
        <p class="small text-muted mb-0 mt-1">Analyste juridique (profil {{ $profilAjId }}), puis responsable juridique, puis transmission au responsable engagements.</p>
    </div>
    <div class="card-body">
        @if($dossier->juridique_analyste_user_id)
            <div class="mb-4 pb-3 border-bottom">
                <h6 class="fw-semibold mb-2">Analyste juridique affecté</h6>
                <p class="mb-1"><strong>{{ $dossier->juridiqueAnalysteUser?->name ?? '—' }}</strong>
                    @if($dossier->juridiqueAnalysteUser?->email)<span class="text-muted small"> — {{ $dossier->juridiqueAnalysteUser->email }}</span>@endif
                </p>
                @if($dossier->juridique_analyste_assigned_at)
                    <p class="small text-muted mb-0">
                        Affectation le {{ $dossier->juridique_analyste_assigned_at->format('d/m/Y') }} à {{ $dossier->juridique_analyste_assigned_at->format('H:i') }}
                        @if($dossier->juridiqueAnalysteAssignedBy)
                            — par <strong>{{ $dossier->juridiqueAnalysteAssignedBy->name }}</strong>
                        @endif
                    </p>
                @endif
            </div>
        @elseif($isReju)
            <div class="alert alert-light border mb-0">
                <p class="mb-0 small">Aucun analyste juridique n’est encore affecté. Utilisez le menu <strong>Actions</strong> → <strong>Affecter un analyste juridique</strong>.</p>
            </div>
        @endif

        @if($analystCanEditAvis)
            <div class="mb-4">
                <h6 class="fw-semibold mb-2">Votre avis (analyste juridique)</h6>
                @if($submittedToReju && $dossier->juridique_analyste_submitted_to_reju_at)
                    <p class="small text-muted mb-2">
                        Première transmission au responsable juridique le {{ $dossier->juridique_analyste_submitted_to_reju_at->format('d/m/Y H:i') }}
                        @if($dossier->juridiqueAnalysteSubmittedToRejuBy)
                            — <strong>{{ $dossier->juridiqueAnalysteSubmittedToRejuBy->name }}</strong>
                        @endif
                    </p>
                @endif
                @error('juridique_analyste_avis')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                <form method="post" action="{{ route('analyste-juridique.dossiers.soumettre-reju', $dossier->token) }}" id="form-analyste-juridique-soumettre">
                    @csrf
                    <div class="mb-3 summernote-wrapper">
                        <label for="juridique_analyste_avis" class="form-label">Rédigez votre avis</label>
                        <textarea name="juridique_analyste_avis" id="juridique_analyste_avis" class="form-control js-summernote-juridique @error('juridique_analyste_avis') is-invalid @enderror" rows="10">{!! old('juridique_analyste_avis', $dossier->juridique_analyste_avis) !!}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ $submittedToReju ? 'Enregistrer la mise à jour de l’avis' : 'Soumettre au responsable juridique' }}</button>
                </form>
                <p class="small text-muted mt-2 mb-0">
                    @if($submittedToReju)
                        Vous pouvez encore modifier votre avis tant que le responsable juridique n’a pas transmis le dossier au <strong>responsable engagements</strong>. La date et l’auteur de la première transmission au responsable juridique restent affichées ci-dessous.
                    @else
                        Après la première soumission, le responsable juridique est informé (horodatage et identité enregistrés). Vous pourrez encore ajuster l’avis jusqu’à la transmission du dossier au responsable engagements.
                    @endif
                </p>
            </div>
        @elseif($submittedToReju)
            <div class="mb-4">
                <h6 class="fw-semibold mb-2">Avis de l’analyste juridique</h6>
                @if($dossier->juridique_analyste_submitted_to_reju_at)
                    <p class="small text-muted mb-2">
                        Soumis le {{ $dossier->juridique_analyste_submitted_to_reju_at->format('d/m/Y H:i') }}
                        @if($dossier->juridiqueAnalysteSubmittedToRejuBy)
                            — <strong>{{ $dossier->juridiqueAnalysteSubmittedToRejuBy->name }}</strong>
                        @endif
                    </p>
                @endif
                <div class="border rounded p-3 bg-light small rich-text-rendered">{!! $dossier->juridique_analyste_avis !!}</div>
            </div>
        @endif

        @if($isReju && $submittedToReju && ! $submittedToEngagements)
            <div class="mb-0">
                <h6 class="fw-semibold mb-2">Avis du responsable juridique</h6>
                @error('juridique_responsable_avis')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('engagements')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                <form method="post" action="{{ route('juridique.dossiers.responsable-avis', $dossier->token) }}" class="mb-3">
                    @csrf
                    <div class="mb-2 summernote-wrapper">
                        <label for="juridique_responsable_avis" class="form-label">Rédigez votre avis</label>
                        <textarea name="juridique_responsable_avis" id="juridique_responsable_avis" class="form-control js-summernote-juridique @error('juridique_responsable_avis') is-invalid @enderror" rows="10">{!! old('juridique_responsable_avis', $dossier->juridique_responsable_avis) !!}</textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm">Enregistrer l’avis</button>
                </form>
                <form method="post" action="{{ route('juridique.dossiers.soumettre-engagements', $dossier->token) }}" onsubmit="return confirm('Transmettre ce dossier au responsable engagements ?');">
                    @csrf
                    <button type="submit" class="btn btn-dark">Soumettre au responsable engagements</button>
                </form>
                <p class="small text-muted mt-2 mb-0">Vous pouvez modifier et ré-enregistrer votre avis autant que nécessaire tant que vous n’avez pas cliqué sur <strong>Soumettre au responsable engagements</strong>. La transmission enregistre la date, l’heure et votre identité ; l’avis du responsable juridique doit être renseigné avant l’envoi.</p>
            </div>
        @elseif($submittedToEngagements)
            <div class="mt-3 pt-3 border-top">
                <h6 class="fw-semibold mb-2">Transmission au responsable engagements</h6>
                <p class="small text-success mb-2">
                    <strong>Dossier transmis</strong>
                    @if($dossier->juridique_submitted_to_engagements_at)
                        le {{ $dossier->juridique_submitted_to_engagements_at->format('d/m/Y H:i') }}
                    @endif
                    @if($dossier->juridiqueSubmittedToEngagementsBy)
                        — <strong>{{ $dossier->juridiqueSubmittedToEngagementsBy->name }}</strong>
                    @endif
                </p>
                @if($isReju && $dossier->juridique_responsable_avis)
                    <h6 class="fw-semibold mb-2 mt-3">Avis du responsable juridique (conservé)</h6>
                    <div class="border rounded p-3 bg-light small rich-text-rendered">{!! $dossier->juridique_responsable_avis !!}</div>
                @endif
            </div>
        @endif
    </div>
</div>
