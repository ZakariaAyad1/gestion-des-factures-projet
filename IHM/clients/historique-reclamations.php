<?php
require '../../BD/ReclamationModel.php';
try {
    $db = DbConnection::getInstance(); 
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

$reclamationModel = new ReclamationModel($db);
$reclamations = $reclamationModel->getAllReclamations();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Réclamations</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <link rel="stylesheet" href="../../assets/css/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
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

<div class="actions">
    <button class="new-claim" onclick="window.location.href='Reclamation.php'">+ Nouvelle Réclamation</button>
</div> 

<div class="container">
    <h2>Mes Réclamations</h2>
    
    <table>
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Date</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reclamations as $reclamation) : ?>
                <tr>
                    <td>REC-<?= str_pad($reclamation['id'], 4, '0', STR_PAD_LEFT) ?></td>
                    <td><?= date("d M Y", strtotime($reclamation['created_at'])) ?></td>
                    <td><?= htmlspecialchars($reclamation['type']) ?></td>
                    <td>
                        <span class="status <?= strtolower(str_replace(' ', '', $reclamation['statut'])) ?>">
                            <?= htmlspecialchars($reclamation['statut']) ?>
                        </span>
                    </td>
                    <td><a href="detailsReclamation.php?id=<?= $reclamation['id'] ?>" class="view-details">Voir détails</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
