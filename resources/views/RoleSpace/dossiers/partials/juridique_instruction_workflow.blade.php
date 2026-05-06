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
    $rejectedByReju = $dossier->isJuridiqueAnalysteRejectedByReju();
    // Verrouillage strict : l'avis est figé dès la soumission au RJU (cohérence avec AF/AC/AR).
    // Réouverture sur rejet : l'analyste peut à nouveau modifier après un rejet motivé du RJU.
    $analystCanEditAvis = $isAssignedAnalyst && (! $submittedToReju || $rejectedByReju);
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
                @include('RoleSpace.dossiers.partials._analyste_reject_banner', [
                    'rejected' => $rejectedByReju,
                    'motif' => $dossier->juridique_analyste_reject_motif,
                    'rejectedAt' => $dossier->juridique_analyste_rejected_at,
                    'rejectedBy' => $dossier->juridiqueAnalysteRejectedBy,
                    'libelleAction' => 'modifier votre avis puis le retransmettre au responsable juridique',
                ])
                @error('juridique_analyste_avis')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                <form method="post" action="{{ route('analyste-juridique.dossiers.soumettre-reju', $dossier->token) }}" id="form-analyste-juridique-soumettre" onsubmit="return confirm('Transmettre votre avis au responsable juridique ? Une fois soumis, l’avis ne sera plus modifiable depuis votre espace.');">
                    @csrf
                    <div class="mb-3 summernote-wrapper">
                        <label for="juridique_analyste_avis" class="form-label">Rédigez votre avis</label>
                        <textarea name="juridique_analyste_avis" id="juridique_analyste_avis" class="form-control js-summernote-juridique @error('juridique_analyste_avis') is-invalid @enderror" rows="10">{!! old('juridique_analyste_avis', $dossier->juridique_analyste_avis) !!}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Soumettre au responsable juridique</button>
                </form>
                <p class="small text-muted mt-2 mb-0">
                    La soumission est définitive : conformément au workflow, votre avis sera figé dès la transmission au responsable juridique (horodatage et identité enregistrés). Une réouverture nécessite une action explicite du maillon suivant.
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
                @include('RoleSpace.dossiers.partials._inter_pole_reject_banner', [
                    'rejected' => $dossier->isEngagementsRejectedToJuridique(),
                    'motif' => $dossier->engagements_rejected_to_juridique_motif,
                    'rejectedAt' => $dossier->engagements_rejected_to_juridique_at,
                    'rejectedBy' => $dossier->engagementsRejectedToJuridiqueBy,
                    'libelleAction' => 'modifier votre avis et retransmettre au responsable engagements',
                    'sourcePole' => 'responsable engagements',
                ])
                @error('juridique_responsable_avis')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('engagements')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('rejet_motif')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('rejet_analyste')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                <form method="post" action="{{ route('juridique.dossiers.responsable-avis', $dossier->token) }}" class="mb-3">
                    @csrf
                    <div class="mb-2 summernote-wrapper">
                        <label for="juridique_responsable_avis" class="form-label">Rédigez votre avis</label>
                        <textarea name="juridique_responsable_avis" id="juridique_responsable_avis" class="form-control js-summernote-juridique @error('juridique_responsable_avis') is-invalid @enderror" rows="10">{!! old('juridique_responsable_avis', $dossier->juridique_responsable_avis) !!}</textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm">Enregistrer l’avis</button>
                </form>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <form method="post" action="{{ route('juridique.dossiers.soumettre-engagements', $dossier->token) }}" onsubmit="return confirm('Transmettre ce dossier au responsable engagements ?');" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-dark">Soumettre au responsable engagements</button>
                    </form>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalRejectAnalysteJuridique">
                        Rejeter l’avis de l’analyste
                    </button>
                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalRejectVersExploitation">
                        Renvoyer au pôle exploitation
                    </button>
                </div>
                <p class="small text-muted mt-2 mb-0">Si l’avis de l’analyste juridique nécessite des corrections, vous pouvez le rejeter (motif obligatoire) : il pourra alors le modifier et vous le retransmettre. Si c’est le travail du pôle exploitation qui nécessite des corrections, vous pouvez renvoyer le dossier au responsable exploitation (motif obligatoire). Sinon, soumettez le dossier au responsable engagements.</p>
            </div>
            @include('RoleSpace.dossiers.partials._pole_analyste_reject_modal', [
                'modalId' => 'modalRejectAnalysteJuridique',
                'action' => route('juridique.dossiers.rejeter-analyste', $dossier->token),
                'titre' => 'Rejeter l’avis de l’analyste juridique',
                'description' => 'Le rejet rend l’avis de l’analyste juridique à nouveau modifiable. Le motif est obligatoire et tracé (date, heure, identité).',
            ])
            @include('RoleSpace.dossiers.partials._inter_pole_reject_modal', [
                'modalId' => 'modalRejectVersExploitation',
                'action' => route('juridique.dossiers.rejeter-vers-exploitation', $dossier->token),
                'titre' => 'Renvoyer le dossier au pôle exploitation',
                'description' => 'Le rejet inter-pôle renvoie le dossier au responsable exploitation pour révision (avis crédit, décision engagements). Le motif est obligatoire et tracé.',
                'ctaLabel' => 'Renvoyer au pôle exploitation',
            ])
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
