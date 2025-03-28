<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factures - Fournisseur</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Style général du body */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Header */
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-top: 20px;
        }

        /* Section de filtrage */
        .filtrage {
            text-align: center;
            margin: 20px;
        }

        .filtrage select {
            padding: 5px 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Table des factures */
        .factures {
            margin: 20px auto;
            width: 90%;
            max-width: 1200px;
            border-collapse: collapse;
        }

        .factures table {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .factures th,
        .factures td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .factures th {
            background-color: #2c3e50;
            color: #fff;
        }

        .factures td {
            background-color: #fff;
        }

        /* Statut de la facture */
        .statut {
            font-weight: bold;
        }

        .statut.payee {
            color: green;
        }

        .statut.non_payee {
            color: red;
        }

        .statut.en_attente {
            color: orange;
        }

        /* Boutons */
        button,
        a {
            text-decoration: none;
            background-color: #3498db;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            margin: 5px;
            display: inline-block;
        }

        button:hover,
        a:hover {
            background-color: #2980b9;
        }

        /* Input editable */
        input[type="text"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <main>
        <h1>Gestion des Factures</h1>


        <section class="filtrage">
            <form method="GET" action="">
                <label for="status">Filtrer par statut :</label>
                <select name="status" id="status">
                    <option value="toutes">Toutes</option>
                    <option value="payee">Payée</option>
                    <option value="non_payee">Non Payée</option>
                    <option value="en_attente">En Attente</option>
                </select>
            </form>
        </section>

        <!-- Liste et gestion des factures -->
        <section class="factures">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Montant HT</th>
                        <th>TVA</th>
                        <th>Montant TTC</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>001</td>
                        <td>Jean Dupont</td>
                        <td>05/03/2024</td>
                        <td>150.00 €</td>
                        <td>18%</td>
                        <td>177.00 €</td>
                        <td><span class="statut payee">Payée</span></td>
                        <td>
                            <a href="Télécharger-Facture.php?id=001" class="download">Télécharger</a>
                            <a href="Modifier-Facture.html?id=001" class="modify">Modifier</a>
                            <button onclick="genererFacture(001)" class="generate">Générer</button>
                        </td>
                    </tr>
                    <tr>
                        <td>002</td>
                        <td>Marie Curie</td>
                        <td>10/03/2024</td>
                        <td>120.00 €</td>
                        <td>18%</td>
                        <td>141.60 €</td>
                        <td><span class="statut non_payee">Non Payée</span></td>
                        <td>
                            <a href="Télécharger-Facture.php?id=002" class="download">Télécharger</a>
                            <a href="Modifier-Facture.html?id=002" class="modify">Modifier</a>
                            <button onclick="genererFacture(002)" class="generate">Générer</button>
                        </td>
                    </tr>
                </tbody>
            </table>


        </section>

    </main>


    <script>
        function genererFacture(factureId) {
            if (confirm("Voulez-vous générer automatiquement la facture #" + factureId + " ?")) {
                window.location.href = "Traitement/Fournisseurs/Generer-Facture.php?id=" + factureId;
            }
        }


        document.addEventListener("DOMContentLoaded", function() {
            const filterSelect = document.getElementById("status");
            const rows = document.querySelectorAll(".factures tbody tr");

            // Charger les données enregistrées et mettre à jour les cellules
            function loadData() {
                const savedData = JSON.parse(localStorage.getItem("factureData")) || {};

                rows.forEach(row => {
                    const id = row.cells[0].textContent.trim();
                    if (savedData[id]) {
                        savedData[id].forEach((value, index) => {
                            if (index < row.cells.length - 1) {
                                row.cells[index].innerHTML = "";
                                if (index === 6) {
                                    // Modifier le statut avec un select
                                    const select = createStatusSelect(id, value);
                                    row.cells[index].appendChild(select);
                                } else {
                                    // Modifier les autres colonnes avec un input texte
                                    const input = createEditableInput(id, index, value);
                                    row.cells[index].appendChild(input);
                                }
                            }
                        });
                    } else {
                        // Initialiser la sauvegarde si elle n'existe pas encore
                        const rowData = [];
                        row.querySelectorAll("td:not(:last-child)").forEach((cell, index) => {
                            if (index === 6) {
                                const select = createStatusSelect(id, cell.textContent.trim());
                                rowData.push(select.value);
                                cell.innerHTML = "";
                                cell.appendChild(select);
                            } else {
                                const input = createEditableInput(id, index, cell.textContent.trim());
                                rowData.push(input.value);
                                cell.innerHTML = "";
                                cell.appendChild(input);
                            }
                        });

                        savedData[id] = rowData;
                        localStorage.setItem("factureData", JSON.stringify(savedData));
                    }
                });

                filterFactures(); // Appliquer le filtre après le chargement des données
            }

            // Créer un élément <select> pour changer le statut
            function createStatusSelect(id, currentStatus) {
                const select = document.createElement("select");
                const statusOptions = {
                    payee: "Payée",
                    non_payee: "Non Payée",
                    en_attente: "En Attente"
                };

                for (let key in statusOptions) {
                    let option = document.createElement("option");
                    option.value = key;
                    option.textContent = statusOptions[key];
                    if (key === currentStatus) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                }

                select.addEventListener("change", function() {
                    saveData(id, 6, this.value);
                });

                return select;
            }

            // Créer un élément <input> pour modifier les autres colonnes
            function createEditableInput(id, columnIndex, value) {
                const input = document.createElement("input");
                input.type = "text";
                input.value = value;
                input.addEventListener("input", function() {
                    saveData(id, columnIndex, this.value);
                });
                return input;
            }

            // Enregistrer les données dans le localStorage
            function saveData(id, columnIndex, value) {
                let savedData = JSON.parse(localStorage.getItem("factureData")) || {};

                if (!savedData[id]) {
                    savedData[id] = [];
                }
                savedData[id][columnIndex] = value;

                localStorage.setItem("factureData", JSON.stringify(savedData));

                if (columnIndex === 6) {
                    filterFactures(); // Appliquer le filtre immédiatement si le statut est modifié
                }
            }

            // Filtrer les factures en fonction du statut sélectionné
            function filterFactures() {
                const selectedStatus = filterSelect.value;

                rows.forEach(row => {
                    const statutSelect = row.querySelector("td:nth-child(7) select");
                    const statut = statutSelect.value;

                    if (selectedStatus === "toutes" || statut === selectedStatus) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            }

            // Appliquer le filtre lorsqu'on change la sélection
            filterSelect.addEventListener("change", filterFactures);

            // Charger les données et appliquer le filtre au chargement
            loadData();
        });






        document.addEventListener("DOMContentLoaded", function() {
            const factureTable = document.querySelector(".factures tbody");

            // Options possibles pour le statut
            const statusOptions = {
                payee: "Payée",
                non_payee: "Non Payée",
                en_attente: "En Attente"
            };

            // Charger les statuts enregistrés dans le localStorage
            function loadStatuses() {
                const savedStatuses = JSON.parse(localStorage.getItem("factureStatuses")) || {};
                document.querySelectorAll(".factures tbody tr").forEach(row => {
                    const id = row.cells[0].textContent.trim();
                    const statutCell = row.cells[6];

                    let currentStatus = "en_attente"; // Statut par défaut
                    if (savedStatuses[id]) {
                        currentStatus = savedStatuses[id];
                    } else {
                        currentStatus = Object.keys(statusOptions).find(
                            key => statusOptions[key] === statutCell.textContent.trim()
                        ) || "en_attente";
                    }

                    // Remplacer le texte par un select
                    const select = createStatusSelect(id, currentStatus);
                    statutCell.innerHTML = ""; // Vider la cellule
                    statutCell.appendChild(select);
                });
            }

            // Créer un élément <select> pour changer le statut
            function createStatusSelect(id, currentStatus) {
                const select = document.createElement("select");

                for (let key in statusOptions) {
                    let option = document.createElement("option");
                    option.value = key;
                    option.textContent = statusOptions[key];
                    if (key === currentStatus) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                }

                select.addEventListener("change", function() {
                    saveStatus(id, this.value);
                });

                return select;
            }

            // Enregistrer les statuts dans le localStorage
            function saveStatus(id, status) {
                let savedStatuses = JSON.parse(localStorage.getItem("factureStatuses")) || {};
                savedStatuses[id] = status;
                localStorage.setItem("factureStatuses", JSON.stringify(savedStatuses));
            }

            // Charger les statuts au démarrage
            loadStatuses();
        });
    </script>


</body>

</html>