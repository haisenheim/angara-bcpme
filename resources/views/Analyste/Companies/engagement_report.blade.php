@extends('Layouts.analyste')

@section('title', 'État des engagements')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('analyste.entreprises.show', $entreprise->token) }}">{{ Str::limit($entreprise->name, 35) }}</a></li>
       <li class="breadcrumb-item active" aria-current="page">État des engagements</li>
    </ol>
</nav>
@endsection

@section('actions')
<div class="btn-group">
    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="demo-psi-two-column-layout me-1"></i> Actions
    </button>
    <ul class="dropdown-menu">
        <li><a data-sequence="1" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Brèves données générales actualisées sur l'emprunteur</a></li>
        <li><a data-sequence="2" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Analyse critique d'ensemble</a></li>
    </ul>
</div>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">État des engagements</h5>
        <p class="text-body-secondary mb-0 mt-1">{{ $entreprise->name }}</p>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="mb-0 fw-semibold"><i class="demo-psi-file-text-image me-2 text-primary"></i>Répartition des engagements</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 engagement-table">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2" class="align-middle">Engagement</th>
                            <th colspan="3" class="text-center border-start">En cours</th>
                            <th colspan="3" class="text-center border-start">Sollicités</th>
                            <th colspan="2" class="text-center border-start">Total</th>
                            <th rowspan="2" class="align-middle text-center" style="min-width: 100px;">Actions</th>
                        </tr>
                        <tr>
                            <th class="text-end border-start">Montant</th>
                            <th class="text-end">Impayés</th>
                            <th class="text-center">Date validité</th>
                            <th class="text-end border-start">Montant</th>
                            <th class="text-center">Date validité</th>
                            <th class="text-end">Variation</th>
                            <th class="text-end border-start">Montant</th>
                            <th class="text-center">Date validité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($engagements as $eng)
                            <x-engagement :eng="json_encode($eng)" :can-edit="true" />
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Détails --}}
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

    {{-- Modal Éditer (Analyste uniquement) --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Éditer l'engagement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('analyste.entreprise.set.engagement') }}" method="post">
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

    <script>
        document.querySelectorAll('.btn-engagement-details').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const data = JSON.parse(this.dataset.engagement || '{}');
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
        });
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('engagement_id').value = this.dataset.engagement_id || '';
            });
        });
    </script>

    <style>
        .engagement-table th { font-weight: 600; font-size: 0.8rem; }
        .engagement-table td { font-size: 0.875rem; }
    </style>
@endsection
