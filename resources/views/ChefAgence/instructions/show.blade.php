@extends('Layouts.ca')

@section('title', 'Validation instruction')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">{{ $eer->entreprise?->name ?? 'Dossier EER' }}</h1>
        <p class="text-muted mb-0">Validation finale chef d'agence pour creation des dossiers d'instruction.</p>
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
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <strong>Programmes soumis</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Programme</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Dossier cree</th>
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
                        <p class="text-muted mb-0">Aucun avis consolide.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Decision chef d'agence</strong>
                </div>
                <div class="card-body">
                    <p><strong>Statut EER :</strong> {{ $eer->statut }}</p>
                    <p><strong>Validation instruction :</strong> {{ $eer->instruction_validation_status }}</p>
                    <p><strong>Soumis par chef de filiere :</strong> {{ optional($eer->programmes_submitted_at)->format('d/m/Y H:i') ?? '-' }}</p>
                    <form method="post" action="{{ route('chef-agence.instructions.approve', $eer->token) }}" onsubmit="return confirm('Valider et creer les dossiers d\\'instruction ?');">
                        @csrf
                        <button type="submit" class="btn btn-success">Valider et creer les dossiers d'instruction</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
