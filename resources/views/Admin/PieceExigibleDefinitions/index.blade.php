@extends('Layouts.admin')

@section('title', 'Pieces exigibles')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pieces exigibles</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0 mt-2">Pieces exigibles</h5>
    <p class="lead">Parametrage de la checklist documentaire du dossier d'entree en relation.</p>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <strong>{{ $editItem ? 'Modifier une piece' : 'Nouvelle piece exigible' }}</strong>
            </div>
            <div class="card-body">
                <form method="post" action="{{ $editItem ? route('admin.pieces-exigibles.update', $editItem) : route('admin.pieces-exigibles.store') }}">
                    @csrf
                    @if($editItem)
                        @method('PUT')
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Libelle</label>
                        <input type="text" name="label" class="form-control" value="{{ old('label', $editItem->label ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $editItem->description ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ordre</label>
                        <input type="number" name="sort_order" min="0" class="form-control" value="{{ old('sort_order', $editItem->sort_order ?? 0) }}">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', $editItem?->active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ $editItem ? 'Mettre a jour' : 'Ajouter' }}</button>
                        @if($editItem)
                            <a href="{{ route('admin.pieces-exigibles.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <strong>Liste parametree</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Ordre</th>
                            <th>Libelle</th>
                            <th>Etat</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->sort_order }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->label }}</div>
                                    <small class="text-muted">{{ $item->description }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->active ? 'success' : 'secondary' }}">{{ $item->active ? 'Active' : 'Inactive' }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.pieces-exigibles.edit', $item) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Aucune piece parametree.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
