<?php
session_start();
include '../config/db.php'; // Inclure le fichier de connexion à la base de données

// Vérifier si l'utilisateur est connecté et est un fournisseur
if (!isset($_SESSION['fournisseur_id'])) {
    header("Location: ../login.php");
    exit();
}

$fournisseur_id = $_SESSION['fournisseur_id'];

// Récupération des données du tableau de bord
$sql = "SELECT mois, annee, montant_factures_non_payees, consommation_totale_mensuelle
        FROM dashboard
        WHERE fournisseur_id = ?
        ORDER BY annee DESC, mois DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $fournisseur_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Fournisseur</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- Assurez-vous d'avoir un fichier CSS -->
</head>
<body>
    <h2>Tableau de bord du fournisseur</h2>
    <table border="1">
        <tr>
            <th>Mois</th>
            <th>Année</th>
            <th>Montant des factures non payées (€)</th>
            <th>Consommation totale mensuelle (m³)</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['mois']; ?></td>
            <td><?php echo $row['annee']; ?></td>
            <td><?php echo number_format($row['montant_factures_non_payees'], 2, ',', ' '); ?> €</td>
            <td><?php echo number_format($row['consommation_totale_mensuelle'], 2, ',', ' '); ?> m³</td>
        </tr>
        <?php } ?>
    </table>
    <a href="../logout.php">Déconnexion</a>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
