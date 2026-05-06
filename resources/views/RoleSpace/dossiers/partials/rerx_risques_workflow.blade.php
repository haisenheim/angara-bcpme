{{-- Workflow pôle risques : analyste risques → resp. risques → direction (DG & DGA) --}}
@php
    /** @var \App\Models\Dossier $dossier */
    $r = $space['route'] ?? '';
    $isRerx = $r === 'rerx';
    $isAnalysteAr = $r === 'analyste-risques';
    $profilArId = (int) config('angara.role_analyste_risques', 18);
    $submittedByAnalyste = $dossier->isRerxAnalysteRisquesSubmittedToRerx();
    $submittedToDirection = $dossier->isSubmittedToDirectionFromRerx();
    $isAssignedAr = $isAnalysteAr && (int) $dossier->rerx_analyste_risques_user_id === (int) auth()->id();
    $rejectedByRerx = $dossier->isRerxAnalysteRisquesRejectedByRisq();
    // Réouverture sur rejet : l'analyste peut à nouveau modifier après un rejet motivé du RISQ.
    $analysteCanEdit = $isAssignedAr && (! $submittedByAnalyste || $rejectedByRerx);
@endphp

<div class="card shadow-sm border-0 mb-4 border-start border-4 border-warning">
    <div class="card-header bg-white py-3">
        <strong>Pôle risques — analyste risques &amp; transmission direction</strong>
        <p class="small text-muted mb-0 mt-1">Analyste risques (profil {{ $profilArId }}), puis avis du responsable risques, puis envoi au directeur général (profil {{ (int) config('angara.role_dg', 4) }}) et au DGA (profil {{ (int) config('angara.role_dga', 5) }}).</p>
    </div>
    <div class="card-body">
        @if($dossier->rerx_analyste_risques_user_id)
            <div class="mb-4 pb-3 border-bottom">
                <h6 class="fw-semibold mb-2">Analyste risques affecté</h6>
                <p class="mb-1"><strong>{{ $dossier->rerxAnalysteRisquesUser?->name ?? '—' }}</strong>
                    @if($dossier->rerxAnalysteRisquesUser?->email)<span class="text-muted small"> — {{ $dossier->rerxAnalysteRisquesUser->email }}</span>@endif
                </p>
                @if($dossier->rerx_analyste_risques_assigned_at)
                    <p class="small text-muted mb-0">
                        Affectation le {{ $dossier->rerx_analyste_risques_assigned_at->format('d/m/Y') }} à {{ $dossier->rerx_analyste_risques_assigned_at->format('H:i') }}
                        @if($dossier->rerxAnalysteRisquesAssignedBy)
                            — par <strong>{{ $dossier->rerxAnalysteRisquesAssignedBy->name }}</strong>
                        @endif
                    </p>
                @endif
            </div>
        @elseif($isRerx)
            <div class="alert alert-light border mb-0">
                <p class="mb-0 small">Aucun analyste risques n’est encore affecté. Utilisez le menu <strong>Actions</strong> → <strong>Affecter un analyste risques</strong>.</p>
            </div>
        @endif

        @if($analysteCanEdit)
            <div class="mb-3">
                <h6 class="fw-semibold mb-2">Votre travail (analyste risques)</h6>
                @include('RoleSpace.dossiers.partials._analyste_reject_banner', [
                    'rejected' => $rejectedByRerx,
                    'motif' => $dossier->rerx_analyste_risques_reject_motif,
                    'rejectedAt' => $dossier->rerx_analyste_risques_rejected_at,
                    'rejectedBy' => $dossier->rerxAnalysteRisquesRejectedBy,
                    'libelleAction' => 'corriger l’analyse / l’avis et retransmettre',
                ])
                @error('draft')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('submit')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('rerx_analyse_risques')<div class="text-danger small mb-1">{{ $message }}</div>@enderror
                @error('rerx_analyste_risques_avis')<div class="text-danger small mb-1">{{ $message }}</div>@enderror

                <form method="post" id="form-analyste-risques-rerx" class="mb-0">
                    @csrf
                    <div class="mb-3 summernote-wrapper">
                        <label class="form-label" for="rerx_analyse_risques">Analyse des risques</label>
                        <textarea name="rerx_analyse_risques" id="rerx_analyse_risques" class="form-control js-summernote-rerx" rows="8">{!! old('rerx_analyse_risques', $dossier->rerx_analyse_risques) !!}</textarea>
                    </div>
                    <div class="mb-3 summernote-wrapper">
                        <label class="form-label" for="rerx_analyste_risques_avis">Avis</label>
                        <textarea name="rerx_analyste_risques_avis" id="rerx_analyste_risques_avis" class="form-control js-summernote-rerx" rows="8">{!! old('rerx_analyste_risques_avis', $dossier->rerx_analyste_risques_avis) !!}</textarea>
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <button type="submit" formaction="{{ route('analyste-risques.dossiers.brouillon', $dossier->token) }}" class="btn btn-outline-secondary btn-sm">Enregistrer le brouillon</button>
                        <button type="submit" formaction="{{ route('analyste-risques.dossiers.soumettre-rerx', $dossier->token) }}" class="btn btn-primary">Soumettre au responsable risques</button>
                    </div>
                </form>
                <p class="small text-muted mt-2 mb-0">La soumission enregistre la date, l’heure et votre identité. Les deux champs doivent être renseignés.</p>
            </div>
        @elseif($submittedByAnalyste)
            <div class="mb-4">
                <h6 class="fw-semibold mb-2">Travail de l’analyste risques</h6>
                @if($dossier->rerx_analyste_risques_submitted_at)
                    <p class="small text-muted mb-2">
                        Soumis le {{ $dossier->rerx_analyste_risques_submitted_at->format('d/m/Y H:i') }}
                        @if($dossier->rerxAnalysteRisquesSubmittedBy)
                            — <strong>{{ $dossier->rerxAnalysteRisquesSubmittedBy->name }}</strong>
                        @endif
                    </p>
                @endif
                <p class="small fw-semibold mb-1">Analyse des risques</p>
                <div class="border rounded p-3 bg-light small rich-text-rendered mb-3">{!! $dossier->rerx_analyse_risques !!}</div>
                <p class="small fw-semibold mb-1">Avis</p>
                <div class="border rounded p-3 bg-light small rich-text-rendered">{!! $dossier->rerx_analyste_risques_avis !!}</div>
            </div>
        @endif

        @if($isRerx && $submittedByAnalyste && ! $submittedToDirection)
            <div class="mb-0">
                <h6 class="fw-semibold mb-2">Avis du responsable risques</h6>
                @include('RoleSpace.dossiers.partials._inter_pole_reject_banner', [
                    'rejected' => $dossier->isDirectionRejectedToRisques(),
                    'motif' => $dossier->direction_rejected_to_risques_motif,
                    'rejectedAt' => $dossier->direction_rejected_to_risques_at,
                    'rejectedBy' => $dossier->directionRejectedToRisquesBy,
                    'libelleAction' => 'modifier votre avis et retransmettre à la direction (DG &amp; DGA)',
                    'sourcePole' => 'la direction (DG / DGA / délégué)',
                ])
                @error('rerx_responsable_avis')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('direction')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('rejet_motif')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('rejet_analyste')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                <form method="post" action="{{ route('rerx.dossiers.responsable-avis', $dossier->token) }}" class="mb-3">
                    @csrf
                    <div class="mb-2 summernote-wrapper">
                        <label for="rerx_responsable_avis" class="form-label">Rédigez votre avis</label>
                        <textarea name="rerx_responsable_avis" id="rerx_responsable_avis" class="form-control js-summernote-rerx @error('rerx_responsable_avis') is-invalid @enderror" rows="10">{!! old('rerx_responsable_avis', $dossier->rerx_responsable_avis) !!}</textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm">Enregistrer l’avis</button>
                </form>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <form method="post" action="{{ route('rerx.dossiers.soumettre-direction', $dossier->token) }}" onsubmit="return confirm('Transmettre ce dossier au directeur général et au directeur général adjoint ?');" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-dark">Soumettre à la direction (DG &amp; DGA)</button>
                    </form>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalRejectAnalysteRisques">
                        Rejeter le travail de l’analyste
                    </button>
                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalRejectVersEngagements">
                        Renvoyer au pôle engagements
                    </button>
                </div>
                <p class="small text-muted mt-2 mb-0">Vous pouvez : rejeter le travail de l’analyste risques, renvoyer le dossier au responsable engagements pour révision (motif obligatoire), ou transmettre à la direction.</p>
            </div>
            @include('RoleSpace.dossiers.partials._pole_analyste_reject_modal', [
                'modalId' => 'modalRejectAnalysteRisques',
                'action' => route('rerx.dossiers.rejeter-analyste-risques', $dossier->token),
                'titre' => 'Rejeter le travail de l’analyste risques',
                'description' => 'Le rejet rend l’analyse et l’avis de l’analyste risques à nouveau modifiables. Le motif est obligatoire et tracé (date, heure, identité).',
            ])
            @include('RoleSpace.dossiers.partials._inter_pole_reject_modal', [
                'modalId' => 'modalRejectVersEngagements',
                'action' => route('rerx.dossiers.rejeter-vers-engagements', $dossier->token),
                'titre' => 'Renvoyer le dossier au pôle engagements',
                'description' => 'Le rejet inter-pôle renvoie le dossier au responsable engagements pour révision. Le motif est obligatoire et tracé.',
                'ctaLabel' => 'Renvoyer au pôle engagements',
            ])
        @elseif($submittedToDirection)
            <div class="mt-3 pt-3 border-top">
                <h6 class="fw-semibold mb-2">Transmission à la direction</h6>
                <p class="small text-success mb-2">
                    <strong>Dossier transmis au DG et au DGA</strong>
                    @if($dossier->rerx_submitted_to_direction_at)
                        le {{ $dossier->rerx_submitted_to_direction_at->format('d/m/Y H:i') }}
                    @endif
                    @if($dossier->rerxSubmittedToDirectionBy)
                        — <strong>{{ $dossier->rerxSubmittedToDirectionBy->name }}</strong>
                    @endif
                </p>
                @if($dossier->rerx_responsable_avis)
                    <h6 class="fw-semibold mb-2 mt-3">Avis du responsable risques</h6>
                    <div class="border rounded p-3 bg-light small rich-text-rendered">{!! $dossier->rerx_responsable_avis !!}</div>
                @endif
            </div>
        @endif
    </div>
</div>
