@extends($workspaceLayout ?? 'Layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/engagements-grid.css') }}">
@endpush

@php
    /** @var \App\Models\Entreprise $entreprise */
    /** @var array $tree */
    /** @var array $stats */
    /** @var array $partenaires */
    /** @var \Illuminate\Support\Collection $produits */
    /** @var array<string,string> $statuts */
    /** @var array<string,string> $kindLabels */
    $entityListLabel = in_array($workspaceRoutePrefix ?? '', ['dg', 'dga'], true) ? 'Clients' : 'Entreprises';
    $statutsJs = json_encode($statuts);
    $partenairesJs = json_encode($partenaires);
@endphp

@section('title', 'Grille des engagements — '.$entreprise->name)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            @if($workspaceRoutePrefix && \Illuminate\Support\Facades\Route::has($workspaceRoutePrefix.'.dashboard'))
                <li class="breadcrumb-item"><a href="{{ route($workspaceRoutePrefix.'.dashboard') }}">Tableau de bord</a></li>
            @endif
            @if($workspaceRoutePrefix && \Illuminate\Support\Facades\Route::has($workspaceRoutePrefix.'.entreprises.index'))
                <li class="breadcrumb-item"><a href="{{ route($workspaceRoutePrefix.'.entreprises.index') }}">{{ $entityListLabel }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">Grille des engagements</li>
        </ol>
    </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Grille des engagements</h5>
        <p class="text-body-secondary mb-0 mt-1">
            <strong>{{ $entreprise->name }}</strong>
            @if($entreprise->niu)<span class="text-muted">— NIU&nbsp;{{ $entreprise->niu }}</span>@endif
        </p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong>Erreur de saisie :</strong>
            <ul class="mb-0 small">
                @foreach($errors->all() as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <div class="row g-3 mb-4 engagement-stats">
        <div class="col-md col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase d-block mb-1">Encours actuel</small>
                    <p class="mb-0 fw-semibold fs-5" data-stat="encours_actuel">
                        {{ number_format($stats['encours_actuel'], 0, ',', ' ') }}
                    </p>
                    <small class="text-muted">XAF</small>
                </div>
            </div>
        </div>
        <div class="col-md col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase d-block mb-1">Sollicité</small>
                    <p class="mb-0 fw-semibold fs-5" data-stat="sollicite_montant">
                        {{ number_format($stats['sollicite_montant'], 0, ',', ' ') }}
                    </p>
                    <small class="text-muted">XAF</small>
                </div>
            </div>
        </div>
        <div class="col-md col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase d-block mb-1">Total engagement</small>
                    <p class="mb-0 fw-semibold fs-5 text-primary" data-stat="total_montant">
                        {{ number_format($stats['total_montant'], 0, ',', ' ') }}
                    </p>
                    <small class="text-muted">XAF</small>
                </div>
            </div>
        </div>
        <div class="col-md col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase d-block mb-1">Variation</small>
                    <p class="mb-0 fw-semibold fs-5" data-stat="variation">
                        {{ number_format($stats['variation'], 0, ',', ' ') }}
                    </p>
                    <small class="text-muted">XAF</small>
                </div>
            </div>
        </div>
        <div class="col-md col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase d-block mb-1">Impayés</small>
                    <p class="mb-0 fw-semibold fs-5 text-danger" data-stat="encours_impayes">
                        {{ number_format($stats['encours_impayes'], 0, ',', ' ') }}
                    </p>
                    <small class="text-muted">XAF</small>
                </div>
            </div>
        </div>
        <div class="col-md col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase d-block mb-1">Lignes saisies</small>
                    <p class="mb-0 fw-semibold fs-5" data-stat="nb_lignes">
                        {{ $stats['nb_lignes'] }}
                    </p>
                    <small class="text-muted">partenaires confondus</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h6 class="mb-0 fw-semibold">
                        <i class="demo-psi-file-text-image me-2 text-primary"></i>
                        Grille d'engagements
                    </h6>
                    <small class="text-body-secondary">
                        Sections, rubriques et produits — saisie par partenaire financier (banque, EMF ou autre).
                    </small>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-end">
                    <div class="engagement-partenaire-search-group">
                        <select id="engagementPartenaireFilter" class="form-select form-select-sm engagement-partenaire-search-select">
                            <option value="">Tous partenaires</option>
                            @foreach(['banque' => 'Banques', 'emf' => 'EMF', 'autre' => 'Autres partenaires'] as $kind => $kindLabel)
                                @if(! empty($partenaires[$kind === 'banque' ? 'banques' : ($kind === 'emf' ? 'emfs' : 'autres')]))
                                    <optgroup label="{{ $kindLabel }}">
                                        @foreach($partenaires[$kind === 'banque' ? 'banques' : ($kind === 'emf' ? 'emfs' : 'autres')] as $partenaire)
                                            <option value="{{ $partenaire->id }}">{{ $partenaire->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                        <input id="engagementSearchInput" type="search" class="form-control form-control-sm engagement-partenaire-search-input" placeholder="Rechercher…" autocomplete="off">
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="engagementResetFilters">
                        Réinitialiser
                    </button>

                    <div class="vr d-none d-md-block mx-1"></div>

                    @if($canEdit)
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#partenaireModal">
                            <i class="demo-psi-add me-1"></i>Nouveau partenaire
                        </button>
                    @endif
                    <div class="d-flex flex-wrap align-items-center gap-1">
                        @include('engagements.partials.export-dropdowns')
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive engagement-grid-wrapper">
                @include('engagements.partials.grid', [
                    'tree' => $tree,
                    'canEdit' => $canEdit,
                    'entreprise' => $entreprise,
                ])
            </div>
        </div>
    </div>

    @if($canEdit)
        @include('engagements.partials.ligne-modal', [
            'entreprise' => $entreprise,
            'produits' => $produits,
            'partenaires' => $partenaires,
            'statuts' => $statuts,
            'kindLabels' => $kindLabels,
        ])
        @include('engagements.partials.partenaire-modal', [
            'kindLabels' => $kindLabels,
        ])
    @endif

    @include('engagements.partials.commentaire-modal')
    @include('engagements.partials.ligne-detail-modal')

@endsection

@push('scripts')
    <script>
        (function () {
            'use strict';

            const dataUrl = @json(route('engagements.data', $entreprise->token));
            const engagementExportBaseUrl = @json(route('engagements.export', $entreprise->token));
            const partenairesUrl = @json(route('engagements.partenaires.store'));
            const canEdit = @json($canEdit);
            const wrapper = document.querySelector('.engagement-grid-wrapper');
            const filterEl = document.getElementById('engagementPartenaireFilter');
            const searchEl = document.getElementById('engagementSearchInput');
            const resetEl = document.getElementById('engagementResetFilters');

            let debounce;

            function fmt(v) {
                try { return new Intl.NumberFormat('fr-FR').format(Number(v || 0)); } catch (_) { return String(v || 0); }
            }

            function escapeHtml(s) {
                return String(s ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function fmtDate(s) {
                if (! s) return '—';
                const m = String(s).match(/^(\d{4})-(\d{2})-(\d{2})/);
                if (m) return m[3] + '/' + m[2] + '/' + m[1];
                return s;
            }

            function statutLabel(code) {
                if (! code) return '';
                const map = @json($statuts);
                return map[code] || code;
            }

            function renderTree(tree) {
                let html = '';
                const rowClassByType = {
                    section: 'engagement-row-section',
                    rubrique: 'engagement-row-rubrique',
                    nature: 'engagement-row-nature',
                    produit: 'engagement-row-produit',
                };
                const renderNode = function (node, depth) {
                    const indent = depth * 18;
                    const cls = rowClassByType[node.type] || '';
                    const totalMontant = (node.encours_actuel || 0) + (node.sollicite_montant || 0);
                    const variation = (node.sollicite_montant || 0) - (node.encours_actuel || 0);

                    html += '<tr class="engagement-grid-row '+ cls +'">';
                    html += '<td class="engagement-libelle"><span style="padding-left:'+ indent +'px;">'+ escapeHtml(node.libelle) +'</span></td>';
                    html += '<td>—</td>';
                    html += '<td class="text-end">'+ fmt(node.encours_initial) +'</td>';
                    html += '<td class="text-end">'+ fmt(node.encours_actuel) +'</td>';
                    html += '<td class="text-end">'+ fmt(node.encours_remboursement_n1) +'</td>';
                    html += '<td class="text-end">'+ fmt(node.encours_retards) +'</td>';
                    html += '<td class="text-end engagement-impayes">'+ fmt(node.encours_impayes) +'</td>';
                    html += '<td>—</td>';
                    html += '<td>—</td>';
                    html += '<td class="text-end">'+ fmt(node.sollicite_montant) +'</td>';
                    html += '<td>—</td>';
                    html += '<td class="text-end engagement-total">'+ fmt(totalMontant) +'</td>';
                    html += '<td class="text-end">'+ fmt(variation) +'</td>';
                    html += '<td class="engagement-actions">';
                    if (node.is_leaf && canEdit) {
                        html += '<button type="button" class="btn btn-xs btn-outline-primary" data-bs-toggle="modal" data-bs-target="#engagementLigneModal" data-action="create" data-categorie-id="'+ node.id +'" data-categorie-libelle="'+ escapeHtml(node.libelle) +'"><i class="demo-psi-add"></i></button>';
                    }
                    html += '</td>';
                    html += '</tr>';

                    (node.lignes || []).forEach(function (ligne) {
                        const totalLigne = (ligne.encours_actuel || 0) + (ligne.sollicite_montant || 0);
                        html += '<tr class="engagement-grid-row engagement-row-ligne" data-ligne-id="'+ ligne.id +'">';
                        html += '<td class="engagement-libelle"><span style="padding-left:'+ (indent + 24) +'px;" class="text-muted small">↳ '+ escapeHtml(ligne.partenaire_nom || '—') +'</span></td>';
                        html += '<td><span class="badge bg-light text-dark border">'+ escapeHtml(ligne.partenaire_kind_label || '—') +'</span></td>';
                        html += '<td class="text-end">'+ fmt(ligne.encours_initial) +'</td>';
                        html += '<td class="text-end">'+ fmt(ligne.encours_actuel) +'</td>';
                        html += '<td class="text-end">'+ fmt(ligne.encours_remboursement_n1) +'</td>';
                        html += '<td class="text-end">'+ fmt(ligne.encours_retards) +'</td>';
                        html += '<td class="text-end engagement-impayes">'+ fmt(ligne.encours_impayes) +'</td>';
                        html += '<td>'+ escapeHtml(statutLabel(ligne.encours_statut) || '—') +'</td>';
                        html += '<td>'+ fmtDate(ligne.encours_date_validite) +'</td>';
                        html += '<td class="text-end">'+ fmt(ligne.sollicite_montant) +'</td>';
                        html += '<td>'+ fmtDate(ligne.sollicite_date_validite) +'</td>';
                        html += '<td class="text-end engagement-total">'+ fmt(totalLigne) +'</td>';
                        html += '<td class="text-end">'+ fmt((ligne.sollicite_montant || 0) - (ligne.encours_actuel || 0)) +'</td>';
                        html += '<td class="engagement-actions text-nowrap">';
                        const detailPayload = JSON.stringify(ligne).replace(/"/g, '&quot;');
                        html += '<button type="button" class="btn btn-xs btn-primary me-1" data-bs-toggle="modal" data-bs-target="#engagementLigneDetailModal" data-ligne="'+ detailPayload +'" title="Consulter le détail"><i class="demo-psi-eye"></i></button>';
                        if (ligne.commentaire) {
                            html += '<button type="button" class="btn btn-xs btn-outline-info me-1" data-bs-toggle="modal" data-bs-target="#engagementCommentaireModal" data-commentaire="'+ escapeHtml(ligne.commentaire) +'" title="Commentaire"><i class="demo-psi-speech-bubble"></i></button>';
                        }
                        if (canEdit) {
                            const payload = JSON.stringify(ligne).replace(/"/g, '&quot;');
                            html += '<button type="button" class="btn btn-xs btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#engagementLigneModal" data-action="edit" data-ligne="'+ payload +'"><i class="demo-psi-pen-5"></i></button>';
                            const deleteUrl = '{{ route('engagements.lignes.destroy', ['token' => $entreprise->token, 'ligne' => '__ID__']) }}'.replace('__ID__', ligne.id);
                            html += '<form method="post" action="'+ deleteUrl +'" class="d-inline" onsubmit="return confirm(\'Supprimer cette ligne ?\');">'
                                + '@csrf @method('DELETE')'
                                + '<button type="submit" class="btn btn-xs btn-outline-danger"><i class="demo-psi-trash"></i></button>'
                                + '</form>';
                        }
                        html += '</td>';
                        html += '</tr>';
                    });

                    (node.children || []).forEach(function (child) {
                        renderNode(child, depth + 1);
                    });
                };

                tree.forEach(function (root) { renderNode(root, 0); });

                if (! html) {
                    html = '<tr><td colspan="14" class="text-center text-muted py-4">Aucun engagement à afficher.</td></tr>';
                }
                return html;
            }

            function refresh() {
                const params = new URLSearchParams();
                if (filterEl && filterEl.value) params.set('partenaire_id', filterEl.value);
                if (searchEl && searchEl.value.trim()) params.set('search', searchEl.value.trim());

                fetch(dataUrl + (params.toString() ? '?' + params.toString() : ''), {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin',
                })
                    .then(function (resp) {
                        if (! resp.ok) throw new Error('HTTP ' + resp.status);
                        return resp.json();
                    })
                    .then(function (json) {
                        const tbody = wrapper.querySelector('tbody');
                        if (tbody) tbody.innerHTML = renderTree(json.tree || []);
                        const stats = json.stats || {};
                        Object.keys(stats).forEach(function (k) {
                            const el = document.querySelector('[data-stat="' + k + '"]');
                            if (el) {
                                el.textContent = (k === 'nb_lignes') ? String(stats[k] || 0) : fmt(stats[k]);
                            }
                        });
                    })
                    .catch(function (err) {
                        console.error('engagements: refresh failed', err);
                    });
            }

            function debouncedRefresh() {
                window.clearTimeout(debounce);
                debounce = window.setTimeout(refresh, 250);
            }

            if (filterEl) filterEl.addEventListener('change', refresh);
            if (searchEl) searchEl.addEventListener('input', debouncedRefresh);
            function buildEngagementExportUrl(format, report) {
                const params = new URLSearchParams();
                params.set('format', format);
                params.set('report', report);
                if (filterEl && filterEl.value) {
                    params.set('partenaire_id', filterEl.value);
                }
                if (searchEl && searchEl.value.trim()) {
                    params.set('search', searchEl.value.trim());
                }
                return engagementExportBaseUrl + '?' + params.toString();
            }

            document.querySelectorAll('.engagement-export-link').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const format = btn.getAttribute('data-format');
                    const report = btn.getAttribute('data-report');
                    if (! format || ! report) return;
                    window.location.href = buildEngagementExportUrl(format, report);
                });
            });

            if (resetEl) resetEl.addEventListener('click', function () {
                if (filterEl) filterEl.value = '';
                if (searchEl) searchEl.value = '';
                refresh();
            });

            // Pré-remplissage modal édition.
            const ligneModal = document.getElementById('engagementLigneModal');
            if (ligneModal) {
                ligneModal.addEventListener('show.bs.modal', function (event) {
                    const trigger = event.relatedTarget;
                    if (! trigger) return;
                    const action = trigger.getAttribute('data-action') || 'create';
                    const form = ligneModal.querySelector('form');
                    if (! form) return;

                    if (action === 'edit') {
                        const raw = trigger.getAttribute('data-ligne') || '{}';
                        let ligne = {};
                        try { ligne = JSON.parse(raw.replace(/&quot;/g, '"')); } catch (_) {}
                        const baseAction = '{{ route('engagements.lignes.update', ['token' => $entreprise->token, 'ligne' => '__ID__']) }}'.replace('__ID__', ligne.id || 0);
                        form.action = baseAction;
                        form.querySelector('[name="_method"]').value = 'PUT';

                        ligneModal.querySelector('.modal-title').textContent = 'Modifier la ligne';
                        ligneModal.querySelector('[data-mode="categorie-readonly"]').classList.remove('d-none');
                        ligneModal.querySelector('[data-mode="categorie-select"]').classList.add('d-none');

                        ligneModal.querySelector('[data-mode="categorie-readonly"] strong').textContent = (function () {
                            const sel = ligneModal.querySelector('[data-categorie-id-input]');
                            sel.value = ligne.engagement_categorie_id || '';
                            const found = (ligneModal.querySelectorAll('[data-mode="categorie-select"] option') || []);
                            for (const opt of found) {
                                if (String(opt.value) === String(ligne.engagement_categorie_id)) return opt.textContent;
                            }
                            return '';
                        })();

                        form.querySelector('[name="partenaire_id"]').value = ligne.partenaire_id || '';
                        form.querySelector('[name="encours_initial"]').value = ligne.encours_initial || 0;
                        form.querySelector('[name="encours_actuel"]').value = ligne.encours_actuel || 0;
                        form.querySelector('[name="encours_remboursement_n1"]').value = ligne.encours_remboursement_n1 || 0;
                        form.querySelector('[name="encours_retards"]').value = ligne.encours_retards || 0;
                        form.querySelector('[name="encours_impayes"]').value = ligne.encours_impayes || 0;
                        form.querySelector('[name="encours_statut"]').value = ligne.encours_statut || '';
                        form.querySelector('[name="encours_date_validite"]').value = ligne.encours_date_validite || '';
                        form.querySelector('[name="sollicite_montant"]').value = ligne.sollicite_montant || 0;
                        form.querySelector('[name="sollicite_date_validite"]').value = ligne.sollicite_date_validite || '';
                        form.querySelector('[name="commentaire"]').value = ligne.commentaire || '';
                    } else {
                        const baseAction = '{{ route('engagements.lignes.store', ['token' => $entreprise->token]) }}';
                        form.action = baseAction;
                        form.querySelector('[name="_method"]').value = 'POST';

                        ligneModal.querySelector('.modal-title').textContent = 'Nouvelle ligne d\u2019engagement';
                        const categorieId = trigger.getAttribute('data-categorie-id');
                        const categorieLibelle = trigger.getAttribute('data-categorie-libelle') || '';

                        if (categorieId) {
                            ligneModal.querySelector('[data-mode="categorie-readonly"]').classList.remove('d-none');
                            ligneModal.querySelector('[data-mode="categorie-select"]').classList.add('d-none');
                            ligneModal.querySelector('[data-mode="categorie-readonly"] strong').textContent = categorieLibelle;
                            ligneModal.querySelector('[data-categorie-id-input]').value = categorieId;
                        } else {
                            ligneModal.querySelector('[data-mode="categorie-readonly"]').classList.add('d-none');
                            ligneModal.querySelector('[data-mode="categorie-select"]').classList.remove('d-none');
                            ligneModal.querySelector('[data-categorie-id-input]').value = '';
                        }

                        form.reset();
                        form.querySelector('[name="_token"]').value = '{{ csrf_token() }}';
                        form.querySelector('[name="_method"]').value = 'POST';
                        if (categorieId) form.querySelector('[data-categorie-id-input]').value = categorieId;
                    }
                });
            }

            // Modal commentaire (lecture).
            const commentaireModal = document.getElementById('engagementCommentaireModal');
            if (commentaireModal) {
                commentaireModal.addEventListener('show.bs.modal', function (event) {
                    const trigger = event.relatedTarget;
                    if (! trigger) return;
                    const txt = trigger.getAttribute('data-commentaire') || '';
                    commentaireModal.querySelector('[data-commentaire-text]').textContent = txt;
                });
            }

            const ligneDetailModal = document.getElementById('engagementLigneDetailModal');
            if (ligneDetailModal) {
                ligneDetailModal.addEventListener('show.bs.modal', function (event) {
                    const trigger = event.relatedTarget;
                    if (! trigger) return;
                    const raw = trigger.getAttribute('data-ligne') || '{}';
                    let ligne = {};
                    try { ligne = JSON.parse(raw.replace(/&quot;/g, '"')); } catch (_) {}

                    const textOrDash = function (v) {
                        return (v !== undefined && v !== null && String(v).trim() !== '') ? String(v) : '—';
                    };

                    const setText = function (key, value) {
                        const el = ligneDetailModal.querySelector('[data-detail="'+ key +'"]');
                        if (! el) return;
                        el.textContent = textOrDash(value);
                    };

                    setText('categorie', ligne.engagement_categorie_libelle);
                    setText('partenaire_nom', ligne.partenaire_nom);
                    setText('partenaire_kind_label', ligne.partenaire_kind_label);

                    const moneyFields = ['encours_initial', 'encours_actuel', 'encours_remboursement_n1', 'encours_retards', 'encours_impayes', 'sollicite_montant', 'total_montant', 'variation'];
                    moneyFields.forEach(function (key) {
                        const el = ligneDetailModal.querySelector('[data-detail="'+ key +'"]');
                        if (! el) return;
                        el.textContent = fmt(ligne[key] || 0);
                        el.classList.remove('text-danger');
                        if (key === 'encours_impayes' && Number(ligne.encours_impayes || 0) > 0) {
                            el.classList.add('text-danger');
                        }
                    });

                    const statutReadable = ligne.encours_statut_label || statutLabel(ligne.encours_statut) || '';
                    setText('encours_statut_label', statutReadable);

                    const encD = ligneDetailModal.querySelector('[data-detail="encours_date_validite"]');
                    if (encD) encD.textContent = textOrDash(fmtDate(ligne.encours_date_validite));

                    const solD = ligneDetailModal.querySelector('[data-detail="sollicite_date_validite"]');
                    if (solD) solD.textContent = textOrDash(fmtDate(ligne.sollicite_date_validite));

                    const commEl = ligneDetailModal.querySelector('[data-detail="commentaire"]');
                    if (commEl) {
                        const c = ligne.commentaire || '';
                        commEl.textContent = c ? c : '—';
                        commEl.classList.toggle('text-muted', ! c);
                        commEl.classList.toggle('fst-italic', ! c);
                    }

                    const metaEl = ligneDetailModal.querySelector('[data-detail="meta"]');
                    if (metaEl) {
                        const parts = [];
                        if (ligne.updated_by) {
                            parts.push('Dernière mise à jour : ' + ligne.updated_by + (ligne.updated_at ? ' — ' + ligne.updated_at : ''));
                        }
                        metaEl.textContent = parts.join('');
                    }
                });
            }

            // Création d'un partenaire en AJAX.
            const partenaireForm = document.getElementById('engagementPartenaireForm');
            if (partenaireForm) {
                partenaireForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const fd = new FormData(partenaireForm);
                    fetch(partenairesUrl, {
                        method: 'POST',
                        body: fd,
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin',
                    })
                        .then(function (r) { if (! r.ok) throw new Error('HTTP '+ r.status); return r.json(); })
                        .then(function (json) {
                            const p = json.partenaire;
                            // Ajoute dans tous les selects partenaire.
                            document.querySelectorAll('select.engagement-partenaire-select, #engagementPartenaireFilter').forEach(function (sel) {
                                const opt = document.createElement('option');
                                opt.value = p.id;
                                opt.textContent = p.name + ' (' + p.kind_label + ')';
                                sel.appendChild(opt);
                            });
                            partenaireForm.reset();
                            const modal = bootstrap.Modal.getInstance(document.getElementById('partenaireModal'));
                            if (modal) modal.hide();
                        })
                        .catch(function (err) {
                            alert('Erreur lors de la création du partenaire : ' + err.message);
                        });
                });
            }
        })();
    </script>
@endpush
