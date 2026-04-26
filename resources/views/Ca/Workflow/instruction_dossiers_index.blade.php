@extends('Layouts.ca')

@section('title', 'Dossiers d’instruction à valider')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Dossiers d’instruction (multi-programmes)</h1>
        <p class="text-muted mb-0">Après validation de la structuration (EER), le chef de filière peut soumettre <strong>plusieurs</strong> dossiers d’instruction (programmes et budgets d’appui). Chaque dossier transmis apparaît ici jusqu’à validation ou rejet.</p>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Agence</th>
                        <th>Soumis le</th>
                        <th class="text-end">Eng. sollicités</th>
                        <th>Programmes</th>
                        <th>État</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $dossier)
                        @php
                            $progLabel = $dossier->instructionProgrammes->isNotEmpty()
                                ? $dossier->instructionProgrammes->map(fn ($l) => $l->programme?->name)->filter()->implode(', ')
                                : '—';
                            $delegation = app(\App\Services\InstructionDelegationService::class);
                        @endphp
                        <tr>
                            <td>{{ $dossier->entreprise?->name ?? '—' }}</td>
                            <td>{{ $dossier->entreprise?->agence?->name ?? '—' }}</td>
                            <td>{{ optional($dossier->chef_filiere_submitted_to_agence_at)->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="text-end small">{{ $dossier->engagements_sollicites_total !== null ? number_format((float) $dossier->engagements_sollicites_total, 0, ',', ' ') : '—' }}</td>
                            <td class="small">{{ $progLabel }}</td>
                            <td class="small"><span class="badge bg-warning text-dark">{{ $delegation->closureStatutLabel($dossier) }}</span></td>
                            <td class="text-end"><a href="{{ route('ca.workflow.instruction-dossiers.show', $dossier->token) }}" class="btn btn-sm btn-outline-primary">Ouvrir</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Aucun dossier en attente.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
