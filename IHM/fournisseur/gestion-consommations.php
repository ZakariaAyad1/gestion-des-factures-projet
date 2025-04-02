<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Consommations</title>
    <style>
        /* Style global */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Barre de navigation */
        nav {
            background-color: #2c3e50;
            color: white;
            padding: 10px;
            text-align: center;
        }

        /* Actions section */
        .actions {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #ecf0f1;
        }

        /* Tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        /* Section Graphique */
        .graphique {
            margin: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
        }

        /* Réactivité */
        @media (max-width: 768px) {
            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <?php include '..\includes\header-fournisseur.php'; ?>

    <!-- Barre de navigation -->
    <nav>
        <h1>Gestion des Consommations</h1>
    </nav>

    <main>




        <!-- Tableau des consommations -->
        <section class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>📛 Client</th>
                        <th>📅 Mois</th>
                        <th>📅 Année</th>
                        <th>🔢 Consommation (kWh)</th>
                        <th>📷 Photo du compteur</th>
                        <th>✅ Valider</th>
                        <th>✏ Modifier</th>
                    </tr>
                </thead>
                <tbody id="consommations-body">
                    <!-- Les données seront insérées ici dynamiquement -->
                </tbody>
            </table>
        </section>

        <!-- Formulaire de modification avec Bootstrap -->
        <div id="form-modification" class="container mt-4 p-4 bg-light shadow-lg rounded" style="display: none; max-width: 500px;">
            <h3 class="text-center text-primary mb-3">Modifier la consommation</h3>
            <form id="modification-form">
                <input type="hidden" id="consommation_id" name="consommation_id">

                <div class="mb-3">
                    <label class="form-label">Mois :</label>
                    <input type="number" class="form-control" id="mois" name="mois" min="1" max="12" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Année :</label>
                    <input type="number" class="form-control" id="annee" name="annee" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Consommation :</label>
                    <input type="number" step="0.01" class="form-control" id="consommation" name="consommation" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Photo du compteur :</label>
                    <input type="file" class="form-control" id="photo_compteur" name="photo_compteur" accept="image/*" required>
                </div>

                <div class="text-center">
                    <img id="photo_preview" src="" alt="Photo compteur" class="img-thumbnail d-none" style="max-width: 150px;">
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                    <button type="button" class="btn btn-danger" id="fermer-form">Annuler</button>
                </div>
            </form>
        </div>

        <!-- Ajout de Bootstrap (si non inclus) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <script src="../../assets/js/fournisseur/consommations.js"></script>

    </main>

    <?php include '..\includes\footer-fournisseur.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>