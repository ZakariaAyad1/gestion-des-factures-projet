<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>
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
            <section class="gestion-clients">
                <h1>Gestion des Clients</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Nom & Prénom</th>
                            <th>Adresse</th>
                            <th>Consommation totale (kWh)</th>
                            <th>Montant impayé</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamically populated with PHP -->
                        <?php
                            // Example to fetch clients from DB
                            $clients = getClients(); // This function should be in your DB connection file
                            foreach ($clients as $client) {
                                echo "<tr>";
                                echo "<td>{$client['nom']} {$client['prenom']}</td>";
                                echo "<td>{$client['adresse']}</td>";
                                echo "<td>{$client['consommation_totale']}</td>";
                                echo "<td>{$client['montant_impaye']}</td>";
                                echo "<td>
                                    <a href='edit-client.php?id={$client['id']}'>Modifier</a> |
                                    <a href='delete-client.php?id={$client['id']}'>Supprimer</a>
                                </td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
                <a href="add-client.php" class="btn">Ajouter un client</a>
            </section>
        </main>
    </div>
</body>
</html>
