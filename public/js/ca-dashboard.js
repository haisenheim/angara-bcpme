/**
 * CA (Chef d'Agence) Dashboard AJAX Manager
 * Handles all dynamic data loading for the CA dashboard
 */

const CADashboard = {
    routes: {
        stats: '/ca/dashboard/stats',
        performance: '/ca/dashboard/performance-data',
        team: '/ca/dashboard/team-performance',
        dossiers: '/ca/dashboard/recent-dossiers',
        monthlyStats: '/ca/dashboard/monthly-stats',
        alerts: '/ca/dashboard/alerts'
    },

    charts: {
        performance: null,
        portfolio: null
    },

    init() {
        console.log('Initializing CA Dashboard...');
        this.loadStats();
        this.loadPerformanceChart();
        this.loadTeamPerformance();
        this.loadRecentDossiers();
        this.loadMonthlyStats();
        this.loadAlerts();
    },

    loadStats() {
        fetch(this.routes.stats)
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-dossiers').textContent = data.total_dossiers;
                document.getElementById('dossiers-en-cours').textContent = data.dossiers_en_cours;
                document.getElementById('total-portfolio').textContent = data.total_entreprises;
                document.getElementById('portfolio-detail').textContent = `${data.total_entreprises} entreprises`;
                document.getElementById('total-users').textContent = data.total_users;
                document.getElementById('total-prospects').textContent = data.total_prospects;

                this.updatePortfolioChart(data.total_entreprises, data.total_cooperatives);
            })
            .catch(error => {
                console.error('Error loading stats:', error);
                this.showError('stats-error', 'Erreur de chargement des statistiques');
            });
    },

    loadPerformanceChart() {
        fetch(this.routes.performance)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById("performanceChart").getContext('2d');

                if (this.charts.performance) {
                    this.charts.performance.destroy();
                }

                this.charts.performance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
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
                            data: data.dossiers,
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
                            data: data.entreprises,
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
            })
            .catch(error => {
                console.error('Error loading performance chart:', error);
                this.showError('performance-chart-error', 'Erreur de chargement du graphique');
            });
    },

    updatePortfolioChart(entreprises, cooperatives) {
        const ctx = document.getElementById("portfolioChart").getContext('2d');

        if (this.charts.portfolio) {
            this.charts.portfolio.destroy();
        }

        this.charts.portfolio = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Entreprises', 'Coopératives'],
                datasets: [{
                    data: [entreprises, cooperatives],
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
    },

    loadTeamPerformance() {
        fetch(this.routes.team)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('team-performance-tbody');
                tbody.innerHTML = '';

                if (data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <i class="pli-conference text-muted fs-1"></i>
                                <p class="text-muted mt-2">Aucun membre d'équipe</p>
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(member => {
                    const initial = member.name.charAt(0).toUpperCase();
                    const row = `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-title bg-primary rounded-circle">
                                            ${initial}
                                        </div>
                                    </div>
                                    <strong>${member.name}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">${member.role}</span>
                            </td>
                            <td>${member.dossiers_count}</td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="demo-psi-check"></i> Actif
                                </span>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            })
            .catch(error => {
                console.error('Error loading team performance:', error);
                this.showError('team-performance-error', 'Erreur de chargement de l\'équipe');
            });
    },

    loadRecentDossiers() {
        fetch(this.routes.dossiers)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('recent-dossiers-container');
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-4">
                            <i class="pli-folder text-muted fs-1"></i>
                            <p class="text-muted mt-2">Aucun dossier récent</p>
                        </div>
                    `;
                    return;
                }

                data.forEach(dossier => {
                    const statusClass = this.getStatusClass(dossier.statut);
                    const statusText = this.formatStatus(dossier.statut_name);

                    const item = `
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <i class="pli-folder text-primary me-1"></i>
                                        ${dossier.entreprise_name}
                                    </h6>
                                    <p class="mb-1 text-muted small">${dossier.programme_name}</p>
                                    <small class="text-muted">
                                        Géré par: ${dossier.gestionnaire_name}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-${statusClass} mb-1">
                                        ${statusText}
                                    </span>
                                    <br>
                                    <small class="text-muted">${dossier.updated_at}</small>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += item;
                });
            })
            .catch(error => {
                console.error('Error loading recent dossiers:', error);
                this.showError('recent-dossiers-error', 'Erreur de chargement des dossiers récents');
            });
    },

    loadMonthlyStats() {
        fetch(this.routes.monthlyStats)
            .then(response => response.json())
            .then(data => {
                document.getElementById('monthly-new-dossiers').textContent = data.new_dossiers;
                document.getElementById('monthly-new-entreprises').textContent = data.new_entreprises;
                document.getElementById('monthly-completed').textContent = data.completed_dossiers;
                document.getElementById('monthly-prospects').textContent = data.active_prospects;
            })
            .catch(error => {
                console.error('Error loading monthly stats:', error);
                this.showError('monthly-stats-error', 'Erreur de chargement des statistiques mensuelles');
            });
    },

    loadAlerts() {
        fetch(this.routes.alerts)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('alerts-container');
                container.innerHTML = '';

                if (data.pending_dossiers > 0) {
                    container.innerHTML += `
                        <div class="list-group-item d-flex align-items-start px-0">
                            <div class="flex-shrink-0 me-3">
                                <i class="demo-psi-clock text-warning fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Dossiers en attente</h6>
                                <small class="text-muted">${data.pending_dossiers} dossier(s) en attente de traitement</small>
                            </div>
                        </div>
                    `;
                }

                if (data.old_pending > 0) {
                    container.innerHTML += `
                        <div class="list-group-item d-flex align-items-start px-0">
                            <div class="flex-shrink-0 me-3">
                                <i class="demo-psi-exclamation-triangle text-danger fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Attention requise</h6>
                                <small class="text-muted">${data.old_pending} dossier(s) en attente depuis plus de 7 jours</small>
                            </div>
                        </div>
                    `;
                }

                if (data.inactive_users > 0) {
                    container.innerHTML += `
                        <div class="list-group-item d-flex align-items-start px-0">
                            <div class="flex-shrink-0 me-3">
                                <i class="demo-psi-information text-info fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Utilisateurs inactifs</h6>
                                <small class="text-muted">${data.inactive_users} compte(s) désactivé(s)</small>
                            </div>
                        </div>
                    `;
                }

                if (container.innerHTML === '') {
                    container.innerHTML = `
                        <div class="text-center py-4">
                            <i class="demo-psi-check text-success fs-1"></i>
                            <p class="text-muted mt-2 small">Aucune alerte pour le moment</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading alerts:', error);
                this.showError('alerts-error', 'Erreur de chargement des alertes');
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
    CADashboard.init();
});

// Refresh dashboard function
function refreshDashboard() {
    CADashboard.init();
}

// Auto-refresh every 5 minutes
setInterval(function() {
    CADashboard.init();
}, 300000);

