@extends('Layouts.chef_filiere')

@section('title', $item->name)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.programmes.index') }}">Programmes</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 40) }}</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">{{ $item->name }}</h5>
        <p class="text-body-secondary mb-0 mt-1 small">Fiche signalétique — budgets, bénéficiaires et contenu opérationnel.</p>
    </div>
@endsection

@section('content')
<div class="cf-page">
    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="card cf-client-card h-100">
                <div class="card-header py-3 border-0">
                    <h6 class="mb-0 fw-semibold text-body">
                        <i class="demo-psi-file-edit me-2 text-primary"></i>Informations générales
                    </h6>
                </div>
                <div class="card-body">
                    <dl class="row g-3 mb-0 programme-info-list">
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Désignation</dt>
                            <dd class="mb-0 fw-medium">{{ $item->name }}</dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">N° Référence convention cadre</dt>
                            <dd class="mb-0">{{ $item->convention ?? '—' }}</dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Date de signature</dt>
                            <dd class="mb-0">{{ $item->dt_sig_conv ? \Carbon\Carbon::parse($item->dt_sig_conv)->format('d/m/Y') : '—' }}</dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Institution signataire</dt>
                            <dd class="mb-0">{{ $item->signataire ?? '—' }}</dd>
                        </div>
                        <div class="col-12 pt-2 border-top">
                            <dt class="text-muted small text-uppercase mb-2">Budgets</dt>
                            <dd class="mb-0">
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex justify-content-between"><span class="text-muted">Appuis financiers</span><span class="fw-medium">{{ number_format($item->budget_af ?? 0, 0, ',', '.') }} XAF</span></div>
                                    <div class="d-flex justify-content-between"><span class="text-muted">Appuis non financiers</span><span class="fw-medium">{{ number_format($item->budget_anf ?? 0, 0, ',', '.') }} XAF</span></div>
                                    <div class="d-flex justify-content-between"><span class="text-muted">Coordination</span><span class="fw-medium">{{ number_format($item->budget_coord ?? 0, 0, ',', '.') }} XAF</span></div>
                                    <div class="d-flex justify-content-between pt-2 border-top"><span class="fw-semibold">Total</span><span class="fw-bold text-primary">{{ number_format($item->budget ?? 0, 0, ',', '.') }} XAF</span></div>
                                </div>
                            </dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Bénéficiaires cibles PM</dt>
                            <dd class="mb-0">{{ $item->type_pm ?? '—' }}</dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Bénéficiaires cibles PP</dt>
                            <dd class="mb-0">{{ $item->type_pp ?? '—' }}</dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Début des activités</dt>
                            <dd class="mb-0">{{ $item->dt_start ? \Carbon\Carbon::parse($item->dt_start)->format('d/m/Y') : '—' }}</dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Contact</dt>
                            <dd class="mb-0">{{ $item->contact ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card cf-client-card">
                <div class="card-body p-0">
                    <ul class="nav nav-underline nav-component border-bottom px-3 pt-2 bg-light bg-opacity-50" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab1" type="button" role="tab">Secteurs cibles</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab2" type="button" role="tab">Appuis proposés</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab3" type="button" role="tab">Composantes</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab4" type="button" role="tab">Résultats attendus</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab5" type="button" role="tab">Entreprises</button></li>
                    </ul>
                    <div class="tab-content p-4">
                        <div id="_tab1" class="tab-pane fade active show">
                            <div class="table-responsive">
                                <table class="table cf-table mb-0">
                                    <thead>
                                        <tr><th>Produit</th><th>Filière</th><th>Branche</th></tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->produits as $p)
                                            <tr><td>{{ $p->name }}</td><td>{{ $p->filiere?->name ?? '—' }}</td><td>{{ $p->branche?->name ?? '—' }}</td></tr>
                                        @empty
                                            <tr><td colspan="3" class="cf-empty border-0">Aucun secteur cible</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="_tab2" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table cf-table mb-0">
                                    <thead>
                                        <tr><th>Service</th><th>Type</th><th>Nature</th></tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->appuis as $service)
                                            <tr><td>{{ $service->name }}</td><td>{{ $service->type?->name ?? '—' }}</td><td><span class="badge bg-{{ $service->financier ? 'success' : 'info' }} bg-opacity-10 text-{{ $service->financier ? 'success' : 'info' }}">{{ $service->financier ? 'Financier' : 'Non financier' }}</span></td></tr>
                                        @empty
                                            <tr><td colspan="3" class="cf-empty border-0">Aucun appui proposé</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="_tab3" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table cf-table mb-0">
                                    <thead>
                                        <tr><th>Entité</th><th>Nature</th></tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->composantes as $cmp)
                                            <tr><td>{{ $cmp->name }}</td><td>{{ $cmp->type ?? '—' }}</td></tr>
                                        @empty
                                            <tr><td colspan="2" class="cf-empty border-0">Aucune composante</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="_tab4" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table cf-table mb-0">
                                    <thead>
                                        <tr><th>Indicateur</th><th>Attentes</th></tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->resultats as $r)
                                            <tr><td>{{ $r->indicateur?->name ?? '—' }}</td><td>{{ $r->attente ?? '—' }}</td></tr>
                                        @empty
                                            <tr><td colspan="2" class="cf-empty border-0">Aucun résultat attendu</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="_tab5" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table cf-table mb-0">
                                    <thead>
                                        <tr><th>Entreprise</th><th>Localité</th><th>Taille</th></tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->entreprises as $ent)
                                            <tr><td>{{ $ent->name }}</td><td>{{ $ent->localite ?? '—' }}</td><td>{{ $ent->taille ?? '—' }}</td></tr>
                                        @empty
                                            <tr><td colspan="3" class="cf-empty border-0">Aucune entreprise</td></tr>
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
</div>
@endsection
