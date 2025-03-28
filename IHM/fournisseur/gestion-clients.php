<?php
// Inclure l'en-tête du fournisseur
include '../Includes/Header-Fournisseur.php';

// Connexion à la base de données
 

// Récupérer les clients depuis la base de données
 
?>
 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>
    <link rel="stylesheet" href="../../Assets/Css/test.css">
</head>
<body>

<div class="container">
    <!-- Titre avec cadre bleu foncé -->
    <h1 class="title-box">👥 Gestion des Clients</h1>

    <!-- Bouton pour ajouter un client -->
    <!-- Bouton pour ajouter un client (Redirection vers une nouvelle page) -->
<div class="button-container">
    <a href="ajouter-client.php" class="btn">➕ Ajouter un client</a>
</div>

     
   
    <!-- Tableau des clients -->
    <table>
        <thead>
            <tr>
                <th>📛 Nom & Prénom</th>
                <th>📍 Adresse</th>
                <th>⚡ Consommation (kWh)</th>
                <th>💰 Montant impayé (DH)</th>
                <th>✏ Modifier</th>
                <th>❌ Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Ali Benomar</td>
                <td>Casablanca</td>
                <td>350 kWh</td>
                <td>200 DH</td>
                <td><button class="editBtn">✏</button></td>
                <td><button class="deleteBtn">❌</button></td>
            </tr>
            <tr>
                <td>Sara El Amrani</td>
                <td>Rabat</td>
                <td>280 kWh</td>
                <td>150 DH</td>
                <td><button class="editBtn">✏</button></td>
                <td><button class="deleteBtn">❌</button></td>
            </tr>
            <tr>
                <td>Khalid Ouazzani</td>
                <td>Marrakech</td>
                <td>420 kWh</td>
                <td>300 DH</td>
                <td><button class="editBtn">✏</button></td>
                <td><button class="deleteBtn">❌</button></td>
            </tr>
        </tbody>
    </table>
</div>

<script src="../../Assets/Js/js.js"></script>
</body>
</html>
