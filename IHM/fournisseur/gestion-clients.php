
<?php if (isset($_GET['ajout']) && $_GET['ajout'] === 'success'): ?>
    <div class="alert alert-success">Client ajouté avec succès !</div>
<?php elseif (isset($_GET['ajout']) && $_GET['ajout'] === 'fail'): ?>
    <div class="alert alert-danger">Erreur lors de l'ajout du client.</div>
<?php endif; ?>
<?php
// Inclure l'en-tête du fournisseur
 


// Connexion à la base de données
include '../../BD/DBConnection.php';

// Création d'une instance de la connexion
$db = DBConnection::getInstance();
$conn = $db->getConnection();

// Requête pour récupérer les clients avec leur consommation et montant impayé
$query = "
    SELECT 
        c.client_id, 
        c.nom, 
        c.prenom, 
        c.adresse, 
        COALESCE(SUM(cm.consommation), 0) AS consommation_kWh,
        COALESCE(SUM(f.prix_ttc), 0) AS montant_impaye_DH
    FROM clients c
    LEFT JOIN consommations_mensuelles cm ON c.client_id = cm.client_id
    LEFT JOIN factures f ON c.client_id = f.client_id AND f.etat = 'non_payee'
    GROUP BY c.client_id, c.nom, c.prenom, c.adresse
";
$stmt = $conn->prepare($query);
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>
    <link rel="stylesheet" href="../../Assets/Css/test.css">
</head>
<body>
<?php include '..\includes\header-fournisseur.php'; ?>

<div class="container">
    <!-- Titre avec cadre bleu foncé -->
    <h1 class="title-box">👥 Gestion des Clients</h1>

    <!-- Bouton pour ajouter un client -->
    <div class="button-container">
        <a href="ajouter-client.php" class="btn">➕ Ajouter un client</a>
    </div>

    <!-- Tableau des clients -->
    <table>
        <thead>
            <tr>
                <th>📛 Nom & Prénom</th>
                <th>📍 Adresse</th>
                <th>⚡ Consommation (kWh)</th>
                <th>💰 Montant impayé (DH)</th>
                <th>✏ Modifier</th>
                <th>❌ Supprimer</th>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($clients as $client) {
        echo "<tr data-id='" . $client['client_id'] . "'>";
        echo "<td>" . htmlspecialchars($client['nom']) . " " . htmlspecialchars($client['prenom']) . "</td>";
        echo "<td>" . htmlspecialchars($client['adresse']) . "</td>";
        echo "<td>" . number_format($client['consommation_kWh'], 2) . " kWh</td>";
        echo "<td>" . number_format($client['montant_impaye_DH'], 2) . " DH</td>";
echo "<td><a href='modifier-client.php?id=" . $client['client_id'] . "' class='editLink'>✏</a></td>";

echo "<td><button class='deleteBtn' data-id='" . $client['client_id'] . "'>❌supprimer</button></td>";

        echo "</tr>";
    }
    ?>
</tbody>

    </table>
</div>

 
    
 
<?php include '..\includes\footer-fournisseur.php'; ?>

<script src="../../Assets/Js/js.js"></script>
</body>
</html>
