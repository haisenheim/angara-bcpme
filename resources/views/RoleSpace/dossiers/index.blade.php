@extends('Layouts.app')

@section('title', 'Dossiers - '.$space['title'])

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Dossiers</h1>
        <p class="text-muted mb-0">Vue transverse des dossiers liés aux entreprises.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route($space['route'].'.dashboard') }}" class="btn btn-sm btn-outline-secondary">Retour au tableau de bord</a>
                    <span class="text-muted small">{{ $dossiers->total() }} dossier(s)</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Entreprise</th>
                                <th>Programme</th>
                                <th>Analyste</th>
                                <th>Gestionnaire</th>
                                <th>État</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dossiers as $dossier)
                                <tr>
                                    <td>{{ $dossier->entreprise?->name ?? '—' }}</td>
                                    <td>{{ $dossier->programme?->name ?? '—' }}</td>
                                    <td>{{ $dossier->analyste?->name ?? '—' }}</td>
                                    <td>{{ $dossier->gestionnaire?->name ?? '—' }}</td>
                                    <td>{{ $dossier->status['name'] ?? '—' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route($space['route'].'.dossiers.show', $dossier->token) }}" class="btn btn-sm btn-primary">Ouvrir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Aucun dossier trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $dossiers->links() }}
            </div>
        </div>
    </div>
@endsection
