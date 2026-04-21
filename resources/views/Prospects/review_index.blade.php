@extends($role === 'juridique' ? 'Layouts.juridique' : 'Layouts.conformite')

@section('title', $title)

@section('content')
@php
    $listMode = $listMode ?? 'open';
@endphp
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">{{ $title }}</h4>
            @if ($listMode === 'treated' && $role === 'conformite')
                <p class="text-body-secondary mb-0 small">Dossiers dont le chef d'agence a clos le circuit (promotion ou refus). Consultation en lecture seule.</p>
            @else
                <p class="text-body-secondary mb-0 small">Prospects soumis par les gestionnaires — vous pouvez saisir ou modifier votre avis tant que le chef d'agence n'a pas validé ou refusé le dossier.</p>
            @endif
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
                        <th>Statut</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $row)
                        <tr>
                            <td class="fw-medium">{{ $row->name }}</td>
                            <td>{{ $row->prospect_submitted_at ? \Illuminate\Support\Carbon::parse($row->prospect_submitted_at)->format('d/m/Y H:i') : '—' }}</td>
                            <td>{{ $row->agence?->name ?? '—' }}</td>
                            <td>
                                @if($listMode === 'treated' && $role === 'conformite')
                                    @if($row->promu_client_at)
                                        <span class="badge bg-success">Promu client</span>
                                        <span class="text-body-secondary small d-block">{{ \Illuminate\Support\Carbon::parse($row->promu_client_at)->format('d/m/Y H:i') }}</span>
                                    @elseif($row->prospect_rejected_at)
                                        <span class="badge bg-danger">Refusé</span>
                                        <span class="text-body-secondary small d-block">{{ \Illuminate\Support\Carbon::parse($row->prospect_rejected_at)->format('d/m/Y H:i') }}</span>
                                    @endif
                                    @if($row->conformite_avis_at)
                                        <span class="small text-body-secondary d-block mt-1">Avis conformité le {{ \Illuminate\Support\Carbon::parse($row->conformite_avis_at)->format('d/m/Y H:i') }}</span>
                                    @else
                                        <span class="small text-warning d-block mt-1">Sans avis conformité saisi</span>
                                    @endif
                                @elseif($role === 'juridique')
                                    @if($row->juridique_avis_at)
                                        <span class="badge bg-success">Avis rendu — modifiable</span>
                                    @else
                                        <span class="badge bg-warning text-dark">En attente de votre avis</span>
                                    @endif
                                @else
                                    @if($row->conformite_avis_at)
                                        <span class="badge bg-success">Avis rendu — modifiable</span>
                                    @else
                                        <span class="badge bg-warning text-dark">En attente de votre avis</span>
                                    @endif
                                @endif
                            </td>
                            <td class="text-end">
                                @if($role === 'juridique')
                                    <a href="{{ route('juridique.prospects.show', $row->token) }}" class="btn btn-sm btn-primary">Ouvrir</a>
                                @else
                                    <a href="{{ route('conformite.prospects.show', $row->token) }}" class="btn btn-sm btn-primary">Ouvrir</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-body-secondary py-4">@if($listMode === 'treated' && $role === 'conformite')Aucun dossier clos pour le moment.@else Aucun dossier dans le circuit (ou tous clos par le chef d'agence).@endif</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
