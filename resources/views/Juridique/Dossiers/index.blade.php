@extends('Layouts.juridique')

@section('title', 'Dossiers d’instruction')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Dossiers d’instruction</h1>
        <p class="text-muted mb-0">Dossiers transmis par le responsable exploitation après validation et avis de crédit.</p>
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

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Entreprise</th>
                                <th>Programme</th>
                                <th>Analyste</th>
                                <th>Transmission</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dossiers as $dossier)
                                <tr>
                                    <td>{{ $dossier->entreprise?->name ?? '—' }}</td>
                                    <td>{{ $dossier->programme?->name ?? '—' }}</td>
                                    <td>{{ $dossier->analyste?->name ?? '—' }}</td>
                                    <td>
                                        @if($dossier->juridique_instruction_submitted_at)
                                            <span class="small text-muted">{{ $dossier->juridique_instruction_submitted_at->format('d/m/Y H:i') }}</span>
                                            @if($dossier->juridiqueInstructionSubmittedBy)
                                                <br><span class="small">{{ $dossier->juridiqueInstructionSubmittedBy->name }}</span>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('juridique.dossiers.show', $dossier->token) }}" class="btn btn-sm btn-primary">Ouvrir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Aucun dossier transmis pour le moment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($dossiers->hasPages())
                <div class="card-footer">{{ $dossiers->links() }}</div>
            @endif
        </div>
    </div>
@endsection
