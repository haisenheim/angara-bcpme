/**
 * Gestionnaire Dashboard AJAX Manager
 * Handles all dynamic data loading for the Gestionnaire dashboard
 */

const GestionnaireDashboard = {
    routes: {
        stats: '/gestionnaire/dashboard/stats',
        recentDossiers: '/gestionnaire/dashboard/recent-dossiers',
        distribution: '/gestionnaire/dashboard/dossiers-distribution',
        entreprises: '/gestionnaire/dashboard/entreprises-data'
    },

    charts: {
        distribution: null,
        entreprises: null
    },

    init() {
        console.log('Initializing Gestionnaire Dashboard...');
        this.loadStats();
        this.loadRecentDossiers();
        this.loadDistributionChart();
        this.loadEntreprisesChart();
    },

    loadStats() {
        fetch(this.routes.stats)
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-entreprises').textContent = data.total_entreprises;
                document.getElementById('total-entites-individuelles').textContent = data.total_entites_individuelles;
                document.getElementById('total-dossiers').textContent = data.total_dossiers;
                document.getElementById('total-prospects').textContent = data.total_prospects;
            })
            .catch(error => {
                console.error('Error loading stats:', error);
                this.showError('stats-error', 'Erreur de chargement des statistiques');
            });
    },

    loadRecentDossiers() {
        fetch(this.routes.recentDossiers)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('recent-dossiers-container');
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-4">
                            <i class="pli-folder text-muted fs-1"></i>
                            <p class="text-muted mt-2">Aucune activité récente</p>
                        </div>
                    `;
                    return;
                }

                data.forEach(dossier => {
                    const statusClass = this.getStatusClass(dossier.statut);
                    const statusText = this.formatStatus(dossier.statut_name);

                    const item = `
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="pli-folder text-primary fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${dossier.entreprise_name}</h6>
                                    <p class="mb-1 text-muted">${dossier.programme_name}</p>
                                    <small class="text-muted">${dossier.updated_at}</small>
                                </div>
                            </div>
                            <span class="badge bg-${statusClass} rounded-pill">${statusText}</span>
                        </div>
                    `;
                    container.innerHTML += item;
                });
            })
            .catch(error => {
                console.error('Error loading recent dossiers:', error);
                this.showError('recent-dossiers-error', 'Erreur de chargement des dossiers');
            });
    },

    loadDistributionChart() {
        fetch(this.routes.distribution)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById("dossiersStatusChart").getContext('2d');

                if (this.charts.distribution) {
                    this.charts.distribution.destroy();
                }

                this.charts.distribution = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['En cours', 'En attente', 'Terminés', 'Rejetés'],
                        datasets: [{
                            data: [data.en_cours, data.en_attente, data.termine, data.rejete],
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
            })
            .catch(error => {
                console.error('Error loading distribution chart:', error);
                this.showError('distribution-chart-error', 'Erreur de chargement du graphique');
            });
    },

    loadEntreprisesChart() {
        fetch(this.routes.entreprises)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById("entreprisesChart").getContext('2d');

                if (this.charts.entreprises) {
                    this.charts.entreprises.destroy();
                }

                this.charts.entreprises = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
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
                console.error('Error loading entreprises chart:', error);
                this.showError('entreprises-chart-error', 'Erreur de chargement du graphique');
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
        return statutName || 'En cours';
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
    GestionnaireDashboard.init();
});

// Refresh dashboard function
function refreshDashboard() {
    GestionnaireDashboard.init();
}

// Auto-refresh every 5 minutes
setInterval(function() {
    GestionnaireDashboard.init();
}, 300000);

