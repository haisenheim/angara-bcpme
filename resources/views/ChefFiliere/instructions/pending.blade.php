@extends('Layouts.chef_filiere')

@section('title', 'Dossiers d\'instruction — en attente')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active" aria-current="page">Instruction — en attente</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">Dossiers d'instruction</h5>
    <p class="text-body-secondary mb-0 mt-1 small">En attente de validation du chef d'agence pour création des dossiers.</p>
</div>
@endsection

@section('content')
<div class="cf-page">
    <div class="cf-hero mb-4">
        <h1 class="cf-hero__title">En attente de validation</h1>
        <p class="cf-hero__lead">Dossiers soumis après qualification — le chef d'agence valide avant instruction.</p>
        <div class="cf-hero__meta">
            <span class="cf-kpi"><span class="text-muted fw-normal">Dossiers</span> <span class="cf-kpi__val">{{ $items->count() }}</span></span>
        </div>
    </div>

    <div class="cf-panel">
        <div class="cf-panel__toolbar">
            <p class="cf-panel__toolbar-label mb-0">Liste</p>
        </div>
        <div class="cf-table-wrap">
            <table class="table cf-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th scope="col">Client</th>
                        <th scope="col">Agence</th>
                        <th scope="col">Soumis le</th>
                        <th scope="col">Programmes</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $eer)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $eer->entreprise?->name ?? '—' }}</td>
                            <td>{{ $eer->entreprise?->agence?->name ?? '—' }}</td>
                            <td><span class="text-nowrap">{{ optional($eer->programmes_submitted_at)->format('d/m/Y H:i') ?? '—' }}</span></td>
                            <td class="small">{{ $eer->programmeSelections->pluck('programme.name')->filter()->implode(', ') ?: '—' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('chef-filiere.clients.show', $eer->entreprise?->token) }}" class="btn btn-sm btn-primary">Consulter</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="cf-empty border-0">
                                <i class="cf-empty__icon pli-folder" aria-hidden="true"></i>
                                Aucun dossier en attente de validation.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
