@extends('Layouts.gestionnaire')

@section('title', 'Qualifications clients')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Qualifications clients</h1>
        <p class="text-muted mb-0">Clients validés à qualifier, orienter et soumettre pour création des dossiers d'instruction.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Agence</th>
                            <th>Promu client</th>
                            <th>EER</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->agence?->name ?? '-' }}</td>
                                <td>{{ optional($item->promu_client_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $item->dossierEntreeRelation?->statut ?? 'non initialisé' }}</td>
                                <td><a href="{{ route('chef-filiere.qualifications.show', $item->token) }}" class="btn btn-sm btn-outline-primary">Ouvrir</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucun client à qualifier.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
