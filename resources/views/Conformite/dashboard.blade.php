@extends('Layouts.conformite')

@section('title', 'Espace conformité')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace conformité</h1>
        <p class="text-muted mb-0">Validation conformité après avis juridique.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="mb-2">Prospects en attente d'avis conformité : <strong id="co-pending-prospects"><span class="spinner-border spinner-border-sm" role="status"></span></strong></p>
                <a href="{{ route('conformite.prospects.index') }}" class="btn btn-primary btn-sm">Ouvrir la file conformité</a>
            </div>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-md-6 col-xl-4">
                <div class="card shadow-sm h-100 border-start border-4 border-warning">
                    <div class="card-body">
                        <div class="text-muted small">Bloqués (attente avis juridique)</div>
                        <div class="h4 mb-0" id="co-bloques-juridique"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
                <h6 class="mb-0">Prospects à traiter</h6>
                <a href="{{ route('conformite.prospects.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
            <div class="card-body">
                <div id="co-todos" class="list-group list-group-flush">
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
            AngaraLoadDashboardStats(@json(route('conformite.dashboard.stats')), {
                pending_prospects: 'co-pending-prospects',
                bloques_juridique: 'co-bloques-juridique',
            });

            fetch(@json(route('conformite.dashboard.todos')), {
                credentials: 'same-origin',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    const container = document.getElementById('co-todos');
                    if (!container) return;
                    const rows = (data && data.prospects) || [];
                    container.innerHTML = '';
                    if (!rows.length) {
                        container.innerHTML = '<div class="text-center py-2 text-muted small">Aucun prospect.</div>';
                        return;
                    }
                    rows.forEach(function (row) {
                        const href = row.token ? @json(url('/conformite/prospects')) + '/' + row.token : '#';
                        const when = row.when_human ? ('Avis juridique ' + row.when_human) : '—';
                        container.innerHTML += '<a class="list-group-item list-group-item-action px-0" href="'+href+'"><div class="d-flex justify-content-between gap-2"><div class="fw-semibold small">'+(row.name||'—')+'</div><div class="text-muted small text-nowrap">'+when+'</div></div></a>';
                    });
                })
                .catch(function () {
                    const container = document.getElementById('co-todos');
                    if (container) container.innerHTML = '<div class="text-center py-2 text-muted small">—</div>';
                });
        });
    </script>
@endsection
