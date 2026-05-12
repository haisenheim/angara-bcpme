@extends($workspaceLayout ?? 'Layouts.app')

@section('title', 'Modèles de documents')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item active" aria-current="page">Modèles de documents</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Modèles de documents</h5>
        <p class="text-body-secondary mb-0 mt-1">Fichiers mis à disposition par l’administration — téléchargement réservé aux utilisateurs connectés.</p>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($templates->isEmpty())
                <p class="text-muted mb-0">Aucun modèle n’est encore disponible.</p>
            @else
                <div class="list-group list-group-flush">
                    @foreach($templates as $t)
                        <div class="list-group-item px-0 py-3 d-flex flex-column flex-md-row gap-3 justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold">{{ $t->title }}</div>
                                @if($t->description)
                                    <div class="small text-muted mt-1">{{ $t->description }}</div>
                                @endif
                                <div class="small text-muted mt-1">
                                    <span class="text-body-secondary">{{ $t->original_filename }}</span>
                                    @if($t->size_bytes)
                                        ·
                                        @if($t->size_bytes >= 1048576)
                                            {{ number_format($t->size_bytes / 1048576, 1, ',', ' ') }} Mo
                                        @elseif($t->size_bytes >= 1024)
                                            {{ number_format($t->size_bytes / 1024, 1, ',', ' ') }} Ko
                                        @else
                                            {{ $t->size_bytes }} o
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('document-templates.download', $t) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-download me-1"></i>Télécharger
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
