@extends('Layouts.juridique')

@section('title', 'Espace juridique')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace juridique</h1>
        <p class="text-muted mb-0">Relecture des prospects et dossiers d’instruction transmis par l’exploitation.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="mb-2">Prospects en attente d'avis juridique : <strong id="ju-pending-prospects"><span class="spinner-border spinner-border-sm" role="status"></span></strong></p>
                <a href="{{ route('juridique.prospects.index') }}" class="btn btn-primary btn-sm">Ouvrir la file juridique</a>
            </div>
        </div>
        <div class="row g-3 mt-1">
            <div class="col-md-6 col-xl-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Dossiers instruction (pôle juridique)</div>
                        <div class="h4 mb-0" id="ju-instruction-dossiers"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card shadow-sm h-100 border-start border-4 border-warning">
                    <div class="card-body">
                        <div class="text-muted small">À affecter (analyste juridique)</div>
                        <div class="h4 mb-0" id="ju-a-affecter"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card shadow-sm h-100 border-start border-4 border-primary">
                    <div class="card-body">
                        <div class="text-muted small">Avis analyste à traiter</div>
                        <div class="h4 mb-0" id="ju-avis-a-traiter"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card shadow-sm h-100 border-start border-4 border-success">
                    <div class="card-body">
                        <div class="text-muted small">À soumettre engagements</div>
                        <div class="h4 mb-0" id="ju-a-soumettre"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
                <h6 class="mb-0">À traiter maintenant</h6>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('juridique.prospects.index') }}" class="btn btn-sm btn-primary">Prospects</a>
                    <a href="{{ route('juridique.dossiers.index') }}" class="btn btn-sm btn-outline-primary">Dossiers</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="text-muted small mb-2">Prospects (avis juridique)</div>
                        <div id="ju-todos-prospects" class="list-group list-group-flush">
                            <div class="text-center py-3">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="text-muted small mb-2">Dossiers (pôle juridique)</div>
                        <div id="ju-todos-dossiers" class="list-group list-group-flush">
                            <div class="text-center py-3">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/simple-dashboard-stats.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            AngaraLoadDashboardStats(@json(route('juridique.dashboard.stats')), {
                pending_prospects: 'ju-pending-prospects',
                instruction_dossiers: 'ju-instruction-dossiers',
                a_affecter_analyste: 'ju-a-affecter',
                avis_analyste_a_traiter: 'ju-avis-a-traiter',
                a_soumettre_engagements: 'ju-a-soumettre',
            });

            fetch(@json(route('juridique.dashboard.todos')), {
                credentials: 'same-origin',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    const p = document.getElementById('ju-todos-prospects');
                    const d = document.getElementById('ju-todos-dossiers');
                    if (p) {
                        const rows = (data && data.prospects) || [];
                        p.innerHTML = rows.length ? '' : '<div class="text-center py-2 text-muted small">Aucun prospect.</div>';
                        rows.forEach(function (row) {
                            const href = row.token ? @json(url('/juridique/prospects')) + '/' + row.token : '#';
                            const when = row.when_human ? ('Soumis ' + row.when_human) : '—';
                            p.innerHTML += '<a class="list-group-item list-group-item-action px-0" href="'+href+'"><div class="d-flex justify-content-between gap-2"><div class="fw-semibold small">'+(row.name||'—')+'</div><div class="text-muted small text-nowrap">'+when+'</div></div></a>';
                        });
                    }
                    if (d) {
                        const rows = (data && data.dossiers) || [];
                        d.innerHTML = rows.length ? '' : '<div class="text-center py-2 text-muted small">Aucun dossier.</div>';
                        rows.forEach(function (row) {
                            const href = row.token ? @json(url('/juridique/dossiers')) + '/' + row.token : '#';
                            d.innerHTML += '<a class="list-group-item list-group-item-action px-0" href="'+href+'"><div class="fw-semibold small">'+(row.entreprise||'—')+'</div><div class="text-muted small">'+(row.programmes||'—')+'</div></a>';
                        });
                    }
                })
                .catch(function () {
                    const p = document.getElementById('ju-todos-prospects');
                    if (p) p.innerHTML = '<div class="text-center py-2 text-muted small">—</div>';
                    const d = document.getElementById('ju-todos-dossiers');
                    if (d) d.innerHTML = '<div class="text-center py-2 text-muted small">—</div>';
                });
        });
    </script>
@endsection
