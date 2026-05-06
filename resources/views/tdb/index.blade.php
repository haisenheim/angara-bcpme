@extends('Layouts.app')

@section('title', 'Tableaux de bord')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Angara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tableaux de bord</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Tableaux de bord</h5>
        <p class="text-body-secondary mb-0 mt-1">5 familles d'indicateurs (référence : prompt.txt l. 86‑105). Sélectionnez une famille pour voir le détail.</p>
    </div>
@endsection

@push('styles')
<style>
    .tdb-card { transition: transform 0.15s; }
    .tdb-card:hover { transform: translateY(-2px); }
    .tdb-kpi { font-size: 1.5rem; font-weight: 700; }
    .tdb-table th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--bs-secondary-color); }
    .tdb-pill { font-size: 0.7rem; padding: 0.2rem 0.5rem; border-radius: 999px; background: var(--bs-secondary-bg); color: var(--bs-secondary-color); }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Filtres globaux --}}
    <form id="tdb-filters" class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-3">
                    <label for="tdb-from" class="form-label small text-body-secondary mb-0">Du</label>
                    <input type="date" id="tdb-from" name="from" class="form-control form-control-sm">
                </div>
                <div class="col-6 col-md-3">
                    <label for="tdb-to" class="form-label small text-body-secondary mb-0">au</label>
                    <input type="date" id="tdb-to" name="to" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-md-auto ms-md-auto">
                    <button type="submit" class="btn btn-primary btn-sm">Recharger</button>
                    <button type="button" id="tdb-reset" class="btn btn-outline-secondary btn-sm">Réinitialiser</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Onglets famille --}}
    <ul class="nav nav-pills mb-3" role="tablist">
        @foreach($familles as $i => $famille)
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($i === 0) active @endif" id="tab-{{ $famille['code'] }}" data-bs-toggle="pill" data-bs-target="#pane-{{ $famille['code'] }}" type="button" role="tab">{{ $famille['label'] }}</button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($familles as $i => $famille)
            <div class="tab-pane fade @if($i === 0) show active @endif" id="pane-{{ $famille['code'] }}" role="tabpanel">
                <p class="text-body-secondary small mb-3">{{ $famille['description'] }}</p>
                <div data-tdb-famille="{{ $famille['code'] }}" data-tdb-url="{{ route('tdb.'.$famille['code']) }}">
                    <div class="text-center py-5 text-body-secondary"><span class="spinner-border spinner-border-sm me-2"></span>Chargement…</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('script')
<script>
(function () {
    const fmt = new Intl.NumberFormat('fr-FR');
    const fmtMoney = (v) => v == null ? '—' : fmt.format(Math.round(Number(v)));
    const escape = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    function buildQuery() {
        const params = new URLSearchParams();
        const from = document.getElementById('tdb-from').value;
        const to = document.getElementById('tdb-to').value;
        if (from) params.append('from', from);
        if (to) params.append('to', to);
        return params.toString() ? '?' + params.toString() : '';
    }

    function renderOperationnel(d) {
        const delai = d.delai_moyen_traitement_jours;
        return `
            <div class="row g-3">
                ${kpi('Dossiers en cours', d.dossiers_en_cours, 'primary')}
                ${kpi('En attente validation CA', d.dossiers_en_attente_validation, 'warning')}
                ${kpi('Dossiers rejetés', d.dossiers_rejetes, 'danger')}
                ${kpi('Délai moyen (jours)', delai != null ? fmt.format(delai) : '—', 'info')}
            </div>`;
    }

    function renderPortefeuille(d) {
        const filiere = (d.repartition_par_filiere || []).map(r => `<tr><td>${escape(r.label)}</td><td class="text-end">${fmt.format(r.nb_dossiers)}</td><td class="text-end">${fmtMoney(r.volume)}</td></tr>`).join('') || '<tr><td colspan="3" class="text-center text-body-secondary">—</td></tr>';
        const secteur = (d.repartition_par_secteur || []).map(r => `<tr><td>${escape(r.label)}</td><td class="text-end">${fmt.format(r.nb_clients)}</td></tr>`).join('') || '<tr><td colspan="2" class="text-center text-body-secondary">—</td></tr>';
        const top = (d.top_clients_par_encours || []).map(r => `<tr><td>${escape(r.name)}</td><td class="text-end">${fmt.format(r.nb_dossiers)}</td><td class="text-end">${fmtMoney(r.encours)}</td></tr>`).join('') || '<tr><td colspan="3" class="text-center text-body-secondary">—</td></tr>';

        return `
            <div class="row g-3 mb-3">
                ${kpi('Volume crédits sollicités', fmtMoney(d.volume_credits_sollicites), 'primary')}
                ${kpi('Encours total', fmtMoney(d.encours_total), 'success')}
                ${kpi('Nombre de dossiers', d.nb_dossiers, 'info')}
                ${kpi('Nombre de clients', d.nb_clients, 'secondary')}
            </div>
            <div class="row g-3">
                ${tableCard('Répartition par filière', ['Filière', 'Dossiers', 'Volume'], filiere, 6)}
                ${tableCard('Top clients par encours', ['Client', 'Dossiers', 'Encours'], top, 6)}
                ${tableCard('Répartition par secteur', ['Secteur', 'Clients'], secteur, 12)}
            </div>`;
    }

    function renderRisques(d) {
        const alertes = d.alertes_seuil || {};
        const impayesNote = '<div class="alert alert-info py-2 small mb-0">Les indicateurs <strong>impayés</strong> et <strong>créances en souffrance</strong> nécessitent un module de gestion des échéances non encore intégré au schéma. Ils s\'afficheront dès que les tables de remboursement seront disponibles.</div>';

        return `
            <div class="row g-3 mb-3">
                ${kpi('Dossiers à risque (rejets en cours d\'instruction)', d.dossiers_a_risque_en_instruction, 'warning')}
                ${kpi('Rejets de clôture finaux', d.rejets_clotures_finaux, 'danger')}
                ${kpi('Alertes — au-dessus du seuil ('+fmtMoney(alertes.seuil)+')', alertes.dossiers_au_dessus_seuil, 'info')}
            </div>
            ${impayesNote}`;
    }

    function renderStrategique(d) {
        const perf = d.performance_globale || {};
        const croissance = (d.croissance_portefeuille || []).map(r => `<tr><td>${escape(r.mois)}</td><td class="text-end">${fmt.format(r.nb_dossiers)}</td><td class="text-end">${fmtMoney(r.volume)}</td></tr>`).join('') || '<tr><td colspan="3" class="text-center text-body-secondary">—</td></tr>';
        const impact = d.impact_programmes || {};

        return `
            <div class="row g-3 mb-3">
                ${kpi('Dossiers ouverts (12 mois)', perf.dossiers_ouverts_12_mois, 'primary')}
                ${kpi('Dossiers clôturés (12 mois)', perf.dossiers_clotures_12_mois, 'success')}
                ${kpi('Taux de clôture', perf.taux_cloture != null ? perf.taux_cloture + ' %' : '—', 'info')}
            </div>
            <div class="row g-3">
                ${tableCard('Croissance du portefeuille (12 mois)', ['Mois', 'Dossiers', 'Volume'], croissance, 8)}
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100"><div class="card-body">
                        <h6 class="text-uppercase text-body-secondary small mb-3">Impact programmes</h6>
                        <p class="mb-1"><span class="text-body-secondary small">Total appui financier</span><br><strong>${fmtMoney(impact.total_appui_financier)}</strong></p>
                        <p class="mb-1"><span class="text-body-secondary small">Total appui non financier</span><br><strong>${fmtMoney(impact.total_appui_non_financier)}</strong></p>
                        <p class="mb-0"><span class="text-body-secondary small">Dossiers couverts</span><br><strong>${fmt.format(impact.nb_dossiers_touches || 0)}</strong></p>
                    </div></div>
                </div>
            </div>`;
    }

    function renderProgrammes(d) {
        const totaux = d.totaux || {};
        const rows = (d.programmes || []).map(r => `<tr><td>${escape(r.name)}</td><td class="text-end">${fmt.format(r.nb_beneficiaires)}</td><td class="text-end">${fmtMoney(r.montant_financier)}</td><td class="text-end">${fmtMoney(r.montant_non_financier)}</td></tr>`).join('') || '<tr><td colspan="4" class="text-center text-body-secondary">—</td></tr>';

        return `
            <div class="row g-3 mb-3">
                ${kpi('Programmes actifs', totaux.nb_programmes, 'primary')}
                ${kpi('Total appui financier', fmtMoney(totaux.total_montant_financier), 'success')}
                ${kpi('Total appui non financier', fmtMoney(totaux.total_montant_non_financier), 'info')}
            </div>
            ${tableCard('Détail par programme', ['Programme', 'Bénéficiaires', 'Appui financier', 'Appui non financier'], rows, 12)}`;
    }

    function kpi(label, value, color) {
        return `<div class="col-12 col-sm-6 col-lg-3"><div class="card tdb-card border-0 shadow-sm h-100"><div class="card-body">
            <div class="small text-body-secondary text-uppercase">${escape(label)}</div>
            <div class="tdb-kpi text-${color}">${value == null || value === '' ? '—' : (typeof value === 'number' ? fmt.format(value) : value)}</div>
        </div></div></div>`;
    }

    function tableCard(title, headers, rowsHtml, cols) {
        const ths = headers.map((h, i) => `<th class="${i > 0 ? 'text-end' : ''}">${escape(h)}</th>`).join('');
        return `<div class="col-12 col-md-${cols}"><div class="card border-0 shadow-sm h-100"><div class="card-body">
            <h6 class="text-uppercase text-body-secondary small mb-2">${escape(title)}</h6>
            <div class="table-responsive"><table class="table table-sm tdb-table mb-0"><thead><tr>${ths}</tr></thead><tbody>${rowsHtml}</tbody></table></div>
        </div></div></div>`;
    }

    const renderers = {
        operationnel: renderOperationnel,
        portefeuille: renderPortefeuille,
        risques: renderRisques,
        strategique: renderStrategique,
        programmes: renderProgrammes,
    };

    const loaded = {};
    function loadFamille(code, force = false) {
        const target = document.querySelector('[data-tdb-famille="'+code+'"]');
        if (!target) return;
        if (loaded[code] && !force) return;

        target.innerHTML = '<div class="text-center py-5 text-body-secondary"><span class="spinner-border spinner-border-sm me-2"></span>Chargement…</div>';
        const url = target.getAttribute('data-tdb-url') + buildQuery();
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                loaded[code] = true;
                target.innerHTML = (renderers[code] || (() => '<pre>'+JSON.stringify(data, null, 2)+'</pre>'))(data);
            })
            .catch(err => {
                target.innerHTML = '<div class="alert alert-danger">Erreur de chargement : '+escape(err.message || err)+'</div>';
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // initial load (active tab)
        loadFamille('operationnel');

        document.querySelectorAll('[data-bs-toggle="pill"]').forEach(btn => {
            btn.addEventListener('shown.bs.tab', function (e) {
                const code = e.target.id.replace(/^tab-/, '');
                loadFamille(code);
            });
        });

        document.getElementById('tdb-filters').addEventListener('submit', function (e) {
            e.preventDefault();
            Object.keys(loaded).forEach(k => delete loaded[k]);
            const active = document.querySelector('.nav-link.active');
            const code = active ? active.id.replace(/^tab-/, '') : 'operationnel';
            loadFamille(code, true);
        });

        document.getElementById('tdb-reset').addEventListener('click', function () {
            document.getElementById('tdb-from').value = '';
            document.getElementById('tdb-to').value = '';
            Object.keys(loaded).forEach(k => delete loaded[k]);
            const active = document.querySelector('.nav-link.active');
            const code = active ? active.id.replace(/^tab-/, '') : 'operationnel';
            loadFamille(code, true);
        });
    });
})();
</script>
@endsection
