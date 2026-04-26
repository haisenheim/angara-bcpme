@extends('Layouts.juridique')

@section('title', 'Entreprises')

@section('page-header')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
        <div>
            <h1 class="h3 mb-0">Entreprises</h1>
            <p class="text-muted mb-0">Portefeuille des entreprises clientes (hors prospects).</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="get" action="{{ route('juridique.entreprises.index') }}" class="row g-2 mb-3 align-items-end">
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label small text-muted mb-0">Recherche</label>
                        <input type="search" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Nom, NIU, RCCM…">
                    </div>
                    <div class="col-md-4 col-lg-3">
                        @include('partials.client-structuration-filter-select', [
                            'id' => 'juridique_entreprises_struct',
                            'name' => 'client_structuration_status',
                            'selected' => $structurationStatus ?? null,
                        ])
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                    </div>
                    @if(($q ?? '') !== '' || !empty($structurationStatus))
                        <div class="col-auto">
                            <a href="{{ route('juridique.entreprises.index') }}" class="btn btn-outline-secondary btn-sm">Réinitialiser</a>
                        </div>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Dénomination</th>
                                <th>NIU</th>
                                <th>Agence</th>
                                <th>Structuration</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $row)
                                <tr>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->niu ?? '—' }}</td>
                                    <td>{{ $row->agence?->name ?? '—' }}</td>
                                    <td>
                                        @if($row->promu_client_at)
                                            @include('partials.client-structuration-badge', ['eer' => $row->dossierEntreeRelation])
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('juridique.entreprises.show', $row->token) }}" class="btn btn-outline-primary btn-sm">Voir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted text-center py-4">Aucune entreprise trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
