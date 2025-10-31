@extends('Layouts.admin')

@section('title', 'Coopératives')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Coopératives</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des coopératives</li>
    </ol>
 </nav>
@endsection



@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Gestion des coopératives</h5>
        <p class="lead">Liste de toutes les coopératives</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Liste des coopératives</h6>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Rechercher...">
                    <button class="btn btn-sm btn-outline-primary" onclick="loadCooperatives()">
                        <i class="demo-psi-refresh-2"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="cooperativesTable">
                <thead>
                    <tr>
                        <th>#</th>
                            <th>Coopérative</th>
                            <th>Téléphone</th>
                            <th>Domaine</th>
                        <th>Arrondissement</th>
                            <th>Département</th>
                            <th>Région</th>
                            <th>Actions</th>
                    </tr>
                </thead>
                    <tbody id="cooperativesTableBody">
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <span class="spinner-border spinner-border-sm" role="status"></span> Chargement des données...
                            </td>
                        </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <script>
        let cooperativesData = [];

        // Load cooperatives on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCooperatives();
        });

        function loadCooperatives() {
            fetch('{{ route("admin.cooperatives.fetchAll") }}')
                .then(response => response.json())
                .then(data => {
                    cooperativesData = data;
                    renderCooperatives(data);
                })
                .catch(error => {
                    console.error('Error loading cooperatives:', error);
                    document.getElementById('cooperativesTableBody').innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="demo-psi-exclamation text-danger fs-1"></i>
                                <p class="text-danger mt-2">Erreur de chargement des données</p>
                            </td>
                        </tr>
                    `;
                });
        }

        function renderCooperatives(data) {
            const tbody = document.getElementById('cooperativesTableBody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="demo-psi-file-html text-muted fs-1"></i>
                            <p class="text-muted mt-2">Aucune coopérative trouvée</p>
                        </td>
                    </tr>
                `;
                return;
            }

            data.forEach((item, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="${item.photo || '/img/logo.png'}" alt="${item.name}"
                                     class="img-xs rounded-circle me-2"
                                     onerror="this.src='/img/logo.png'">
                                <strong>${item.name}</strong>
                            </div>
                        </td>
                        <td>${item.phone || '-'}</td>
                        <td>${item.filiere || '-'}</td>
                        <td>${item.commune || '-'}</td>
                        <td>${item.departement || '-'}</td>
                        <td>${item.region || '-'}</td>

                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/admin/cooperatives/${item.token}" class="btn btn-sm btn-outline-primary" title="Voir">
                                    <i class="demo-psi-eye"></i>
                                </a>
                                <a href="/admin/cooperatives/${item.token}/edit" class="btn btn-sm btn-outline-info" title="Modifier">
                                    <i class="demo-psi-pen-5"></i>
                                </a>
                                <button onclick="deleteCooperative('${item.token}', '${item.name}')"
                                        class="btn btn-sm btn-outline-danger" title="Supprimer">
                                    <i class="demo-psi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const filtered = cooperativesData.filter(item =>
                    (item.name && item.name.toLowerCase().includes(searchTerm)) ||
                    (item.phone && item.phone.includes(searchTerm)) ||
                    (item.arrondissement?.name && item.arrondissement.name.toLowerCase().includes(searchTerm))
                );
                renderCooperatives(filtered);
            });
        });

        function deleteCooperative(token, name) {
            if (confirm(`Êtes-vous sûr de vouloir supprimer la coopérative "${name}" ?`)) {
                fetch(`/admin/cooperatives/${token}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Coopérative supprimée avec succès!');
                        loadCooperatives();
                    } else {
                        alert('Erreur lors de la suppression');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la suppression');
                });
            }
        }
    </script>

    <style>
        .img-xs {
            width: 2rem;
            height: 2rem;
            object-fit: cover;
        }
    </style>
@endsection
