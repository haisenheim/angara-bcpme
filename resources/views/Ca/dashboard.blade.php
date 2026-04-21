@extends('Layouts.ca')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Tableau de bord</a></li>
       <li class="breadcrumb-item active" aria-current="page">Accueil</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
    <div>
            <h1 class="h3 mb-0">Tableau de bord - Chef d'agence</h1>
            <p class="text-muted mb-0">Bienvenue {{ auth()->user()->name }}, vue d'ensemble de votre agence</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                <i class="demo-psi-printer"></i> Imprimer
            </button>
            <button class="btn btn-primary btn-sm" onclick="refreshDashboard()">
                <i class="demo-psi-refresh-2"></i> Actualiser
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Top Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Dossiers totaux</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-dossiers">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </div>
                                <div class="text-xs mt-1">
                                    <span class="text-success" id="dossiers-en-cours">
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                    </span> en cours
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="pli-folder fs-1 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Portefeuille</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-portfolio">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </div>
                                <div class="text-xs mt-1" id="portfolio-detail">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="pli-bank fs-1 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Équipe active</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-users">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </div>
                                <div class="text-xs mt-1">
                                    Utilisateurs de l'agence
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="pli-conference fs-1 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Prospects actifs</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-prospects">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </div>
                                <div class="text-xs mt-1">
                                    En cours de qualification
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="pli-phone-2 fs-1 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-primary border-opacity-25">
                    <div class="card-body py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h6 class="mb-1 text-primary">Décision chef d'agence</h6>
                            <p class="text-muted small mb-0">
                                Prospects soumis à arbitrage : <strong>{{ $workflowPendingCount ?? 0 }}</strong>
                                &nbsp;·&nbsp;
                                Validations instruction (EER) : <strong>{{ $workflowInstructionCount ?? 0 }}</strong>
                            </p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('ca.workflow.prospects.index') }}" class="btn btn-primary btn-sm">File arbitrage prospects</a>
                            <a href="{{ route('ca.workflow.instructions.index') }}" class="btn btn-outline-secondary btn-sm">Validations instruction</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="row mb-4">
            <div class="col-xl-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Performance de l'agence</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="demo-psi-dot-vertical text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Options:</div>
                                <a class="dropdown-item" href="#">Exporter</a>
                                <a class="dropdown-item" href="#">Actualiser</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Répartition du portefeuille</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="portfolioChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> Entreprises
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Coopératives
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Performance and Recent Activities -->
        <div class="row">
            <!-- Team Performance -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Performance de l'équipe</h6>
                        <a href="{{ route('ca.users.index') }}" class="btn btn-sm btn-primary">
                            Gérer <i class="demo-psi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Membre</th>
                                        <th>Rôle</th>
                                        <th>Dossiers</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody id="team-performance-tbody">
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <span class="spinner-border spinner-border-sm" role="status"></span> Chargement...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Dossiers -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Dossiers récents</h6>
                        <a href="{{ route('ca.dossiers.index') }}" class="btn btn-sm btn-primary">
                            Voir tous <i class="demo-psi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush" id="recent-dossiers-container">
                            <div class="text-center py-4">
                                <span class="spinner-border spinner-border-sm" role="status"></span> Chargement...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions and Statistics -->
        <div class="row">
            <!-- Quick Management Actions -->
            <div class="col-xl-4 col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('ca.entreprises.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="pli-bank me-2"></i>Gérer les entreprises
                            </a>
                            <a href="{{ route('ca.dossiers.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="pli-folder me-2"></i>Suivre les dossiers
                            </a>
                            <a href="{{ route('ca.users.index') }}" class="btn btn-outline-warning btn-sm">
                                <i class="pli-conference me-2"></i>Gérer l'équipe
                            </a>
                            <a href="{{ route('ca.programmes.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="pli-affiliate me-2"></i>Voir les programmes
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Statistics -->
            <div class="col-xl-4 col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Statistiques du mois</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="pli-folder text-primary me-2"></i>
                                    <span class="small">Nouveaux dossiers</span>
                                </div>
                                <span class="badge bg-primary rounded-pill" id="monthly-new-dossiers">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="pli-bank text-info me-2"></i>
                                    <span class="small">Nouvelles entreprises</span>
                                </div>
                                <span class="badge bg-info rounded-pill" id="monthly-new-entreprises">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="demo-psi-check text-success me-2"></i>
                                    <span class="small">Dossiers complétés</span>
                                </div>
                                <span class="badge bg-success rounded-pill" id="monthly-completed">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="pli-phone-2 text-warning me-2"></i>
                                    <span class="small">Prospects actifs</span>
                                </div>
                                <span class="badge bg-warning rounded-pill" id="monthly-prospects">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                            </div>
                        </div>

                        <hr>

                        <div class="text-center">
                            <small class="text-muted">Période: {{ now()->format('F Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts and Notifications -->
            <div class="col-xl-4 col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Alertes & Notifications</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush" id="alerts-container">
                            <div class="text-center py-4">
                                <span class="spinner-border spinner-border-sm" role="status"></span> Chargement...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/chart.js/chart.umd.min.js') }}"></script>
    <script src="{{ asset('js/ca-dashboard.js') }}"></script>

    <style>
        .text-bold {
            font-weight: 800;
        }

        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }

        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }

        .shadow {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }

        .chart-pie {
            position: relative;
            height: 15rem;
        }

        .chart-area {
            position: relative;
            height: 15rem;
        }

        .avatar {
            width: 2rem;
            height: 2rem;
        }

        .avatar-sm {
            width: 2rem;
            height: 2rem;
        }

        .avatar-title {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .chart-pie,
            .chart-area {
                height: 12rem;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection
