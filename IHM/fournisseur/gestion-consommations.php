<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Consommations</title>
    <link rel="stylesheet" href="styles.css">
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

th, td {
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

    <!-- Barre de navigation -->
    <nav>
        <h1>Gestion des Consommations</h1>
    </nav>

    <main>

        <!-- Actions -->
        <section class="actions">
            <button id="ajouter">➕ Ajouter Consommation</button>
            <input type="text" id="search" placeholder="🔍 Rechercher...">
            
        </section>

        <!-- Tableau des consommations -->
        <section class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>📛 Client</th>
                        <th>📅 Mois</th>
                        <th>🔢 Consommation (kWh)</th>
                        <th>📷 Photo du compteur</th>
                        <th>✅ Valider</th>
                        <th>✏ Modifier</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Jean Dupont</td>
                        <td>Juillet</td>
                        <td>250 kWh</td>
                        <td><img src="compteur.jpg" alt="Photo compteur" width="50"></td>
                        <td><button class="valider">✅</button></td>
                        <td><button class="modifier">✏</button></td>
                    </tr>
                    <!-- D'autres lignes seront ajoutées dynamiquement -->
                </tbody>
            </table>
        </section>

        <!-- Graphique -->
        <section class="graphique">
            <h2>📊 Évolution des Consommations</h2>
            <canvas id="chart"></canvas>
        </section>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        // Données fictives pour l'évolution des consommations
        const labels = ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin", "Juil", "Août", "Sep", "Oct", "Nov", "Déc"];
        const data = {
            labels: labels,
            datasets: [{
                label: "Consommation (kWh)",
                data: [120, 140, 180, 200, 230, 250, 300, 280, 270, 260, 240, 220], // Consommations par mois
                borderColor: "blue", // Couleur de la ligne
                backgroundColor: "rgba(0, 0, 255, 0.2)", // Couleur de fond (transparente)
                borderWidth: 2, // Largeur de la ligne
                fill: true // Remplir sous la courbe
            }]
        };

        // Configuration du graphique
        const config = {
            type: "line", // Type de graphique (ligne)
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "top", // Position de la légende
                    },
                    title: {
                        display: true,
                        text: "Évolution des Consommations Mensuelles" // Titre du graphique
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Mois' // Légende de l'axe X
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Consommation (kWh)' // Légende de l'axe Y
                        },
                        beginAtZero: true // Commencer l'axe Y à zéro
                    }
                }
            }
        };

        // Initialisation du graphique
        const myChart = new Chart(document.getElementById("chart"), config);
    </script>
</body>

</html>