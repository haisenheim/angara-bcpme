@extends('Layouts.ca')

@section('title', 'Validation qualification')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">{{ $eer->entreprise?->name ?? 'Dossier EER' }}</h1>
        <p class="text-muted mb-0">Validation de la qualification par le chef d'agence. Les inscriptions aux programmes et la création des dossiers d'instruction sont ensuite effectuées par le chef de filière depuis la fiche client.</p>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            @if($eer->programmeSelections->isNotEmpty())
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <strong>Programmes (ancien flux)</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Programme</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Dossier créé</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eer->programmeSelections as $selection)
                                <tr>
                                    <td>{{ $selection->programme?->name ?? '-' }}</td>
                                    <td>{{ $selection->type_appui }}</td>
                                    <td><span class="badge bg-info">{{ $selection->statut }}</span></td>
                                    <td>{{ $selection->instruction_dossier_id ? 'Oui' : 'Non' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="alert alert-light border mb-4">
                <strong>Nouveau flux :</strong> aucun programme n'est joint à la qualification. Après validation, le chef de filière inscrit le client programme par programme depuis la fiche client (création automatique du dossier d'instruction).
            </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Analyse critique</strong>
                </div>
                <div class="card-body">
                    @forelse($eer->entreprise?->dossierAnalyseCritique?->avis ?? [] as $avis)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>{{ $avis->source_label ?? $avis->source_type }}</strong>
                                <span class="badge bg-secondary">{{ $avis->etat }}</span>
                            </div>
                            <div class="small text-muted mb-2">
                                {{ $avis->emisPar?->name ?? 'Systeme' }} · {{ optional($avis->emis_at)->format('d/m/Y H:i') ?? '-' }}
                            </div>
                            <div style="white-space: pre-wrap;">{{ $avis->contenu }}</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucun avis consolidé.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Décision chef d'agence</strong>
                </div>
                <div class="card-body">
                    <p><strong>Statut EER :</strong> {{ $eer->statut }}</p>
                    <p><strong>Validation qualification :</strong> {{ $eer->instruction_validation_status }}</p>
                    <p><strong>Soumis par le chef de filière :</strong> {{ optional($eer->programmes_submitted_at)->format('d/m/Y H:i') ?? '-' }}</p>
                    @if($eer->qualification_validated_by_agence_at)
                        <p class="text-success mb-3"><strong>Qualification validée le</strong> {{ $eer->qualification_validated_by_agence_at->format('d/m/Y H:i') }}
                            @if($eer->qualificationValidatedByAgenceUser)
                                — {{ $eer->qualificationValidatedByAgenceUser->name }}
                            @endif
                        </p>
                    @endif
                    @if(!$eer->qualification_validated_by_agence_at && !$eer->instruction_validated_at)
                        @if($eer->programmeSelections->isNotEmpty())
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCaApproveInstructionLegacy">
                                Valider et créer les dossiers (ancien flux)
                            </button>
                        @else
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCaApproveInstruction">
                                Valider la qualification
                            </button>
                        @endif
                    @else
                        <p class="text-muted mb-0">Aucune action requise sur cet écran.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(!$eer->qualification_validated_by_agence_at && !$eer->instruction_validated_at)
    @if($eer->programmeSelections->isNotEmpty())
    <div class="modal fade" id="modalCaApproveInstructionLegacy" tabindex="-1" aria-labelledby="modalCaApproveInstructionLegacyLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('ca.workflow.instructions.approve', $eer->token) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCaApproveInstructionLegacyLabel">Valider (ancien flux)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Valider et créer les dossiers d’instruction pour les programmes listés (ancien flux) ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @else
    <div class="modal fade" id="modalCaApproveInstruction" tabindex="-1" aria-labelledby="modalCaApproveInstructionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('ca.workflow.instructions.approve', $eer->token) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCaApproveInstructionLabel">Valider la qualification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Confirmer la validation de la qualification ? Les inscriptions aux programmes se feront sur la fiche client (chef de filière).</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endif
@endsection
