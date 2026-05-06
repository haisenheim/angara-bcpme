@extends('Layouts.ca')

@section('title', 'Analyse critique analyste — grille et 7 zones')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.dossiers.index') }}">Dossiers</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.dossiers.show', $item->token) }}">{{ $item->entreprise?->name ?? 'Dossier' }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Grille et sept zones</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown button-id="caDossierGrilleActions" menu-class="dropdown-menu dropdown-menu-end border shadow-sm py-2 analyse">
        @if(($canApproveRejectInstructionTransmission ?? false) && $item->isInstructionPendingAgenceValidation())
            <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#caInstructionTransmissionApproveModal"><i class="demo-psi-check me-2 text-success"></i> Valider la transmission</button></li>
            <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#caInstructionTransmissionRejectModal"><i class="demo-psi-cross me-2 text-danger"></i> Rejeter la transmission</button></li>
            <li><hr class="dropdown-divider"></li>
        @endif
        <li><a class="dropdown-item" href="{{ route('ca.dossier.analyse-critique.synthese', $item->token) }}"><i class="demo-psi-file-text me-2"></i> Dossier d’analyse critique (7 zones)</a></li>
        <li><a class="dropdown-item" href="{{ route('ca.dossier.analyse-critique.synthese.pdf', $item->token) }}" target="_blank" rel="noopener"><i class="demo-psi-download me-2"></i> Exporter le dossier en PDF</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('ca.dossiers.show', $item->token) }}"><i class="demo-psi-file me-2"></i> Retour fiche dossier</a></li>
    </x-page-actions-dropdown>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    @include('partials.dossier-pieces-jointes', [
        'dossier' => $item,
        'showUpload' => false,
    ])

    <div class="container">
        <div class="d-flex justify-content-center">
            <div style="width:800px" class="card border-0 shadow-sm">
                <div class="card-header p-4 bg-transparent">
                    <h4 class="text-center mb-0">SEPT ZONES DE L’ANALYSE CRITIQUE (ANALYSTE)</h4>
                    <p class="text-center text-muted small mb-0 mt-2">Dossier d’analyse critique : uniquement ces saisies analyste (la grille est dans la fiche instruction). Programme(s) : {{ $item->programmesLabel() }}</p>
                    @if($item->instructionProgrammes->isNotEmpty())
                        <div class="table-responsive mt-3 mx-auto" style="max-width: 640px;">
                            <table class="table table-sm table-bordered mb-0 bg-white">
                                <thead class="table-light">
                                    <tr>
                                        <th>Programme</th>
                                        <th class="text-end">Appui financier (XAF)</th>
                                        <th class="text-end">Appui non financier (XAF)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->instructionProgrammes as $dip)
                                        <tr>
                                            <td>{{ $dip->programme?->name ?? '—' }}</td>
                                            <td class="text-end">{{ number_format((float) $dip->budget_appui_financier, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format((float) $dip->budget_appui_non_financier, 0, ',', ' ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="card-body table-responsive">
                    <div role="tabpanel">
                        <div class="mt-1 border rounded rounded-2 p-2">
                            <h4 class="fs-5">1. INFORMATIONS GENERALES</h4>
                            <p class="lh-base"><?= $item['donnees_generales'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2">
                            <h4 class="fs-5">2. ANALYSE D'ENSEMBLE</h4>
                            <p class="lh-base"><?= $item['analyse_ensemble'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2">
                            <h4 class="fs-5">3. ANALYSE FINANCIERE</h4>
                            <p class="lh-base"><?= $item['analyse_financiere'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2">
                            <h4 class="fs-5">4. APPUIS FINANCIERS ET NON FINANCIERS</h4>
                            <p class="lh-base"><?= $item['appuis'] ?? '—' ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-5">5. ANALYSE DU RISQUE ET DE LA CAPACITE DE REMBOURSEMENT</h4>
                            <p class="lh-base"><?= $item['analyse_risque'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2">
                            <h4 class="fs-5">6. RENTABILITE DE LA RELATION POUR L'ETABLISSEMENT</h4>
                            <p class="lh-base"><?= $item['analyse_rentabilite'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2">
                            <h4 class="fs-5">7. CONCLUSIONS MOTIVEES, RECOMMANDATIONS DE L'ANALYSTE FINANCIER</h4>
                            <p class="lh-base"><?= $item['conclusions_analyste'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2 border-primary border-2">
                            <h4 class="fs-5">8. CONCLUSIONS ET RECOMMANDATIONS DU GESTIONNAIRE</h4>
                            <p class="lh-base"><?= $item['conclusions_gestionnaire'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2 border-primary border-2">
                            <h4 class="fs-5">Complément — chef d’agence</h4>
                            <p class="small text-muted">Hors des sept zones analyste.</p>
                            <p class="lh-base"><?= $item['conclusions_ca'] ?? '—' ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(($canApproveRejectInstructionTransmission ?? false) && $item->isInstructionPendingAgenceValidation())
        @include('partials.ca-instruction-transmission-modals', ['dossier' => $item])
    @endif
@endsection
