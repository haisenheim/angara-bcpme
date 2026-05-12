{{-- Workflow engagements : analyste crédit → resp. engagements → risques --}}
@php
    /** @var \App\Models\Dossier $dossier */
    $r = $space['route'] ?? '';
    $isReng = $r === 'reng';
    $isAnalysteAc = $r === 'analyste-credit';
    $profilAcId = (int) config('angara.role_analyste_credit', 20);
    $submittedByAnalyste = $dossier->isRengAnalysteCreditSubmittedToReng();
    $submittedToRisques = $dossier->isSubmittedToRisquesFromReng();
    $isAssignedCredit = $isAnalysteAc && (int) $dossier->reng_analyste_credit_user_id === (int) auth()->id();
    $rejectedByReng = $dossier->isRengAnalysteCreditRejectedByReng();
    // Réouverture sur rejet : l'analyste peut à nouveau modifier après un rejet motivé du RENG.
    $analysteCanEdit = $isAssignedCredit && (! $submittedByAnalyste || $rejectedByReng);
@endphp

<div class="card mb-4 border-start border-4 border-success">
    <div class="card-header bg-white py-3">
        <strong>Pôle engagements — analyste crédit &amp; transmission risques</strong>
        <p class="small text-muted mb-0 mt-1">Analyste crédit (profil {{ $profilAcId }}), puis avis du responsable engagements, puis envoi au responsable risques (profil {{ (int) config('angara.role_responsable_risques', 12) }}).</p>
    </div>
    <div class="card-body">
        @if($dossier->reng_analyste_credit_user_id)
            <div class="mb-4 pb-3 border-bottom">
                <h6 class="fw-semibold mb-2">Analyste crédit affecté</h6>
                <p class="mb-1"><strong>{{ $dossier->rengAnalysteCreditUser?->name ?? '—' }}</strong>
                    @if($dossier->rengAnalysteCreditUser?->email)<span class="text-muted small"> — {{ $dossier->rengAnalysteCreditUser->email }}</span>@endif
                </p>
                @if($dossier->reng_analyste_credit_assigned_at)
                    <p class="small text-muted mb-0">
                        Affectation le {{ $dossier->reng_analyste_credit_assigned_at->format('d/m/Y') }} à {{ $dossier->reng_analyste_credit_assigned_at->format('H:i') }}
                        @if($dossier->rengAnalysteCreditAssignedBy)
                            — par <strong>{{ $dossier->rengAnalysteCreditAssignedBy->name }}</strong>
                        @endif
                    </p>
                @endif
            </div>
        @elseif($isReng)
            <div class="alert alert-light border mb-0">
                <p class="mb-0 small">Aucun analyste crédit n’est encore affecté. Utilisez le menu <strong>Actions</strong> → <strong>Affecter un analyste crédit</strong>.</p>
            </div>
        @endif

        @if($analysteCanEdit)
            <div class="mb-3">
                <h6 class="fw-semibold mb-2">Votre travail (analyste crédit)</h6>
                @include('RoleSpace.dossiers.partials._analyste_reject_banner', [
                    'rejected' => $rejectedByReng,
                    'motif' => $dossier->reng_analyste_credit_reject_motif,
                    'rejectedAt' => $dossier->reng_analyste_credit_rejected_at,
                    'rejectedBy' => $dossier->rengAnalysteCreditRejectedBy,
                    'libelleAction' => 'corriger votre contre-analyse / avis et retransmettre',
                ])
                @error('draft')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('submit')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @foreach (['reng_contre_analyse' => 'Contre-analyse', 'reng_analyste_credit_avis' => 'Avis'] as $field => $label)
                    @error($field)<div class="text-danger small mb-1">{{ $message }}</div>@enderror
                @endforeach

                <form method="post" id="form-analyste-credit-reng" class="mb-0">
                    @csrf
                    <div class="mb-3 summernote-wrapper">
                        <label class="form-label" for="reng_contre_analyse">Contre-analyse</label>
                        <textarea name="reng_contre_analyse" id="reng_contre_analyse" class="form-control js-summernote-reng" rows="8">{!! old('reng_contre_analyse', $dossier->reng_contre_analyse) !!}</textarea>
                    </div>
                    <div class="mb-3 summernote-wrapper">
                        <label class="form-label" for="reng_analyste_credit_avis">Avis</label>
                        <textarea name="reng_analyste_credit_avis" id="reng_analyste_credit_avis" class="form-control js-summernote-reng" rows="8">{!! old('reng_analyste_credit_avis', $dossier->reng_analyste_credit_avis) !!}</textarea>
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <button type="submit" formaction="{{ route('analyste-credit.dossiers.brouillon', $dossier->token) }}" class="btn btn-outline-secondary btn-sm">Enregistrer le brouillon</button>
                        <button type="submit" formaction="{{ route('analyste-credit.dossiers.soumettre-reng', $dossier->token) }}" class="btn btn-primary">Soumettre au responsable engagements</button>
                    </div>
                </form>
                <p class="small text-muted mt-2 mb-0">La soumission enregistre la date, l’heure et votre identité. La contre-analyse et l’avis doivent être renseignés.</p>
            </div>
        @elseif($submittedByAnalyste)
            <div class="mb-4">
                <h6 class="fw-semibold mb-2">Travail de l’analyste crédit</h6>
                @if($dossier->reng_analyste_credit_submitted_at)
                    <p class="small text-muted mb-2">
                        Soumis le {{ $dossier->reng_analyste_credit_submitted_at->format('d/m/Y H:i') }}
                        @if($dossier->rengAnalysteCreditSubmittedBy)
                            — <strong>{{ $dossier->rengAnalysteCreditSubmittedBy->name }}</strong>
                        @endif
                    </p>
                @endif
                <p class="small fw-semibold mb-1">Contre-analyse</p>
                <div class="border rounded p-3 bg-body-tertiary small rich-text-rendered mb-3">{!! $dossier->reng_contre_analyse !!}</div>
                <p class="small fw-semibold mb-1">Avis</p>
                <div class="border rounded p-3 bg-body-tertiary small rich-text-rendered mb-3">{!! $dossier->reng_analyste_credit_avis !!}</div>
                @if($dossier->entreprise?->token)
                    <p class="small text-muted mb-0">
                        La grille des engagements du client est celle de la fiche entreprise (répartition par banque et type d’engagement).
                        @if($isAnalysteAc)
                            <a href="{{ route('analyste-credit.entreprise.get.engagements', $dossier->entreprise->token) }}">Ouvrir la grille des engagements</a>
                        @elseif($r === 'reng' && \Illuminate\Support\Facades\Route::has('reng.entreprises.engagements'))
                            <a href="{{ route('reng.entreprises.engagements', $dossier->entreprise->token) }}">Consulter la grille des engagements</a>
                        @endif
                    </p>
                @endif
            </div>
        @endif

        @if($isReng && $submittedByAnalyste && ! $submittedToRisques)
            <div class="mb-0">
                <h6 class="fw-semibold mb-2">Avis du responsable engagements</h6>
                @include('RoleSpace.dossiers.partials._inter_pole_reject_banner', [
                    'rejected' => $dossier->isRisquesRejectedToEngagements(),
                    'motif' => $dossier->risques_rejected_to_engagements_motif,
                    'rejectedAt' => $dossier->risques_rejected_to_engagements_at,
                    'rejectedBy' => $dossier->risquesRejectedToEngagementsBy,
                    'libelleAction' => 'modifier votre avis et retransmettre au responsable risques',
                    'sourcePole' => 'responsable risques',
                ])
                @error('reng_responsable_avis')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('risques')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('rejet_motif')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                @error('rejet_analyste')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                <form method="post" action="{{ route('reng.dossiers.responsable-avis', $dossier->token) }}" class="mb-3">
                    @csrf
                    <div class="mb-2 summernote-wrapper">
                        <label for="reng_responsable_avis" class="form-label">Rédigez votre avis</label>
                        <textarea name="reng_responsable_avis" id="reng_responsable_avis" class="form-control js-summernote-reng @error('reng_responsable_avis') is-invalid @enderror" rows="10">{!! old('reng_responsable_avis', $dossier->reng_responsable_avis) !!}</textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm">Enregistrer l’avis</button>
                </form>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <form method="post" action="{{ route('reng.dossiers.soumettre-risques', $dossier->token) }}" onsubmit="return confirm('Transmettre ce dossier au responsable risques ?');" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-dark">Soumettre au responsable risques</button>
                    </form>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalRejectAnalysteCredit">
                        Rejeter le travail de l’analyste
                    </button>
                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalRejectVersJuridique">
                        Renvoyer au pôle juridique
                    </button>
                </div>
                <p class="small text-muted mt-2 mb-0">Vous pouvez : rejeter le travail de l’analyste crédit (réouverture interne), renvoyer le dossier au responsable juridique pour révision (motif obligatoire), ou transmettre au responsable risques.</p>
            </div>
            @include('RoleSpace.dossiers.partials._pole_analyste_reject_modal', [
                'modalId' => 'modalRejectAnalysteCredit',
                'action' => route('reng.dossiers.rejeter-analyste-credit', $dossier->token),
                'titre' => 'Rejeter le travail de l’analyste crédit',
                'description' => 'Le rejet rend la contre-analyse et l’avis de l’analyste crédit à nouveau modifiables. Le motif est obligatoire et tracé (date, heure, identité).',
            ])
            @include('RoleSpace.dossiers.partials._inter_pole_reject_modal', [
                'modalId' => 'modalRejectVersJuridique',
                'action' => route('reng.dossiers.rejeter-vers-juridique', $dossier->token),
                'titre' => 'Renvoyer le dossier au pôle juridique',
                'description' => 'Le rejet inter-pôle renvoie le dossier au responsable juridique pour révision. Le motif est obligatoire et tracé.',
                'ctaLabel' => 'Renvoyer au pôle juridique',
            ])
        @elseif($submittedToRisques)
            <div class="mt-3 pt-3 border-top">
                <h6 class="fw-semibold mb-2">Transmission au responsable risques</h6>
                <p class="small text-success mb-2">
                    <strong>Dossier transmis</strong>
                    @if($dossier->reng_submitted_to_risques_at)
                        le {{ $dossier->reng_submitted_to_risques_at->format('d/m/Y H:i') }}
                    @endif
                    @if($dossier->rengSubmittedToRisquesBy)
                        — <strong>{{ $dossier->rengSubmittedToRisquesBy->name }}</strong>
                    @endif
                </p>
                @if($dossier->reng_responsable_avis)
                    <h6 class="fw-semibold mb-2 mt-3">Avis du responsable engagements</h6>
                    <div class="border rounded p-3 bg-body-tertiary small rich-text-rendered">{!! $dossier->reng_responsable_avis !!}</div>
                @endif
            </div>
        @endif
    </div>
</div>
