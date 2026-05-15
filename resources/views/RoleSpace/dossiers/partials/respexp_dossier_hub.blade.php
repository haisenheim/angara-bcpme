{{-- Hub parcours instruction (REXP : actions ; juridique : lecture seule) --}}
@php
    /** @var \App\Models\Dossier $dossier */
    $steps = $exploitationSteps ?? [];
    $respexpInstructionLocked = $respexpInstructionLocked ?? false;
    $transmisJuridique = $dossier->isSubmittedToJuridique();
    $spaceRoute = $space['route'] ?? 'respexp';
    $isRespexp = $spaceRoute === 'respexp';
@endphp

@if($isRespexp)
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light border-0 py-3">
        <strong class="text-brand">Parcours dossier d'instruction</strong>
        <p class="small text-muted mb-0 mt-1">Cotation, instruction, soumission analyste, avis de crédit et validation par le REXP, puis transmission au pôle juridique.</p>
    </div>
    <div class="card-body">
        <div class="row g-2 row-cols-2 row-cols-md-3 row-cols-xl-6">
            @foreach($steps as $i => $step)
                <div class="col">
                    <div class="rounded border p-2 h-100 {{ $step['done'] ? 'border-success bg-body-tertiary' : 'border-light bg-body-tertiary' }}">
                        <div class="small text-muted">Étape {{ $i + 1 }}</div>
                        <div class="fw-semibold small">{{ $step['label'] }}</div>
                        <div class="mt-1">
                            @if($step['done'])
                                <span class="badge bg-success">OK</span>
                            @else
                                <span class="badge bg-secondary">En cours</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@if($isRespexp && isset($analystesExploitation) && $analystesExploitation->isNotEmpty() && $dossier->analyste_id === null)
    <div class="alert alert-info border-0 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <span class="mb-0"><strong>Dossier sans analyste affecté.</strong> Ouvrez le menu <strong>Actions</strong> puis <strong>Affecter à un analyste financier</strong>.</span>
    </div>
@elseif($isRespexp && $dossier->analyste_id === null)
    <div class="alert alert-warning border-0 mb-4">
        Aucun utilisateur au profil instruction (id 17 — analyste financier / AFE) n’est actif. Vérifiez les comptes utilisateurs.
    </div>
@elseif($dossier->analyste_id)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <strong>Analyste financier affecté</strong>
                    <div class="text-muted small mt-1">{{ $dossier->analyste?->name ?? '—' }} @if($dossier->analyste?->email)<span class="d-none d-md-inline">— {{ $dossier->analyste->email }}</span>@endif</div>
                    @if($dossier->exploitation_analyste_assigned_at)
                        <p class="small mb-0 mt-2">
                            <span class="text-muted text-uppercase fw-semibold">Cotation (affectation au chargé d’instruction)</span><br>
                            <span class="text-muted">Le</span> {{ $dossier->exploitation_analyste_assigned_at->format('d/m/Y') }} à {{ $dossier->exploitation_analyste_assigned_at->format('H:i') }}
                            @if($dossier->exploitationAnalysteAssignedBy)
                                <span class="text-muted"> — par</span> <strong>{{ $dossier->exploitationAnalysteAssignedBy->name }}</strong>
                            @else
                                <span class="text-muted"> — auteur de la cotation non renseigné</span>
                            @endif
                        </p>
                    @elseif($dossier->analyste_id)
                        <p class="small text-muted mb-0 mt-2">Historique d’affectation non renseigné (dossier antérieur à l’enregistrement de la trace).</p>
                    @endif
                    @if($dossier->isInstructionTransmittedToExploitationByAnalysteFinancier() && $dossier->exploitation_analyste_transmitted_to_exploitation_at)
                        <p class="small mb-0 mt-2 text-success">
                            <strong>Transmission analyste financier</strong> — le {{ $dossier->exploitation_analyste_transmitted_to_exploitation_at->format('d/m/Y') }} à {{ $dossier->exploitation_analyste_transmitted_to_exploitation_at->format('H:i') }}
                            @if($dossier->exploitationAnalysteTransmittedToExploitationBy)
                                <span class="text-muted"> — par</span> <strong>{{ $dossier->exploitationAnalysteTransmittedToExploitationBy->name }}</strong>
                            @endif
                        </p>
                    @endif
                    @if($dossier->isInstructionCaTransmittedToExploitation() && $dossier->instruction_ca_transmitted_to_exploitation_at)
                        <p class="small mb-0 mt-2 text-success">
                            <strong>Transmission chef d’agence</strong> — le {{ $dossier->instruction_ca_transmitted_to_exploitation_at->format('d/m/Y') }} à {{ $dossier->instruction_ca_transmitted_to_exploitation_at->format('H:i') }}
                            @if($dossier->instructionCaTransmittedToExploitationBy)
                                <span class="text-muted"> — par</span> <strong>{{ $dossier->instructionCaTransmittedToExploitationBy->name }}</strong>
                            @endif
                        </p>
                    @endif
                </div>
                @if($isRespexp)
                <div class="small text-muted" style="max-width: 22rem;">
                    L’instruction (notation, grille des engagements, soumission) se fait dans l’<strong>espace Analyste</strong> (connexion dédiée).
                    <span class="d-block mt-1">Pour <strong>réaffecter</strong> le dossier : menu <strong>Actions</strong>.</span>
                </div>
                @endif
            </div>
        </div>
    </div>
@endif

@if($respexpInstructionLocked)
    <div class="alert alert-warning border-0 mb-4">
        <strong>Instruction non transmise.</strong>
        Le chargé d’instruction n’a pas encore soumis ce dossier pour validation. Vous ne pouvez pas consulter la synthèse détaillée de son travail ni saisir l’avis de crédit ni la validation des engagements tant que cette soumission n’est pas effectuée.
    </div>
@endif

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><strong>Synthèse instruction</strong></div>
            <div class="card-body">
                @if($respexpInstructionLocked)
                    <p class="mb-0 text-muted">Les compteurs et le détail du travail d’instruction seront visibles après soumission par l’analyste financier.</p>
                @else
                    <p class="mb-2">Indicateurs financiers saisis : <strong>{{ $dossier->indicateurs->count() }}</strong></p>
                    <p class="mb-2">Réponses critères d’instruction : <strong>{{ $dossier->reponses->count() }}</strong></p>
                    @php
                        $dossier->loadMissing('analyste');
                    @endphp
                    <p class="mb-3 text-muted small">Accédez au <strong>contenu d’instruction</strong> (grille de notation, indicateurs DSF) et au <strong>dossier d’analyse critique</strong> : <strong>analyse critique faite par {{ $dossier->analyste?->name ?? 'l’analyste financier' }}</strong> (section ci-dessous ou document PDF).</p>
                    @php
                        $dossiersRoutePrefix = $roleSpaceDossiersRoutePrefix ?? ($spaceRoute.'.dossiers');
                        $instructionRouteName = $dossiersRoutePrefix.'.instruction';
                        $analyseCritiqueRouteName = $dossiersRoutePrefix.'.dossier-analyse-critique';
                        $hasInstructionRoute = \Illuminate\Support\Facades\Route::has($instructionRouteName);
                        $hasAnalyseCritiqueRoute = \Illuminate\Support\Facades\Route::has($analyseCritiqueRouteName);
                        $showSynthèseInstructionLinks = ($hasInstructionRoute || $hasAnalyseCritiqueRoute)
                            && (! $readonly || in_array($spaceRoute, ['conformite', 'juridique'], true));
                    @endphp
                    @if($showSynthèseInstructionLinks)
                        <div class="d-flex flex-wrap gap-2">
                            @if($hasInstructionRoute)
                                <a href="{{ route($instructionRouteName, $dossier->token) }}" class="btn btn-sm btn-primary">
                                    <i class="demo-psi-bar-chart me-1"></i> Ouvrir le contenu d’instruction (grille de notation)
                                </a>
                            @endif
                            @if($hasAnalyseCritiqueRoute)
                                <a href="{{ route($analyseCritiqueRouteName, $dossier->token) }}" class="btn btn-sm btn-outline-primary">
                                    Dossier d’analyse critique (AFE)
                                </a>
                            @endif
                        </div>
                    @endif
                    @if($dossier->isInstructionSubmittedToExploitation() && $dossier->hasExploitationAnalysteInstructionAvisSubstance())
                        <div class="mt-3 pt-3 border-top">
                            @include('partials.exploitation-analyste-instruction-zones', [
                                'dossier' => $dossier,
                                'showSectionTitle' => true,
                                'showEmptyZones' => true,
                            ])
                        </div>
                    @endif
                    @if($dossier->isInstructionCaTransmittedToExploitation() && strlen(trim(strip_tags((string) ($dossier->instruction_agence_ca_avis ?? '')))) > 0)
                        <div class="mt-3 pt-3 border-top">
                            <p class="small fw-semibold mb-2 text-uppercase text-muted">Avis du chef d’agence</p>
                            <div class="rich-text-rendered small border rounded p-3 bg-body-tertiary">{!! $dossier->instruction_agence_ca_avis !!}</div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white"><strong>Avis de crédit (responsable exploitation)</strong></div>
    <div class="card-body">
        @if($respexpInstructionLocked)
            <p class="text-muted mb-0">La saisie de l’avis de crédit est disponible après soumission du dossier par l’analyste financier.</p>
        @elseif(! $isRespexp)
            @if($dossier->exploitation_avis_credit_at)
                <p class="small text-muted mb-2">
                    Saisi le {{ $dossier->exploitation_avis_credit_at->format('d/m/Y H:i') }}
                    @if($dossier->exploitationAvisCreditUser)
                        — {{ $dossier->exploitationAvisCreditUser->name }}
                    @endif
                </p>
            @endif
            <div class="border rounded p-3 bg-body-tertiary small rich-text-rendered">{!! $dossier->exploitation_avis_credit !!}</div>
            <p class="text-muted small mb-0 mt-2">Lecture seule — saisie réservée au responsable exploitation.</p>
        @elseif($transmisJuridique)
            @if($dossier->exploitation_avis_credit_at)
                <p class="small text-muted mb-2">
                    Saisi le {{ $dossier->exploitation_avis_credit_at->format('d/m/Y H:i') }}
                    @if($dossier->exploitationAvisCreditUser)
                        — {{ $dossier->exploitationAvisCreditUser->name }}
                    @endif
                </p>
            @endif
            <div class="border rounded p-3 bg-body-tertiary small rich-text-rendered">{!! $dossier->exploitation_avis_credit !!}</div>
            <p class="text-muted small mb-0 mt-2">Dossier transmis au pôle juridique — l’avis n’est plus modifiable.</p>
        @else
            @if($dossier->exploitation_avis_credit_at)
                <p class="small text-muted mb-2">
                    Dernière saisie le {{ $dossier->exploitation_avis_credit_at->format('d/m/Y H:i') }}
                    @if($dossier->exploitationAvisCreditUser)
                        — {{ $dossier->exploitationAvisCreditUser->name }}
                    @endif
                </p>
            @endif
            <form id="avis-credit-form" method="post" action="{{ route('respexp.dossiers.avis-credit', $dossier->token) }}">
                @csrf
                <div class="mb-3">
                    <label for="exploitation_avis_credit" class="form-label">Texte de l’avis</label>
                    <div class="summernote-wrapper">
                        <textarea name="exploitation_avis_credit" id="exploitation_avis_credit" class="form-control js-summernote-fr @error('exploitation_avis_credit') is-invalid @enderror" rows="8">{!! old('exploitation_avis_credit', $dossier->exploitation_avis_credit) !!}</textarea>
                    </div>
                    @error('exploitation_avis_credit')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer l’avis de crédit</button>
            </form>
        @endif
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white"><strong>Validation du dossier des engagements</strong></div>
    <div class="card-body">
        @if(! $dossier->analyste_id)
            <p class="text-muted mb-0">Affectez d’abord un analyste financier pour pouvoir statuer sur le dossier des engagements.</p>
        @elseif($respexpInstructionLocked)
            <p class="text-muted mb-0">La validation du dossier des engagements est disponible après soumission de l’instruction par l’analyste financier.</p>
        @elseif(! $isRespexp)
            @if($dossier->exploitation_engagements_decision_at)
                <div class="alert alert-light border mb-3">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge {{ $dossier->exploitation_engagements_decision === 'accord' ? 'bg-success' : 'bg-danger' }}">
                            {{ $dossier->exploitation_engagements_decision === 'accord' ? 'Validé' : 'Rejeté' }}
                        </span>
                        <span class="small text-muted">{{ $dossier->exploitation_engagements_decision_at->format('d/m/Y H:i') }}
                            @if($dossier->exploitationEngagementsDecisionUser) — {{ $dossier->exploitationEngagementsDecisionUser->name }} @endif
                        </span>
                    </div>
                    @if($dossier->exploitation_engagements_decision_comment)
                        <p class="mb-0 mt-2 small"><strong>Commentaire :</strong> {{ $dossier->exploitation_engagements_decision_comment }}</p>
                    @endif
                </div>
            @else
                <p class="text-muted small mb-0">Aucune décision enregistrée par le responsable exploitation.</p>
            @endif
            <p class="text-muted small mb-0 mt-2">Lecture seule — décision réservée au responsable exploitation.</p>
        @else
            @if($dossier->exploitation_engagements_decision_at)
                <div class="alert alert-light border mb-3">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge {{ $dossier->exploitation_engagements_decision === 'accord' ? 'bg-success' : 'bg-danger' }}">
                            {{ $dossier->exploitation_engagements_decision === 'accord' ? 'Validé' : 'Rejeté' }}
                        </span>
                        <span class="small text-muted">{{ $dossier->exploitation_engagements_decision_at->format('d/m/Y H:i') }}
                            @if($dossier->exploitationEngagementsDecisionUser) — {{ $dossier->exploitationEngagementsDecisionUser->name }} @endif
                        </span>
                    </div>
                    @if($dossier->exploitation_engagements_decision_comment)
                        <p class="mb-0 mt-2 small"><strong>Commentaire :</strong> {{ $dossier->exploitation_engagements_decision_comment }}</p>
                    @endif
                </div>
            @endif
            @if($transmisJuridique)
                <p class="text-muted small mb-0">Dossier transmis au pôle juridique — la décision n’est plus modifiable.</p>
            @else
                <form method="post" action="{{ route('respexp.dossiers.validation-engagements', $dossier->token) }}">
                    @csrf
                    <div class="mb-3">
                        <span class="form-label d-block">Décision sur le dossier d’instruction</span>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="decision" id="dec_accord" value="accord" autocomplete="off" {{ old('decision', $dossier->exploitation_engagements_decision) === 'accord' ? 'checked' : '' }} required>
                            <label class="btn btn-outline-success" for="dec_accord">Valider</label>
                            <input type="radio" class="btn-check" name="decision" id="dec_rejet" value="rejet" autocomplete="off" {{ old('decision', $dossier->exploitation_engagements_decision) === 'rejet' ? 'checked' : '' }}>
                            <label class="btn btn-outline-danger" for="dec_rejet">Rejeter</label>
                        </div>
                        @error('decision')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Commentaire @if(old('decision', $dossier->exploitation_engagements_decision) === 'rejet')<span class="text-danger">*</span>@endif</label>
                        <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror" rows="4" placeholder="Motif ou précisions (obligatoire en cas de rejet)">{{ old('comment', $dossier->exploitation_engagements_decision_comment) }}</textarea>
                        @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-dark">Enregistrer la décision</button>
                </form>
            @endif
        @endif
    </div>
</div>

@if(! $respexpInstructionLocked && $isRespexp)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white"><strong>Transmission au pôle juridique</strong></div>
        <div class="card-body">
            @include('RoleSpace.dossiers.partials._inter_pole_reject_banner', [
                'rejected' => $dossier->isJuridiqueRejectedToExploitation(),
                'motif' => $dossier->juridique_rejected_to_exploitation_motif,
                'rejectedAt' => $dossier->juridique_rejected_to_exploitation_at,
                'rejectedBy' => $dossier->juridiqueRejectedToExploitationBy,
                'libelleAction' => 'modifier votre avis crédit et/ou votre décision sur les engagements puis retransmettre au pôle juridique',
                'sourcePole' => 'responsable juridique',
            ])
            @error('juridique')<div class="alert alert-danger py-2 small">{{ $message }}</div>@enderror
            @error('rejet_motif')<div class="alert alert-danger py-2 small">{{ $message }}</div>@enderror
            @error('rejet_analyste')<div class="alert alert-danger py-2 small">{{ $message }}</div>@enderror
            @if($transmisJuridique && $dossier->juridique_instruction_submitted_at)
                <p class="mb-0 small text-success">
                    <strong>Dossier transmis</strong> — le {{ $dossier->juridique_instruction_submitted_at->format('d/m/Y') }} à {{ $dossier->juridique_instruction_submitted_at->format('H:i') }}
                    @if($dossier->juridiqueInstructionSubmittedBy)
                        <span class="text-muted"> — par</span> <strong>{{ $dossier->juridiqueInstructionSubmittedBy->name }}</strong>
                    @endif
                </p>
                <p class="small text-muted mb-0 mt-2">Le responsable juridique peut consulter ce dossier dans son espace (menu <strong>Dossiers d’instruction</strong>).</p>
            @elseif($dossier->canRespexpSoumettreAuJuridique())
                <p class="small text-muted mb-3">Après validation du dossier d’instruction et saisie de l’avis de crédit, transmettez le dossier au pôle juridique. Si la soumission de l’analyste financier nécessite des corrections, vous pouvez la rejeter (motif obligatoire).</p>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <form method="post" action="{{ route('respexp.dossiers.soumettre-juridique', $dossier->token) }}" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-primary">Soumettre au pôle juridique</button>
                    </form>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalRejectAnalysteFinancier">
                        Rejeter la soumission de l’analyste
                    </button>
                </div>
                @include('RoleSpace.dossiers.partials._pole_analyste_reject_modal', [
                    'modalId' => 'modalRejectAnalysteFinancier',
                    'action' => route('respexp.dossiers.rejeter-analyste', $dossier->token),
                    'titre' => 'Rejeter la soumission de l’analyste financier',
                    'description' => 'Le rejet rend les rubriques d’analyse de l’analyste financier à nouveau modifiables. Le motif est obligatoire et tracé (date, heure, identité).',
                ])
            @elseif($dossier->isInstructionTransmittedToExploitationByAnalysteFinancier() && ! $dossier->isSubmittedToJuridique())
                {{-- Permettre le rejet même quand l'avis crédit n'est pas encore renseigné, afin de renvoyer une saisie problématique. --}}
                <p class="small text-muted mb-3">Si la soumission de l’analyste financier nécessite des corrections avant de pouvoir saisir l’avis de crédit, vous pouvez la rejeter (motif obligatoire) : l’analyste pourra modifier puis retransmettre.</p>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalRejectAnalysteFinancier">
                    Rejeter la soumission de l’analyste
                </button>
                @include('RoleSpace.dossiers.partials._pole_analyste_reject_modal', [
                    'modalId' => 'modalRejectAnalysteFinancier',
                    'action' => route('respexp.dossiers.rejeter-analyste', $dossier->token),
                    'titre' => 'Rejeter la soumission de l’analyste financier',
                    'description' => 'Le rejet rend les rubriques d’analyse de l’analyste financier à nouveau modifiables. Le motif est obligatoire et tracé (date, heure, identité).',
                ])
                <p class="small text-muted mb-2 mt-3">La transmission au pôle juridique nécessite par ailleurs :</p>
                <ul class="small text-muted mb-0">
                    <li>l’avis de crédit renseigné ;</li>
                    <li>la décision sur le dossier d’instruction en <strong>accord</strong>.</li>
                </ul>
            @else
                <p class="small text-muted mb-2">La transmission est possible lorsque :</p>
                <ul class="small text-muted mb-0">
                    <li>l’instruction a été transmise par l’analyste ;</li>
                    <li>l’avis de crédit est renseigné ;</li>
                    <li>la décision sur le dossier d’instruction est <strong>validation</strong> (accord).</li>
                </ul>
                @if($dossier->exploitation_engagements_decision === 'rejet')
                    <p class="small text-danger mb-0 mt-2">Un dossier rejeté ne peut pas être transmis au pôle juridique.</p>
                @endif
            @endif
        </div>
    </div>
@elseif(! $respexpInstructionLocked && ! $isRespexp && $transmisJuridique && $dossier->juridique_instruction_submitted_at)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white"><strong>Transmission au pôle juridique</strong></div>
        <div class="card-body">
            <p class="mb-0 small text-success">
                <strong>Dossier reçu au pôle juridique</strong> — le {{ $dossier->juridique_instruction_submitted_at->format('d/m/Y') }} à {{ $dossier->juridique_instruction_submitted_at->format('H:i') }}
                @if($dossier->juridiqueInstructionSubmittedBy)
                    <span class="text-muted"> — transmis par</span> <strong>{{ $dossier->juridiqueInstructionSubmittedBy->name }}</strong>
                @endif
            </p>
        </div>
    </div>
@endif
