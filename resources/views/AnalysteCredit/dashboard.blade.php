@extends('Layouts.analyste-credit')

@section('title', 'Espace analyste crédit')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace analyste crédit</h1>
        <p class="text-muted mb-0">Dossiers du pôle engagements qui vous sont affectés par le responsable engagements.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-3">
            <div class="col-md-6 col-xl-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Dossiers affectés</div>
                        <div class="h4 mb-0" id="ancdt-dossiers"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card shadow-sm h-100 border-start border-4 border-primary">
                    <div class="card-body">
                        <div class="text-muted small">À traiter</div>
                        <div class="h4 mb-0" id="ancdt-a-traiter"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card shadow-sm h-100 border-start border-4 border-success">
                    <div class="card-body">
                        <div class="text-muted small">Soumis au resp. engagements</div>
                        <div class="h4 mb-0" id="ancdt-soumis"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card shadow-sm h-100 border-start border-4 border-warning">
                    <div class="card-body">
                        <div class="text-muted small">En retard (&gt; 3j)</div>
                        <div class="h4 mb-0" id="ancdt-retard"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="text-muted small">Accès rapide</div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('analyste-credit.dossiers.index') }}" class="btn btn-primary">Voir les dossiers</a>
                    <a href="{{ route('analyste-credit.entreprises.index') }}" class="btn btn-outline-primary">Entreprises</a>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
                <h6 class="mb-0">Mes dossiers à traiter</h6>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('analyste-credit.dossiers.index') }}">Voir tout</a>
            </div>
            <div class="card-body">
                <div id="ancdt-todos" class="list-group list-group-flush">
                    <div class="text-center py-3">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/simple-dashboard-stats.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            AngaraLoadDashboardStats(@json(route('analyste-credit.dashboard.stats')), {
                dossiers_count: 'ancdt-dossiers',
                a_traiter: 'ancdt-a-traiter',
                soumis_reng: 'ancdt-soumis',
                en_retard: 'ancdt-retard',
            });

            fetch(@json(route('analyste-credit.dashboard.todos')), {
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    const container = document.getElementById('ancdt-todos');
                    if (!container) return;
                    const rows = (data && data.todos) || [];
                    container.innerHTML = '';
                    if (!rows.length) {
                        container.innerHTML = '<div class="text-center py-2 text-muted small">Aucun dossier à traiter.</div>';
                        return;
                    }
                    rows.forEach(function (row) {
                        const href = row.token ? @json(url('/analyste-credit/dossiers')) + '/' + row.token : '#';
                        const entreprise = row.entreprise || '—';
                        const programmes = row.programmes || '—';
                        const when = row.assigned_human ? ('Affecté ' + row.assigned_human) : '—';

                        container.innerHTML +=
                            '<a class="list-group-item list-group-item-action px-0" href="' + href + '">' +
                            '<div class="d-flex justify-content-between align-items-start gap-2">' +
                            '<div class="flex-grow-1">' +
                            '<div class="fw-semibold small">' + entreprise + '</div>' +
                            '<div class="text-muted small">' + programmes + '</div>' +
                            '</div>' +
                            '<div class="text-muted small text-nowrap">' + when + '</div>' +
                            '</div>' +
                            '</a>';
                    });
                })
                .catch(function () {
                    const container = document.getElementById('ancdt-todos');
                    if (container) container.innerHTML = '<div class="text-center py-2 text-muted small">—</div>';
                });
        });
    </script>
@endsection
