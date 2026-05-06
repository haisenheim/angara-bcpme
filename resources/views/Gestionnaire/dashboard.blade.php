@extends('Layouts.gestionnaire')

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
    <div class="">
        <h1 class="h3 mb-0">Tableau de bord</h1>
        <p class="text-muted mb-0">Vue d'ensemble de vos activités</p>
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

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Dossiers en cours</div>
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
                                    Prospects</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-prospects">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
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
                            <h6 class="mb-1 text-primary">Workflow prospects</h6>
                            <p class="text-muted small mb-0">
                                Brouillon : <strong id="wf-prospects-brouillon"><span class="spinner-border spinner-border-sm" role="status"></span></strong>
                                &nbsp;·&nbsp;
                                Soumis : <strong id="wf-prospects-soumis"><span class="spinner-border spinner-border-sm" role="status"></span></strong>
                                &nbsp;·&nbsp;
                                Bloqués (avis) : <strong id="wf-prospects-bloques"><span class="spinner-border spinner-border-sm" role="status"></span></strong>
                                &nbsp;·&nbsp;
                                Prêts arbitrage : <strong id="wf-prospects-prets"><span class="spinner-border spinner-border-sm" role="status"></span></strong>
                            </p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('gestionnaire.entreprises.prospects') }}" class="btn btn-primary btn-sm">Ouvrir les prospects</a>
                            <a href="{{ route('gestionnaire.entreprises.prospects.create') }}" class="btn btn-outline-secondary btn-sm">Créer un prospect</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Prospects brouillon (à compléter)</h6>
                        <a href="{{ route('gestionnaire.entreprises.prospects') }}" class="btn btn-sm btn-primary">
                            Voir tous <i class="demo-psi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush" id="gest-drafts-container">
                            <div class="text-center py-3">
                                <span class="spinner-border spinner-border-sm" role="status"></span> Chargement...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Prospects bloqués (avis manquants)</h6>
                        <a href="{{ route('gestionnaire.entreprises.prospects') }}" class="btn btn-sm btn-outline-primary">
                            Ouvrir <i class="demo-psi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush" id="gest-blocked-container">
                            <div class="text-center py-3">
                                <span class="spinner-border spinner-border-sm" role="status"></span> Chargement...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Dossiers Status Chart -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Statut des dossiers</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="dossiersStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Entreprises par mois -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Entreprises créées par mois</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="entreprisesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Activités récentes</h6>
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
    </div>

    <script src="{{ asset('assets/vendors/chart.js/chart.umd.min.js') }}"></script>
    <script src="{{ asset('js/gestionnaire-dashboard.js') }}"></script>
    
    <style>
        .text-bold{
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
        
        .chart-pie {
            position: relative;
            height: 15rem;
        }
        
        .chart-area {
            position: relative;
            height: 10rem;
        }
        
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }
        
        .shadow {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }
        
        @media (max-width: 768px) {
            .chart-pie,
            .chart-area {
                height: 12rem;
            }
        }
    </style>
@endsection

