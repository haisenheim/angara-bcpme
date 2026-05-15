@extends('Layouts.juridique')

@section('title', 'Dossier d’instruction')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Dossier d’instruction</h1>
        <p class="text-muted mb-0">{{ $dossier->entreprise?->name ?? '—' }} — {{ $dossier->programmesLabel() }}</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="mb-3">
            <a href="{{ route('juridique.dossiers.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Retour à la liste</a>
            @if($dossier->entreprise?->token)
                <a href="{{ route('juridique.entreprises.show', $dossier->entreprise->token) }}" class="btn btn-sm btn-outline-primary ms-1">Fiche entreprise</a>
            @endif
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block">Agence</small>
                        <p class="mb-0 fw-semibold">{{ $dossier->agence?->name ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block">Analyste instruction</small>
                        <p class="mb-0 fw-semibold">{{ $dossier->analyste?->name ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block">Transmission juridique</small>
                        <p class="mb-0 small">
                            {{ $dossier->juridique_instruction_submitted_at?->format('d/m/Y H:i') ?? '—' }}
                            @if($dossier->juridiqueInstructionSubmittedBy)
                                <br><span class="text-muted">{{ $dossier->juridiqueInstructionSubmittedBy->name }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($dossier->exploitation_analyste_transmitted_to_exploitation_at)
            <p class="small text-muted mb-3">
                Transmission analyste à l’exploitation le {{ $dossier->exploitation_analyste_transmitted_to_exploitation_at->format('d/m/Y H:i') }}
                @if($dossier->exploitationAnalysteTransmittedToExploitationBy)
                    — {{ $dossier->exploitationAnalysteTransmittedToExploitationBy->name }}
                @endif
            </p>
        @endif

        <div class="mb-4">
            @include('partials.instruction-dossier-consultation', ['dossier' => $dossier, 'instructionConsultation' => $instructionConsultation ?? null])
        </div>

        @include('partials.dossier-pieces-jointes', [
            'dossier' => $dossier,
            'showUpload' => false,
        ])

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white"><strong>Décision exploitation (validation du dossier d’instruction)</strong></div>
            <div class="card-body">
                @if($dossier->exploitation_engagements_decision_at)
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                        <span class="badge {{ $dossier->exploitation_engagements_decision === 'accord' ? 'bg-success' : 'bg-danger' }}">
                            {{ $dossier->exploitation_engagements_decision === 'accord' ? 'Validé' : 'Rejeté' }}
                        </span>
                        <span class="small text-muted">{{ $dossier->exploitation_engagements_decision_at->format('d/m/Y H:i') }}
                            @if($dossier->exploitationEngagementsDecisionUser) — {{ $dossier->exploitationEngagementsDecisionUser->name }} @endif
                        </span>
                    </div>
                    @if($dossier->exploitation_engagements_decision_comment)
                        <p class="mb-0 small"><strong>Commentaire :</strong> {{ $dossier->exploitation_engagements_decision_comment }}</p>
                    @endif
                @else
                    <p class="text-muted mb-0 small">—</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white"><strong>Avis de crédit (responsable exploitation)</strong></div>
            <div class="card-body">
                @if($dossier->exploitation_avis_credit_at)
                    <p class="small text-muted mb-2">
                        Saisi le {{ $dossier->exploitation_avis_credit_at->format('d/m/Y H:i') }}
                        @if($dossier->exploitationAvisCreditUser)
                            — {{ $dossier->exploitationAvisCreditUser->name }}
                        @endif
                    </p>
                @endif
                <div class="rich-text-rendered small border rounded p-3 bg-light">{!! $dossier->exploitation_avis_credit !!}</div>
            </div>
        </div>
    </div>
@endsection
