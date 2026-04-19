@extends($role === 'juridique' ? 'Layouts.juridique' : 'Layouts.app')

@section('title', $title)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">{{ $title }}</h4>
            <p class="text-body-secondary mb-0 small">Prospects soumis par les gestionnaires — dossiers en attente de votre avis.</p>
        </div>
        <form action="{{ route('logout') }}" method="post" class="m-0">@csrf<button type="submit" class="btn btn-outline-secondary btn-sm">Déconnexion</button></form>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Dénomination</th>
                        <th>Soumis le</th>
                        <th>Agence</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $row)
                        <tr>
                            <td class="fw-medium">{{ $row->name }}</td>
                            <td>{{ $row->prospect_submitted_at ? \Illuminate\Support\Carbon::parse($row->prospect_submitted_at)->format('d/m/Y H:i') : '—' }}</td>
                            <td>{{ $row->agence?->name ?? '—' }}</td>
                            <td class="text-end">
                                @if($role === 'juridique')
                                    <a href="{{ route('juridique.prospects.show', $row->token) }}" class="btn btn-sm btn-primary">Traiter</a>
                                @else
                                    <a href="{{ route('conformite.prospects.show', $row->token) }}" class="btn btn-sm btn-primary">Traiter</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-body-secondary py-4">Aucun dossier en attente.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
