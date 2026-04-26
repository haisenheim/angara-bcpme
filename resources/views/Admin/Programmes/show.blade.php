@extends('Layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('jquery-easyui/themes/default/easyui.css') }}">
<link rel="stylesheet" href="{{ asset('jquery-easyui/themes/icon.css') }}">
<link rel="stylesheet" href="{{ asset('css/programme-fiche.css') }}">
@endpush

@section('title', $item->name)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.programmes.index') }}">Programmes</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 48) }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown menu-class="dropdown-menu dropdown-menu-end border shadow-sm py-2">
        <li><a class="dropdown-item" data-bs-target="#addModal" data-bs-toggle="modal" href="#">Ajouter une composante</a></li>
        <li><a class="dropdown-item" data-bs-target="#addIndModal" data-bs-toggle="modal" href="#">Ajouter un objectif</a></li>
        <li><a class="dropdown-item" data-bs-target="#addAppuiModal" data-bs-toggle="modal" href="#">Ajouter un appui</a></li>
        <li><a class="dropdown-item" data-bs-target="#addProdModal" data-bs-toggle="modal" href="#">Ajouter un secteur</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('admin.programmes.edit', $item->token) }}">Modifier le programme</a></li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
    <p class="text-body-secondary mb-0 mt-1 small">Fiche programme, périmètre opérationnel et dossiers d'instruction.</p>
</div>
@endsection

@section('content')
@php
    $fmtMoney = fn ($v) => $v !== null && $v !== '' ? number_format((float) $v, 0, ',', '.').' XAF' : '—';
    $dossiersCount = $item->dossiers->count();
@endphp

<div class="programme-fiche-hero">
    <div class="row g-3 align-items-center">
        <div class="col-lg-8">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Programme</span>
                @if(isset($item->active))
                    <span class="badge rounded-pill {{ $item->active ? 'text-bg-success' : 'text-bg-secondary' }}">
                        {{ $item->active ? 'Actif' : 'Inactif' }}
                    </span>
                @endif
            </div>
            <p class="mb-0 text-muted small">
                <span class="fw-semibold text-dark">Convention :</span> {{ $item->convention ?? '—' }}
                <span class="mx-2">·</span>
                <span class="fw-semibold text-dark">Signataire :</span> {{ $item->signataire ?? '—' }}
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('admin.programmes.index') }}" class="btn btn-sm btn-outline-secondary">← Liste des programmes</a>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="programme-fiche-kpi">
            <div class="programme-fiche-kpi__label">Budget total</div>
            <div class="programme-fiche-kpi__val text-primary">{{ $fmtMoney($item->budget ?? null) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="programme-fiche-kpi">
            <div class="programme-fiche-kpi__label">Dossiers d'instruction</div>
            <div class="programme-fiche-kpi__val">{{ $dossiersCount }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="programme-fiche-kpi">
            <div class="programme-fiche-kpi__label">Entreprises liées</div>
            <div class="programme-fiche-kpi__val">{{ $item->entreprises->count() }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="programme-fiche-kpi">
            <div class="programme-fiche-kpi__label">Secteurs (produits)</div>
            <div class="programme-fiche-kpi__val">{{ $item->produits->count() }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="programme-fiche-info-card mb-4">
            <div class="px-3 py-2 border-bottom bg-light bg-opacity-50">
                <span class="fw-bold text-dark small text-uppercase">Données contractuelles</span>
            </div>
            <table class="table table-sm align-middle">
                <tbody>
                    <tr><td>Désignation</td><th class="text-dark">{{ $item->name }}</th></tr>
                    <tr><td>Réf. convention</td><th>{{ $item->convention ?? '—' }}</th></tr>
                    <tr><td>Date signature</td><th>@if($item->dt_sig_conv){{ \Carbon\Carbon::parse($item->dt_sig_conv)->format('d/m/Y') }}@else — @endif</th></tr>
                    <tr><td>Institution signataire</td><th>{{ $item->signataire ?? '—' }}</th></tr>
                    <tr><td>Budget AF</td><th>{{ $fmtMoney($item->budget_af ?? null) }}</th></tr>
                    <tr><td>Budget ANF</td><th>{{ $fmtMoney($item->budget_anf ?? null) }}</th></tr>
                    <tr><td>Coordination</td><th>{{ $fmtMoney($item->budget_coord ?? null) }}</th></tr>
                    <tr><td>Bénéficiaires PM</td><th>{{ $item->type_pm ?? '—' }}</th></tr>
                    <tr><td>Bénéficiaires PP</td><th>{{ $item->type_pp ?? '—' }}</th></tr>
                    <tr><td>Début activités</td><th>@if($item->dt_start){{ \Carbon\Carbon::parse($item->dt_start)->format('d/m/Y') }}@else — @endif</th></tr>
                    <tr><td>Contact</td><th>{{ $item->contact ?? '—' }}</th></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="programme-fiche-main-card">
            <div class="tab-base programme-fiche-tabs">
                <ul class="nav nav-underline nav-component border-bottom flex-nowrap overflow-auto px-2 pt-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2 active" data-bs-toggle="tab" data-bs-target="#tab_dossiers" type="button" role="tab">Dossiers d'instruction</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2" data-bs-toggle="tab" data-bs-target="#tab_secteurs" type="button" role="tab">Secteurs cibles</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2" data-bs-toggle="tab" data-bs-target="#tab_appuis" type="button" role="tab">Appuis</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2" data-bs-toggle="tab" data-bs-target="#tab_comp" type="button" role="tab">Composantes</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2" data-bs-toggle="tab" data-bs-target="#tab_res" type="button" role="tab">Résultats</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2" data-bs-toggle="tab" data-bs-target="#tab_ent" type="button" role="tab">Entreprises</button>
                    </li>
                </ul>

                <div class="tab-content p-3 p-md-4">
                    <div id="tab_dossiers" class="tab-pane fade show active" role="tabpanel">
                        <p class="text-muted small mb-3">Dossiers d'instruction rattachés à ce programme (les plus récents en premier).</p>
                        <div class="programme-fiche-table-wrap">
                            <table class="table table-hover align-middle mb-0 programme-fiche-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Entreprise</th>
                                        <th>Agence</th>
                                        <th>Gestionnaire</th>
                                        <th>Analyste</th>
                                        <th>Statut</th>
                                        <th>Créé le</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->dossiers as $dossier)
                                        @php $st = $dossier->status; @endphp
                                        <tr>
                                            <td class="fw-semibold">{{ $dossier->entreprise?->name ?? '—' }}</td>
                                            <td>{{ $dossier->agence?->name ?? '—' }}</td>
                                            <td>{{ $dossier->gestionnaire?->name ?? '—' }}</td>
                                            <td>{{ $dossier->analyste?->name ?? '—' }}</td>
                                            <td><span class="badge rounded-pill bg-secondary bg-opacity-75">{{ $st['name'] ?? '—' }}</span></td>
                                            <td class="text-nowrap">{{ optional($dossier->created_at)->format('d/m/Y H:i') ?? '—' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.dossiers.show', $dossier->token) }}" class="btn btn-sm btn-primary">Ouvrir</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="programme-fiche-empty border-0">Aucun dossier d'instruction pour ce programme.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="tab_secteurs" class="tab-pane fade" role="tabpanel">
                        <div class="programme-fiche-table-wrap">
                            <table class="table table-hover table-sm mb-0 programme-fiche-table">
                                <thead class="table-light">
                                    <tr><th>Produit</th><th>Filière</th><th>Branche</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->produits as $p)
                                        <tr>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->filiere?->name ?? '—' }}</td>
                                            <td>{{ $p->branche?->name ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="programme-fiche-empty border-0">Aucun secteur cible.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="tab_appuis" class="tab-pane fade" role="tabpanel">
                        <div class="programme-fiche-table-wrap">
                            <table class="table table-hover table-sm mb-0 programme-fiche-table">
                                <thead class="table-light">
                                    <tr><th>Service</th><th>Type</th><th>Nature</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->appuis as $service)
                                        <tr>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ $service->type?->name ?? '—' }}</td>
                                            <td><span class="badge bg-{{ $service->financier ? 'success' : 'info' }} bg-opacity-10 text-{{ $service->financier ? 'success' : 'info' }}">{{ $service->financier ? 'Financier' : 'Non financier' }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="programme-fiche-empty border-0">Aucun appui paramétré.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="tab_comp" class="tab-pane fade" role="tabpanel">
                        <div class="programme-fiche-table-wrap">
                            <table class="table table-hover table-sm mb-0 programme-fiche-table">
                                <thead class="table-light">
                                    <tr><th>Entité</th><th>Nature</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->composantes as $cmp)
                                        <tr>
                                            <td>{{ $cmp->name }}</td>
                                            <td>{{ $cmp->type ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="programme-fiche-empty border-0">Aucune composante.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="tab_res" class="tab-pane fade" role="tabpanel">
                        <div class="programme-fiche-table-wrap">
                            <table class="table table-hover table-sm mb-0 programme-fiche-table">
                                <thead class="table-light">
                                    <tr><th>Indicateur</th><th>Attentes</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->resultats as $row)
                                        <tr>
                                            <td>{{ $row->indicateur?->name ?? '—' }}</td>
                                            <td>{{ $row->attente ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="programme-fiche-empty border-0">Aucun résultat attendu.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="tab_ent" class="tab-pane fade" role="tabpanel">
                        <div class="programme-fiche-table-wrap">
                            <table class="table table-hover table-sm mb-0 programme-fiche-table">
                                <thead class="table-light">
                                    <tr><th>Entreprise</th><th>Localité</th><th>Taille</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($item->entreprises as $ent)
                                        <tr>
                                            <td>{{ $ent->name }}</td>
                                            <td>{{ $ent->localite ?? '—' }}</td>
                                            <td>{{ $ent->taille ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="programme-fiche-empty border-0">Aucune entreprise liée.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('Admin.Programmes.partials.show_modals')
@endsection

@section('scripts')
<script src="{{ asset('jquery-easyui/jquery.easyui.min.js') }}"></script>
<script>
(function ($) {
    $(function () {
        $('#banque_id').on('change', function () {
            $('#comp_name').val($('#banque_id option:selected').text());
            $('#organisme_id').prop('disabled', true);
        });
        $('#organisme_id').on('change', function () {
            $('#comp_name').val($('#organisme_id option:selected').text());
            $('#banque_id').prop('disabled', true);
        });

        var prodModal = document.getElementById('addProdModal');
        if (prodModal && typeof $.fn.parser !== 'undefined') {
            prodModal.addEventListener('shown.bs.modal', function () {
                try {
                    $.parser.parse($('.programme-fiche-combotree-wrap'));
                } catch (e) {}
            });
        }
    });
})(jQuery);
</script>
@endsection
