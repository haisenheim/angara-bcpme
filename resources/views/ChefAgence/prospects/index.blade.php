@extends('Layouts.ca')

@section('title', 'Validation prospects')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Validation chef d'agence</h1>
        <p class="text-muted mb-0">Vue consolidée des prospects soumis avant promotion en client.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Prospect</th>
                            <th>Agence</th>
                            <th>Avis juridique</th>
                            <th>Avis conformité</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->agence?->name ?? '-' }}</td>
                                <td>{{ $item->juridique_avis_at ? 'Rendu' : 'En attente' }}</td>
                                <td>{{ $item->conformite_avis_at ? 'Rendu' : 'En attente' }}</td>
                                <td><a href="{{ route('chef-agence.prospects.show', $item->token) }}" class="btn btn-sm btn-outline-primary">Ouvrir</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucun prospect soumis.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
