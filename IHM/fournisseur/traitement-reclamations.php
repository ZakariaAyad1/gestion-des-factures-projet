<?php include '../includes/header-fournisseur.php'; ?>
<?php
require_once '../../Traitement/fournisseurs/ReclamationsController.php';

$controller = new ReclamationController();

// Récupérer les filtres
$statutFilter = $_GET['statut'] ?? 'all';
$typeFilter = $_GET['type'] ?? 'all';

// Récupérer les réclamations filtrées
$reclamations = $controller->afficherReclamations($statutFilter, $typeFilter);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau des Réclamations</title>
    <link rel="stylesheet" href="../../assets/css/style_reclamation.css">
</head>
<body>
    <h2>📋 Liste des Réclamations</h2>

    <!-- Filtres -->
    <form method="get">
        <label for="filtre_statut">🚦 Filtrer par statut :</label>
        <select id="filtre_statut" name="statut">
            <option value="all">Tous</option>
            <option value="En cours" <?= $statutFilter === 'En cours' ? 'selected' : '' ?>>En cours</option>
            <option value="Resolu" <?= $statutFilter === 'Resolu' ? 'selected' : '' ?>>Résolue</option>
        </select>

        <label for="filtre_type">📄 Filtrer par type :</label>
        <select id="filtre_type" name="type">
            <option value="all">Tous</option>
            <option value="Fuite externe" <?= $typeFilter === 'Fuite externe' ? 'selected' : '' ?>>Fuite externe</option>
            <option value="Fuite interne" <?= $typeFilter === 'Fuite interne' ? 'selected' : '' ?>>Fuite interne</option>
            <option value="Facture" <?= $typeFilter === 'Facture' ? 'selected' : '' ?>>Facture</option>
            <option value="Autre" <?= $typeFilter === 'Autre' ? 'selected' : '' ?>>Autre</option>
        </select>

        <button type="submit">Filtrer</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>📛 Client</th>
                <th>📅 Date</th>
                <th>📄 Type</th>
                <th>🚦 Statut</th>
                <th>🔍 Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reclamations as $reclamation): ?>
                <tr data-type="<?= htmlspecialchars($reclamation['type']) ?>" 
                    data-statut="<?= htmlspecialchars($reclamation['statut'] === 'Resolu' ? 'Résolue' : $reclamation['statut']) ?>">
                    <td><?= htmlspecialchars($reclamation['nom'] . ' ' . $reclamation['prenom']) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($reclamation['created_at']))) ?></td>
                    <td><?= htmlspecialchars($reclamation['type']) ?></td>
                    <td><?= htmlspecialchars($reclamation['statut'] === 'Resolu' ? 'Résolue' : $reclamation['statut']) ?></td>
                    <td><a href="detailReclamation.php?id=<?= $reclamation['reclamation_id'] ?>">🔍 Voir / Traiter</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Filtrage côté client
        document.getElementById("filtre_statut").addEventListener("change", function() {
            filterTable();
        });
        document.getElementById("filtre_type").addEventListener("change", function() {
            filterTable();
        });

        function filterTable() {
            const statutFilter = document.getElementById("filtre_statut").value;
            const typeFilter = document.getElementById("filtre_type").value;
            const rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const type = row.getAttribute("data-type");
                const statut = row.getAttribute("data-statut");

                const statutMatch = statutFilter === "all" || 
                                  (statutFilter === "Resolu" ? statut === "Résolue" : statut === statutFilter);
                const typeMatch = typeFilter === "all" || type === typeFilter;

                row.style.display = statutMatch && typeMatch ? "" : "none";
            });
        }
    </script>
</body>
</html>
<?php include '../includes/footer-fournisseur.php'; ?>