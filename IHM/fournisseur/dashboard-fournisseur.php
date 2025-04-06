<?php
include '../../Traitement/Fournisseurs/dashboard-handler.php';
  include '../Includes/Header-Fournisseur.php';  
   

// Vérifier que les variables existent pour éviter des erreurs PHP
$months = isset($months) ? $months : [];
$monthlyConsumptions = isset($monthlyConsumptions) ? $monthlyConsumptions : [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Fournisseur</title>
    <!-- Lien vers ton fichier CSS -->
    <link rel="stylesheet" href="../../Assets/Css/test.css">

    <!-- Chargement de Chart.js depuis un CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
</head>
<body>
    <div class="container">
       <!-- 
        <aside>
            <nav>
                <ul class="horizontal-menu">
                    <li><a href="Dashboard-Fournisseur.php">Tableau de bord</a></li>
                    <li><a href="Gestion-Clients.php">Clients</a></li>
                    <li><a href="Factures-Fournisseur.php">Factures</a></li>
                    <li><a href="Gestion-Consommations.php">Consommations</a></li>
                    <li><a href="Traitement-Reclamations.php">Réclamations</a></li>
                    <li><a href="../Auth-Controller.php?logout">Déconnexion</a></li>
                </ul>
            </nav>
        </aside> -->

        <main>
            <h1>Tableau de Bord Fournisseur</h1>
            <div class="stats">
                <div class="stat">
                    <p>Total Clients</p>
                    <span id="totalClients"><?php echo htmlspecialchars($totalClients); ?></span>
                </div>
                <div class="stat">
                    <p>Factures Impayées</p>
                    <span id="totalUnpaidInvoices"><?php echo htmlspecialchars($totalUnpaidInvoices); ?></span>
                </div>
                <div class="stat">
                    <p>Réclamations en Attente</p>
                    <span id="totalPendingReclamations"><?php echo htmlspecialchars($totalPendingReclamations); ?></span>
                </div>
            </div>

            <canvas id="consumptionChart" width="400" height="200"></canvas>
        </main>
    </div>

    <!-- Script du graphique -->
    <script>
    // Vérification que Chart.js est bien chargé
    console.log(typeof Chart !== "undefined" ? "Chart.js chargé" : "Chart.js non chargé");

    // Récupérer les données PHP dans JavaScript
    const months = <?php echo json_encode($months); ?>;
    const monthlyConsumptions = <?php echo json_encode($monthlyConsumptions); ?>;

    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('consumptionChart').getContext('2d');

        if (months.length === 0 || monthlyConsumptions.length === 0) {
            console.warn("Données du graphique manquantes ou vides !");
            return;
        }

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Consommation (kWh)',
                    data: monthlyConsumptions,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    </script>

    <script src="../../Assets/Js/js.js"></script>
</body>
</html>
<?php include '../Includes/Footer-Fournisseur.php'; ?>
