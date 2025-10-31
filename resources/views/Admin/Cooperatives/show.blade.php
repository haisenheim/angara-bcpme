@extends('Layouts.admin')

@section('title', 'Détails coopérative')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('admin.cooperatives.index') }}">Coopératives</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection

@section('actions')
    <a href="{{ route('admin.cooperatives.edit', $item->token) }}" class="btn btn-info btn-sm">
        <i class="demo-psi-pen-5 me-2"></i> Modifier
    </a>
    <button onclick="deleteCooperative()" class="btn btn-danger btn-sm">
        <i class="demo-psi-trash me-2"></i> Supprimer
    </button>
@endsection

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
            <p class="lead">Détails de la coopérative</p>
        </div>
        <div>
            <img src="{{ $item->photo }}" alt="{{ $item->name }}"
                 class="img-thumbnail" style="max-width: 100px; max-height: 100px;"
                 onerror="this.src='/img/logo.png'">
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="demo-psi-information me-2"></i> Informations générales</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <small class="text-muted">Nom</small>
                            <p class="mb-0"><strong>{{ $item->name }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Téléphone</small>
                            <p class="mb-0">{{ $item->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Email</small>
                            <p class="mb-0">{{ $item->email ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Domaine d'activité</small>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ $item->domaine->name ?? 'N/A' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Date de création</small>
                            <p class="mb-0">{{ $item->dtn ? $item->dtn->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <small class="text-muted">Adresse</small>
                            <p class="mb-0">{{ $item->address ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">Région</small>
                            <p class="mb-0">{{ $item->region->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Département</small>
                            <p class="mb-0">{{ $item->departement->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Arrondissement</small>
                            <p class="mb-0">{{ $item->arrondissement->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Members Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="demo-psi-male me-2"></i> Membres de la coopérative</h6>
                    <span class="badge bg-primary" id="membres-count">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                    </span>
                </div>
                <div class="card-body">
                    <div id="membres-list">
                        <div class="text-center py-4">
                            <span class="spinner-border spinner-border-sm" role="status"></span> Chargement...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Entrepots Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="demo-psi-home me-2"></i> Entrepôts</h6>
                    <span class="badge bg-success" id="entrepots-count">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                    </span>
                </div>
                <div class="card-body">
                    <div id="entrepots-list">
                        <div class="text-center py-4">
                            <span class="spinner-border spinner-border-sm" role="status"></span> Chargement...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="demo-psi-bar-chart me-2"></i> Statistiques</h6>
                </div>
                <div class="card-body" id="stats-container">
                    <div class="text-center py-4">
                        <span class="spinner-border spinner-border-sm" role="status"></span> Chargement...
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="demo-psi-wallet-3 me-2"></i> Portefeuilles</h6>
                </div>
                <div class="card-body">
                    <div id="wallets-list">
                        <div class="text-center py-4">
                            <span class="spinner-border spinner-border-sm" role="status"></span> Chargement...
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="demo-psi-coin me-2"></i> Caisses</h6>
                </div>
                <div class="card-body">
                    <div id="caisses-list">
                        <div class="text-center py-4">
                            <span class="spinner-border spinner-border-sm" role="status"></span> Chargement...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const cooperativeToken = '{{ $item->token }}';

        document.addEventListener('DOMContentLoaded', function() {
            loadStatistics();
            loadMembres();
            loadEntrepots();
            loadWallets();
            loadCaisses();
        });

        function loadStatistics() {
            fetch(`/admin/cooperatives/${cooperativeToken}/stats`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('stats-container');
                    container.innerHTML = `
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">
                                    <i class="demo-psi-male text-primary me-2"></i>Membres
                                </span>
                                <span class="badge bg-primary rounded-pill">${data.total_membres || 0}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">
                                    <i class="demo-psi-home text-success me-2"></i>Entrepôts
                                </span>
                                <span class="badge bg-success rounded-pill">${data.total_entrepots || 0}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">
                                    <i class="demo-psi-coin text-warning me-2"></i>Caisses
                                </span>
                                <span class="badge bg-warning rounded-pill">${data.total_caisses || 0}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">
                                    <i class="demo-psi-wallet-3 text-info me-2"></i>Portefeuilles
                                </span>
                                <span class="badge bg-info rounded-pill">${data.total_wallets || 0}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">
                                    <i class="demo-psi-box-with-folders text-secondary me-2"></i>Stock total
                                </span>
                                <span class="badge bg-secondary rounded-pill">${data.stock_total || 0} kg</span>
                            </div>
                        </div>
                    `;

                    document.getElementById('membres-count').textContent = data.total_membres || 0;
                    document.getElementById('entrepots-count').textContent = data.total_entrepots || 0;
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                    document.getElementById('stats-container').innerHTML = `
                        <p class="text-danger small">Erreur de chargement</p>
                    `;
                });
        }

        function loadMembres() {
            // Implementation depends on your member endpoint
            const container = document.getElementById('membres-list');
            container.innerHTML = '<p class="text-muted small">Liste des membres disponible prochainement</p>';
        }

        function loadEntrepots() {
            // Implementation depends on your entrepot endpoint
            const container = document.getElementById('entrepots-list');
            container.innerHTML = '<p class="text-muted small">Liste des entrepôts disponible prochainement</p>';
        }

        function loadWallets() {
            const container = document.getElementById('wallets-list');
            container.innerHTML = '<p class="text-muted small">Liste des portefeuilles disponible prochainement</p>';
        }

        function loadCaisses() {
            const container = document.getElementById('caisses-list');
            container.innerHTML = '<p class="text-muted small">Liste des caisses disponible prochainement</p>';
        }

        function deleteCooperative() {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette coopérative ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.cooperatives.destroy", $item->token) }}';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection

