@extends('Layouts.chef_filiere')

@section('title', 'Espace chef de filière')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">Tableau de bord</h5>
    <p class="text-body-secondary mb-0 mt-1 small">Pilotage de la structuration et des dossiers d'instruction de votre agence.</p>
</div>
@endsection

@section('content')
<div class="cf-page">
    <div class="cf-hero mb-4">
        <h1 class="cf-hero__title">Vue d'ensemble</h1>
        <p class="cf-hero__lead">Accédez rapidement aux files d'attente et au portefeuille clients.</p>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric" id="cf-m-pending-qualif"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    <p class="cf-dash-card__label">Structurations en attente</p>
                    <a href="{{ route('chef-filiere.qualifications.index') }}" class="btn btn-primary btn-sm align-self-start">Ouvrir la file</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric" id="cf-m-clients"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    <p class="cf-dash-card__label">Clients (agence)</p>
                    <a href="{{ route('chef-filiere.clients.index') }}" class="btn btn-outline-primary btn-sm align-self-start">Voir la liste</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric" id="cf-m-instruction-pending"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    <p class="cf-dash-card__label">Instruction — en attente (chef d'agence)</p>
                    <a href="{{ route('chef-filiere.instructions.pending') }}" class="btn btn-outline-primary btn-sm align-self-start">Consulter</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric" id="cf-m-instruction-encours"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    <p class="cf-dash-card__label">Dossiers d'instruction en cours</p>
                    <a href="{{ route('chef-filiere.instructions.in-progress') }}" class="btn btn-outline-primary btn-sm align-self-start">Consulter</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-lg-4 mt-1">
        <div class="col-xl-4">
            <div class="cf-panel h-100">
                <div class="cf-panel__toolbar">
                    <p class="cf-panel__toolbar-label mb-0">Structuration clients</p>
                </div>
                <div class="p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Structuré</span>
                        <span class="fw-semibold" id="cf-s-structure"><span class="spinner-border spinner-border-sm" role="status"></span></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">En cours</span>
                        <span class="fw-semibold" id="cf-s-encours"><span class="spinner-border spinner-border-sm" role="status"></span></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">En attente</span>
                        <span class="fw-semibold" id="cf-s-attente"><span class="spinner-border spinner-border-sm" role="status"></span></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Rejetée</span>
                        <span class="fw-semibold" id="cf-s-rejetee"><span class="spinner-border spinner-border-sm" role="status"></span></span>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('chef-filiere.qualifications.index') }}" class="btn btn-sm btn-outline-primary">Ouvrir la structuration</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="cf-panel h-100">
                <div class="cf-panel__toolbar">
                    <p class="cf-panel__toolbar-label mb-0">À structurer (priorité)</p>
                </div>
                <div class="p-3 p-md-4">
                    <div id="cf-list-to-structure" class="list-group list-group-flush">
                        <div class="text-center py-3">
                            <span class="spinner-border spinner-border-sm" role="status"></span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('chef-filiere.clients.index') }}" class="btn btn-sm btn-primary">Voir les clients</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="cf-panel h-100">
                <div class="cf-panel__toolbar">
                    <p class="cf-panel__toolbar-label mb-0">Rejets agence à corriger</p>
                </div>
                <div class="p-3 p-md-4">
                    <div id="cf-list-rejected" class="list-group list-group-flush">
                        <div class="text-center py-3">
                            <span class="spinner-border spinner-border-sm" role="status"></span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('chef-filiere.qualifications.index') }}" class="btn btn-sm btn-outline-primary">Ouvrir la file</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 cf-panel">
        <div class="cf-panel__toolbar">
            <p class="cf-panel__toolbar-label mb-0">Documentation</p>
        </div>
        <div class="p-3 p-md-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h2 class="h6 mb-1 fw-semibold">Référentiel programmes</h2>
                <p class="text-muted small mb-0">Consultez les fiches signalétiques et critères d'éligibilité.</p>
            </div>
            <a href="{{ route('chef-filiere.programmes.index') }}" class="btn btn-sm btn-primary">Ouvrir les programmes</a>
        </div>
    </div>
</div>
<script src="{{ asset('js/simple-dashboard-stats.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        AngaraLoadDashboardStats(@json(route('chef-filiere.dashboard.stats')), {
            pending_qualif: 'cf-m-pending-qualif',
            clients_count: 'cf-m-clients',
            instruction_pending: 'cf-m-instruction-pending',
            instruction_en_cours: 'cf-m-instruction-encours',
        });

        fetch(@json(route('chef-filiere.dashboard.insights')), {
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                const s = (data && data.structuration) || {};
                const setText = function (id, v) {
                    const el = document.getElementById(id);
                    if (el) el.textContent = (v === undefined || v === null) ? '—' : String(v);
                };
                setText('cf-s-structure', s.structure);
                setText('cf-s-encours', s.en_cours);
                setText('cf-s-attente', s.attente);
                setText('cf-s-rejetee', s.rejetee);

                const renderList = function (containerId, rows, emptyText) {
                    const el = document.getElementById(containerId);
                    if (!el) return;
                    el.innerHTML = '';
                    if (!rows || !rows.length) {
                        el.innerHTML = '<div class="text-center py-2 text-muted small">' + emptyText + '</div>';
                        return;
                    }
                    rows.forEach(function (row) {
                        const name = row.name || '—';
                        const token = row.token;
                        const href = token ? @json(url('/chef-filiere/qualifications')) + '/' + token : '#';
                        el.innerHTML +=
                            '<a class="list-group-item list-group-item-action px-0" href="' + href + '">' +
                            '<div class="d-flex justify-content-between align-items-start gap-2">' +
                            '<div class="flex-grow-1">' +
                            '<div class="fw-semibold small">' + name + '</div>' +
                            '</div>' +
                            '<div class="text-muted small">Ouvrir</div>' +
                            '</div>' +
                            '</a>';
                    });
                };

                renderList('cf-list-to-structure', data.to_structure, 'Aucun client à structurer.');
                renderList('cf-list-rejected', data.rejected_to_fix, 'Aucun rejet à corriger.');
            })
            .catch(function () {
                const set = function (id) {
                    const el = document.getElementById(id);
                    if (el) el.textContent = '—';
                };
                set('cf-s-structure');
                set('cf-s-encours');
                set('cf-s-attente');
                set('cf-s-rejetee');
                const e1 = document.getElementById('cf-list-to-structure');
                if (e1) e1.innerHTML = '<div class="text-center py-2 text-muted small">—</div>';
                const e2 = document.getElementById('cf-list-rejected');
                if (e2) e2.innerHTML = '<div class="text-center py-2 text-muted small">—</div>';
            });
    });
</script>
@endsection
