@extends(match ($space['route'] ?? '') {
    'respexp' => 'Layouts.respexp',
    'juridique' => 'Layouts.juridique',
    'analyste-juridique' => 'Layouts.analyste-juridique',
    'reng' => 'Layouts.reng',
    'analyste-credit' => 'Layouts.analyste-credit',
    'analyste-risques' => 'Layouts.analyste-risques',
    'rerx' => 'Layouts.rerx',
    'dg' => 'Layouts.dg',
    'dga' => 'Layouts.dga',
    default => 'Layouts.app',
})

@php
    $roleSpaceDossierSummernoteRoutes = ['respexp', 'juridique', 'analyste-juridique', 'reng', 'analyste-credit', 'rerx', 'analyste-risques', 'dg', 'dga'];
    $roleSpaceDossierNeedsSummernote = in_array($space['route'] ?? '', $roleSpaceDossierSummernoteRoutes, true);
@endphp

@if($roleSpaceDossierNeedsSummernote)
@push('styles')
@include('partials.summernote-fr-styles', ['variant' => 'bs5'])
@endpush
@endif

@section('title', 'Dossier - '.$space['title'])

@section('breadcrumb')
@php
    $dossiersListRoute = in_array($space['route'] ?? '', ['dg', 'dga'], true)
        ? $space['route'].'.dossiers.valides-chef-agence'
        : $space['route'].'.dossiers.index';
@endphp
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route($dossiersListRoute) }}">Dossiers</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($dossier->programmesLabel() ?: 'Dossier', 42) }}</li>
    </ol>
</nav>
@endsection

@section('actions')
@php
    $spaceRoute = (string) ($space['route'] ?? '');
    $dossiersListRoute = in_array($spaceRoute, ['dg', 'dga'], true)
        ? $spaceRoute.'.dossiers.valides-chef-agence'
        : $spaceRoute.'.dossiers.index';
    $showGestionDossier = $spaceRoute === 'respexp'
        || ($spaceRoute === 'juridique' && ! $dossier->isSubmittedToEngagementsFromJuridique() && ! $dossier->isJuridiqueAnalysteAvisSubmittedToReju())
        || ($spaceRoute === 'reng' && ! $dossier->isSubmittedToRisquesFromReng())
        || ($spaceRoute === 'rerx' && ! $dossier->isSubmittedToDirectionFromRerx());
@endphp
<x-page-actions-dropdown button-id="dossierActionsDropdown" menu-class="dropdown-menu dropdown-menu-end border shadow-sm py-2" menu-style="min-width: 15rem;">
        <li><h6 class="dropdown-header text-uppercase small text-muted px-3 mb-0">Navigation</h6></li>
        <li>
            <a class="dropdown-item rounded-0 py-2" href="{{ route($dossiersListRoute) }}">
                <i class="demo-pli-arrow-left me-2 text-body-secondary"></i>Retour aux dossiers
            </a>
        </li>
        @if($dossier->entreprise)
            <li>
                <a class="dropdown-item rounded-0 py-2" href="{{ route($spaceRoute.'.entreprises.show', $dossier->entreprise->token) }}">
                    <i class="demo-pli-building me-2 text-body-secondary"></i>Voir l'entreprise
                </a>
            </li>
        @endif

        <li><hr class="dropdown-divider my-2"></li>
        <li>
            <button type="button" class="dropdown-item rounded-0 py-2" data-bs-toggle="modal" data-bs-target="#{{ $piecesModalId ?? 'dossierPieceUploadModal' }}">
                <i class="demo-psi-upload me-2 text-body-secondary"></i>Ajouter une pièce au dossier
            </button>
        </li>

        @if($showGestionDossier)
            <li><hr class="dropdown-divider my-2"></li>
            <li><h6 class="dropdown-header text-uppercase small text-muted px-3 mb-0">Gestion du dossier</h6></li>

            @if($spaceRoute === 'respexp')
                @if(isset($analystesExploitation) && $analystesExploitation->isNotEmpty())
                    <li>
                        <button type="button" class="dropdown-item rounded-0 py-2" data-bs-toggle="modal" data-bs-target="#modalAffecterAnalyste">
                            <i class="demo-psi-user me-2 text-body-secondary"></i>
                            @if($dossier->analyste_id)
                                Réaffecter à un autre analyste
                            @else
                                Affecter à un analyste financier
                            @endif
                        </button>
                    </li>
                @else
                    <li>
                        <span class="dropdown-item disabled text-muted small py-2" tabindex="-1">
                            Aucun analyste financier (profil {{ (int) config('angara.role_analyste_financier', 17) }}) disponible pour l’affectation.
                        </span>
                    </li>
                @endif
            @endif

            @if($spaceRoute === 'juridique')
                @if(isset($analystesJuridique) && $analystesJuridique->isNotEmpty())
                    <li>
                        <button type="button" class="dropdown-item rounded-0 py-2" data-bs-toggle="modal" data-bs-target="#modalAffecterAnalysteJuridique">
                            <i class="demo-psi-user me-2 text-body-secondary"></i>{{ $dossier->juridique_analyste_user_id ? 'Réaffecter un analyste juridique' : 'Affecter un analyste juridique' }}
                        </button>
                    </li>
                @else
                    <li>
                        <span class="dropdown-item disabled text-muted small py-2" tabindex="-1">
                            Aucun analyste juridique (profil {{ (int) config('angara.role_analyste_juridique', 19) }}) disponible.
                        </span>
                    </li>
                @endif
            @endif

            @if($spaceRoute === 'reng')
                @if(isset($analystesCredit) && $analystesCredit->isNotEmpty() && ! $dossier->isRengAnalysteCreditSubmittedToReng())
                    <li>
                        <button type="button" class="dropdown-item rounded-0 py-2" data-bs-toggle="modal" data-bs-target="#modalAffecterAnalysteCredit">
                            <i class="demo-psi-user me-2 text-body-secondary"></i>{{ $dossier->reng_analyste_credit_user_id ? 'Réaffecter un analyste crédit' : 'Affecter un analyste crédit' }}
                        </button>
                    </li>
                @elseif($dossier->isRengAnalysteCreditSubmittedToReng())
                    <li>
                        <span class="dropdown-item disabled text-muted small py-2" tabindex="-1">L’affectation analyste crédit est verrouillée après soumission.</span>
                    </li>
                @else
                    <li>
                        <span class="dropdown-item disabled text-muted small py-2" tabindex="-1">
                            Aucun analyste crédit (profil {{ (int) config('angara.role_analyste_credit', 20) }}) disponible.
                        </span>
                    </li>
                @endif
            @endif

            @if($spaceRoute === 'rerx')
                @if(isset($analystesRisques) && $analystesRisques->isNotEmpty() && ! $dossier->isRerxAnalysteRisquesSubmittedToRerx())
                    <li>
                        <button type="button" class="dropdown-item rounded-0 py-2" data-bs-toggle="modal" data-bs-target="#modalAffecterAnalysteRisques">
                            <i class="demo-psi-user me-2 text-body-secondary"></i>{{ $dossier->rerx_analyste_risques_user_id ? 'Réaffecter un analyste risques' : 'Affecter un analyste risques' }}
                        </button>
                    </li>
                @elseif($dossier->isRerxAnalysteRisquesSubmittedToRerx())
                    <li>
                        <span class="dropdown-item disabled text-muted small py-2" tabindex="-1">L’affectation analyste risques est verrouillée après soumission.</span>
                    </li>
                @else
                    <li>
                        <span class="dropdown-item disabled text-muted small py-2" tabindex="-1">
                            Aucun analyste risques (profil {{ (int) config('angara.role_analyste_risques', 18) }}) disponible.
                        </span>
                    </li>
                @endif
            @endif
        @endif

        <li><hr class="dropdown-divider my-2"></li>
        <li><h6 class="dropdown-header text-uppercase small text-muted px-3 mb-0">Clôture instruction</h6></li>
        @if($dossier->isInstructionClosed())
            <li>
                <span class="dropdown-item disabled text-muted small py-2" tabindex="-1">
                    {{ $instructionClosureStatutLabel ?? 'Dossier d’instruction clos' }}
                </span>
            </li>
        @elseif(! empty($canCloseInstruction))
            <li>
                <button type="button" class="dropdown-item rounded-0 py-2" data-bs-toggle="modal" data-bs-target="#modalInstructionClosureApprove">
                    <i class="demo-psi-check me-2 text-body-secondary"></i>Clôturer le dossier d’instruction
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item rounded-0 py-2 text-danger" data-bs-toggle="modal" data-bs-target="#modalInstructionClosureReject">
                    <i class="demo-psi-cross me-2 text-danger"></i>Rejeter la clôture instruction
                </button>
            </li>
            @if(! empty($instructionClosureRuleDescription))
                <li>
                    <span class="dropdown-item disabled text-muted small py-2" tabindex="-1">{{ $instructionClosureRuleDescription }}</span>
                </li>
            @endif
        @else
            <li>
                <span class="dropdown-item disabled text-muted small py-2" tabindex="-1">
                    Vous n’êtes pas habilité à clôturer ce dossier d’instruction.
                </span>
            </li>
        @endif
</x-page-actions-dropdown>
@endsection

@if(! $dossier->isInstructionClosed() && ! empty($canCloseInstruction))
    <div class="modal fade" id="modalInstructionClosureApprove" tabindex="-1" aria-labelledby="modalInstructionClosureApproveLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInstructionClosureApproveLabel">Clôturer le dossier d’instruction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form method="post" action="{{ route('instruction.dossiers.closure.approve', $dossier->token) }}">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted small mb-3">Cette clôture correspond à la <strong>fin de parcours</strong> du dossier d’instruction (délégation de pouvoir).</p>
                        <label class="form-label small" for="instruction_closure_note">Note (optionnel)</label>
                        <textarea class="form-control" id="instruction_closure_note" name="instruction_closure_note" rows="3" maxlength="5000" placeholder="Note éventuelle"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Clôturer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalInstructionClosureReject" tabindex="-1" aria-labelledby="modalInstructionClosureRejectLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInstructionClosureRejectLabel">Rejeter la clôture instruction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form method="post" action="{{ route('instruction.dossiers.closure.reject', $dossier->token) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="instruction_closure_reject_motif">Motif (optionnel)</label>
                            <textarea class="form-control" id="instruction_closure_reject_motif" name="instruction_closure_reject_motif" rows="4" maxlength="5000"></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small" for="instruction_closure_reject_note">Note interne (optionnel)</label>
                            <textarea class="form-control" id="instruction_closure_reject_note" name="instruction_closure_note" rows="2" maxlength="5000"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Rejeter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@section('page-header')
<div>
    @php
        $spaceRoute = (string) ($space['route'] ?? '');
        $respexpInstructionLocked = ($spaceRoute === 'respexp') && ! $dossier->isInstructionSubmittedToExploitation();
    @endphp
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <h5 class="page-title mb-0">Dossier — {{ $dossier->programmesLabel() ?: '—' }}</h5>
        <span class="badge bg-secondary-subtle text-secondary border">
            {{ $dossier->currentOrganisationLabel() ?: '—' }}
        </span>
    </div>
    <p class="text-body-secondary mb-0 mt-1">{{ $dossier->entreprise?->name ?? 'Entreprise non renseignée' }}</p>
    <div class="d-flex flex-wrap gap-2 mt-2">
        <span class="badge bg-light text-dark border">
            <span class="text-muted">Agence :</span> <strong>{{ $dossier->agence?->name ?? '—' }}</strong>
        </span>
        @if($respexpInstructionLocked)
            <span class="badge bg-light text-dark border">
                <span class="text-muted">Note agrégée :</span> <span class="text-muted">non affichée (instruction non soumise)</span>
            </span>
        @else
            <span class="badge bg-light text-dark border">
                <span class="text-muted">Note agrégée :</span> <strong>{{ number_format((float) ($dossier->note ?? 0), 2, ',', ' ') }}</strong>
            </span>
        @endif
    </div>
    @if(in_array($space['route'] ?? '', ['respexp', 'juridique'], true) && $dossier->exploitation_analyste_assigned_at)
        <p class="small text-muted mb-0 mt-2 ps-3 border-start border-3 border-brand">
            <span class="fw-semibold">Cotation du dossier</span> —
            le {{ $dossier->exploitation_analyste_assigned_at->format('d/m/Y') }} à {{ $dossier->exploitation_analyste_assigned_at->format('H:i') }}
            @if($dossier->exploitationAnalysteAssignedBy)
                par <strong>{{ $dossier->exploitationAnalysteAssignedBy->name }}</strong>
            @else
                <span class="text-muted">(auteur non renseigné)</span>
            @endif
        </p>
    @endif
    @if(in_array($space['route'] ?? '', ['reng', 'analyste-credit'], true) && $dossier->reng_analyste_credit_assigned_at && $dossier->reng_analyste_credit_user_id)
        <p class="small text-muted mb-0 mt-2 ps-3 border-start border-3 border-success">
            <span class="fw-semibold">Analyste crédit</span> —
            affectation le {{ $dossier->reng_analyste_credit_assigned_at->format('d/m/Y H:i') }}
            @if($dossier->rengAnalysteCreditAssignedBy)
                par <strong>{{ $dossier->rengAnalysteCreditAssignedBy->name }}</strong>
            @endif
        </p>
    @endif
    @if(($space['route'] ?? '') === 'rerx' && $dossier->rerx_analyste_risques_assigned_at && $dossier->rerx_analyste_risques_user_id)
        <p class="small text-muted mb-0 mt-2 ps-3 border-start border-3 border-warning">
            <span class="fw-semibold">Analyste risques</span> —
            affectation le {{ $dossier->rerx_analyste_risques_assigned_at->format('d/m/Y H:i') }}
            @if($dossier->rerxAnalysteRisquesAssignedBy)
                par <strong>{{ $dossier->rerxAnalysteRisquesAssignedBy->name }}</strong>
            @endif
        </p>
    @endif
</div>
@endsection

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
            </div>
        @endif

        @php
            $spaceRoute = (string) ($space['route'] ?? '');
            $isHubSpace = in_array($spaceRoute, ['respexp', 'juridique', 'analyste-juridique', 'reng', 'analyste-credit', 'rerx', 'analyste-risques', 'dg', 'dga'], true);
            $timeline = $dossier->instructionWorkflowHistoryTimeline();
            $structurationSvc = app(\App\Services\StructurationClosureService::class);
        @endphp

        {{-- Présentation linéaire (ordre chronologique croissant) --}}
        @php
            $timelineSorted = $timeline
                ->sortBy(fn ($r) => $r['at']?->getTimestamp() ?? PHP_INT_MAX)
                ->values();
        @endphp

        {{-- Bloc synthèse : consultation (lecture) --}}
        @include('partials.instruction-dossier-consultation', ['dossier' => $dossier, 'instructionConsultation' => $instructionConsultation ?? null])

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-clock me-2 text-primary"></i>Historique (chronologie)</h6>
                <p class="text-muted small mb-0 mt-1">Affichage linéaire et croissant (du plus ancien au plus récent).</p>
            </div>
            <div class="card-body pt-0">
                @if($timelineSorted->isEmpty())
                    <p class="text-muted small mb-0">Aucun événement enregistré pour ce dossier.</p>
                @else
                    <div class="vstack gap-2">
                        @foreach($timelineSorted as $row)
                            <div class="border rounded p-2 bg-white">
                                <div class="d-flex justify-content-between gap-2">
                                    <div class="small fw-semibold text-break">{{ $row['label'] }}</div>
                                    <div class="small text-muted text-nowrap">{{ $row['at']?->format('d/m/Y H:i') }}</div>
                                </div>
                                @if(! empty($row['actor']))
                                    <div class="small text-muted mt-1 text-break">Par <strong>{{ $row['actor']->name }}</strong></div>
                                @endif
                                @if(! empty($row['body_html']))
                                    <div class="small mt-2 text-break" style="overflow-wrap:anywhere; word-break:break-word;">{!! $row['body_html'] !!}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Parcours : sections linéaires par étape (contenus volumineux protégés) --}}
        @if($isHubSpace)
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-transparent border-0 py-3">
                    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                        <div>
                            <h6 class="mb-0 fw-semibold"><i class="demo-psi-shuffle me-2 text-primary"></i>Parcours d’instruction (par étapes)</h6>
                            <p class="text-muted small mb-0 mt-1">Chaque section regroupe les contenus produits au fil du parcours.</p>
                        </div>
                        @if($dossier->isInstructionClosed())
                            <span class="badge bg-dark">Dossier d’instruction clos</span>
                        @endif
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="border rounded p-3 bg-white mb-3">
                        <h6 class="mb-2">1) Structuration (chef de filière → chef d’agence)</h6>
                        <div class="d-flex gap-2 flex-wrap align-items-center mb-2">
                            <span class="badge bg-light text-dark border">{{ $structurationSvc->closureStatutLabel($dossier) }}</span>
                            @if($dossier->chef_filiere_submitted_to_agence_at)
                                <span class="text-muted small">Soumis le {{ $dossier->chef_filiere_submitted_to_agence_at->format('d/m/Y H:i') }}</span>
                            @endif
                            @if($dossier->instruction_agence_validated_at)
                                <span class="text-success small">Validé le {{ $dossier->instruction_agence_validated_at->format('d/m/Y H:i') }}</span>
                            @elseif($dossier->instruction_agence_rejected_at)
                                <span class="text-danger small">Rejeté le {{ $dossier->instruction_agence_rejected_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                        @if($dossier->instruction_agence_reject_motif)
                            <div class="alert alert-danger py-2 small mb-0 text-break" style="white-space: pre-wrap; overflow-wrap:anywhere; word-break:break-word;">{{ $dossier->instruction_agence_reject_motif }}</div>
                        @endif
                        @if($dossier->instruction_agence_closing_note)
                            <div class="alert alert-secondary py-2 small mb-0 mt-2 text-break" style="white-space: pre-wrap; overflow-wrap:anywhere; word-break:break-word;"><strong>Note :</strong> {{ $dossier->instruction_agence_closing_note }}</div>
                        @endif
                    </div>

                    <div class="border rounded p-3 bg-white mb-3">
                        <h6 class="mb-2">2) Exploitation (analyste financier → REXP)</h6>
                        <div class="text-break" style="overflow-wrap:anywhere; word-break:break-word;">
                            @include('RoleSpace.dossiers.partials.respexp_dossier_hub')
                        </div>
                    </div>

                    <div class="border rounded p-3 bg-white mb-3">
                        <h6 class="mb-2">3) Juridique (analyste juridique → responsable juridique)</h6>
                        <div class="text-break" style="overflow-wrap:anywhere; word-break:break-word;">
                            @include('RoleSpace.dossiers.partials.juridique_instruction_workflow')
                        </div>
                    </div>

                    <div class="border rounded p-3 bg-white mb-3">
                        <h6 class="mb-2">4) Engagements (analyste crédit → RENG)</h6>
                        <div class="text-break" style="overflow-wrap:anywhere; word-break:break-word;">
                            @include('RoleSpace.dossiers.partials.reng_instruction_workflow')
                        </div>
                    </div>

                    <div class="border rounded p-3 bg-white mb-3">
                        <h6 class="mb-2">5) Risques (analyste risques → RERX)</h6>
                        <div class="text-break" style="overflow-wrap:anywhere; word-break:break-word;">
                            @include('RoleSpace.dossiers.partials.rerx_risques_workflow')
                        </div>
                    </div>

                    <div class="border rounded p-3 bg-white">
                        <h6 class="mb-2">6) Clôture du dossier d’instruction (délégation de pouvoir)</h6>
                        <div class="d-flex gap-2 flex-wrap align-items-center mb-2">
                            <span class="badge bg-light text-dark border">{{ $instructionClosureStatutLabel ?? '—' }}</span>
                            @if($dossier->instruction_closure_validated_at)
                                <span class="text-success small">Clos le {{ $dossier->instruction_closure_validated_at->format('d/m/Y H:i') }}</span>
                            @elseif($dossier->instruction_closure_rejected_at)
                                <span class="text-danger small">Rejet le {{ $dossier->instruction_closure_rejected_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                        @if(! empty($instructionClosureRuleDescription))
                            <p class="text-muted small mb-2 text-break">{{ $instructionClosureRuleDescription }}</p>
                        @endif
                        @if($dossier->instruction_closure_reject_motif)
                            <div class="alert alert-danger py-2 small mb-0 text-break" style="white-space: pre-wrap; overflow-wrap:anywhere; word-break:break-word;">{{ $dossier->instruction_closure_reject_motif }}</div>
                        @endif
                        @if($dossier->instruction_closure_note)
                            <div class="alert alert-secondary py-2 small mb-0 mt-2 text-break" style="white-space: pre-wrap; overflow-wrap:anywhere; word-break:break-word;"><strong>Note :</strong> {{ $dossier->instruction_closure_note }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Modals d’affectation (inchangées) --}}
            @if($spaceRoute === 'respexp' && isset($analystesExploitation) && $analystesExploitation->isNotEmpty())
                @include('RoleSpace.dossiers.partials.respexp_assign_analyste_modal')
            @endif
            @if($spaceRoute === 'juridique' && isset($analystesJuridique) && $analystesJuridique->isNotEmpty() && ! $dossier->isSubmittedToEngagementsFromJuridique() && ! $dossier->isJuridiqueAnalysteAvisSubmittedToReju())
                @include('RoleSpace.dossiers.partials.juridique_assign_analyste_modal')
            @endif
            @if($spaceRoute === 'reng' && isset($analystesCredit) && $analystesCredit->isNotEmpty() && ! $dossier->isSubmittedToRisquesFromReng() && ! $dossier->isRengAnalysteCreditSubmittedToReng())
                @include('RoleSpace.dossiers.partials.reng_assign_analyste_credit_modal')
            @endif
            @if($spaceRoute === 'rerx' && isset($analystesRisques) && $analystesRisques->isNotEmpty() && ! $dossier->isSubmittedToDirectionFromRerx() && ! $dossier->isRerxAnalysteRisquesSubmittedToRerx())
                @include('RoleSpace.dossiers.partials.rerx_assign_analyste_risques_modal')
            @endif
        @endif

        <div class="mt-3">
            @include('partials.dossier-pieces-jointes', [
                'dossier' => $dossier,
                'routePiecesStore' => ($spaceRoute).'.dossiers.pieces.store',
                'modalId' => $piecesModalId ?? 'dossierPieceUploadModal',
                'fichierTypes' => $fichierTypes ?? collect(),
            ])
        </div>
    </div>
@endsection

@if($roleSpaceDossierNeedsSummernote)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-fr-FR.min.js"></script>
<script>
jQuery(window).on('load', function () {
(function ($) {
    var toolbarFull = [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'hr']],
        ['view', ['codeview']]
    ];
    function baseOptions(height, toolbar) {
        return {
            lang: 'fr-FR',
            height: height,
            dialogsInBody: true,
            toolbar: toolbar,
            callbacks: {
                onChange: function (contents) {
                    $(this).val(contents);
                }
            }
        };
    }
    function syncSummernoteToTextarea($ta) {
        if ($ta.length && $ta.next('.note-editor').length) {
            $ta.val($ta.summernote('code'));
        }
    }
    if ($('#exploitation_avis_credit').length) {
        $('#exploitation_avis_credit').summernote($.extend({}, baseOptions(280, toolbarFull), {
            placeholder: 'Rédigez l’avis de crédit…'
        }));
        $('#avis-credit-form').on('submit', function () {
            syncSummernoteToTextarea($('#exploitation_avis_credit'));
        });
    }
    $('.js-summernote-juridique').each(function () {
        var $ta = $(this);
        if ($ta.next('.note-editor').length) return;
        $ta.summernote($.extend({}, baseOptions(260, toolbarFull), {
            placeholder: 'Rédigez votre avis…'
        }));
    });
    $('#form-analyste-juridique-soumettre').on('submit', function () {
        syncSummernoteToTextarea($('#juridique_analyste_avis'));
    });
    $('form[action*="/juridique/dossiers/"][action*="responsable-avis"]').on('submit', function () {
        syncSummernoteToTextarea($('#juridique_responsable_avis'));
    });
    $('form[action*="/reng/dossiers/"][action*="responsable-avis"]').on('submit', function () {
        syncSummernoteToTextarea($('#reng_responsable_avis'));
    });
    $('form[action*="/rerx/dossiers/"][action*="responsable-avis"]').on('submit', function () {
        syncSummernoteToTextarea($('#rerx_responsable_avis'));
    });
    $('.js-summernote-reng').each(function () {
        var $ta = $(this);
        if ($ta.next('.note-editor').length) return;
        $ta.summernote($.extend({}, baseOptions(240, toolbarFull), {
            placeholder: '…'
        }));
    });
    $('#form-analyste-credit-reng').on('submit', function () {
        syncSummernoteToTextarea($('#reng_contre_analyse'));
        syncSummernoteToTextarea($('#reng_analyste_credit_avis'));
    });
    $('.js-summernote-rerx').each(function () {
        var $ta = $(this);
        if ($ta.next('.note-editor').length) return;
        $ta.summernote($.extend({}, baseOptions(240, toolbarFull), {
            placeholder: '…'
        }));
    });
    $('#form-analyste-risques-rerx').on('submit', function () {
        syncSummernoteToTextarea($('#rerx_analyse_risques'));
        syncSummernoteToTextarea($('#rerx_analyste_risques_avis'));
    });
    @if($errors->has('analyste_user_id'))
    (function () {
        var el = document.getElementById('modalAffecterAnalyste');
        if (el && window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(el).show();
        }
    })();
    @endif
    @if($errors->has('juridique_analyste_user_id'))
    (function () {
        var el = document.getElementById('modalAffecterAnalysteJuridique');
        if (el && window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(el).show();
        }
    })();
    @endif
    @if($errors->has('reng_analyste_credit_user_id'))
    (function () {
        var el = document.getElementById('modalAffecterAnalysteCredit');
        if (el && window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(el).show();
        }
    })();
    @endif
    @if($errors->has('rerx_analyste_risques_user_id'))
    (function () {
        var el = document.getElementById('modalAffecterAnalysteRisques');
        if (el && window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(el).show();
        }
    })();
    @endif
})(jQuery);
});
</script>
@endpush
@endif
