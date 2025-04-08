
<?php include '../includes/header-fournisseur.php'; ?>
<?php
require_once __DIR__.'/../../Traitement/fournisseurs/VerificationController.php';

$controller = new VerificationController();
$annee = $_GET['annee'] ?? date('Y') - 1;
$rapport = $controller->verifierAnnuelle($annee);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification Consommations <?= $annee ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .anomalie { background-color: #ffdddd; }
        .mois-table { margin: 0 auto; }
        .mois-table td { min-width: 60px; }
        .header { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Vérification des Consommations - <?= $annee ?></h1>
        <form method="get">
            <label>Année: 
                <input type="number" name="annee" value="<?= $annee ?>" min="2000" max="<?= date('Y') ?>">
            </label>
            <button type="submit">Actualiser</button>
        </form>
        <p>Anomalies détectées: <?= $rapport['total_anomalies'] ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Consommation Mensuelle (kWh)</th>
                <th>Total Client</th>
                <th>Conso Agent</th>
                <th>Différence</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rapport['clients'] as $item): 
                $client = $item['info'];
                $mois_data = array_column($client['mois'], 'consommation', 'mois');
            ?>
            <tr class="<?= $item['anomalie'] ? 'anomalie' : '' ?>">
                <td><?= htmlspecialchars($client['nom'].' '.$client['prenom']) ?></td>
                <td>
                    <table class="mois-table">
                        <tr>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                            <td><?= number_format($mois_data[$i] ?? 0, 0, ',', ' ') ?></td>
                            <?php endfor; ?>
                        </tr>
                        <tr>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                            <th><?= DateTime::createFromFormat('!m', $i)->format('M') ?></th>
                            <?php endfor; ?>
                        </tr>
                    </table>
                </td>
                <td><?= number_format($client['conso_client'], 0, ',', ' ') ?></td>
                <td><?= number_format($client['conso_agent'], 0, ',', ' ') ?></td>
                <td><?= number_format($client['difference'], 0, ',', ' ') ?></td>
                <td><?= $item['anomalie'] ? '❌ Anomalie' : '✓ Valide' ?></td>
                
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
<?php include '../includes/footer-fournisseur.php'; ?>