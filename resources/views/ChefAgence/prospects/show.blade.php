@extends('Layouts.ca')

@section('title', 'Décision chef d\'agence')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">{{ $item->name }}</h1>
        <p class="text-muted mb-0">Synthèse avant validation du passage prospect vers client.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif
        <div class="card shadow-sm">
            <div class="card-body">
                <p><strong>Agence :</strong> {{ $item->agence?->name ?? '-' }}</p>
                <p><strong>Soumis le :</strong> {{ optional($item->prospect_submitted_at)->format('d/m/Y H:i') ?? '-' }}</p>
                <p><strong>Avis juridique :</strong></p>
                <div class="mb-3">{!! $item->juridique_avis ?: '<span class="text-muted">Aucun avis</span>' !!}</div>
                <p><strong>Avis conformité :</strong></p>
                <div class="mb-3">{!! $item->conformite_avis ?: '<span class="text-muted">Aucun avis</span>' !!}</div>
                <div class="mt-3">
                    @if($item->juridique_avis_at && $item->conformite_avis_at)
                        <form method="post" action="{{ route('chef-agence.prospects.approve', $item->token) }}" onsubmit="return confirm('Valider ce prospect comme client ?');">
                            @csrf
                            <button type="submit" class="btn btn-success">Valider et promouvoir en client</button>
                        </form>
                    @else
                        <span class="badge bg-warning text-dark">Validation impossible tant que les deux avis ne sont pas rendus.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
