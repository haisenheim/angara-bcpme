@extends('Layouts.analyste')

@section('title', 'Entreprises')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Entreprises</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Entreprises</h5>
        <p class="lead mb-0">Entreprises pour lesquelles vous avez des dossiers d'instruction</p>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 py-3">
            <h6 class="mb-0 fw-semibold">Filtres</h6>
        </div>
        <div class="card-body">
            <form id="filter-form" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Recherche</label>
                    <input type="text" name="search" id="filter-search" class="form-control" placeholder="Nom, RCCM, NIU, Dirigeant...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Région</label>
                    <select name="region_id" id="filter-region" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($regions as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Département</label>
                    <select name="departement_id" id="filter-departement" class="form-select">
                        <option value="">Tous</option>
                        @foreach($departements as $d)
                            <option value="{{ $d->id }}" data-region="{{ $d->region_id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Forme juridique</label>
                    <select name="forme_id" id="filter-forme" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($formes as $f)
                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Par page</label>
                    <select name="per_page" id="per-page" class="form-select">
                        <option value="10">10</option>
                        <option value="15" selected>15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="demo-psi-magnifi-glass me-1"></i> Filtrer
                    </button>
                    <button type="button" id="btn-reset" class="btn btn-outline-secondary">Réinitialiser</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-3">
        <div class="card-body">
            <div id="table-loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement des données...</p>
            </div>
            <div id="table-container" class="d-none">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Désignation</th>
                                <th>RCCM</th>
                                <th>NIU</th>
                                <th>Dirigeant</th>
                                <th>Région</th>
                                <th>Forme</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                        </tbody>
                    </table>
                </div>
                <div id="pagination-container" class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                </div>
            </div>
            <div id="table-empty" class="text-center py-5 d-none">
                <i class="demo-psi-inbox text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3 text-muted">Aucune entreprise trouvée</p>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
(function() {
    const url = "{{ route('analyste.entreprises.paginated') }}";
    let searchTimeout;

    function getFilters() {
        return {
            search: document.getElementById('filter-search').value.trim(),
            region_id: document.getElementById('filter-region').value || '',
            departement_id: document.getElementById('filter-departement').value || '',
            forme_id: document.getElementById('filter-forme').value || '',
            page: 1,
            per_page: document.getElementById('per-page')?.value || 15
        };
    }

    function buildQuery(params) {
        const q = new URLSearchParams();
        Object.entries(params).forEach(([k, v]) => { if (v) q.set(k, v); });
        return q.toString();
    }

    function renderRow(item) {
        const link = "{{ route('analyste.entreprises.index') }}/" + item.token;
        return `
            <tr>
                <td><a href="${link}" class="text-decoration-none fw-medium">${escapeHtml(item.name || '—')}</a></td>
                <td>${escapeHtml(item.rccm || '—')}</td>
                <td>${escapeHtml(item.niu || '—')}</td>
                <td>${escapeHtml(item.manager || '—')}</td>
                <td>${escapeHtml(item.region || '—')}</td>
                <td>${escapeHtml(item.forme || '—')}</td>
                <td class="text-end">
                    <a href="${link}" class="btn btn-sm btn-outline-primary"><i class="demo-psi-eye"></i></a>
                </td>
            </tr>
        `;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function renderPagination(pagination) {
        const { current_page, last_page, total, from, to } = pagination;
        if (last_page <= 1) return '';

        let html = `<nav><ul class="pagination pagination-sm mb-0">`;

        html += `<li class="page-item ${current_page <= 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${current_page - 1}" aria-label="Précédent">‹</a>
        </li>`;

        const start = Math.max(1, current_page - 2);
        const end = Math.min(last_page, current_page + 2);
        for (let i = start; i <= end; i++) {
            html += `<li class="page-item ${i === current_page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        html += `<li class="page-item ${current_page >= last_page ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${current_page + 1}" aria-label="Suivant">›</a>
        </li>`;
        html += '</ul></nav>';

        const info = total > 0
            ? `Affichage de ${from} à ${to} sur ${total}`
            : 'Aucun résultat';
        return `<div class="text-muted small">${info}</div>${html}`;
    }

    function loadData(page = 1) {
        const params = getFilters();
        params.page = page;

        document.getElementById('table-loading').classList.remove('d-none');
        document.getElementById('table-container').classList.add('d-none');
        document.getElementById('table-empty').classList.add('d-none');

        fetch(url + '?' + buildQuery(params), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(res => {
            document.getElementById('table-loading').classList.add('d-none');

            const tbody = document.getElementById('table-body');
            tbody.innerHTML = res.data.map(renderRow).join('');

            if (res.data.length === 0) {
                document.getElementById('table-empty').classList.remove('d-none');
            } else {
                document.getElementById('table-container').classList.remove('d-none');
                const pagEl = document.getElementById('pagination-container');
                pagEl.innerHTML = renderPagination(res.pagination);
                pagEl.querySelectorAll('.page-link').forEach(link => {
                    link.addEventListener('click', e => {
                        e.preventDefault();
                        const p = parseInt(link.dataset.page, 10);
                        if (p >= 1 && p <= res.pagination.last_page) loadData(p);
                    });
                });
            }
        })
        .catch(err => {
            document.getElementById('table-loading').classList.add('d-none');
            document.getElementById('table-empty').classList.remove('d-none');
            document.getElementById('table-empty').querySelector('p').textContent = 'Erreur lors du chargement.';
        });
    }

    document.getElementById('filter-form').addEventListener('submit', e => {
        e.preventDefault();
        loadData(1);
    });

    document.getElementById('btn-reset').addEventListener('click', () => {
        document.getElementById('filter-search').value = '';
        document.getElementById('filter-region').value = '';
        document.getElementById('filter-departement').value = '';
        document.getElementById('filter-forme').value = '';
        loadData(1);
    });

    document.getElementById('per-page').addEventListener('change', () => loadData(1));

    document.getElementById('filter-region').addEventListener('change', () => {
        const regionId = document.getElementById('filter-region').value;
        const opts = document.getElementById('filter-departement').querySelectorAll('option');
        opts.forEach(o => {
            if (o.value === '') {
                o.style.display = '';
            } else {
                o.style.display = (!regionId || o.dataset.region === regionId) ? '' : 'none';
            }
        });
        document.getElementById('filter-departement').value = '';
    });

    loadData(1);
})();
</script>
@endsection
