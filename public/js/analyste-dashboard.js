/**
 * Analyste Dashboard AJAX Manager
 * Handles all dynamic data loading for the Analyste dashboard
 */

const AnalysteDashboard = {
    routes: {
        stats: '/analyste/dashboard/stats',
        distribution: '/analyste/dashboard/dossiers-distribution',
        monthlyAnalysis: '/analyste/dashboard/monthly-analysis',
        recentDossiers: '/analyste/dashboard/recent-dossiers',
        performanceMetrics: '/analyste/dashboard/performance-metrics',
        alerts: '/analyste/dashboard/alerts',
        programmes: '/analyste/dashboard/programmes'
    },

    charts: {
        distribution: null,
        monthly: null
    },

    init() {
        console.log('Initializing Analyste Dashboard...');
        this.loadStats();
        this.loadDistributionChart();
        this.loadMonthlyAnalysisChart();
        this.loadRecentDossiers();
        this.loadPerformanceMetrics();
        this.loadAlerts();
        this.loadProgrammes();
    },

    loadStats() {
        fetch(this.routes.stats)
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-dossiers').textContent = data.total_dossiers;
                document.getElementById('pending-analysis').textContent = data.pending_analysis;
                document.getElementById('completed-analysis').textContent = data.completed_analysis;
                document.getElementById('total-entreprises').textContent = data.total_entreprises;
            })
            .catch(error => {
                console.error('Error loading stats:', error);
                this.showError('stats-error', 'Erreur de chargement des statistiques');
            });
    },

    loadDistributionChart() {
        fetch(this.routes.distribution)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById("dossiersDistributionChart").getContext('2d');

                if (this.charts.distribution) {
                    this.charts.distribution.destroy();
                }

                this.charts.distribution = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['En cours', 'En attente', 'Terminés', 'Rejetés'],
                        datasets: [{
                            data: [data.en_cours, data.en_attente, data.termine, data.rejete],
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
            })
            .catch(error => {
                console.error('Error loading distribution chart:', error);
                this.showError('distribution-chart-error', 'Erreur de chargement du graphique');
            });
    },

    loadMonthlyAnalysisChart() {
        fetch(this.routes.monthlyAnalysis)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById("monthlyAnalysisChart").getContext('2d');

                if (this.charts.monthly) {
                    this.charts.monthly.destroy();
                }

                this.charts.monthly = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
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
                            data: data.data,
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
            })
            .catch(error => {
                console.error('Error loading monthly analysis chart:', error);
                this.showError('monthly-chart-error', 'Erreur de chargement du graphique');
            });
    },

    loadRecentDossiers() {
        fetch(this.routes.recentDossiers)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('recent-dossiers-table-body');
                tbody.innerHTML = '';

                if (data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="pli-folder text-muted fs-1"></i>
                                <p class="text-muted mt-2">Aucun dossier récent</p>
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(dossier => {
                    const statusClass = this.getStatusClass(dossier.statut);
                    const statusText = this.formatStatus(dossier.statut_name);

                    const row = `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="pli-folder text-primary me-2"></i>
                                    <strong>${dossier.entreprise_name}</strong>
                                </div>
                            </td>
                            <td>${dossier.programme_name}</td>
                            <td>
                                <span class="badge bg-${statusClass}">
                                    ${statusText}
                                </span>
                            </td>
                            <td>
                                <small>${dossier.updated_at}</small>
                            </td>
                            <td>
                                <a href="/analyste/dossiers/${dossier.token}" class="btn btn-sm btn-outline-primary">
                                    <i class="demo-psi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            })
            .catch(error => {
                console.error('Error loading recent dossiers:', error);
                this.showError('recent-dossiers-error', 'Erreur de chargement des dossiers');
            });
    },

    loadPerformanceMetrics() {
        fetch(this.routes.performanceMetrics)
            .then(response => response.json())
            .then(data => {
                document.getElementById('completion-rate').textContent = `${data.completion_rate}%`;
                document.getElementById('completion-progress').style.width = `${data.completion_rate}%`;
                document.getElementById('this-month-completed').textContent = data.this_month_completed;
                document.getElementById('total-analyzed').textContent = data.total_analyzed;
                document.getElementById('pending-count').textContent = data.pending_count;
            })
            .catch(error => {
                console.error('Error loading performance metrics:', error);
                this.showError('performance-error', 'Erreur de chargement des métriques');
            });
    },

    loadAlerts() {
        fetch(this.routes.alerts)
            .then(response => response.json())
            .then(data => {
                const urgentAlert = document.getElementById('urgent-alert');
                const inProgressAlert = document.getElementById('in-progress-alert');

                if (data.urgent_dossiers > 0) {
                    urgentAlert.style.display = 'block';
                    document.getElementById('urgent-count').textContent = data.urgent_dossiers;
                } else {
                    urgentAlert.style.display = 'none';
                }

                if (data.in_progress_dossiers > 0) {
                    inProgressAlert.style.display = 'block';
                    document.getElementById('in-progress-count').textContent = data.in_progress_dossiers;
                } else {
                    inProgressAlert.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading alerts:', error);
                this.showError('alerts-error', 'Erreur de chargement des alertes');
            });
    },

    loadProgrammes() {
        fetch(this.routes.programmes)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('programmes-list');
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-3">
                            <i class="pli-affiliate text-muted fs-3"></i>
                            <p class="text-muted small mt-2">Aucun programme</p>
                        </div>
                    `;
                    return;
                }

                data.forEach(programme => {
                    const item = `
                        <div class="list-group-item d-flex align-items-center px-0">
                            <div class="flex-shrink-0 me-2">
                                <i class="pli-affiliate text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 small">${programme.name}</h6>
                                <small class="text-muted">${programme.dossiers_count} dossiers</small>
                            </div>
                        </div>
                    `;
                    container.innerHTML += item;
                });
            })
            .catch(error => {
                console.error('Error loading programmes:', error);
                this.showError('programmes-error', 'Erreur de chargement des programmes');
            });
    },

    getStatusClass(statutCode) {
        // Status codes: 0 = en attente, 1 = en cours
        const statusMap = {
            0: 'warning',  // en attente
            1: 'primary',  // en cours
            2: 'success',  // termine
            3: 'danger'    // rejete
        };
        return statusMap[statutCode] || 'secondary';
    },

    formatStatus(statutName) {
        return statutName || 'En attente';
    },

    showError(elementId, message) {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = `<span class="text-danger small">${message}</span>`;
        }
    }
};

// Initialize dashboard on page load
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined') {
        Chart.defaults.font.family = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.color = '#858796';
    }
    AnalysteDashboard.init();
});

// Refresh dashboard function
function refreshDashboard() {
    AnalysteDashboard.init();
}

// Auto-refresh every 5 minutes
setInterval(function() {
    AnalysteDashboard.init();
}, 300000);

