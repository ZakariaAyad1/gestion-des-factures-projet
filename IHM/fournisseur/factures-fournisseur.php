<!-- filepath: c:\Users\LENOVO\Documents\Ensa\s8\Programmation Web 2\tp2\github\gestion-des-factures-projet\IHM\fournisseur\factures-fournisseur.php -->
<?php
require('../../Traitement/fournisseurs/facture.php');  // Assurez-vous que ce chemin est correct

// Récupérer la liste des factures
$factures = $factureModel->getAllFactures();  // La méthode qui récupère toutes les factures depuis la base de données

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture Détails</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include '..\includes\header-fournisseur.php'; ?>

    <div class="container my-5">
        <h2 class="text-center mb-4">Liste des Factures</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID Facture</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Consommation</th>
                        <th>Prix HT</th>
                        <th>TVA</th>
                        <th>Prix TTC</th>
                        <th>État</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($factures as $facture) : ?>
                        <tr>
                            <td><?= $facture['facture_id'] ?></td>
                            <td><?= htmlspecialchars($facture['nom'] . ' ' . $facture['prenom']) ?></td>
                            <td><?= htmlspecialchars($facture['email']) ?></td>
                            <td><?= htmlspecialchars($facture['consommation']) ?> KWH</td>
                            <td><?= htmlspecialchars($facture['prix_ht']) ?> DH</td>
                            <td><?= htmlspecialchars($facture['tva']) ?> %</td>
                            <td><?= htmlspecialchars($facture['prix_ttc']) ?> DH</td>
                            <td>
                                <span class="badge bg-<?= $facture['etat'] == 'payee' ? 'success' : ($facture['etat'] == 'non_payee' ? 'danger' : 'warning') ?>">
                                    <?= htmlspecialchars($facture['etat']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <select class="form-select me-2 etat-select" data-id="<?= $facture['facture_id'] ?>">
                                        <option value="payee" <?= $facture['etat'] == 'payee' ? 'selected' : '' ?>>Payée</option>
                                        <option value="non_payee" <?= $facture['etat'] == 'non_payee' ? 'selected' : '' ?>>Non Payée</option>
                                        <option value="en_attente" <?= $facture['etat'] == 'en_attente' ? 'selected' : '' ?>>En Attente</option>
                                    </select>
                                    <button class="btn btn-primary generate-pdf" data-id="<?= $facture['facture_id'] ?>">Générer Facture</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '..\includes\footer-fournisseur.php'; ?>

    <script src="../../assets/js/fournisseur/factures-fournisseur.js"></script>
</body>

</html>