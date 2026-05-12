@extends('Layouts.admin')

@section('title', 'Ajouter un modèle de document')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Administration</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.document-templates.index') }}">Modèles de documents</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ajouter</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouveau modèle</h5>
        <p class="lead mb-0">Le fichier sera stocké sur le serveur et proposé au téléchargement à tous les profils.</p>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm border-0" style="max-width: 42rem;">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.document-templates.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="dt-title">Titre affiché</label>
                    <input type="text" name="title" id="dt-title" class="form-control" value="{{ old('title') }}" required maxlength="255">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="dt-desc">Description (optionnel)</label>
                    <textarea name="description" id="dt-desc" class="form-control" rows="3" maxlength="10000">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="dt-order">Ordre d’affichage</label>
                    <input type="number" name="sort_order" id="dt-order" class="form-control" value="{{ old('sort_order', 0) }}" min="0" max="999999">
                    <div class="form-text">Les valeurs les plus élevées apparaissent en premier.</div>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="dt-file">Fichier</label>
                    <input type="file" name="file" id="dt-file" class="form-control" required accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt,.png,.jpg,.jpeg,.odt,.ods">
                    <div class="form-text">PDF, Word, Excel, CSV, texte, images, OpenDocument — max. 15 Mo.</div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('admin.document-templates.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
