<?php
require_once'../../Traitement/clients/facture_controller.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des Factures</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <link rel="stylesheet" href="../../assets/css/style2.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <svg width="100" height="40" viewBox="0 0 100 40">
            <path d="M12,15 L30,15 L24,27 L12,27 Z" fill="#4270F4" />
            <text x="38" y="27" fill="#262A39" font-size="20" font-weight="bold">
                Client
            </text>
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
        <a href="#" class="nav-item" style="margin-top: auto;">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
        </a>
    </div>
</div>

<div class="container">
    <h2>MES Factures</h2>
    <table>
    <thead>
    <tr>
        <th>Numéro</th>
        <th>Date</th>
        <th>Total</th>
        <th>Statut</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
<?php if (!empty($factures)): ?>
    <?php foreach ($factures as $facture): ?>
        <tr>
            <td><?= 'INV-' . str_pad($facture['facture_id'], 4, '0', STR_PAD_LEFT) ?></td>
            <td><?= str_pad($facture['mois'], 2, '0', STR_PAD_LEFT) ?>/<?= $facture['annee'] ?></td>
            <td><?= number_format($facture['prix_ttc'], 2, ',', ' ') ?> €</td>
            <td>
                <span class="status <?= $facture['etat'] == 'payee' ? 'paid' : 
                                      ($facture['etat'] == 'non_payee' ? 'unpaid' : 
                                      ($facture['etat'] == 'en_attente' ? 'pending' : 'refunded')) ?>">
                    <?= ucfirst(str_replace('_', ' ', $facture['etat'])) ?>
                </span>
            </td>
            <td>
                <a href="../../factures/facture_<?= $facture['facture_id'] ?>" class="download-link">
                    Télécharger
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="5">Aucune facture disponible.</td></tr>
<?php endif; ?>
</tbody>
    </table>
</div>
</body>
</html>