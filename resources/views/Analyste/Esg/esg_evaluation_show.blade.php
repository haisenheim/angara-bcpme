@extends('Layouts.analyste')

@section('title', 'Évaluation ESG - ' . ($dossier->entreprise?->name ?? 'Dossier'))
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('analyste.dossiers.esg-evaluations.index') }}">Évaluations ESG</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $dossier->entreprise?->name }} - {{ $dossier->programme?->name }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    @if(!$evaluation || $evaluation->canBeEdited())
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                <i class="demo-psi-dot-vertical"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @if($evaluation)
                    <li><a class="dropdown-item" href="{{ route('analyste.dossiers.esg-evaluation.edit', $dossier) }}"><i class="demo-psi-pen-5 me-2"></i> Modifier</a></li>
                    <li>
                        <form action="{{ route('analyste.dossiers.esg-evaluation.rebuild-scores', $dossier) }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-start w-100"><i class="demo-psi-data-settings me-2"></i> Recalculer scores</button>
                        </form>
                    </li>
                    @if($evaluation->isDraft())
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('analyste.dossiers.esg-evaluation.submit', $dossier) }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-start w-100 text-primary"><i class="demo-psi-upload me-2"></i> Soumettre</button>
                            </form>
                        </li>
                    @endif
                @else
                    <li><a class="dropdown-item" href="{{ route('analyste.dossiers.esg-evaluation.create', $dossier) }}"><i class="demo-pli-add me-2"></i> Créer l'évaluation</a></li>
                @endif
            </ul>
        </div>
    @endif
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Évaluation ESG - {{ $dossier->entreprise?->name }}</h5>
        <p class="lead">{{ $dossier->programme?->name }}</p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    @if(!$evaluation)
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="demo-psi-file-search text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 mb-2">Aucune évaluation ESG</h5>
                <p class="text-muted mb-4">Créez une évaluation ESG pour ce dossier afin de renseigner les scores et conclusions.</p>
                <a href="{{ route('analyste.dossiers.esg-evaluation.create', $dossier) }}" class="btn btn-primary">
                    <i class="demo-pli-add me-2"></i> Créer l'évaluation
                </a>
            </div>
        </div>
    @else
        @php
            $statusConfig = [
                'draft' => ['label' => 'Brouillon', 'bg' => 'secondary', 'icon' => 'demo-psi-pen-5'],
                'submitted' => ['label' => 'Soumise', 'bg' => 'warning', 'icon' => 'demo-psi-upload'],
                'validated' => ['label' => 'Validée', 'bg' => 'success', 'icon' => 'demo-psi-check'],
                'rejected' => ['label' => 'Rejetée', 'bg' => 'danger', 'icon' => 'demo-psi-close'],
            ];
            $status = $statusConfig[$evaluation->status] ?? ['label' => ucfirst($evaluation->status), 'bg' => 'info', 'icon' => 'demo-psi-information'];
            $scoreGlobal = (float) $evaluation->score_global;
            $scoreColor = $scoreGlobal >= 70 ? 'success' : ($scoreGlobal >= 50 ? 'warning' : 'danger');
        @endphp

        {{-- En-tête : Score global et statut --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="position-relative me-4">
                            <svg class="progress-ring" width="90" height="90" viewBox="0 0 90 90">
                                <circle class="progress-ring-bg" cx="45" cy="45" r="38" fill="none" stroke="var(--bs-light)" stroke-width="8"/>
                                <circle class="progress-ring-fill" cx="45" cy="45" r="38" fill="none" stroke="var(--bs-{{ $scoreColor }})" stroke-width="8" stroke-linecap="round"
                                    stroke-dasharray="{{ $scoreGlobal * 2.39 }} 239" transform="rotate(-90 45 45)"/>
                            </svg>
                            <span class="position-absolute top-50 start-50 translate-middle fw-bold fs-4">{{ number_format($scoreGlobal, 1) }}</span>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase mb-1 small">Score global</h6>
                            <p class="mb-0 fs-5 fw-semibold">sur 100</p>
                            <span class="badge bg-{{ $status['bg'] }} mt-2">
                                <i class="{{ $status['icon'] }} me-1"></i>{{ $status['label'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row g-3 h-100">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body py-3">
                                <small class="text-muted text-uppercase">Niveau de risque</small>
                                <p class="mb-0 fw-semibold fs-6">{{ $evaluation->risk_level ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body py-3">
                                <small class="text-muted text-uppercase">Bancabilité</small>
                                <p class="mb-0 fw-semibold fs-6">{{ $evaluation->bankability_level ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body py-3">
                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    <span class="text-muted small">Éligibilités :</span>
                                    <span class="badge {{ $evaluation->eligibility_blending ? 'bg-success' : 'bg-light text-dark' }}">
                                        <i class="demo-psi-{{ $evaluation->eligibility_blending ? 'check' : 'close' }} me-1"></i> Blending
                                    </span>
                                    <span class="badge {{ $evaluation->eligibility_guarantee ? 'bg-success' : 'bg-light text-dark' }}">
                                        <i class="demo-psi-{{ $evaluation->eligibility_guarantee ? 'check' : 'close' }} me-1"></i> Garantie
                                    </span>
                                    <span class="badge {{ $evaluation->eligibility_global_gateway ? 'bg-success' : 'bg-light text-dark' }}">
                                        <i class="demo-psi-{{ $evaluation->eligibility_global_gateway ? 'check' : 'close' }} me-1"></i> Global Gateway
                                    </span>
                                    @if($evaluation->exclusion_flag)
                                        <span class="badge bg-danger"><i class="demo-psi-exclamation me-1"></i> Exclusion</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scores par pilier ESG --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-bar-chart me-2 text-primary"></i>Scores par pilier</h6>
            </div>
            <div class="card-body">
                @php
                    $pillars = [
                        ['key' => 'environmental', 'label' => 'Environnement', 'icon' => 'demo-psi-electricity', 'color' => 'success'],
                        ['key' => 'social', 'label' => 'Social', 'icon' => 'demo-psi-checked-user', 'color' => 'info'],
                        ['key' => 'governance', 'label' => 'Gouvernance', 'icon' => 'demo-psi-building', 'color' => 'primary'],
                        ['key' => 'financial', 'label' => 'Financier', 'icon' => 'demo-psi-coin', 'color' => 'warning'],
                        ['key' => 'compliance', 'label' => 'Conformité', 'icon' => 'demo-psi-check', 'color' => 'secondary'],
                    ];
                @endphp
                <div class="row g-3">
                    @foreach($pillars as $p)
                        @php $val = (float) ($evaluation->{'score_' . $p['key']} ?? 0); $pct = min(100, max(0, $val)); @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="d-flex align-items-center">
                                <i class="{{ $p['icon'] }} text-{{ $p['color'] }} me-3" style="font-size: 1.5rem;"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small fw-medium">{{ $p['label'] }}</span>
                                        <span class="small fw-semibold">{{ number_format($val, 1) }}/100</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $p['color'] }}" role="progressbar" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Conclusion et analyse --}}
        @if($evaluation->analyst_conclusion || $evaluation->strengths || $evaluation->weaknesses || $evaluation->recommendations)
            <div class="row g-3">
                @if($evaluation->analyst_conclusion)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent border-0 py-3">
                                <h6 class="mb-0 fw-semibold"><i class="demo-psi-speech-bubble-3 me-2 text-primary"></i>Conclusion analyste</h6>
                            </div>
                            <div class="card-body pt-0">
                                <p class="mb-0 text-body">{{ $evaluation->analyst_conclusion }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if($evaluation->strengths)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm border-start border-3 border-success h-100">
                            <div class="card-header bg-transparent border-0 py-3">
                                <h6 class="mb-0 fw-semibold text-success"><i class="demo-psi-like me-2"></i>Forces</h6>
                            </div>
                            <div class="card-body pt-0">
                                <p class="mb-0 text-body">{{ $evaluation->strengths }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if($evaluation->weaknesses)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm border-start border-3 border-warning h-100">
                            <div class="card-header bg-transparent border-0 py-3">
                                <h6 class="mb-0 fw-semibold text-warning"><i class="demo-psi-information me-2"></i>Faiblesses</h6>
                            </div>
                            <div class="card-body pt-0">
                                <p class="mb-0 text-body">{{ $evaluation->weaknesses }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if($evaluation->recommendations)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm border-start border-3 border-info h-100">
                            <div class="card-header bg-transparent border-0 py-3">
                                <h6 class="mb-0 fw-semibold text-info"><i class="demo-psi-idea me-2"></i>Recommandations</h6>
                            </div>
                            <div class="card-body pt-0">
                                <p class="mb-0 text-body">{{ $evaluation->recommendations }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @endif
@endsection
