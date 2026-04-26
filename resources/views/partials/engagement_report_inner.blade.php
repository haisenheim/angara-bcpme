@php
    $canEdit = $canEdit ?? false;
    $setEngagementUrl = $setEngagementUrl ?? null;
@endphp
@php
    $exportBase = request()->routeIs('analyste-credit.*')
        ? route('analyste-credit.entreprise.engagements.export', $entreprise->token)
        : route('analyste.entreprise.engagements.export', $entreprise->token);
    $dataUrl = request()->routeIs('analyste-credit.*')
        ? route('analyste-credit.entreprise.engagements.data', $entreprise->token)
        : route('analyste.entreprise.engagements.data', $entreprise->token);
@endphp

<div class="angara-table">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div>
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-file-text-image me-2 text-primary"></i>Répartition des engagements</h6>
                    <div class="small text-body-secondary mt-1" data-angara-table-info></div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                    <select id="engagement_banque_id" class="form-select form-select-sm" style="min-width: 220px;">
                        <option value="">Toutes banques</option>
                        @foreach ($banques as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                    <input
                        id="engagement_search"
                        data-angara-table-search
                        class="form-control form-control-sm"
                        style="min-width: 240px;"
                        placeholder="Rechercher (engagement)…"
                    />

                    <button type="button" class="btn btn-sm btn-outline-secondary" id="engagement_filters_reset">
                        Réinitialiser
                    </button>

                    <div class="vr d-none d-md-block mx-1"></div>

                    <a class="btn btn-sm btn-outline-primary js-engagement-export"
                       data-format="pdf"
                       data-export-base="{{ $exportBase }}"
                       href="{{ $exportBase }}?format=pdf">
                        <i class="demo-psi-file me-1"></i> Export PDF
                    </a>
                    <a class="btn btn-sm btn-outline-success js-engagement-export"
                       data-format="xlsx"
                       data-export-base="{{ $exportBase }}"
                       href="{{ $exportBase }}?format=xlsx">
                        <i class="demo-psi-file-excel me-1"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 75vh; overflow-y: auto;">
                <table class="table table-hover table-bordered align-middle mb-0 engagement-table" id="engagement_table">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 260px;">Engagement</th>
                            <th class="text-end" style="min-width: 140px;">Encours</th>
                            <th class="text-end" style="min-width: 120px;">Impayés</th>
                            <th class="text-end" style="min-width: 140px;">Sollicité</th>
                            <th class="text-end" style="min-width: 140px;">Variation</th>
                            <th class="text-end" style="min-width: 140px;">Total</th>
                            <th class="text-center" style="min-width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="angara-table-empty d-none" data-angara-table-empty>
                Aucune donnée.
            </div>

            <div class="d-flex align-items-center justify-content-between p-3 border-top bg-white">
                <div data-angara-table-paging></div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-body-secondary small">Lignes:</span>
                    <select class="form-select form-select-sm" style="width: 110px;" data-angara-table-length>
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="engagementDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="demo-psi-eye me-2"></i>Détails de l'engagement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <h6 id="detailsEngagementName" class="mb-3 fw-semibold"></h6>
                <div id="detailsEngagementContent"></div>
            </div>
        </div>
    </div>
</div>

@if($canEdit && $setEngagementUrl)
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Éditer l'engagement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ $setEngagementUrl }}" method="post">
                        <input type="hidden" name="engagement_id" id="engagement_id">
                        <input type="hidden" name="entreprise_id" value="{{ $entreprise->id }}">
                        @csrf
                        <div class="mb-3">
                            <label for="banque_id" class="form-label">Banque</label>
                            <select required name="banque_id" id="banque_id" class="form-select">
                                <option value="">Sélectionner la banque</option>
                                @foreach ($banques as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <fieldset class="mb-3">
                            <legend class="form-label">Engagements en cours</legend>
                            <div class="row g-3">
                                <div class="col-md-4 col-sm-12">
                                    <label for="">Montant</label>
                                    <input required value="0" type="number" class="form-control" name="encours_montant">
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label for="">Impayé</label>
                                    <input required value="0" type="number" class="form-control" name="encours_impaye">
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label for="">Date de validité</label>
                                    <input required value="0" type="date" class="form-control" name="encours_dt_validite">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-3">
                            <legend class="form-label">Engagements sollicités</legend>
                            <div class="row g-3">
                                <div class="col-md-6 col-sm-12">
                                    <label for="">Montant</label>
                                    <input required value="0" type="number" class="form-control" name="sollicite_montant">
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="">Date de validité</label>
                                    <input required value="0" type="date" class="form-control" name="sollicite_dt_validite">
                                </div>
                            </div>
                        </fieldset>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

<script src="{{ asset('js/angara-table-filters.js') }}"></script>
<script>
    (function () {
        const filterFields = { banque_id: 'engagement_banque_id' };
        const searchDomId = 'engagement_search';

        const tableEl = document.getElementById('engagement_table');
        const banqueEl = document.getElementById('engagement_banque_id');
        const searchEl = document.getElementById('engagement_search');
        const resetBtn = document.getElementById('engagement_filters_reset');

        function fmt(n) {
            try { return new Intl.NumberFormat('fr-FR').format(Number(n || 0)); } catch (_) { return String(n || 0); }
        }

        function escapeHtml(s) {
            return String(s || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function applyExportsHref() {
            document.querySelectorAll('.js-engagement-export').forEach(a => {
                const baseUrl = a.getAttribute('data-export-base') || a.getAttribute('href') || '';
                const format = a.getAttribute('data-format') || 'xlsx';
                if (!baseUrl) return;

                const qs = (window.AngaraTableFilters && window.AngaraTableFilters.buildExportQuery)
                    ? window.AngaraTableFilters.buildExportQuery({ format, baseUrl, filterFields, searchDomId })
                    : ('format=' + encodeURIComponent(format));

                a.setAttribute('href', baseUrl + (baseUrl.includes('?') ? '&' : '?') + qs);
            });
        }

        let engagementTable = null;
        if (tableEl && window.AngaraTable) {
            engagementTable = new window.AngaraTable(tableEl, {
                ajaxUrl: @json($dataUrl),
                pageLength: 25,
                searchDelay: 300,
                filters: filterFields,
                columns: [
                    {
                        data: 'name',
                        name: 'name',
                        render: function (val, _type, row) {
                            const indent = Math.max(0, Number(row.niveau || 0)) * 12;
                            const fw = Number(row.niveau || 0) === 0 ? 700 : (Number(row.is_leaf) ? 400 : 600);
                            return '<div style="padding-left:' + indent + 'px;font-weight:' + fw + ';">' + escapeHtml(val) + '</div>';
                        }
                    },
                    { data: 'encours_montant', name: 'encours_montant', className: 'text-end', render: (v) => fmt(v) },
                    { data: 'encours_impaye', name: 'encours_impaye', className: 'text-end', render: (v) => fmt(v) },
                    { data: 'sollicite_montant', name: 'sollicite_montant', className: 'text-end', render: (v) => fmt(v) },
                    { data: 'variation', name: 'variation', className: 'text-end fw-medium', render: (v) => fmt(v) },
                    { data: 'total_montant', name: 'total_montant', className: 'text-end', render: (v) => fmt(v) },
                    {
                        data: null,
                        name: 'actions',
                        className: 'text-center text-nowrap',
                        orderable: false,
                        searchable: false,
                        render: function (_v, _type, row) {
                            const payload = encodeURIComponent(JSON.stringify(row.payload || {}));
                            const canEdit = @json((bool) $canEdit);
                            const isLeaf = !!row.is_leaf;
                            const editItem = (isLeaf && canEdit && row.id)
                                ? '<li><a class="dropdown-item btn-edit" data-engagement_id="' + escapeHtml(row.id) + '" data-bs-target="#editModal" data-bs-toggle="modal" href="#"><i class="demo-psi-pen-5 me-2"></i> Éditer</a></li>'
                                : '';

                            return (
                                '<div class="btn-group">' +
                                '  <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">' +
                                '    <i class="demo-psi-two-column-layout me-1"></i> Actions' +
                                '  </button>' +
                                '  <ul class="dropdown-menu dropdown-menu-end">' +
                                '    <li><a class="dropdown-item btn-engagement-details" href="#" data-engagement="' + payload + '">' +
                                '      <i class="demo-psi-eye me-2"></i> Afficher les détails' +
                                '    </a></li>' +
                                     editItem +
                                '  </ul>' +
                                '</div>'
                            );
                        }
                    }
                ],
                drawCallback: function () {
                    applyExportsHref();
                }
            });
        }

        if (banqueEl) banqueEl.addEventListener('change', applyExportsHref);
        if (searchEl) searchEl.addEventListener('input', function () {
            window.clearTimeout(searchEl._t);
            searchEl._t = window.setTimeout(applyExportsHref, 250);
        });
        if (resetBtn) resetBtn.addEventListener('click', function () {
            if (banqueEl) banqueEl.value = '';
            if (searchEl) searchEl.value = '';
            if (engagementTable) {
                engagementTable.state.page = 0;
                engagementTable.reload();
            }
            applyExportsHref();
        });

        applyExportsHref();
    })();

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-engagement-details');
        if (!btn) return;
        e.preventDefault();
        let data = {};
        try {
            const raw = btn.getAttribute('data-engagement') || '';
            data = raw ? JSON.parse(decodeURIComponent(raw)) : {};
        } catch (_) {
            data = {};
        }

        document.getElementById('detailsEngagementName').textContent = data.name || '—';
        const content = document.getElementById('detailsEngagementContent');
        if (data.elts && data.elts.length > 0) {
            let html = '<div class="table-responsive"><table class="table table-sm table-bordered"><thead class="table-light"><tr><th>Banque</th><th class="text-end">Encours</th><th class="text-end">Impayés</th><th>Date encours</th><th class="text-end">Sollicité</th><th>Date sollicité</th></tr></thead><tbody>';
            data.elts.forEach(elt => {
                html += '<tr><td>' + (elt.banque_name || '—') + '</td><td class="text-end">' + new Intl.NumberFormat('fr-FR').format(elt.encours_montant || 0) + '</td><td class="text-end">' + new Intl.NumberFormat('fr-FR').format(elt.encours_impaye || 0) + '</td><td>' + (elt.encours_dt_validite || '—') + '</td><td class="text-end">' + new Intl.NumberFormat('fr-FR').format(elt.sollicite_montant || 0) + '</td><td>' + (elt.sollicite_dt_validite || '—') + '</td></tr>';
            });
            html += '</tbody></table></div>';
            content.innerHTML = html;
        } else if (data.children && data.children.length > 0) {
            let html = '<div class="table-responsive"><table class="table table-sm table-bordered"><thead class="table-light"><tr><th>Sous-engagement</th><th class="text-end">Encours</th><th class="text-end">Impayés</th><th class="text-end">Sollicité</th><th class="text-end">Variation</th></tr></thead><tbody>';
            data.children.forEach(child => {
                html += '<tr><td>' + (child.name || '—') + '</td><td class="text-end">' + new Intl.NumberFormat('fr-FR').format(child.encours_montant || 0) + '</td><td class="text-end">' + new Intl.NumberFormat('fr-FR').format(child.encours_impaye || 0) + '</td><td class="text-end">' + new Intl.NumberFormat('fr-FR').format(child.sollicite_montant || 0) + '</td><td class="text-end">' + new Intl.NumberFormat('fr-FR').format(child.variation || 0) + '</td></tr>';
            });
            html += '<tr class="table-light fw-semibold"><td>Total</td><td class="text-end">' + new Intl.NumberFormat('fr-FR').format(data.encours_montant || 0) + '</td><td class="text-end">' + new Intl.NumberFormat('fr-FR').format(data.encours_impaye || 0) + '</td><td class="text-end">' + new Intl.NumberFormat('fr-FR').format(data.sollicite_montant || 0) + '</td><td class="text-end">' + new Intl.NumberFormat('fr-FR').format(data.variation || 0) + '</td></tr></tbody></table></div>';
            content.innerHTML = html;
        } else {
            content.innerHTML = '<div class="alert alert-info mb-0">Aucun détail enregistré pour cet engagement.</div>';
        }
        new bootstrap.Modal(document.getElementById('engagementDetailsModal')).show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit');
        if (!btn) return;
        const el = document.getElementById('engagement_id');
        if (el) el.value = btn.dataset.engagement_id || '';
    });
</script>

<style>
    .engagement-table { border-collapse: collapse; }
    .engagement-table th,
    .engagement-table td { border: 1px solid #dee2e6; font-size: 0.875rem; }
</style>
