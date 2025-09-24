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
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">Tableau de bord</h1>
            <p class="text-muted mb-0">Vue d'ensemble de vos activités</p>
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
                                    Entreprises</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-entreprises">
                                    {{ \App\Models\Entreprise::where('user_id', auth()->user()->id)->count() }}
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
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Coopératives</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-cooperatives">
                                    {{ \App\Models\Tenant::where('gestionnaire_id', auth()->user()->id)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="pli-leafs fs-1 text-gray-300"></i>
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
                                    {{ \App\Models\Dossier::where('gestionnaire_id', auth()->user()->id)->where('statut', '!=', 'termine')->count() }}
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
                                    {{ \App\Models\Entreprise::where('user_id', auth()->user()->id)->where('statut', 'prospect')->count() }}
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

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('gestionnaire.entreprises.index') }}" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="pli-bank me-2"></i>Gérer les entreprises
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('gestionnaire.cooperatives.index') }}" class="btn btn-outline-success btn-sm w-100">
                                    <i class="pli-leafs me-2"></i>Gérer les coopératives
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('gestionnaire.dossiers.index') }}" class="btn btn-outline-info btn-sm w-100">
                                    <i class="pli-folder me-2"></i>Instruire les dossiers
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('gestionnaire.entreprises.prospects') }}" class="btn btn-outline-warning btn-sm w-100">
                                    <i class="pli-phone-2 me-2"></i>Voir les prospects
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Activités récentes</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @php
                                $recentDossiers = \App\Models\Dossier::where('gestionnaire_id', auth()->user()->id)
                                    ->with(['entreprise', 'programme'])
                                    ->orderBy('updated_at', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp

                            @forelse($recentDossiers as $dossier)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="pli-folder text-primary fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $dossier->entreprise->name ?? 'N/A' }}</h6>
                                            <p class="mb-1 text-muted">{{ $dossier->programme->name ?? 'N/A' }}</p>
                                            <small class="text-muted">{{ $dossier->updated_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $dossier->statut ?? 'En cours' }}</span>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="pli-folder text-muted fs-1"></i>
                                    <p class="text-muted mt-2">Aucune activité récente</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/chart.js/chart.umd.min.js') }}"></script>
    <script>
        // Chart.js configuration
        Chart.defaults.font.family = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.color = '#858796';

        // Dossiers Status Pie Chart
        const ctx1 = document.getElementById("dossiersStatusChart").getContext('2d');
        const dossiersStatusChart = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['En cours', 'En attente', 'Terminés', 'Rejetés'],
                datasets: [{
                    data: [
                        {{ \App\Models\Dossier::where('gestionnaire_id', auth()->user()->id)->where('statut', 'en_cours')->count() }},
                        {{ \App\Models\Dossier::where('gestionnaire_id', auth()->user()->id)->where('statut', 'en_attente')->count() }},
                        {{ \App\Models\Dossier::where('gestionnaire_id', auth()->user()->id)->where('statut', 'termine')->count() }},
                        {{ \App\Models\Dossier::where('gestionnaire_id', auth()->user()->id)->where('statut', 'rejete')->count() }}
                    ],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#e02d1b'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });

        // Entreprises Chart
        const ctx2 = document.getElementById("entreprisesChart").getContext('2d');
        const entreprisesChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: [
                    @for($i = 5; $i >= 0; $i--)
                        "{{ now()->subMonths($i)->format('M Y') }}",
                    @endfor
                ],
                datasets: [{
                    label: "Entreprises créées",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: [
                        @for($i = 5; $i >= 0; $i--)
                            {{ \App\Models\Entreprise::where('user_id', auth()->user()->id)->whereMonth('created_at', now()->subMonths($i)->month)->whereYear('created_at', now()->subMonths($i)->year)->count() }},
                        @endfor
                    ],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                }
            }
        });

        // Refresh dashboard function
        function refreshDashboard() {
            location.reload();
        }

        // Auto-refresh every 5 minutes
        setInterval(function() {
            // You can implement AJAX refresh here if needed
        }, 300000);
    </script>
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
