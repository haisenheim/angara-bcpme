@extends(match ($space['route'] ?? '') {
    'respexp' => 'Layouts.respexp',
    'juridique' => 'Layouts.juridique',
    'analyste-juridique' => 'Layouts.analyste-juridique',
    'reng' => 'Layouts.reng',
    'analyste-credit' => 'Layouts.analyste-credit',
    'analyste-risques' => 'Layouts.analyste-risques',
    'rerx' => 'Layouts.rerx',
    'dg' => 'Layouts.dg',
    'dga' => 'Layouts.dga',
    default => 'Layouts.app',
})

@push('styles')
    @if(in_array($space['route'] ?? '', ['dg', 'dga'], true))
        <link rel="stylesheet" href="{{ asset('css/chef-filiere.css') }}">
    @endif
@endpush

@section('title', $space['title'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Espace {{ $space['title'] }}</h5>
        <p class="text-muted mb-0">
            @if($space['route'] === 'respexp')
                Dossiers sans analyste à affecter (périmètre banque) à un utilisateur du profil instruction (id 17), puis instruction par l’analyste.
            @elseif($space['route'] === 'reng')
                Dossiers transmis au pôle engagements après le pôle juridique — affectation analyste crédit et transmission risques.
            @elseif($space['route'] === 'analyste-credit')
                Contre-analyse et avis sur les dossiers du pôle engagements qui vous sont affectés.
            @elseif($space['route'] === 'rerx')
                Dossiers reçus du responsable engagements pour le pôle risques — affectation analyste risques et transmission direction.
            @elseif($space['route'] === 'analyste-risques')
                Dossiers du pôle risques qui vous sont affectés en tant qu’analyste risques.
            @elseif(in_array($space['route'] ?? '', ['dg', 'dga'], true))
                Vue d’ensemble nationale : portefeuille, structuration des clients, dossiers d’instruction et priorités pour l’avis direction.
            @else
                Consultation transverse des clients, dossiers et pièces du portefeuille.
            @endif
        </p>
    </div>
@endsection

@section('content')
    @if(in_array($space['route'] ?? '', ['dg', 'dga'], true))
        @include('RoleSpace.partials.dashboard-dg-dga', ['space' => $space])
    @else
    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Entreprises</small>
                        <div class="fs-4 fw-semibold" id="rs-stat-entreprises"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Dossiers</small>
                        <div class="fs-4 fw-semibold" id="rs-stat-dossiers"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
            @if(($space['route'] ?? '') === 'respexp')
            <div class="col-md-4">
                <div class="card shadow-sm border-0 border-warning">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">À affecter (sans analyste)</small>
                        <div class="fs-4 fw-semibold text-warning-emphasis" id="rs-stat-affecter"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        <a href="{{ route('respexp.dossiers.index') }}" class="small">Voir la liste</a>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Pièces fournies</small>
                        <div class="fs-4 fw-semibold" id="rs-stat-pieces"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
        </div>

        @if(in_array($space['route'] ?? '', ['respexp', 'reng', 'rerx'], true))
        <div class="row g-3 mb-4">
            @if(($space['route'] ?? '') === 'respexp')
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase d-block mb-1">En instruction (analystes)</small>
                            <div class="fs-5 fw-semibold" id="rs-stat-exp-en-instruction"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase d-block mb-1">À valider (réception)</small>
                            <div class="fs-5 fw-semibold" id="rs-stat-exp-a-valider"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase d-block mb-1">Validation engagements</small>
                            <div class="fs-5 fw-semibold" id="rs-stat-exp-valid-eng"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase d-block mb-1">À soumettre juridique</small>
                            <div class="fs-5 fw-semibold" id="rs-stat-exp-a-soumettre-jur"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        </div>
                    </div>
                </div>
            @elseif(($space['route'] ?? '') === 'reng')
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase d-block mb-1">À affecter analyste crédit</small>
                            <div class="fs-5 fw-semibold" id="rs-stat-reng-a-affecter"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase d-block mb-1">Analyste crédit en cours</small>
                            <div class="fs-5 fw-semibold" id="rs-stat-reng-en-cours"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase d-block mb-1">À valider (avis)</small>
                            <div class="fs-5 fw-semibold" id="rs-stat-reng-a-valider"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase d-block mb-1">À soumettre risques</small>
                            <div class="fs-5 fw-semibold" id="rs-stat-reng-a-soumettre"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        </div>
                    </div>
                </div>
            @elseif(($space['route'] ?? '') === 'rerx')
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase d-block mb-1">À affecter analyste risques</small>
                            <div class="fs-5 fw-semibold" id="rs-stat-rerx-a-affecter"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase d-block mb-1">Analyste risques en cours</small>
                            <div class="fs-5 fw-semibold" id="rs-stat-rerx-en-cours"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase d-block mb-1">À valider (avis)</small>
                            <div class="fs-5 fw-semibold" id="rs-stat-rerx-a-valider"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase d-block mb-1">À soumettre direction</small>
                            <div class="fs-5 fw-semibold" id="rs-stat-rerx-a-soumettre"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
                <h6 class="mb-0">À traiter maintenant</h6>
                <a class="btn btn-sm btn-outline-primary" href="{{ route($space['route'].'.dossiers.index') }}">Voir tout</a>
            </div>
            <div class="card-body">
                <div id="rs-todos" class="list-group list-group-flush">
                    <div class="text-center py-3">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-wrap gap-2">
                <a href="{{ route($space['route'].'.entreprises.index') }}" class="btn btn-primary">Voir les entreprises</a>
                @php
                    $dashDossiersRoute = in_array($space['route'] ?? '', ['dg', 'dga'], true)
                        ? $space['route'].'.dossiers.valides-chef-agence'
                        : $space['route'].'.dossiers.index';
                @endphp
                <a href="{{ route($dashDossiersRoute) }}" class="btn btn-outline-primary">Voir les dossiers</a>
                @if(in_array($space['route'] ?? '', ['dg', 'dga'], true))
                    <a href="{{ route($space['route'].'.dossiers.en-attente-direction') }}" class="btn btn-outline-warning">Dossiers en attente avis direction</a>
                @endif
            </div>
        </div>
    </div>
    <script src="{{ asset('js/simple-dashboard-stats.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const map = {
                entreprises: 'rs-stat-entreprises',
                dossiers: 'rs-stat-dossiers',
                pieces: 'rs-stat-pieces',
            };
            @if(($space['route'] ?? '') === 'respexp')
            map.dossiers_a_affecter = 'rs-stat-affecter';
            map.dossiers_en_instruction = 'rs-stat-exp-en-instruction';
            map.dossiers_a_valider = 'rs-stat-exp-a-valider';
            map.validation_engagements_a_faire = 'rs-stat-exp-valid-eng';
            map.a_soumettre_juridique = 'rs-stat-exp-a-soumettre-jur';
            @endif
            @if(($space['route'] ?? '') === 'reng')
            map.a_affecter_analyste_credit = 'rs-stat-reng-a-affecter';
            map.analyste_credit_en_cours = 'rs-stat-reng-en-cours';
            map.a_valider_avis_engagements = 'rs-stat-reng-a-valider';
            map.a_soumettre_risques = 'rs-stat-reng-a-soumettre';
            @endif
            @if(($space['route'] ?? '') === 'rerx')
            map.a_affecter_analyste_risques = 'rs-stat-rerx-a-affecter';
            map.analyste_risques_en_cours = 'rs-stat-rerx-en-cours';
            map.a_valider_avis_risques = 'rs-stat-rerx-a-valider';
            map.a_soumettre_direction = 'rs-stat-rerx-a-soumettre';
            @endif
            AngaraLoadDashboardStats(@json(route($space['route'].'.dashboard.stats')), map);

            @if(in_array($space['route'] ?? '', ['respexp', 'reng', 'rerx'], true))
            fetch(@json(route($space['route'].'.dashboard.todos')), {
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    const container = document.getElementById('rs-todos');
                    if (!container) return;
                    const rows = (data && data.todos) || [];
                    container.innerHTML = '';
                    if (!rows.length) {
                        container.innerHTML = '<div class="text-center py-2 text-muted small">Aucun élément.</div>';
                        return;
                    }
                    rows.forEach(function (row) {
                        const href = row.token ? @json(url('')) + '/{{ $space['route'] }}/dossiers/' + row.token : '#';
                        const entreprise = row.entreprise || '—';
                        const programmes = row.programmes || '—';
                        const when = row.when_human ? row.when_human : '—';
                        const kind = row.kind || null;
                        const badge = kind && kind.label
                            ? '<span class="badge bg-' + (kind.variant || 'secondary') + ' me-2">' + kind.label + '</span>'
                            : '';
                        container.innerHTML +=
                            '<a class="list-group-item list-group-item-action px-0" href="' + href + '">' +
                            '<div class="d-flex justify-content-between align-items-start gap-2">' +
                            '<div class="flex-grow-1">' +
                            '<div class="fw-semibold small">' + badge + entreprise + '</div>' +
                            '<div class="text-muted small">' + programmes + '</div>' +
                            '</div>' +
                            '<div class="text-muted small text-nowrap">' + when + '</div>' +
                            '</div>' +
                            '</a>';
                    });
                })
                .catch(function () {
                    const container = document.getElementById('rs-todos');
                    if (container) container.innerHTML = '<div class="text-center py-2 text-muted small">—</div>';
                });
            @endif
        });
    </script>
    @endif
@endsection
