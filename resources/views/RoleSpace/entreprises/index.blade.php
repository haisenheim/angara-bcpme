@extends(match ($space['route'] ?? '') {
    'respexp' => 'Layouts.respexp',
    'juridique' => 'Layouts.juridique',
    'analyste-juridique' => 'Layouts.analyste-juridique',
    'reng' => 'Layouts.reng',
    'analyste-credit' => 'Layouts.analyste-credit',
    'analyste-risques' => 'Layouts.analyste-risques',
    'rerx' => 'Layouts.rerx',
    default => 'Layouts.app',
})

@section('title', 'Entreprises - '.$space['title'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active" aria-current="page">Entreprises</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Entreprises</h5>
        <p class="text-muted mb-0">Vue transverse de toutes les entreprises du portefeuille.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">{{ $entreprises->total() }} entreprise(s)</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Entreprise</th>
                                <th>Statut</th>
                                <th>Gestionnaire</th>
                                <th>Dossiers</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entreprises as $entreprise)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $entreprise->name }}</div>
                                        <small class="text-muted">{{ $entreprise->forme?->name ?? '—' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $entreprise->prospect ? 'warning' : 'success' }}">
                                            {{ $entreprise->prospect ? 'Prospect' : 'Client' }}
                                        </span>
                                    </td>
                                    <td>{{ $entreprise->user?->name ?? '—' }}</td>
                                    <td>{{ $entreprise->dossiers_count }}</td>
                                    <td class="text-end">
                                        <a href="{{ route($space['route'].'.entreprises.show', $entreprise->token) }}" class="btn btn-sm btn-primary">Ouvrir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Aucune entreprise trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $entreprises->links() }}
            </div>
        </div>
    </div>
@endsection
