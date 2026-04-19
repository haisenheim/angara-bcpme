@extends('Layouts.analyste')

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
            <h1 class="h3 mb-0">Tableau de bord AnalysteFinancier</h1>
            <p class="text-muted mb-0">Bienvenue {{ auth()->user()->name }}, vue d'ensemble de vos analyses financieres</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm" onclick="refreshDashboard()">
                <i class="demo-psi-refresh-2"></i> Actualiser
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Dossiers assignés</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-dossiers">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
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
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    En attente d'analyse</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="pending-analysis">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="demo-psi-clock fs-1 text-gray-300"></i>
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
                                    Analyses complétées</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="completed-analysis">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="demo-psi-check fs-1 text-gray-300"></i>
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
                                    Entreprises</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-entreprises">
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
        </div>

        <!-- Alert Cards for Urgent Items -->
        <div class="row mb-4">
            <div class="col-xl-6 mb-3" id="urgent-alert" style="display:none;">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="demo-psi-exclamation-triangle fs-2 me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Attention!</h5>
                            <p class="mb-0">Vous avez <strong id="urgent-count">0</strong> dossier(s) en attente depuis plus de 3 jours.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>

            <div class="col-xl-6 mb-3" id="in-progress-alert" style="display:none;">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="demo-psi-information fs-2 me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">En cours</h5>
                            <p class="mb-0"><span id="in-progress-count">0</span> dossier(s) en cours d'analyse.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Dossiers Status Distribution -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Distribution des dossiers</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="dossiersDistributionChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> En cours
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-warning"></i> En attente
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Terminés
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-danger"></i> Rejetés
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Analysis Trend -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Analyses mensuelles</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="monthlyAnalysisChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Recent Dossiers -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Dossiers récents</h6>
                        <a href="{{ route('analyste.dossiers.index') }}" class="btn btn-sm btn-primary">
                            Voir tous <i class="demo-psi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Entreprise</th>
                                        <th>Programme</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-dossiers-table-body">
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <span class="spinner-border spinner-border-sm" role="status"></span> Chargement...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions and Performance -->
            <div class="col-xl-4 col-lg-5">
                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('analyste.dossiers.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="pli-folder me-2"></i>Gérer les dossiers
                            </a>
                            <a href="{{ route('analyste.entreprises.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="pli-bank me-2"></i>Voir les entreprises
                            </a>
                            <a href="{{ route('analyste.programmes.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="pli-affiliate me-2"></i>Gérer les programmes
                            </a>
                            <a href="{{ route('analyste.entreprises.prospects') }}" class="btn btn-outline-warning btn-sm">
                                <i class="pli-phone-2 me-2"></i>Voir les prospects
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Métriques de performance</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small">Taux de complétion</span>
                                <span class="small font-weight-bold" id="completion-rate">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" id="completion-progress" style="width: 0%" 
                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <hr>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">Ce mois</span>
                                <span class="badge bg-primary rounded-pill" id="this-month-completed">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">Total analysé</span>
                                <span class="badge bg-success rounded-pill" id="total-analyzed">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">En attente</span>
                                <span class="badge bg-warning rounded-pill" id="pending-count">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Programs Overview -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Programmes actifs</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush" id="programmes-list">
                            <div class="text-center py-3">
                                <span class="spinner-border spinner-border-sm" role="status"></span> Chargement...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/chart.js/chart.umd.min.js') }}"></script>
    <script src="{{ asset('js/analyste-dashboard.js') }}"></script>

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
            height: 10rem;
        }
        
        @media (max-width: 768px) {
            .chart-pie,
            .chart-area {
                height: 12rem;
            }
        }
    </style>
@endsection

