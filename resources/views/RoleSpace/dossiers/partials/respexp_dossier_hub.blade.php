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
        <strong class="text-primary">Parcours dossier d'instruction</strong>
        <p class="small text-muted mb-0 mt-1">Cotation, instruction, soumission analyste, avis de crédit et validation par le REXP, puis transmission au pôle juridique.</p>
    </div>
    <div class="card-body">
        <div class="row g-2 row-cols-2 row-cols-md-3 row-cols-xl-6">
            @foreach($steps as $i => $step)
                <div class="col">
                    <div class="rounded border p-2 h-100 {{ $step['done'] ? 'border-success bg-light' : 'border-light bg-light' }}">
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
                    @if($dossier->isInstructionSubmittedToExploitation() && $dossier->exploitation_instruction_submitted_at)
                        <p class="small mb-0 mt-2 text-success">
                            <strong>Instruction transmise</strong> — le {{ $dossier->exploitation_instruction_submitted_at->format('d/m/Y') }} à {{ $dossier->exploitation_instruction_submitted_at->format('H:i') }}
                            @if($dossier->exploitationInstructionSubmittedBy)
                                <span class="text-muted"> — par</span> <strong>{{ $dossier->exploitationInstructionSubmittedBy->name }}</strong>
                            @endif
                        </p>
                    @endif
                </div>
                @if($isRespexp)
                <div class="small text-muted" style="max-width: 22rem;">
                    L’instruction (grilles, état des engagements, soumission) se fait dans l’<strong>espace Analyste</strong> (connexion dédiée).
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
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><strong>Synthèse instruction</strong></div>
            <div class="card-body">
                @if($respexpInstructionLocked)
                    <p class="mb-0 text-muted">Les compteurs et le détail du travail d’instruction seront visibles après soumission par l’analyste financier.</p>
                @else
                    <p class="mb-2">Indicateurs financiers saisis : <strong>{{ $dossier->indicateurs->count() }}</strong></p>
                    <p class="mb-2">Réponses critères d’instruction : <strong>{{ $dossier->reponses->count() }}</strong></p>
                    <p class="mb-3 text-muted small">Accédez à la <strong>grille de notation</strong>, aux indicateurs DSF et à la <strong>grille d’analyse critique</strong> tels que renseignés par l’analyste.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route($spaceRoute.'.dossiers.instruction', $dossier->token) }}" class="btn btn-sm btn-primary">
                            <i class="demo-psi-bar-chart me-1"></i> Ouvrir le contenu d’instruction (grille de notation)
                        </a>
                        <a href="{{ route($spaceRoute.'.dossiers.analyse-critique', $dossier->token) }}" class="btn btn-sm btn-outline-primary">
                            Grille d’analyse critique
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><strong>Entreprise &amp; agence</strong></div>
            <div class="card-body">
                <p class="mb-2">Entreprise : <strong>{{ $dossier->entreprise?->name ?? '—' }}</strong></p>
                <p class="mb-2">Agence : <strong>{{ $dossier->agence?->name ?? '—' }}</strong></p>
                @if($respexpInstructionLocked)
                    <p class="mb-0 text-muted small">Note agrégée : non affichée tant que l’instruction n’est pas soumise.</p>
                @else
                    <p class="mb-0">Note agrégée (indicateurs + critères) : <strong>{{ number_format((float) ($dossier->note ?? 0), 2, ',', ' ') }}</strong></p>
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
            <div class="border rounded p-3 bg-light small rich-text-rendered">{!! $dossier->exploitation_avis_credit !!}</div>
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
            <div class="border rounded p-3 bg-light small rich-text-rendered">{!! $dossier->exploitation_avis_credit !!}</div>
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
            @error('juridique')<div class="alert alert-danger py-2 small">{{ $message }}</div>@enderror
            @if($transmisJuridique && $dossier->juridique_instruction_submitted_at)
                <p class="mb-0 small text-success">
                    <strong>Dossier transmis</strong> — le {{ $dossier->juridique_instruction_submitted_at->format('d/m/Y') }} à {{ $dossier->juridique_instruction_submitted_at->format('H:i') }}
                    @if($dossier->juridiqueInstructionSubmittedBy)
                        <span class="text-muted"> — par</span> <strong>{{ $dossier->juridiqueInstructionSubmittedBy->name }}</strong>
                    @endif
                </p>
                <p class="small text-muted mb-0 mt-2">Le responsable juridique peut consulter ce dossier dans son espace (menu <strong>Dossiers d’instruction</strong>).</p>
            @elseif($dossier->canRespexpSoumettreAuJuridique())
                <p class="small text-muted mb-3">Après validation du dossier d’instruction et saisie de l’avis de crédit, transmettez le dossier au pôle juridique.</p>
                <form method="post" action="{{ route('respexp.dossiers.soumettre-juridique', $dossier->token) }}" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-primary">Soumettre au pôle juridique</button>
                </form>
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
