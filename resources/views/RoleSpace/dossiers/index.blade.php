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

@section('title', 'Dossiers - '.$space['title'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dossiers d'instruction</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Dossiers d'instruction</h5>
        <p class="text-muted mb-0">
            @if($space['route'] === 'respexp' && ($dossiersFilter ?? null) === 'a_affecter')
                Liste des dossiers <strong>sans analyste affecté</strong> (à coter) — périmètre banque.
            @elseif($space['route'] === 'respexp')
                Tous les dossiers d’instruction — consultation, cotation analyste, avis de crédit et validation des engagements.
            @elseif($space['route'] === 'juridique')
                Dossiers transmis au pôle juridique — même présentation que pour le responsable exploitation (consultation).
            @elseif($space['route'] === 'analyste-juridique')
                Dossiers qui vous sont affectés par le responsable juridique.
            @elseif($space['route'] === 'reng')
                Dossiers transmis par le pôle juridique au responsable engagements.
            @elseif($space['route'] === 'analyste-credit')
                Dossiers du pôle engagements qui vous sont affectés en tant qu’analyste crédit.
            @elseif($space['route'] === 'rerx')
                Dossiers transmis par le responsable engagements au responsable risques.
            @elseif($space['route'] === 'analyste-risques')
                Dossiers du pôle risques qui vous sont affectés en tant qu’analyste risques.
            @elseif($space['route'] === 'dg')
                Dossiers d’instruction transmis par le responsable risques à la direction (DG).
            @elseif($space['route'] === 'dga')
                Dossiers d’instruction transmis par le responsable risques à la direction (DGA).
            @else
                Vue transverse des dossiers liés aux entreprises.
            @endif
        </p>
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
                                @if($space['route'] === 'respexp')
                                    <th class="text-nowrap">Cotation du dossier</th>
                                @endif
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
                                    <td colspan="{{ $space['route'] === 'respexp' ? 7 : 6 }}" class="text-center text-muted py-4">Aucun dossier trouvé.</td>
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
