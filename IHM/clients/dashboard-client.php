<?php
// 1. Inclure et initialiser le contrôleur
require_once '../../BD/DbConnection.php';
require_once '../../Traitement/clients/dashboard_controller.php';

// 2. Initialiser la connexion et le contrôleur
$db = DbConnection::getInstance()->getConnection();
$controller = new DashboardController($db);

// 3. Récupérer les données
$data = $controller->getDashboardData();

// 4. Extraire les variables pour la vue
extract($data);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tableau de Bord Client</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="../../assets/css/style1.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <svg width="100" height="40" viewBox="0 0 100 40">
                <path d="M12,15 L30,15 L24,27 L12,27 Z" fill="#4270F4" />
                <text x="38" y="27" fill="#262A39" font-size="20" font-weight="bold">Client</text>
            </svg>
        </div>

        <div class="nav-menu">
            <a href="dashboard-client.php" class="nav-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="factures.php" class="nav-item">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Factures</span>
            </a>
            <a href="Saisie-Consommation.php" class="nav-item">
                <i class="fas fa-tachometer"></i>
                <span>Saisie Consommation</span>
            </a>
            <a href="Historique-Reclamations.php" class="nav-item">
                <i class="fas fa-exclamation-circle"></i>
                <span>Réclamations</span>
            </a>
            <a href="../../logout.php" class="nav-item" style="margin-top: auto;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <div class="welcome-section">
                <p class="greeting">Bonjour <?= htmlspecialchars($client_prenom . ' ' . $client_nom) ?></p>
                <h1 class="welcome-title">Votre Tableau de Bord</h1>
            </div>
        </div>

        <div class="transfer-cards">
            <div class="transfer-card">
                <div class="card-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <p class="card-title">Factures Impayées</p>
                <h2 class="card-amount"><?= htmlspecialchars($factureStats['non_payee'] ?? 0) ?></h2>
            </div>

            <div class="transfer-card">
                <div class="card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p class="card-title">Factures Payées</p>
                <h2 class="card-amount"><?= htmlspecialchars($factureStats['payee'] ?? 0) ?></h2>
            </div>

            <div class="transfer-card">
                <div class="card-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <p class="card-title">Réclamations</p>
                <h2 class="card-amount"><?= htmlspecialchars($reclamationCount ?? 0) ?></h2>
            </div>
        </div>

        <div class="savings-card">
            <h3 class="savings-title">Historique des Consommations</h3>
            <div class="savings-amount"><?= htmlspecialchars($factureStats['total'] ?? 0) ?> Factures</div>

            <div class="chart-container">
                <canvas id="consoChart" height="100"></canvas>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('consoChart').getContext('2d');
                    const chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: <?= json_encode($chartData['labels'] ?? []) ?>,
                            datasets: [{
                                label: 'Consommation (kWh)',
                                data: <?= json_encode($chartData['data'] ?? []) ?>,
                                backgroundColor: <?= json_encode($chartData['colors'] ?? []) ?>,
                                borderColor: '#4270F4',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(0,0,0,0.05)' }
                                },
                                x: {
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                });
            </script>
        </div>
    </div>
</body>
</html>