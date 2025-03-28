<?php
 
include '../Includes/Header-Fournisseur.php';  
 
$totalClients = $totalClients ?? 0;
$totalUnpaidInvoices = $totalUnpaidInvoices ?? 0;
$totalPendingReclamations = $totalPendingReclamations ?? 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Fournisseur</title>
    <!-- Lien vers ton fichier CSS existant -->
    <link rel="stylesheet" href="../../Assets/Css/test.css">
    <script src="../../Assets/Js/chart.min.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <?php include '../Includes/Header-Fournisseur.php'; ?>
        </header>

        <aside>
            <nav>
                <ul class="horizontal-menu">
                    <li><a href="Dashboard-Fournisseur.php">Tableau de bord</a></li>
                    <li><a href="Gestion-Clients.php">Clients</a></li>
                    <li><a href="Factures-Fournisseur.php">Factures</a></li>
                    <li><a href="Gestion-Consommations.php">consommations</a></li>
                    <li><a href="Traitement-Reclamations.php">Réclamations</a></li>
                    <li><a href="../Auth-Controller.php?logout">Déconnexion</a></li>
                </ul>
            </nav>
        </aside>

        <main>
            <h1>Tableau de Bord Fournisseur</h1>
            <div class="stats">
                <div class="stat">
                    <p>Total Clients</p>
                    <span id="totalClients"><?php echo $totalClients; ?></span>
                </div>
                <div class="stat">
                    <p>Factures Impayées</p>
                    <span id="totalUnpaidInvoices"><?php echo $totalUnpaidInvoices; ?></span>
                </div>
                <div class="stat">
                    <p>Réclamations en Attente</p>
                    <span id="totalPendingReclamations"><?php echo $totalPendingReclamations; ?></span>
                </div>
            </div>

            <canvas id="consumptionChart" width="400" height="200"></canvas>
        </main>
    </div>

    <!-- Lien vers ton fichier JS -->
    <script src="../../Assets/Js/js.js"></script>
</body>
</html>
