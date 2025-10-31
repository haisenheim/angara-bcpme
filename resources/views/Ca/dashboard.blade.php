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
    @php
        $agenceId = auth()->user()->agence_id;
        $totalDossiers = \App\Models\Dossier::where('agence_id', $agenceId)->count();
        $totalEntreprises = \App\Models\Entreprise::where('agence_id', $agenceId)->count();
        $totalCooperatives = \App\Models\Structuration\Cooperative::where('agence_id', $agenceId)->count();
        $totalUsers = \App\Models\User::where('agence_id', $agenceId)->where('active', 1)->count();
        $totalProspects = \App\Models\Entreprise::where('agence_id', $agenceId)->where('statut', 'prospect')->count();
    @endphp

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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $totalDossiers }}
                                </div>
                                <div class="text-xs mt-1">
                                    <span class="text-success">
                                        {{ \App\Models\Dossier::where('agence_id', $agenceId)->where('statut', 'en_cours')->count() }}
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $totalEntreprises + $totalCooperatives }}
                                </div>
                                <div class="text-xs mt-1">
                                    {{ $totalEntreprises }} entreprises, {{ $totalCooperatives }} coopératives
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $totalUsers }}
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $totalProspects }}
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
                        @php
                            $teamMembers = \App\Models\User::where('agence_id', $agenceId)
                                ->where('active', 1)
                                ->with('role')
                                ->limit(5)
                                ->get();
                        @endphp

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
                                <tbody>
                                    @forelse($teamMembers as $member)
                                        @php
                                            $memberDossiers = 0;
                                            if(in_array($member->role_id, [3, 4])) { // Gestionnaire or Analyste
                                                $memberDossiers = \App\Models\Dossier::where(
                                                    $member->role_id == 3 ? 'gestionnaire_id' : 'analyste_id',
                                                    $member->id
                                                )->count();
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <div class="avatar-title bg-primary rounded-circle">
                                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <strong>{{ $member->name }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $member->role->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>{{ $memberDossiers }}</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="demo-psi-check"></i> Actif
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="pli-conference text-muted fs-1"></i>
                                                <p class="text-muted mt-2">Aucun membre d'équipe</p>
                                            </td>
                                        </tr>
                                    @endforelse
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
                        @php
                            $recentDossiers = \App\Models\Dossier::where('agence_id', $agenceId)
                                ->with(['entreprise', 'programme', 'gestionnaire'])
                                ->orderBy('updated_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp

                        <div class="list-group list-group-flush">
                            @forelse($recentDossiers as $dossier)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <i class="pli-folder text-primary me-1"></i>
                                                {{ $dossier->entreprise->name ?? 'N/A' }}
                                            </h6>
                                            <p class="mb-1 text-muted small">{{ $dossier->programme->name ?? 'N/A' }}</p>
                                            <small class="text-muted">
                                                Géré par: {{ $dossier->gestionnaire->name ?? 'Non assigné' }}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            @php
                                                $statusClass = match($dossier->statut ?? 'en_attente') {
                                                    'en_cours' => 'primary',
                                                    'en_attente' => 'warning',
                                                    'termine' => 'success',
                                                    'rejete' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }} mb-1">
                                                {{ ucfirst(str_replace('_', ' ', $dossier->statut ?? 'en attente')) }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $dossier->updated_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="pli-folder text-muted fs-1"></i>
                                    <p class="text-muted mt-2">Aucun dossier récent</p>
                                </div>
                            @endforelse
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
                            <a href="{{ route('ca.cooperatives.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="pli-leafs me-2"></i>Gérer les coopératives
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
                        @php
                            $thisMonthDossiers = \App\Models\Dossier::where('agence_id', $agenceId)
                                ->whereMonth('created_at', now()->month)
                                ->count();
                            $thisMonthEntreprises = \App\Models\Entreprise::where('agence_id', $agenceId)
                                ->whereMonth('created_at', now()->month)
                                ->count();
                            $completedThisMonth = \App\Models\Dossier::where('agence_id', $agenceId)
                                ->where('statut', 'termine')
                                ->whereMonth('updated_at', now()->month)
                                ->count();
                        @endphp

                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="pli-folder text-primary me-2"></i>
                                    <span class="small">Nouveaux dossiers</span>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $thisMonthDossiers }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="pli-bank text-info me-2"></i>
                                    <span class="small">Nouvelles entreprises</span>
                                </div>
                                <span class="badge bg-info rounded-pill">{{ $thisMonthEntreprises }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="demo-psi-check text-success me-2"></i>
                                    <span class="small">Dossiers complétés</span>
                                </div>
                                <span class="badge bg-success rounded-pill">{{ $completedThisMonth }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="pli-phone-2 text-warning me-2"></i>
                                    <span class="small">Prospects actifs</span>
                                </div>
                                <span class="badge bg-warning rounded-pill">{{ $totalProspects }}</span>
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
                        @php
                            $pendingDossiers = \App\Models\Dossier::where('agence_id', $agenceId)
                                ->where('statut', 'en_attente')
                                ->count();
                            $oldPending = \App\Models\Dossier::where('agence_id', $agenceId)
                                ->where('statut', 'en_attente')
                                ->where('created_at', '<=', now()->subDays(7))
                                ->count();
                            $inactiveUsers = \App\Models\User::where('agence_id', $agenceId)
                                ->where('active', 0)
                                ->count();
                        @endphp

                        <div class="list-group list-group-flush">
                            @if($pendingDossiers > 0)
                            <div class="list-group-item d-flex align-items-start px-0">
                                <div class="flex-shrink-0 me-3">
                                    <i class="demo-psi-clock text-warning fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Dossiers en attente</h6>
                                    <small class="text-muted">{{ $pendingDossiers }} dossier(s) en attente de traitement</small>
                                </div>
                            </div>
                            @endif

                            @if($oldPending > 0)
                            <div class="list-group-item d-flex align-items-start px-0">
                                <div class="flex-shrink-0 me-3">
                                    <i class="demo-psi-exclamation-triangle text-danger fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Attention requise</h6>
                                    <small class="text-muted">{{ $oldPending }} dossier(s) en attente depuis plus de 7 jours</small>
                                </div>
                            </div>
                            @endif

                            @if($inactiveUsers > 0)
                            <div class="list-group-item d-flex align-items-start px-0">
                                <div class="flex-shrink-0 me-3">
                                    <i class="demo-psi-information text-info fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Utilisateurs inactifs</h6>
                                    <small class="text-muted">{{ $inactiveUsers }} compte(s) désactivé(s)</small>
                                </div>
                            </div>
                            @endif

                            @if($pendingDossiers == 0 && $oldPending == 0 && $inactiveUsers == 0)
                            <div class="text-center py-4">
                                <i class="demo-psi-check text-success fs-1"></i>
                                <p class="text-muted mt-2 small">Aucune alerte pour le moment</p>
                            </div>
                            @endif
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

        // Performance Line Chart
        const ctx1 = document.getElementById("performanceChart").getContext('2d');
        const performanceChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: [
                    @for($i = 5; $i >= 0; $i--)
                        "{{ now()->subMonths($i)->format('M Y') }}",
                    @endfor
                ],
                datasets: [{
                    label: "Dossiers créés",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 4,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: [
                        @for($i = 5; $i >= 0; $i--)
                            {{ \App\Models\Dossier::where('agence_id', $agenceId)
                                ->whereMonth('created_at', now()->subMonths($i)->month)
                                ->whereYear('created_at', now()->subMonths($i)->year)
                                ->count() }},
                        @endfor
                    ],
                }, {
                    label: "Entreprises créées",
                    lineTension: 0.3,
                    backgroundColor: "rgba(28, 200, 138, 0.05)",
                    borderColor: "rgba(28, 200, 138, 1)",
                    pointRadius: 4,
                    pointBackgroundColor: "rgba(28, 200, 138, 1)",
                    pointBorderColor: "rgba(28, 200, 138, 1)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(28, 200, 138, 1)",
                    pointHoverBorderColor: "rgba(28, 200, 138, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: [
                        @for($i = 5; $i >= 0; $i--)
                            {{ \App\Models\Entreprise::where('agence_id', $agenceId)
                                ->whereMonth('created_at', now()->subMonths($i)->month)
                                ->whereYear('created_at', now()->subMonths($i)->year)
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
                    display: true,
                    position: 'bottom'
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
                    displayColors: true,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                }
            }
        });

        // Portfolio Pie Chart
        const ctx2 = document.getElementById("portfolioChart").getContext('2d');
        const portfolioChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Entreprises', 'Coopératives'],
                datasets: [{
                    data: [{{ $totalEntreprises }}, {{ $totalCooperatives }}],
                    backgroundColor: ['#4e73df', '#1cc88a'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673'],
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
