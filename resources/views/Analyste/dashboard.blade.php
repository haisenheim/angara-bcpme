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
            <h1 class="h3 mb-0">Tableau de bord analyste</h1>
            <p class="text-muted mb-0">Bienvenue {{ auth()->user()->name }}, vue d'ensemble de vos analyses</p>
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
                                    {{ \App\Models\Dossier::where('analyste_id', auth()->user()->id)->count() }}
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
                                    {{ \App\Models\Dossier::where('analyste_id', auth()->user()->id)->where('statut', 'en_attente')->count() }}
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
                                    {{ \App\Models\Dossier::where('analyste_id', auth()->user()->id)->where('statut', 'termine')->count() }}
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
        </div>

        <!-- Alert Cards for Urgent Items -->
        @php
            $urgentDossiers = \App\Models\Dossier::where('analyste_id', auth()->user()->id)
                ->where('statut', 'en_attente')
                ->where('created_at', '<=', now()->subDays(3))
                ->count();
            $inProgressDossiers = \App\Models\Dossier::where('analyste_id', auth()->user()->id)
                ->where('statut', 'en_cours')
                ->count();
        @endphp

        @if($urgentDossiers > 0 || $inProgressDossiers > 0)
        <div class="row mb-4">
            @if($urgentDossiers > 0)
            <div class="col-xl-6 mb-3">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="demo-psi-exclamation-triangle fs-2 me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Attention!</h5>
                            <p class="mb-0">Vous avez <strong>{{ $urgentDossiers }}</strong> dossier(s) en attente depuis plus de 3 jours.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

            @if($inProgressDossiers > 0)
            <div class="col-xl-6 mb-3">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="demo-psi-information fs-2 me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">En cours</h5>
                            <p class="mb-0">{{ $inProgressDossiers }} dossier(s) en cours d'analyse.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
        </div>
        @endif

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
                                <tbody>
                                    @php
                                        $recentDossiers = \App\Models\Dossier::where('analyste_id', auth()->user()->id)
                                            ->with(['entreprise', 'programme'])
                                            ->orderBy('updated_at', 'desc')
                                            ->limit(5)
                                            ->get();
                                    @endphp

                                    @forelse($recentDossiers as $dossier)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="pli-folder text-primary me-2"></i>
                                                    <strong>{{ $dossier->entreprise->name ?? 'N/A' }}</strong>
                                                </div>
                                            </td>
                                            <td>{{ $dossier->programme->name ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $statusClass = match($dossier->statut ?? 'en_attente') {
                                                        'en_cours' => 'primary',
                                                        'en_attente' => 'warning',
                                                        'termine' => 'success',
                                                        'rejete' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ ucfirst(str_replace('_', ' ', $dossier->statut ?? 'en attente')) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $dossier->updated_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('analyste.dossiers.show', $dossier->token ?? '#') }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="demo-psi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="pli-folder text-muted fs-1"></i>
                                                <p class="text-muted mt-2">Aucun dossier récent</p>
                                            </td>
                                        </tr>
                                    @endforelse
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
                        @php
                            $totalDossiers = \App\Models\Dossier::where('analyste_id', auth()->user()->id)->count();
                            $completedDossiers = \App\Models\Dossier::where('analyste_id', auth()->user()->id)->where('statut', 'termine')->count();
                            $completionRate = $totalDossiers > 0 ? round(($completedDossiers / $totalDossiers) * 100) : 0;
                            $thisMonthCompleted = \App\Models\Dossier::where('analyste_id', auth()->user()->id)
                                ->where('statut', 'termine')
                                ->whereMonth('updated_at', now()->month)
                                ->count();
                        @endphp

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small">Taux de complétion</span>
                                <span class="small font-weight-bold">{{ $completionRate }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completionRate }}%"
                                     aria-valuenow="{{ $completionRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <hr>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">Ce mois</span>
                                <span class="badge bg-primary rounded-pill">{{ $thisMonthCompleted }} complétés</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">Total analysé</span>
                                <span class="badge bg-success rounded-pill">{{ $completedDossiers }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="small">En attente</span>
                                <span class="badge bg-warning rounded-pill">
                                    {{ \App\Models\Dossier::where('analyste_id', auth()->user()->id)->where('statut', 'en_attente')->count() }}
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
                        @php
                            $programmes = \App\Models\Programme::limit(5)->get();
                        @endphp

                        <div class="list-group list-group-flush">
                            @forelse($programmes as $programme)
                                <div class="list-group-item d-flex align-items-center px-0">
                                    <div class="flex-shrink-0 me-2">
                                        <i class="pli-affiliate text-success"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 small">{{ $programme->name }}</h6>
                                        <small class="text-muted">{{ $programme->dossiers_count ?? 0 }} dossiers</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-3">
                                    <i class="pli-affiliate text-muted fs-3"></i>
                                    <p class="text-muted small mt-2">Aucun programme</p>
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

        // Dossiers Distribution Pie Chart
        const ctx1 = document.getElementById("dossiersDistributionChart").getContext('2d');
        const dossiersDistributionChart = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['En cours', 'En attente', 'Terminés', 'Rejetés'],
                datasets: [{
                    data: [
                        {{ \App\Models\Dossier::where('analyste_id', auth()->user()->id)->where('statut', 'en_cours')->count() }},
                        {{ \App\Models\Dossier::where('analyste_id', auth()->user()->id)->where('statut', 'en_attente')->count() }},
                        {{ \App\Models\Dossier::where('analyste_id', auth()->user()->id)->where('statut', 'termine')->count() }},
                        {{ \App\Models\Dossier::where('analyste_id', auth()->user()->id)->where('statut', 'rejete')->count() }}
                    ],
                    backgroundColor: ['#4e73df', '#f6c23e', '#1cc88a', '#e74a3b'],
                    hoverBackgroundColor: ['#2e59d9', '#dda20a', '#17a673', '#e02d1b'],
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
                cutoutPercentage: 70,
            },
        });

        // Monthly Analysis Line Chart
        const ctx2 = document.getElementById("monthlyAnalysisChart").getContext('2d');
        const monthlyAnalysisChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: [
                    @for($i = 5; $i >= 0; $i--)
                        "{{ now()->subMonths($i)->format('M Y') }}",
                    @endfor
                ],
                datasets: [{
                    label: "Analyses complétées",
                    lineTension: 0.3,
                    backgroundColor: "rgba(28, 200, 138, 0.05)",
                    borderColor: "rgba(28, 200, 138, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(28, 200, 138, 1)",
                    pointBorderColor: "rgba(28, 200, 138, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(28, 200, 138, 1)",
                    pointHoverBorderColor: "rgba(28, 200, 138, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: [
                        @for($i = 5; $i >= 0; $i--)
                            {{ \App\Models\Dossier::where('analyste_id', auth()->user()->id)
                                ->where('statut', 'termine')
                                ->whereMonth('updated_at', now()->subMonths($i)->month)
                                ->whereYear('updated_at', now()->subMonths($i)->year)
                                ->count() }},
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
                            beginAtZero: true
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
