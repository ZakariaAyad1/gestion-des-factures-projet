<?php
require '../../BD/Reclamation-Model.php';
try {
    $db = DBConnection::getInstance(); 
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

$reclamationModel = new ReclamationModel($db);

// Vérifier si l'ID de la réclamation est passé via l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $reclamationId = $_GET['id'];
    $reclamation = $reclamationModel->getReclamationById($reclamationId); // Méthode à implémenter dans le modèle
} else {
    die("ID de réclamation invalide.");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Réclamation</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <link rel="stylesheet" href="../../assets/css/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        .reclamation-details {
    margin-top: 20px;
}

.reclamation-details p {
    font-size: 16px;
    margin: 10px 0;
}

.reclamation-details .status {
    font-weight: bold;
}

.reclamation-details .status.enattente {
    color: #ff9800;
}

.reclamation-details .status.resolue {
    color: #4caf50;
}

.reclamation-details .status.cloturee {
    color: #f44336;
}

.back-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4285F4;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 20px;
}

.back-btn:hover {
    background-color: #357ae8;
}

    </style>
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
        <a href="dashboard-client.php" class="nav-item">
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

<div class="container">
    <h2>Détails de la Réclamation #REC-<?= str_pad($reclamation['id'], 4, '0', STR_PAD_LEFT) ?></h2>

    <div class="reclamation-details">
        <p><strong>Date :</strong> <?= date("d M Y", strtotime($reclamation['created_at'])) ?></p>
        <p><strong>Type :</strong> <?= htmlspecialchars($reclamation['type']) ?></p>
        <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($reclamation['description'])) ?></p>
        
        <p><strong>Statut :</strong> <span class="status <?= strtolower(str_replace(' ', '', $reclamation['statut'])) ?>"><?= htmlspecialchars($reclamation['statut']) ?></span></p>
        
        <?php if ($reclamation['piece_jointe']) : ?>
            <p><strong>Pièce jointe :</strong> <a href="../../assets/uploads/reclamations/reclamation_<?= $reclamation['id'] ?>" target="_blank">Voir la pièce jointe</a></p>
        <?php else: ?>
            <p><strong>Aucune pièce jointe</strong></p>
        <?php endif; ?>

        <?php if (!empty($reclamation['reponse'])): ?>
    <p><strong>Réponse :</strong><br><?= nl2br(htmlspecialchars($reclamation['reponse'])) ?></p>
<?php else: ?>
    <p><strong>Aucune réponse pour le moment.</strong></p>
<?php endif; ?>
    </div>
    
    <a href="Historique-Reclamations.php" class="back-btn">Retour à l'historique des réclamations</a>
</div>

</body>
</html>
