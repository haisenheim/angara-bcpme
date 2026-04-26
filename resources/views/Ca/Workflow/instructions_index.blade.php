@extends('Layouts.ca')

@section('title', 'Validations structuration EER')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Validations structuration (EER)</h1>
        <p class="text-muted mb-0">Structurations soumises par le chef de filière avant validation d’agence. La constitution du dossier d’instruction multi-programmes (programmes et budgets d’appui) et sa validation se font ensuite via <a href="{{ route('ca.workflow.instruction-dossiers.index') }}">Dossiers d’instruction (multi-programmes)</a>.</p>
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
                        <th>Programmes</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $eer)
                        <tr>
                            <td>{{ $eer->entreprise?->name ?? '-' }}</td>
                            <td>{{ $eer->entreprise?->agence?->name ?? '-' }}</td>
                            <td>{{ optional($eer->programmes_submitted_at)->format('d/m/Y H:i') ?? '-' }}</td>
                            <td>{{ $eer->programmeSelections->pluck('programme.name')->filter()->implode(', ') ?: 'aucun' }}</td>
                            <td class="text-end"><a href="{{ route('ca.workflow.instructions.show', $eer->token) }}" class="btn btn-sm btn-outline-primary">Ouvrir</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Aucun dossier en attente.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
