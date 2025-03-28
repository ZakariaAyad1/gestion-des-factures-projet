<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traitement des Réclamations</title>
    <link rel="stylesheet" href="../../Assets/Css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <!-- Include Header for Fournisseur -->
            <?php include '../Includes/Header-Fournisseur.php'; ?>
        </header>

        <aside>
            <!-- Menu latéral -->
            <nav>
                <ul>
                    <li><a href="Dashboard-Fournisseur.php">Tableau de bord</a></li>
                    <li><a href="Gestion-Clients.php">Clients</a></li>
                    <li><a href="Factures-Fournisseur.php">Factures</a></li>
                    <li><a href="Traitement-Reclamations.php">Réclamations</a></li>
                    <li><a href="../Auth-Controller.php?logout">Déconnexion</a></li>
                </ul>
            </nav>
        </aside>

        <main>
            <section class="reclamations">
                <h1>Réclamations à Traiter</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Type de réclamation</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamically populated with PHP -->
                        <?php
                            // Example to fetch reclamations from DB
                            $reclamations = getReclamations(); // This function should be in your DB connection file
                            foreach ($reclamations as $reclamation) {
                                echo "<tr>";
                                echo "<td>{$reclamation['client']}</td>";
                                echo "<td>{$reclamation['date']}</td>";
                                echo "<td>{$reclamation['type']}</td>";
                                echo "<td>{$reclamation['statut']}</td>";
                                echo "<td>
                                    <a href='respond-reclamation.php?id={$reclamation['id']}'>Répondre</a>
                                </td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
