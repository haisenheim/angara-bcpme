@php
    $rp = $space['route'] ?? 'dg';
    $pfx = preg_replace('/\W+/', '_', $rp);
@endphp
<div class="container-fluid cf-page">
    <div class="cf-hero mb-4">
        <h1 class="cf-hero__title">Pilotage de l’activité</h1>
        <p class="cf-hero__lead">Indicateurs transverses, répartition du circuit d’instruction et priorités pour la direction — même esprit que le tableau de bord chef de filière, à l’échelle de la banque.</p>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric" id="{{ $pfx }}-m-prospects-soumis"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    <p class="cf-dash-card__label">Prospects soumis</p>
                    <a href="{{ route($rp.'.prospects.index') }}" class="btn btn-outline-primary btn-sm align-self-start">Liste prospects</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric" id="{{ $pfx }}-m-clients"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    <p class="cf-dash-card__label">Clients</p>
                    <a href="{{ route($rp.'.entreprises.index') }}" class="btn btn-outline-primary btn-sm align-self-start">Portefeuille clients</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric" id="{{ $pfx }}-m-dossiers-valides-agence"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    <p class="cf-dash-card__label">Dossiers validés chef d’agence</p>
                    <a href="{{ route($rp.'.dossiers.valides-chef-agence') }}" class="btn btn-outline-primary btn-sm align-self-start">Tous les dossiers</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100 border-warning border-opacity-50">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric text-warning-emphasis" id="{{ $pfx }}-m-attente-direction"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    <p class="cf-dash-card__label">En attente avis direction</p>
                    <a href="{{ route($rp.'.dossiers.en-attente-direction') }}" class="btn btn-primary btn-sm align-self-start">Traiter</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-lg-4 mt-1">
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric" id="{{ $pfx }}-m-instruction-en-cours"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    <p class="cf-dash-card__label">Instructions en cours</p>
                    <span class="small text-muted">Dossiers validés agence, parcours non clos</span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric" id="{{ $pfx }}-m-instruction-clos"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    <p class="cf-dash-card__label">Instructions closes</p>
                    <span class="small text-muted">Clôture enregistrée (parcours terminé)</span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric" id="{{ $pfx }}-m-somme-sollicites"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    <p class="cf-dash-card__label">Total engagements sollicités</p>
                    <span class="small text-muted">Somme sur les dossiers validés chef d’agence</span>
                    <a href="{{ route($rp.'.dossiers.valides-chef-agence') }}" class="btn btn-outline-primary btn-sm align-self-start mt-2">Détail dossiers</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric" id="{{ $pfx }}-m-somme-encours"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    <p class="cf-dash-card__label">Total engagements en cours</p>
                    <span class="small text-muted">Somme sur les dossiers validés chef d’agence</span>
                    <a href="{{ route($rp.'.dossiers.valides-chef-agence') }}" class="btn btn-outline-primary btn-sm align-self-start mt-2">Détail dossiers</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-lg-4 mt-1">
        <div class="col-xl-4">
            <div class="cf-panel h-100">
                <div class="cf-panel__toolbar">
                    <p class="cf-panel__toolbar-label mb-0">Structuration clients (promus)</p>
                </div>
                <div class="p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Structuré</span>
                        <span class="fw-semibold" id="{{ $pfx }}-s-structure"><span class="spinner-border spinner-border-sm" role="status"></span></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">En cours</span>
                        <span class="fw-semibold" id="{{ $pfx }}-s-encours"><span class="spinner-border spinner-border-sm" role="status"></span></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">En attente</span>
                        <span class="fw-semibold" id="{{ $pfx }}-s-attente"><span class="spinner-border spinner-border-sm" role="status"></span></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Rejetée</span>
                        <span class="fw-semibold" id="{{ $pfx }}-s-rejetee"><span class="spinner-border spinner-border-sm" role="status"></span></span>
                    </div>
                    <div class="mt-3 ratio ratio-4x3 position-relative" style="max-height: 200px;">
                        <canvas id="{{ $pfx }}-chart-structuration" aria-label="Répartition structuration"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="cf-panel h-100">
                <div class="cf-panel__toolbar">
                    <p class="cf-panel__toolbar-label mb-0">Circuit instruction (validés agence)</p>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-muted small mb-2">Répartition des dossiers par étape du parcours (hors clos / dossiers clos en fin de barre).</p>
                    <div class="ratio ratio-4x3 position-relative" style="max-height: 260px;">
                        <canvas id="{{ $pfx }}-chart-pipeline" aria-label="Parcours instruction"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="cf-panel h-100">
                <div class="cf-panel__toolbar">
                    <p class="cf-panel__toolbar-label mb-0">Priorité — avis direction</p>
                </div>
                <div class="p-3 p-md-4">
                    <div id="{{ $pfx }}-list-attente-direction" class="list-group list-group-flush">
                        <div class="text-center py-3">
                            <span class="spinner-border spinner-border-sm" role="status"></span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route($rp.'.dossiers.en-attente-direction') }}" class="btn btn-sm btn-primary">Ouvrir la file complète</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-lg-4 mt-1">
        <div class="col-lg-6">
            <div class="cf-panel h-100">
                <div class="cf-panel__toolbar">
                    <p class="cf-panel__toolbar-label mb-0">Créations de dossiers (6 mois, validés agence)</p>
                </div>
                <div class="p-3 p-md-4">
                    <div class="ratio ratio-21x9 position-relative" style="max-height: 280px;">
                        <canvas id="{{ $pfx }}-chart-trends" aria-label="Volume mensuel"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="cf-panel h-100">
                <div class="cf-panel__toolbar">
                    <p class="cf-panel__toolbar-label mb-0">Portefeuille entreprises</p>
                </div>
                <div class="p-3 p-md-4">
                    <div class="row align-items-center">
                        <div class="col-sm-7">
                            <div class="ratio ratio-1x1 position-relative" style="max-height: 240px;">
                                <canvas id="{{ $pfx }}-chart-portefeuille" aria-label="Prospects / clients"></canvas>
                            </div>
                        </div>
                        <div class="col-sm-5 small">
                            <div class="mb-2"><span class="d-inline-block rounded-circle me-2 align-middle" style="width:10px;height:10px;background:#D0362E"></span> Prospects soumis : <strong id="{{ $pfx }}-pf-pros">—</strong></div>
                            <div><span class="d-inline-block rounded-circle me-2 align-middle" style="width:10px;height:10px;background:#4B5563"></span> Clients : <strong id="{{ $pfx }}-pf-cli">—</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 cf-panel">
        <div class="cf-panel__toolbar">
            <p class="cf-panel__toolbar-label mb-0">Accès rapides</p>
        </div>
        <div class="p-3 p-md-4 d-flex flex-wrap gap-2">
            <a href="{{ route($rp.'.entreprises.index') }}" class="btn btn-primary btn-sm">Clients</a>
            <a href="{{ route($rp.'.prospects.index') }}" class="btn btn-outline-primary btn-sm">Prospects</a>
            <a href="{{ route($rp.'.dossiers.valides-chef-agence') }}" class="btn btn-outline-primary btn-sm">Dossiers d’instruction</a>
            <a href="{{ route($rp.'.dossiers.en-attente-direction') }}" class="btn btn-outline-warning btn-sm">En attente direction</a>
            <a href="{{ route($rp.'.programmes.index') }}" class="btn btn-outline-secondary btn-sm">Programmes</a>
            <a href="{{ route($rp.'.users.index') }}" class="btn btn-outline-secondary btn-sm">Comptes utilisateurs</a>
        </div>
    </div>
</div>

<script src="{{ asset('js/simple-dashboard-stats.js') }}"></script>
<script src="{{ asset('assets/vendors/chart.js/chart.umd.min.js') }}"></script>
<script>
function angaraDashToInt(v, d) {
    const n = parseInt(v, 10);
    return isNaN(n) ? d : n;
}
document.addEventListener('DOMContentLoaded', function () {
    const pfx = @json($pfx);
    const rp = @json($rp);
    const baseUrl = @json(url(''));

    const statsMap = {
        prospects_soumis: pfx + '-m-prospects-soumis',
        clients: pfx + '-m-clients',
        dossiers_valides_agence: pfx + '-m-dossiers-valides-agence',
        dossiers_attente_direction: pfx + '-m-attente-direction',
        dossiers_instruction_en_cours: pfx + '-m-instruction-en-cours',
        dossiers_instruction_clos: pfx + '-m-instruction-clos',
        somme_engagements_sollicites_valides_agence: pfx + '-m-somme-sollicites',
        somme_engagements_en_cours_valides_agence: pfx + '-m-somme-encours',
    };

    AngaraLoadDashboardStats(@json(route($rp.'.dashboard.stats')), statsMap);

    const accent = '#D0362E';
    const muted = '#6B7280';

    function safeChartDestroy(chart) {
        if (chart && typeof chart.destroy === 'function') chart.destroy();
    }

    fetch(@json(route($rp.'.dashboard.insights')), {
        credentials: 'same-origin',
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
                const s = (data && data.structuration) || {};
                const setText = function (id, v) {
                    const el = document.getElementById(id);
                    if (el) el.textContent = (v === undefined || v === null) ? '—' : String(v);
                };
                setText(pfx + '-s-structure', s.structure);
                setText(pfx + '-s-encours', s.en_cours);
                setText(pfx + '-s-attente', s.attente);
                setText(pfx + '-s-rejetee', s.rejetee);

                const structValues = [s.structure || 0, s.en_cours || 0, s.attente || 0, s.rejetee || 0];
                const structLabels = ['Structuré', 'En cours', 'En attente', 'Rejetée'];
                const ctxSt = document.getElementById(pfx + '-chart-structuration');
                if (ctxSt && window.Chart) {
                    window['__angaraStructChart_' + pfx] && safeChartDestroy(window['__angaraStructChart_' + pfx]);
                    window['__angaraStructChart_' + pfx] = new Chart(ctxSt, {
                        type: 'doughnut',
                        data: {
                            labels: structLabels,
                            datasets: [{
                                data: structValues,
                                backgroundColor: [accent, '#3B82F6', '#F59E0B', '#9CA3AF'],
                                borderWidth: 0,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
                        },
                    });
                }

                const p = (data && data.pipeline) || {};
                const pipeLabels = ['Exploitation', 'Juridique', 'Engagements', 'Risques', 'Direction', 'Clos'];
                const pipeKeys = ['exploitation', 'juridique', 'engagements', 'risques', 'direction', 'clos'];
                const pipeValues = pipeKeys.map(function (k) { return angaraDashToInt(p[k], 0); });
                const ctxP = document.getElementById(pfx + '-chart-pipeline');
                if (ctxP && window.Chart) {
                    window['__angaraPipeChart_' + pfx] && safeChartDestroy(window['__angaraPipeChart_' + pfx]);
                    window['__angaraPipeChart_' + pfx] = new Chart(ctxP, {
                        type: 'bar',
                        data: {
                            labels: pipeLabels,
                            datasets: [{
                                label: 'Dossiers',
                                data: pipeValues,
                                backgroundColor: [muted, '#8B5CF6', '#0D9488', '#EA580C', accent, '#22C55E'],
                                borderRadius: 4,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: { ticks: { maxRotation: 45, minRotation: 0, font: { size: 10 } } },
                                y: { beginAtZero: true, ticks: { precision: 0 } },
                            },
                            plugins: { legend: { display: false } },
                        },
                    });
                }

                const trends = (data && data.trends) || [];
                const ctxT = document.getElementById(pfx + '-chart-trends');
                if (ctxT && window.Chart && trends.length) {
                    window['__angaraTrendChart_' + pfx] && safeChartDestroy(window['__angaraTrendChart_' + pfx]);
                    window['__angaraTrendChart_' + pfx] = new Chart(ctxT, {
                        type: 'line',
                        data: {
                            labels: trends.map(function (t) { return t.label || ''; }),
                            datasets: [{
                                label: 'Dossiers créés',
                                data: trends.map(function (t) { return angaraDashToInt(t.count, 0); }),
                                borderColor: accent,
                                backgroundColor: 'rgba(208, 54, 46, 0.12)',
                                fill: true,
                                tension: 0.25,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                            plugins: { legend: { display: false } },
                        },
                    });
                }

                const pf = (data && data.portefeuille) || {};
                setText(pfx + '-pf-pros', pf.prospects_soumis);
                setText(pfx + '-pf-cli', pf.clients);
                const ctxPf = document.getElementById(pfx + '-chart-portefeuille');
                if (ctxPf && window.Chart) {
                    const pv = [angaraDashToInt(pf.prospects_soumis, 0), angaraDashToInt(pf.clients, 0)];
                    window['__angaraPfChart_' + pfx] && safeChartDestroy(window['__angaraPfChart_' + pfx]);
                    window['__angaraPfChart_' + pfx] = new Chart(ctxPf, {
                        type: 'doughnut',
                        data: {
                            labels: ['Prospects soumis', 'Clients'],
                            datasets: [{
                                data: pv,
                                backgroundColor: [accent, '#374151'],
                                borderWidth: 0,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                        },
                    });
                }

                const rows = (data && data.attente_direction) || [];
                const listEl = document.getElementById(pfx + '-list-attente-direction');
                if (listEl) {
                    listEl.innerHTML = '';
                    if (!rows.length) {
                        listEl.innerHTML = '<div class="text-center py-2 text-muted small">Aucun dossier en attente d’avis direction.</div>';
                    } else {
                        rows.forEach(function (row) {
                            const href = row.token ? baseUrl + '/' + rp + '/dossiers/' + row.token : '#';
                            listEl.innerHTML +=
                                '<a class="list-group-item list-group-item-action px-0" href="' + href + '">' +
                                '<div class="d-flex justify-content-between align-items-start gap-2">' +
                                '<div class="flex-grow-1">' +
                                '<div class="fw-semibold small">' + (row.entreprise || '—') + '</div>' +
                                '<div class="text-muted small">' + (row.programmes || '—') + '</div>' +
                                '</div>' +
                                '<div class="text-muted small text-nowrap">' + (row.since || '—') + '</div>' +
                                '</div>' +
                                '</a>';
                        });
                    }
                }
            })
        .catch(function () {
            [pfx + '-s-structure', pfx + '-s-encours', pfx + '-s-attente', pfx + '-s-rejetee'].forEach(function (id) {
                const el = document.getElementById(id);
                if (el) el.textContent = '—';
            });
            const le = document.getElementById(pfx + '-list-attente-direction');
            if (le) le.innerHTML = '<div class="text-center py-2 text-muted small">—</div>';
        });
});
</script>
